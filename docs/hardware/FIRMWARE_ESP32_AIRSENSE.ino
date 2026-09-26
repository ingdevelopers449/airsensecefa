#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// =========================================================================
// ⚙️ 1. CONFIGURACIÓN DE RED Y DISPOSITIVO (EDITA ESTOS VALORES)
// =========================================================================
const char* WIFI_SSID     = "NOMBRE_DE_TU_WIFI";       // Escribe el SSID de tu Wi-Fi
const char* WIFI_PASSWORD = "CLAVE_DE_TU_WIFI";        // Escribe la clave del Wi-Fi

// Reemplaza 192.168.X.X por la dirección IP local de tu computador donde corre Laragon
const char* API_URL = "http://192.168.1.15:8000/api/v1/nodes/telemetry";

// Credenciales de Seguridad del Nodo registradas en la Base de Datos
const char* DEVICE_UID   = "ESP32_XX5R69";
const char* DEVICE_TOKEN = "secret_token_abc123";

// Frecuencia de envío en milisegundos (60.000 ms = 1 Minuto)
const unsigned long INTERVALO_ENVIO_MS = 60000;
unsigned long ultimaLecturaMs = 0;

// =========================================================================
// 🔌 2. CONFIGURACIÓN DE SENSORES (PINES GPIO)
// =========================================================================
// Sensor DHT11 (Temperatura y Humedad)
#define DHTPIN 4
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// Sensor MH-Z16 / MH-Z19B (CO2 por Puerto Serial 2)
// Conexiones: RXD2 (GPIO 16) -> TXD del MH-Z16 | TXD2 (GPIO 17) -> RXD del MH-Z16
#define RX2_PIN 16
#define TX2_PIN 17
HardwareSerial sensorSerial(2);

// Comando estándar en bytes para solicitar la lectura de CO2 al sensor MH-Z16
const byte cmdReadCO2[9] = {0xFF, 0x01, 0x86, 0x00, 0x00, 0x00, 0x00, 0x00, 0x79};

// =========================================================================
// 🚀 3. INICIALIZACIÓN (SETUP)
// =========================================================================
void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.println("\n==============================================");
  Serial.println("🌿 AirSense CEFA - Nodo IoT ESP32 Invocado");
  Serial.println("==============================================");

  // Inicializar Sensores
  dht.begin();
  sensorSerial.begin(9600, SERIAL_8N1, RX2_PIN, TX2_PIN);

  // Conectar a Wi-Fi
  conectarWiFi();
}

// =========================================================================
// 🔄 4. BUCLE PRINCIPAL (LOOP)
// =========================================================================
void loop() {
  // Asegurar reconexión Wi-Fi si se pierde la señal
  if (WiFi.status() != WL_CONNECTED) {
    conectarWiFi();
  }

  // Ejecutar envío cada 60 segundos
  unsigned long msActuales = millis();
  if (msActuales - ultimaLecturaMs >= INTERVALO_ENVIO_MS || ultimaLecturaMs == 0) {
    ultimaLecturaMs = msActuales;

    Serial.println("\n----------------------------------------------");
    Serial.println("📊 Tomando lecturas de sensores...");

    // 1. Leer DHT11
    float temperatura = dht.readTemperature();
    float humedad     = dht.readHumidity();

    // 2. Leer MH-Z16
    int co2Ppm = leerCO2();

    // Validar lecturas
    if (isnan(temperatura) || isnan(humedad)) {
      Serial.println("⚠️ Error leyendo el sensor DHT11. Se reintentará...");
    } else {
      Serial.printf("🌡️ Temp: %.1f °C | 💧 Hum: %.1f %% | 💨 CO2: %d ppm\n", temperatura, humedad, co2Ppm);
      
      // 3. Enviar lecturas a la API de Laravel
      enviarTelemetriaLaravel(co2Ppm, temperatura, humedad);
    }
  }
}

// =========================================================================
// 📡 5. FUNCIONES AUXILIARES
// =========================================================================

// Función para conectar / reconectar Wi-Fi
void conectarWiFi() {
  Serial.print("📶 Conectando a la red Wi-Fi: ");
  Serial.println(WIFI_SSID);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  int intentos = 0;
  while (WiFi.status() != WL_CONNECTED && intentos < 20) {
    delay(500);
    Serial.print(".");
    intentos++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n✅ Wi-Fi Conectado con éxito!");
    Serial.print("🌐 Dirección IP asignada al ESP32: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\n❌ No se pudo conectar al Wi-Fi. Reintentando en el siguiente ciclo...");
  }
}

// Función para leer concentración de CO2 desde el sensor MH-Z16 mediante puerto UART
int leerCO2() {
  sensorSerial.write(cmdReadCO2, 9);
  delay(100);

  byte response[9];
  if (sensorSerial.available() >= 9) {
    sensorSerial.readBytes(response, 9);

    // Validar byte de inicio y comando devuelto
    if (response[0] == 0xFF && response[1] == 0x86) {
      int high = (int) response[2];
      int low  = (int) response[3];
      int ppm  = (high * 256) + low;
      return ppm;
    }
  }
  return 450; // Valor de resguardo / aire ambiente por defecto si el sensor calienta
}

// Función para ensamblar el JSON y enviar por HTTP POST a Laravel
void enviarTelemetriaLaravel(int co2, float temp, float hum) {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(API_URL);

  // Encabezados HTTP de Autenticación IoT y Formato
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");
  http.addHeader("X-Device-UID", DEVICE_UID);
  http.addHeader("X-Device-Token", DEVICE_TOKEN);

  // Crear el documento JSON
  StaticJsonDocument<512> doc;
  doc["device_uid"] = DEVICE_UID;

  JsonArray measurements = doc.createNestedArray("measurements");

  // Variable 1: CO2
  JsonObject m1 = measurements.createNestedObject();
  m1["variable_type"] = "co2";
  m1["value"]         = co2;
  m1["unit"]          = "ppm";

  // Variable 2: Temperatura
  JsonObject m2 = measurements.createNestedObject();
  m2["variable_type"] = "temperature";
  m2["value"]         = temp;
  m2["unit"]          = "°C";

  // Variable 3: Humedad
  JsonObject m3 = measurements.createNestedObject();
  m3["variable_type"] = "humidity";
  m3["value"]         = hum;
  m3["unit"]          = "%";

  String jsonPayload;
  serializeJson(doc, jsonPayload);

  Serial.println("📤 Enviando datos a la API de Laravel...");
  int httpResponseCode = http.POST(jsonPayload);

  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.printf("✅ Respuesta Servidor [HTTP %d]: %s\n", httpResponseCode, response.c_str());
  } else {
    Serial.printf("❌ Error enviando HTTP POST: %s\n", http.errorToString(httpResponseCode).c_str());
  }

  http.end();
}
