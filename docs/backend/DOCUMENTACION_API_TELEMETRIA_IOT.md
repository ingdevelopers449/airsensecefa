# 📡 Documentación Técnica de la API REST IoT, Sincronización & Scheduler - AirSense CEFA

## 1. Visión General de la Arquitectura de Telemetría

El ecosistema **AirSense CEFA** utiliza una arquitectura de sincronización híbrida distribuida (Nube + Entornos de Desarrollo Local + Dispositivos IoT Edge):

```
       [ Nodo ESP32 + Sensores + GPS ]
                    │
                    ▼ (HTTPS POST / JSON)
        [ Nube 24/7: Hostinger ]
      https://airsensecefa.site/api/v1/...
                    │
                    ▼ (GET / Cloud-to-Local Sync)
  ┌─────────────────┴─────────────────┐
  ▼                                   ▼
[ Dev 1: Laragon ]           [ Dev 2: Laragon ]
  (php artisan                 (php artisan 
   sync:telemetry)              schedule:work)
```

1. **Nube 24/7 (Hostinger - `https://airsensecefa.site`):** Recibe las lecturas de los nodos ESP32 vía HTTPS en tiempo real y las almacena en la base de datos MySQL de producción.
2. **Nodos ESP32 (Hardware IoT):** Miden variables (CO2, Temperatura, Humedad, PM2.5, etc.), obtienen coordenadas GPS (NEO-6M) y transmiten vía SSL (`WiFiClientSecure`). Si no hay WiFi disponible, almacenan temporalmente las lecturas en la memoria flash interna (**LittleFS**) y las sincronizan automáticamente al recuperar conectividad.
3. **Entornos Locales de Desarrolladores (Laragon / Artisan):** Consumen el endpoint de sincronización `GET /api/v1/nodes/latest-telemetry` para alimentar sus bases de datos locales sin necesidad de tener hardware físico conectado localmente.

---

## 2. Especificación Completa de Endpoints de la API (v1)

### 🔹 Endpoint 1: Ingesta de Telemetría IoT (ESP32 ➔ Nube)
* **Método:** `POST`
* **URL:** `https://airsensecefa.site/api/v1/nodes/telemetry`
* **Encabezados Requeridos (HTTP Headers):**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `X-Device-UID`: `<UID_DEL_NODO>` *(Ejemplo: `ESP32_CEFA_01`)*
  - `X-Device-Token`: `<TOKEN_DE_SEGURIDAD>`
* **Payload JSON:**
```json
{
  "device_uid": "ESP32_CEFA_01",
  "latitude": 2.441234,
  "longitude": -76.605678,
  "measurements": [
    { "variable_type": "co2", "value": 435.0, "unit": "ppm" },
    { "variable_type": "temperature", "value": 24.5, "unit": "°C" },
    { "variable_type": "humidity", "value": 65.0, "unit": "%" }
  ]
}
```
* **Respuestas del Servidor:**
  - `201 Created`: Lectura almacenada exitosamente.
  - `400 Bad Request`: Payload JSON malformado o campos faltantes.
  - `404 Not Found`: Dispositivo/Nodo no registrado en la base de datos.

---

### 🔹 Endpoint 2: Consulta de Telemetría Reciente (Nube ➔ Sincronización Local)
* **Método:** `GET`
* **URL:** `https://airsensecefa.site/api/v1/nodes/latest-telemetry`
* **Parámetros de Consulta (Query Params):**
  - `limit` *(Opcional)*: Número de lecturas a retornar. Valor por defecto: `30`. Máximo permitido: `100`.
* **Ejemplo de Petición:** `GET https://airsensecefa.site/api/v1/nodes/latest-telemetry?limit=50`
* **Respuesta JSON:**
```json
{
  "success": true,
  "count": 1,
  "data": [
    {
      "message_id": "MSG-1727928123-ESP32_CEFA_01",
      "device_uid": "ESP32_CEFA_01",
      "node_name": "Nodo Central CEFA",
      "latitude": 2.441234,
      "longitude": -76.605678,
      "recorded_at": "2026-10-02 22:50:00",
      "measurements": [
        { "variable_type": "co2", "value": 435.0, "unit": "ppm" },
        { "variable_type": "temperature", "value": 24.5, "unit": "°C" },
        { "variable_type": "humidity", "value": 65.0, "unit": "%" }
      ]
    }
  ]
}
```

---

## 3. Guía Paso a Paso para Integración y Sincronización del Equipo

Todos los integrantes del equipo pueden trabajar con datos reales de telemetría en sus entornos locales siguiendo este paso a paso sin omitir nada.

### 📋 Paso 1: Actualizar la Rama de Trabajo
Abre tu terminal en VS Code y asegúrate de tener los últimos cambios de la rama principal:
```bash
git checkout main
git pull origin main
```
*(O si estás trabajando en una rama `feature/*`, asegúrate de estar al día con `main` o `develop`)*.

---

### 📋 Paso 2: Ejecutar las Migraciones (Si hay cambios en base de datos)
Si no has ejecutado las migraciones locales recientemente:
```bash
php artisan migrate
```

---

### 📋 Paso 3: Sincronización de Datos (Elige tu Opción Preferida)

#### 🔹 Opción A: Sincronización Manual (Bajo Demanda)
Ejecuta el siguiente comando en tu terminal cada vez que desees descargar las lecturas más recientes cargadas por los nodos en la nube:
```bash
php artisan sync:telemetry
```
* **Opción con límite personalizado:** Si deseas descargar las últimas 50 lecturas de una sola vez:
  ```bash
  php artisan sync:telemetry --limit=50
  ```

#### 🔹 Opción B: Sincronización Automática Periódica (Recomendada)
Para que tu entorno local se mantenga actualizado automáticamente cada minuto en segundo plano sin que tengas que ejecutar el comando manualmente:
```bash
php artisan schedule:work
```
* **¿Cómo funciona?** El programador de tareas de Laravel (`routes/console.php`) ejecutará `sync:telemetry` cada minuto automáticamente mientras mantengas esa consola abierta.

---

### 📋 Paso 4: Iniciar el Servidor Local y Verificar
Abre una terminal paralela e inicia tu servidor local:
```bash
php artisan serve
```
Abre tu navegador en `http://localhost:8000` y navega hacia:
1. **Dashboard de Instructor/Administrador:** Verás las gráficas, semáforos ambientales e historial actualizados con los datos de la nube.
2. **Monitor de Diagnóstico (Laravel Telescope):** Ingresa a `http://localhost:8000/telescope` para auditar peticiones, consultas SQL y logs del sistema.

---

## 4. Detalles del Firmware ESP32 (Hardware IoT)

El firmware actualizado del dispositivo se encuentra disponible en la ruta del repositorio:
[`docs/hardware/FIRMWARE_ESP32_AIRSENSE/FIRMWARE_ESP32_AIRSENSE.ino`](file:///c:/laragon/www/airsense-cefa/docs/hardware/FIRMWARE_ESP32_AIRSENSE/FIRMWARE_ESP32_AIRSENSE.ino)

### 📌 Características Clave del Firmware:
* **Conexión HTTPS SSL Segura:** Implementa `WiFiClientSecure` con `client.setInsecure()` para interactuar de forma transparente con el certificado SSL de Hostinger (`https://airsensecefa.site`).
* **Soporte GPS NEO-6M:** Conectado a los pines RX/TX (GPIO 18 / GPIO 19) procesado mediante la librería `TinyGPS++`.
* **Memoria Buffer Offline (LittleFS):** Si la red WiFi falla, las lecturas se guardan localmente en la memoria Flash en `/pending_telemetry.txt`. Al restablecer la conexión WiFi, el nodo transmite en ráfaga el histórico acumulado antes de continuar con las mediciones en vivo.
* **Headers HTTP Configurables:** Incluye `X-Device-UID` y `X-Device-Token` para autenticación directa contra la API Laravel.

---

## 5. Preguntas Frecuentes y Resolución de Problemas (Troubleshooting)

### ❓ ¿Qué pasa si ejecuto `sync:telemetry` varias veces? ¿Se duplican las mediciones?
**No.** El sistema genera un identificador único por mensaje (`message_id` / `device_message_id`). Si el registro ya existe en tu base de datos local, la API local lo omite de forma transparente.

### ❓ ¿Por qué obtengo error de conexión al sincronizar?
1. Verifica que tengas acceso a internet en tu equipo.
2. Comprueba que la URL `https://airsensecefa.site` sea accesible desde tu navegador.
3. Asegúrate de que las migraciones (`php artisan migrate`) hayan creado la columna `device_message_id` en la tabla `sensor_readings`.

### ❓ ¿Cómo verifico los logs de la sincronización en vivo?
Puedes consultar los archivos de log en `storage/logs/laravel.log` o visualizarlos gráficamente abriendo **Laravel Telescope** en `http://localhost:8000/telescope/logs`.

