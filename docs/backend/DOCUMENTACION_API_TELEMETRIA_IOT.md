# 📡 Documentación Técnica de la API REST IoT & Guía de Sincronización - AirSense CEFA

## 1. Visión General de la Arquitectura API

La API de **AirSense CEFA** opera sobre un entorno híbrido de alta disponibilidad (Nube + Entornos de Desarrollo Local):

* **Servidor en la Nube 24/7 (Hostinger):** Recibe las lecturas HTTPS enviadas por los nodos ESP32.
* **Entornos Locales de Desarrollo (Laragon / Artisan):** Consultan y sincronizan automáticamente las lecturas de la nube mediante comandos de Laravel.

---

## 2. Especificación Técnica de Endpoints (v1)

### 🔹 1. Ingesta de Telemetría IoT (Hardware -> API)
* **Método:** `POST`
* **URL:** `https://airsensecefa.site/api/v1/nodes/telemetry`
* **Encabezados de Autenticación Requeridos:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `X-Device-UID`: `ESP32_XX5R69` (UID registrado en BD)
  - `X-Device-Token`: `secret_token_abc123` (Token de seguridad hash)
* **Cuerpo de la Petición (JSON Payload):**
```json
{
  "device_uid": "ESP32_XX5R69",
  "latitude": 2.441234,
  "longitude": -76.605678,
  "measurements": [
    { "variable_type": "co2", "value": 435, "unit": "ppm" },
    { "variable_type": "temperature", "value": 24.5, "unit": "°C" },
    { "variable_type": "humidity", "value": 65.0, "unit": "%" }
  ]
}
```

---

### 🔹 2. Consulta Pública de Telemetría Reciente (Cloud -> Local Sync)
* **Método:** `GET`
* **URL:** `https://airsensecefa.site/api/v1/nodes/latest-telemetry`
* **Parámetros Opcionales:** `?limit=20` (Cantidad de lecturas a retornar, máx 100).

---

## 3. Guía Paso a Paso para Desarrolladores (Cómo Sincronizar la API a tu MySQL Local)

Tus compañeros de equipo no necesitan conectar sensores ni configurar servidores adicionales. Para sincronizar su base de datos local de Laragon con la información real enviada desde el ESP32 a la nube, siguen estos 3 pasos:

### 🛠️ Paso 1: Actualizar su Repositorio Local
En la terminal de VS Code:
```bash
git checkout main
git pull origin main
```

### 🛠️ Paso 2: Ejecutar el Comando Artisan de Sincronización
En su consola ejecutan:
```bash
php artisan sync:telemetry
```

#### 💡 ¿Qué hace este comando tras bambalinas?
1. Se conecta de forma segura a `https://airsensecefa.site/api/v1/nodes/latest-telemetry`.
2. Descarga las lecturas reales más recientes ingresadas por los ESP32.
3. Inserta automáticamente los registros en sus tablas locales `nodes`, `sensor_readings` y `sensor_measurements` en Laragon.
4. Evita duplicados basándose en el identificador único de mensaje `device_message_id`.

---

### 🛠️ Paso 3: Visualizar en su Servidor Local
Una vez sincronizado, ejecutan:
```bash
php artisan serve
```
Y al ingresar a `http://localhost:8000`, verán reflejados en sus dashboards las gráficas, semáforos ambientales e historial con los **datos reales de la nube**.
