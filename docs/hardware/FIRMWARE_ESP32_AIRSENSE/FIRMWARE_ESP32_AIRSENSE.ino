#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// =========================================================================
// ⚙️ 1. CONFIGURACIÓN DE RED Y DISPOSITIVO (EDITA ESTOS VALORES)
// =========================================================================
const char* WIFI_SSID     = "TV CAMPH PAREJA";       // Escribe el SSID de tu Wi-Fi
const char* WIFI_PASSWORD = "STIVEN1006512755";        // Escribe la clave del Wi-Fi

// Lista de Servidores API de destino (Agrega las IPs de tus compañeros de equipo)
const char* API_SERVERS[] = {
  "http://192.168.1.16/airsense-cefa/public/api/v1/nodes/telemetry", // IP Líder (Cable)
  "http://192.168.0.116/airsense-cefa/public/api/v1/nodes/telemetry"  // IP Líder (Wi-Fi)
  "http://192.168.1.15/airsense-cefa/public/api/v1/nodes/telemetry", // IP Luis
  "http://192.168.1.12/airsense-cefa/public/api/v1/nodes/telemetry", // IP Lizbeth
  "http://192.168.1.3/airsense-cefa/public/api/v1/nodes/telemetry"  // IP Michaell
};
const int NUM_SERVERS = sizeof(API_SERVERS) / sizeof(API_SERVERS[0]);

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

// Función para ensamblar el JSON y enviar por HTTP POST a Laravel (Multi-destino)
void enviarTelemetriaLaravel(int co2, float temp, float hum) {
  if (WiFi.status() != WL_CONNECTED) return;

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

  // Iterar por cada servidor del equipo
  for (int i = 0; i < NUM_SERVERS; i++) {
    WiFiClient client;
    HTTPClient http;
    http.begin(client, API_SERVERS[i]);

    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    http.addHeader("X-Device-UID", DEVICE_UID);
    http.addHeader("X-Device-Token", DEVICE_TOKEN);

    Serial.printf("📤 Enviando datos a Servidor [%d/%d]: %s\n", i + 1, NUM_SERVERS, API_SERVERS[i]);
    int httpResponseCode = http.POST(jsonPayload);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.printf("✅ [OK %d]: %s\n", httpResponseCode, response.c_str());
    } else {
      Serial.printf("⚠️ [No recibido]: %s\n", http.errorToString(httpResponseCode).c_str());
    }

    http.end();
  }
}
