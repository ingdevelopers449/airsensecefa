# 🔭 Guía de Uso y Monitoreo con Laravel Telescope - AirSense CEFA

## 1. ¿Qué es Laravel Telescope?
**Laravel Telescope** es el panel oficial de depuración e inspección en tiempo real para aplicaciones Laravel. En el proyecto **AirSense CEFA**, se utiliza como la herramienta principal para monitorear la llegada de datos de telemetría IoT desde los nodos ESP32, verificar consultas a la base de datos MySQL y diagnosticar errores sin revisar archivos de log extensos.

---

## 2. Acceso al Dashboard

### A. Entorno con `php artisan serve` (Desarrollo Directo)
Si estás ejecutando el servidor de desarrollo de Laravel en tu consola:
- **URL de acceso:** [http://localhost:8000/telescope](http://localhost:8000/telescope)

### B. Entorno con Laragon (Servidor Local Apache)
Si utilizas el servidor local de Laragon:
- **URL de acceso:** [http://localhost/airsense-cefa/public/telescope](http://localhost/airsense-cefa/public/telescope)

---

## 3. Secciones Clave para la Ingesta de Datos IoT

Telescope cuenta con un menú lateral que organiza la información en diferentes inspecciones (Watchers). Las secciones más relevantes para el ecosistema IoT son:

- **Requests (Peticiones HTTP):** Audita la llegada de cada `POST /api/v1/nodes/telemetry` enviado por el ESP32, sus encabezados de seguridad y el cuerpo del JSON recibido.
- **Exceptions (Depuración de Errores):** Muestra el mensaje exacto y la línea de código PHP cuando ocurre una falla `500`.
- **Queries (Consultas SQL):** Permite revisar las sentencias `INSERT` ejecutadas en `sensor_readings` y `sensor_measurements` dentro de MySQL.
- **Models (Modelos Eloquent):** Registra los eventos de creación y actualización de `SensorReading`, `SensorMeasurement` y `Node`.

---

## 4. Flujo de Trabajo Recomendado durante Pruebas con ESP32

1. Abre el navegador en `http://localhost:8000/telescope/requests`.
2. Conecta el ESP32 o ejecuta una simulación en Postman.
3. Observa la llegada inmediata de la petición sin necesidad de recargar la página (*Auto-refresh active*).
4. Si la petición falla (color rojo), haz clic sobre ella para ver los detalles exactos del error.

---

## 5. Mantenimiento y Limpieza de Datos en Telescope

Para evitar que la base de datos de depuración se llene demasiado durante pruebas intensivas, puedes ejecutar en la terminal:

```bash
# Limpiar todas las entradas registradas en Telescope
php artisan telescope:clear
```
