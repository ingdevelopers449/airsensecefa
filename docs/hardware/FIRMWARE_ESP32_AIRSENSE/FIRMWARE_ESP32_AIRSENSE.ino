#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>
#include <TinyGPS++.h>
#include "LittleFS.h"

// =========================================================================
// ⚙️ 1. CONFIGURACIÓN DE RED Y DISPOSITIVO (EDITA ESTOS VALORES)
// =========================================================================
const char* WIFI_SSID     = "FAMILIA LOZADA";       // Escribe el SSID de tu Wi-Fi
const char* WIFI_PASSWORD = "1077845912@";        // Escribe la clave del Wi-Fi

// Lista de Servidores API de destino
const char* API_SERVERS[] = {
  "https://airsensecefa.site/api/v1/nodes/telemetry", // 🌐 Servidor Oficial Producción Hostinger (24/7)
  // "http://192.168.0.133/airsense-cefa/public/api/v1/nodes/telemetry" // Servidor Local Red Interna
};
const int NUM_SERVERS = sizeof(API_SERVERS) / sizeof(API_SERVERS[0]);

// Credenciales de Seguridad del Nodo registradas en la Base de Datos
const char* DEVICE_UID   = "ESP32_XX5R69";
const char* DEVICE_TOKEN = "secret_token_abc123";

// Frecuencia de envío en milisegundos (60.000 ms = 1 Minuto)
const unsigned long INTERVALO_ENVIO_MS = 60000;
unsigned long ultimaLecturaMs = 0;

// Nombre del archivo para la cola de registros fuera de línea (LittleFS)
const char* OFFLINE_FILE = "/offline_queue.json";

// =========================================================================
// 🔌 2. CONFIGURACIÓN DE SENSORES (PINES GPIO)
// =========================================================================
// Sensor DHT11 (Temperatura y Humedad)
#define DHTPIN 4
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// Sensor MH-Z16 / MH-Z19B (CO2 por Puerto Serial 2)
#define RX2_PIN 16
#define TX2_PIN 17
HardwareSerial sensorSerial(2);

// Módulo GPS NEO-6M (por Puerto Serial 1)
// Conexiones: RX1 (GPIO 18 del ESP32) -> TXD del GPS | TX1 (GPIO 19 del ESP32) -> RXD del GPS
#define GPS_RX_PIN 18
#define GPS_TX_PIN 19
HardwareSerial gpsSerial(1);
TinyGPSPlus gps;

// Comando estándar en bytes para solicitar la lectura de CO2 al sensor MH-Z16
const byte cmdReadCO2[9] = {0xFF, 0x01, 0x86, 0x00, 0x00, 0x00, 0x00, 0x00, 0x79};

// Coordenadas GPS / Ubicación del Nodo
float nodoLatitud  = 2.441234;  // Resguardo por defecto si el GPS no ha fijado satélites
float nodoLongitud = -76.605678;

// Prototipos de funciones
void conectarWiFi();
int leerCO2();
void actualizarCoordenadasGPS();
void enviarTelemetriaLaravel(int co2, float temp, float hum, float lat = 0.0, float lon = 0.0);
void guardarLecturaOffline(const String& payload);
void procesarPendientesOffline();

// =========================================================================
// 🚀 3. INICIALIZACIÓN (SETUP)
// =========================================================================
void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.println("\n==============================================");
  Serial.println("🌿 AirSense CEFA - Nodo IoT ESP32 (LittleFS Buffer Active)");
  Serial.println("==============================================");

  // Inicializar Sistema de Archivos LittleFS
  if (!LittleFS.begin(true)) {
    Serial.println("⚠️ Error al montar LittleFS. Formateando...");
  } else {
    Serial.println("💾 LittleFS montado correctamente.");
  }

  // Inicializar Sensores
  dht.begin();
  sensorSerial.begin(9600, SERIAL_8N1, RX2_PIN, TX2_PIN);
  gpsSerial.begin(9600, SERIAL_8N1, GPS_RX_PIN, GPS_TX_PIN);

  // Conectar a Wi-Fi
  conectarWiFi();
}

// =========================================================================
// 🔄 4. BUCLE PRINCIPAL (LOOP)
// =========================================================================
void loop() {
  // Escuchar constantemente el flujo NMEA del módulo GPS NEO-6M
  actualizarCoordenadasGPS();

  // Asegurar reconexión Wi-Fi si se pierde la señal
  if (WiFi.status() != WL_CONNECTED) {
    conectarWiFi();
  }

  // Si hay Wi-Fi y tenemos lecturas offline almacenadas, las reenviamos
  if (WiFi.status() == WL_CONNECTED) {
    procesarPendientesOffline();
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
      
      // 3. Enviar lecturas a la API de Laravel (con coordenadas GPS)
      enviarTelemetriaLaravel(co2Ppm, temperatura, humedad, nodoLatitud, nodoLongitud);
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
  while (WiFi.status() != WL_CONNECTED && intentos < 15) {
    delay(500);
    Serial.print(".");
    intentos++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n✅ Wi-Fi Conectado con éxito!");
    Serial.print("🌐 Dirección IP asignada al ESP32: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\n❌ Sin Wi-Fi en este momento. El modo Offline se activará...");
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

// Función para decodificar sentencias GPS mediante la librería TinyGPS++
void actualizarCoordenadasGPS() {
  while (gpsSerial.available() > 0) {
    gps.encode(gpsSerial.read());
  }

  if (gps.location.isUpdated() && gps.location.isValid()) {
    nodoLatitud  = gps.location.lat();
    nodoLongitud = gps.location.lng();
    Serial.printf("📍 [GPS FIX OK] Lat: %.6f | Lon: %.6f | Satélites: %d\n", nodoLatitud, nodoLongitud, gps.satellites.value());
  } else if (millis() % 10000 < 50) { // Diagnóstico periódico cada 10 segundos
    if (gps.charsProcessed() < 10) {
      Serial.println("⚠️ [GPS DIAGNÓSTICO] Sin comunicación serial en GPIO 18/19. Revisa/invierte los cables RX-TX.");
    } else {
      Serial.printf("⚠️ [GPS DIAGNÓSTICO] Buscando satélites (Caracteres NMEA recibidos: %lu)... Saca la antena hacia una ventana.\n", gps.charsProcessed());
    }
  }
}

// Guardar payload JSON en LittleFS cuando falla la conexión
void guardarLecturaOffline(const String& payload) {
  File file = LittleFS.open(OFFLINE_FILE, FILE_APPEND);
  if (!file) {
    Serial.println("❌ Error abriendo el archivo LittleFS para escritura offline.");
    return;
  }
  file.println(payload);
  file.close();
  Serial.println("💾 [MODO OFFLINE ACTIVADO] Lectura guardada en la memoria Flash del ESP32.");
}

// Procesar y reenviar lecturas guardadas en LittleFS cuando retorna el Wi-Fi
void procesarPendientesOffline() {
  if (!LittleFS.exists(OFFLINE_FILE)) return;

  File file = LittleFS.open(OFFLINE_FILE, FILE_READ);
  if (!file || file.size() == 0) {
    if (file) file.close();
    LittleFS.remove(OFFLINE_FILE);
    return;
  }

  Serial.println("\n📦 [SYNC OFFLINE] Detectados registros pendientes en LittleFS. Iniciando sincronización...");
  
  // Crear archivo temporal para conservar los que no se puedan enviar
  String contenidoRestante = "";
  int enviados = 0;

  while (file.available()) {
    String lineaPayload = file.readStringUntil('\n');
    lineaPayload.trim();
    if (lineaPayload.length() == 0) continue;

    bool enviadoExitoso = false;

    for (int i = 0; i < NUM_SERVERS; i++) {
      WiFiClientSecure client;
      client.setInsecure(); // Desactivar validación de cadena SSL para acelerar el envío IoT
      HTTPClient http;
      http.begin(client, API_SERVERS[i]);
      http.addHeader("Content-Type", "application/json");
      http.addHeader("Accept", "application/json");
      http.addHeader("X-Device-UID", DEVICE_UID);
      http.addHeader("X-Device-Token", DEVICE_TOKEN);
      http.addHeader("Bypass-Tunnel-Reminder", "true");

      int httpCode = http.POST(lineaPayload);
      if (httpCode > 0 && httpCode < 300) {
        enviadoExitoso = true;
        Serial.printf("✅ [SYNC OFFLINE OK %d]: Entregado a %s\n", httpCode, API_SERVERS[i]);
      } else {
        Serial.printf("⚠️ [SYNC OFFLINE ERROR %d]: %s (%s)\n", httpCode, http.errorToString(httpCode).c_str(), API_SERVERS[i]);
      }
      http.end();
      if (enviadoExitoso) break;
    }

    if (enviadoExitoso) {
      enviados++;
      delay(150); // Pausa breve para evitar saturar el servidor en reenvío masivo
    } else {
      // Si falló el reenvío, conservamos la línea para el siguiente intento
      contenidoRestante += lineaPayload + "\n";
    }
  }

  file.close();

  // Actualizar el archivo con los datos que hayan quedado pendientes
  if (contenidoRestante.length() > 0) {
    File tempFile = LittleFS.open(OFFLINE_FILE, FILE_WRITE);
    if (tempFile) {
      tempFile.print(contenidoRestante);
      tempFile.close();
    }
  } else {
    LittleFS.remove(OFFLINE_FILE);
    Serial.println("🎉 [SYNC COMPLETA] Todos los registros offline fueron entregados con éxito a la API!");
  }

  if (enviados > 0) {
    Serial.printf("✅ Sincronizados %d registros acumulados a la Base de Datos.\n", enviados);
  }
}

// Función para ensamblar el JSON y enviar por HTTP POST a Laravel (Multi-destino + Fallback Offline)
void enviarTelemetriaLaravel(int co2, float temp, float hum, float lat, float lon) {
  // Crear el documento JSON
  StaticJsonDocument<512> doc;
  doc["device_uid"] = DEVICE_UID;

  if (lat != 0.0 || lon != 0.0) {
    doc["latitude"]  = lat;
    doc["longitude"] = lon;
  }

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

  // Si no hay Wi-Fi en este momento, guardar directamente en LittleFS
  if (WiFi.status() != WL_CONNECTED) {
    guardarLecturaOffline(jsonPayload);
    return;
  }

  bool alMenosUnEnvioExitoso = false;

  // Iterar por cada servidor del equipo
  for (int i = 0; i < NUM_SERVERS; i++) {
    WiFiClientSecure client;
    client.setInsecure(); // Permitir cifrado SSL HTTPS sin requerir certificado CA embebido
    HTTPClient http;
    http.begin(client, API_SERVERS[i]);

    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    http.addHeader("X-Device-UID", DEVICE_UID);
    http.addHeader("X-Device-Token", DEVICE_TOKEN);
    http.addHeader("Bypass-Tunnel-Reminder", "true");

    Serial.printf("📤 Enviando datos a Servidor [%d/%d]: %s\n", i + 1, NUM_SERVERS, API_SERVERS[i]);
    int httpResponseCode = http.POST(jsonPayload);

    if (httpResponseCode > 0 && httpResponseCode < 300) {
      String response = http.getString();
      Serial.printf("✅ [OK %d]: %s\n", httpResponseCode, response.c_str());
      alMenosUnEnvioExitoso = true;
    } else {
      Serial.printf("⚠️ [No recibido]: %s\n", http.errorToString(httpResponseCode).c_str());
    }

    http.end();
  }

  // Si fallaron todos los servidores destinos, activar respaldo en LittleFS
  if (!alMenosUnEnvioExitoso) {
    guardarLecturaOffline(jsonPayload);
  }
}
