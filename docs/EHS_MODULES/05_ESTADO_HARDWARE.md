# 📡 Módulo 5: Estado de Hardware y Conectividad IoT (EHS CEFA)

## 1. Visión General y Propósito
El módulo de **Estado de Hardware** permite auditar la salud técnica, la señal y el estado de conectividad en tiempo real de todos los nodos **ESP32** instalados en el centro de formación. Permite detectar caídas de red, pérdidas de señal GPS y cambios físicos no autorizados en la ubicación de los dispositivos.

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-21 (Monitor Keep-Alive):** Monitorear continuamente la conectividad del hardware mediante señales de latido (Keep-Alive).
* **RF-30 (Autenticidad del Dispositivo):** Validación de peticiones HTTPS mediante `X-Device-UID` y `X-Device-Token`.
* **RF-31 (Gestión de Nodos Registrados y No Registrados):** Listar dispositivos activos y aquellos que se conectan por primera vez.
* **RF-33 (Coordenadas Geográficas GPS):** Registro de latitud/longitud enviadas por el GPS NEO-6M del nodo.
* **RF-40 & RF-41 (Detección y Confirmación de Desplazamiento Físico):** Alerta automática si el nodo reporta coordenadas distintas a las configuradas, permitiendo confirmar o rechazar el cambio.

---

## 3. Historias de Usuario Asociadas
* **HU-008 (Asociación y Diagnóstico de Hardware):** Como EHS, necesito verificar qué dispositivos han dejado de transmitir para solicitar soporte técnico.
* **HU-041 (Confirmación de Cambio de Ubicación):** Como SST, recibir una alerta si un nodo fue movido de un aula a un laboratorio para actualizar su registro geográfico.

---

## 4. Métricas e Interfaz de Usuario
1. **Consola de Diagnóstico:**
   - **Indicador Keep-Alive:** 🟢 *En Línea* (últimos 5 min), 🔴 *Fuera de Línea* (> 5 min sin transmitir).
   - **Identificador de Hardware:** `device_uid` (ej. `ESP32_CEFA_01`).
   - **Dirección / Ubicación Asignada:** Nombre del ambiente o zona.
   - **Coordenadas GPS Actuales:** Latitud / Longitud en vivo.
   - **Respaldo Offline (LittleFS):** Muestra si el nodo sincronizó lecturas diferidas acumuladas durante fallos de WiFi.
2. **Alerta de Movimiento (RF-40):** Cuadro emergente con botón *"Confirmar Registro de Nueva Ubicación"*.

---

## 5. Origen de Datos y Modelos Laravel (MySQL)
* **Controlador:** `App\Http\Controllers\Ehs\EHSController@estadoHardware`
* **Modelo Principal:** `App\Models\Node`
* **Ruta:** `Route::get('/ehscefa/hardware/nodo', [EHSController::class, 'estadoHardware'])->name('ehscefa.hardware.nodo')`
* **Vista:** `resources/views/ehscefa/hardware/nodo.blade.php`
