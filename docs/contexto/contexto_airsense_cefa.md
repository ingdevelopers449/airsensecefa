# Contexto completo del chat --- AirSense CEFA

## 1. Proyecto

El proyecto que se está desarrollando es **AirSense CEFA**, un sistema
de monitoreo ambiental basado en:

-   ESP32.
-   Sensor de CO₂ **MH-Z16**.
-   Sensor de temperatura y humedad **DHT11**.
-   Laravel como backend.
-   MySQL como base de datos.
-   Laragon como entorno local.
-   Arduino IDE para programar el ESP32.
-   Vistas Blade de Laravel para mostrar la información.
-   Un módulo GPS/GNSS estaba contemplado, pero **por ahora no se
    dispone del módulo GPS**, por lo que el desarrollo actual se
    concentrará únicamente en los sensores ambientales.

El proyecto Laravel se encuentra en:

``` text
C:\laragon\www\airsense-cefa
```

La versión de Laravel confirmada es:

``` text
Laravel Framework 13.33.0
```

------------------------------------------------------------------------

# 2. Objetivo actual

El objetivo inmediato es conseguir este flujo:

``` text
DHT11 ─────┐
           │
           ├── ESP32 ── Wi-Fi ── Laravel ── MySQL
           │                         │
MH-Z16 ────┘                         ├── sensor_readings
                                     │
                                     └── sensor_measurements
                                              │
                                              ▼
                                      Vistas de Laravel
```

El ESP32 debe leer:

-   CO₂ mediante MH-Z16.
-   Temperatura mediante DHT11.
-   Humedad mediante DHT11.

Después debe enviar los valores mediante Wi-Fi a una API de Laravel.

Laravel debe:

1.  Identificar el ESP32.
2.  Validar su token.
3.  Identificar el nodo.
4.  Obtener el ambiente asociado.
5.  Registrar la lectura.
6.  Registrar cada variable.
7.  Actualizar el estado de conectividad del nodo.
8.  Permitir que las vistas de Laravel consuman posteriormente esos
    registros.

------------------------------------------------------------------------

# 3. Estado del hardware

## ESP32

El ESP32 utilizado es identificado por el usuario como:

``` text
ESP-32 XX5R69
```

El ESP32 ya fue configurado/programado mediante Arduino IDE.

También se solucionó el problema inicial de que no aparecía el puerto
serial.

El usuario confirmó posteriormente que el ESP32 estaba funcionando.

Durante la carga del programa Arduino IDE mostró:

``` text
Writing at 0x00051bf0 [==============================] 100.0% 155511/155511 bytes...
Wrote 269296 bytes (155511 compressed) at 0x00010000 in 2.6 seconds (837.3 kbit/s).
Verifying written data...
Hash of data verified.

Hard resetting via RTS pin...
```

Esto fue interpretado como una carga exitosa del programa al ESP32.

------------------------------------------------------------------------

# 4. Configuración del monitor serial

El usuario mostró las velocidades disponibles del monitor serial de
Arduino IDE, entre ellas:

``` text
300 baudios
600 baudios
750 baudios
1200 baudios
2400 baudios
4800 baudios
9600 baudios
19200 baudios
31250 baudios
38400 baudios
57600 baudios
74880 baudios
115200 baudios
230400 baudios
```

La velocidad debe coincidir con el valor utilizado en:

``` cpp
Serial.begin(...);
```

del programa del ESP32.

------------------------------------------------------------------------

# 5. Sensor DHT11

El usuario indicó que el DHT11 es de tres pines y que ya fue conectado.

El DHT11 proporciona:

``` text
Temperatura
Humedad
```

El ESP32 debe leer ambos valores y posteriormente enviarlos a Laravel.

La variable lógica que se utilizará en la base de datos será:

``` text
temperature
humidity
```

------------------------------------------------------------------------

# 6. Sensor MH-Z16

El usuario compartió la conexión del módulo MH-Z16 con el ESP32.

Conexiones mostradas:

``` text
Pad 3 (GND)
GND del MH-Z16 → GND del ESP32

Pad 4 (Vin)
Vin del MH-Z16 → 5V / VIN del ESP32

Pad 5 (RXD)
RXD del MH-Z16 → GPIO 17 (TX2) del ESP32

Pad 6 (TXD)
TXD del MH-Z16 → GPIO 16 (RX2) del ESP32
```

El MH-Z16 proporciona:

``` text
CO₂
```

La variable lógica que se utilizará en la base de datos será:

``` text
co2
```

La unidad será:

``` text
ppm
```

------------------------------------------------------------------------

# 7. GPS/GNSS

Originalmente se contempló utilizar un módulo GPS/GNSS.

Sin embargo:

``` text
Actualmente NO se dispone del módulo GPS.
```

Por esta razón el desarrollo actual se hará solamente con:

``` text
ESP32
├── MH-Z16
└── DHT11
```

Las coordenadas quedarán inicialmente como:

``` text
latitude  = NULL
longitude = NULL
```

La estructura de base de datos ya contempla:

``` text
last_reported_latitude
last_reported_longitude
reported_latitude
reported_longitude
```

Por lo tanto, cuando posteriormente se consiga el GPS, podrá integrarse
sin tener que rehacer la arquitectura principal.

------------------------------------------------------------------------

# 8. Base de datos

La base de datos es nueva.

Actualmente el usuario indicó que solamente existen datos en:

``` text
users
roles
```

Las tablas relacionadas con el sistema IoT están inicialmente vacías.

Entre las tablas existentes están:

``` text
nodes
node_location_changes
node_categories
sensor_readings
sensor_measurements
environments
```

No se desea crear tablas duplicadas innecesariamente.

La intención es utilizar la estructura existente.

------------------------------------------------------------------------

# 9. Tabla `nodes`

La tabla `nodes` mostrada por el usuario tiene esta estructura
conceptual:

``` sql
CREATE TABLE IF NOT EXISTS nodes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    environment_id BIGINT UNSIGNED NULL,
    device_uid VARCHAR(150) NOT NULL,
    device_token_hash VARCHAR(255) NOT NULL,
    token_version INT UNSIGNED NOT NULL DEFAULT 1,
    name VARCHAR(180) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    connectivity_status ENUM('online','offline','unknown') NOT NULL DEFAULT 'unknown',
    last_seen_at DATETIME(6) NULL,
    last_keep_alive_at DATETIME(6) NULL,
    last_reported_latitude DECIMAL(10,7) NULL,
    last_reported_longitude DECIMAL(10,7) NULL,
    token_rotated_at DATETIME(6) NULL,
    first_seen_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),

    UNIQUE KEY uq_nodes_device_uid (device_uid),
    KEY idx_nodes_environment_active (environment_id, is_active),
    KEY idx_nodes_connectivity (connectivity_status, last_seen_at),

    CONSTRAINT fk_nodes_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;
```

Aspectos importantes:

-   `device_uid` identifica al ESP32.
-   `device_token_hash` almacena el hash del token.
-   El token real no debe almacenarse directamente en la base de datos.
-   `environment_id` permite asociar el nodo con un ambiente.
-   `connectivity_status` permite manejar `online`, `offline` y
    `unknown`.
-   Las coordenadas pueden quedar en `NULL` mientras no exista GPS.

------------------------------------------------------------------------

# 10. Tabla `node_location_changes`

La estructura mostrada incluye:

``` text
id
node_id
previous_place_name
previous_latitude
previous_longitude
new_place_name
new_latitude
new_longitude
changed_by
confirmed_at
created_at
```

Esta tabla está pensada para registrar cambios de ubicación de los
nodos.

No es necesaria para la primera etapa porque actualmente no se dispone
del GPS.

------------------------------------------------------------------------

# 11. Tabla `node_categories`

La tabla mostrada es:

``` sql
CREATE TABLE IF NOT EXISTS node_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),

    UNIQUE KEY uq_node_categories_name (name)
) ENGINE=InnoDB;
```

Sirve para clasificar nodos.

Por ahora no es necesario complicar el firmware del ESP32 con esta
información.

------------------------------------------------------------------------

# 12. Tabla `sensor_readings`

La estructura mostrada por el usuario es:

``` sql
CREATE TABLE IF NOT EXISTS sensor_readings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    node_id BIGINT UNSIGNED NOT NULL,
    environment_id BIGINT UNSIGNED NOT NULL,
    device_message_id VARCHAR(180) NULL,
    measured_at DATETIME(6) NOT NULL,
    received_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    source ENUM('online','offline_sync') NOT NULL DEFAULT 'online',
    is_valid TINYINT(1) NOT NULL DEFAULT 1,
    reported_latitude DECIMAL(10,7) NULL,
    reported_longitude DECIMAL(10,7) NULL,
    raw_payload JSON NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),

    UNIQUE KEY uq_sensor_readings_device_message (device_message_id),
    KEY idx_sensor_readings_environment_time (environment_id, measured_at),
    KEY idx_sensor_readings_node_time (node_id, measured_at),
    KEY idx_sensor_readings_received (received_at),

    CONSTRAINT fk_sensor_readings_node
        FOREIGN KEY (node_id) REFERENCES nodes(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT fk_sensor_readings_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;
```

Esta tabla representa una lectura general realizada por el nodo.

------------------------------------------------------------------------

# 13. Tabla `sensor_measurements`

La estructura mostrada por el usuario es:

``` sql
CREATE TABLE IF NOT EXISTS sensor_measurements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reading_id BIGINT UNSIGNED NOT NULL,
    variable_type ENUM('co2','temperature','humidity') NOT NULL,
    value DECIMAL(14,4) NOT NULL,
    unit VARCHAR(20) NOT NULL,
    is_valid TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),

    UNIQUE KEY uq_measurement_reading_variable
        (reading_id, variable_type),

    KEY idx_measurement_variable_time
        (variable_type, created_at),

    KEY idx_measurement_reading
        (reading_id),

    CONSTRAINT fk_measurement_reading
        FOREIGN KEY (reading_id) REFERENCES sensor_readings(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;
```

Las variables disponibles ya están definidas:

``` text
co2
temperature
humidity
```

Esto encaja directamente con los sensores actuales.

Ejemplo:

``` text
reading_id | variable_type | value | unit
-----------|---------------|-------|------
1          | co2           | 643   | ppm
1          | temperature   | 26.4  | °C
1          | humidity      | 58    | %
```

------------------------------------------------------------------------

# 14. Arquitectura de datos deseada

El flujo de datos será:

``` text
MH-Z16
   │
   │ CO₂
   ▼
ESP32
   ▲
   │ Temperatura/Humedad
   │
DHT11

ESP32
   │
   │ Wi-Fi
   ▼
Laravel API
   │
   ├── valida device_uid
   ├── valida token
   ├── identifica node
   ├── identifica environment
   │
   ▼
sensor_readings
   │
   ▼
sensor_measurements
   │
   ▼
MySQL
   │
   ▼
Vistas Blade
```

------------------------------------------------------------------------

# 15. Autenticación del ESP32

La arquitectura utiliza:

``` text
device_uid
device_token
```

En la base de datos solamente debe quedar:

``` text
device_token_hash
```

La idea es:

``` text
ESP32
    │
    ├── device_uid
    └── token real
            │
            ▼
        Laravel
            │
            ▼
    Hash::check(...)
            │
            ▼
device_token_hash en MySQL
```

Esto permite validar que las lecturas realmente provienen de un nodo
registrado.

El token no debe almacenarse en texto plano en la base de datos.

------------------------------------------------------------------------

# 16. Endpoint propuesto

El endpoint planteado para recibir las mediciones es:

``` text
POST /api/iot/readings
```

El ESP32 enviaría algo conceptualmente similar a:

``` json
{
    "device_uid": "ESP32-001",
    "token": "TOKEN_REAL_DEL_ESP32",
    "device_message_id": "ESP32-001-000001",
    "measured_at": "2026-09-25T11:30:00",
    "measurements": {
        "co2": 643,
        "temperature": 26.4,
        "humidity": 58
    }
}
```

Laravel deberá:

1.  Buscar el nodo por `device_uid`.
2.  Comprobar que existe.
3.  Comprobar que está activo.
4.  Validar el token.
5.  Evitar duplicados mediante `device_message_id`.
6.  Crear `sensor_readings`.
7.  Crear `sensor_measurements`.
8.  Actualizar `last_seen_at`.
9.  Actualizar `connectivity_status` a `online`.
10. Responder al ESP32.

------------------------------------------------------------------------

# 17. Ejemplo de respuesta correcta

Si la lectura se registra correctamente:

``` json
{
    "success": true,
    "message": "Lectura registrada correctamente.",
    "reading_id": 1
}
```

------------------------------------------------------------------------

# 18. Diseño del módulo administrativo

Actualmente el sidebar del administrador contiene:

``` text
Administrador

Personal
├── Gestión de Usuarios
└── Asignar Ambientes

Configuración & IoT
├── Umbrales de Alerta
├── Nodos IoT (ESP32)
├── Monitor Conectividad
└── Ubicación de Nodos

Auditoría & Seguridad
├── Log de Auditoría
└── Reportes Protegidos

Inteligencia Artificial
└── Módulo Predictivo
```

El usuario quiere empezar a implementar módulos reales para que otros
integrantes puedan entender cómo se integra cada parte.

------------------------------------------------------------------------

# 19. Módulo que se decidió implementar primero

Se decidió que el primer módulo funcional debe ser:

``` text
Configuración & IoT
└── Nodos IoT (ESP32)
```

La razón es que el nodo es el vínculo entre:

``` text
Hardware
   ↓
ESP32
   ↓
Laravel
   ↓
Base de datos
```

Antes de visualizar correctamente las mediciones necesitamos registrar e
identificar el dispositivo.

------------------------------------------------------------------------

# 20. Vista propuesta de `Nodos IoT`

La primera vista puede tener:

``` text
┌──────────────────────────────────────────────────────────┐
│ NODOS IoT                                                │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ [ + Registrar nodo ]                                     │
│                                                          │
│ ┌────────────┬──────────────┬──────────┬──────────────┐ │
│ │ Nombre     │ Device UID   │ Estado   │ Ambiente     │ │
│ ├────────────┼──────────────┼──────────┼──────────────┤ │
│ │ ESP32-001  │ ESP32-001    │ Activo   │ Sin asignar  │ │
│ └────────────┴──────────────┴──────────┴──────────────┘ │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

Al registrar un nodo se pueden manejar inicialmente:

``` text
Nombre
Device UID
Ambiente
Estado
```

El token real no debería mostrarse como un dato común en la tabla.

------------------------------------------------------------------------

# 21. Ambientes

Como la base de datos es nueva, inicialmente no existen ambientes.

Antes de asociar un nodo a un ambiente hay que crear al menos un
ambiente.

Ejemplo:

``` text
Nombre:
Ambiente CEFA

Descripción:
Ambiente de monitoreo ambiental de AirSense CEFA
```

La relación esperada será:

``` text
Ambiente CEFA
      │
      └── ESP32-001
```

------------------------------------------------------------------------

# 22. `Asignar Ambientes`

El módulo:

``` text
Personal
└── Asignar Ambientes
```

tiene una función distinta de `Nodos IoT`.

La idea es que después de tener ambientes y nodos disponibles se puedan
establecer las relaciones necesarias.

El requisito correspondiente contempla asignar ambientes de formación e
instructores responsables, así como modificar esas asignaciones.

Por eso el flujo no debe mezclarse completamente:

``` text
Nodos IoT
    ↓
Registrar dispositivo

Ambientes
    ↓
Crear ambientes

Asignar Ambientes
    ↓
Establecer relaciones entre ambientes/instructores/nodos según el diseño
```

------------------------------------------------------------------------

# 23. Orden de implementación acordado

El orden propuesto para el proyecto es:

``` text
1. Nodos IoT (ESP32)
        ↓
2. Ambientes
        ↓
3. Asignar Ambientes
        ↓
4. API IoT
        ↓
5. sensor_readings
        ↓
6. sensor_measurements
        ↓
7. Monitor de Conectividad
        ↓
8. Visualización de variables
        ↓
9. Umbrales de Alerta
        ↓
10. Ubicación de Nodos
        ↓
11. Historial
        ↓
12. IA Predictiva
```

Este orden permite probar primero la infraestructura básica y después
construir funcionalidades dependientes de ella.

------------------------------------------------------------------------

# 24. GPS se implementará después

El GPS no debe bloquear el desarrollo actual.

Primera etapa:

``` text
ESP32
├── MH-Z16
└── DHT11
```

Segunda etapa:

``` text
ESP32
├── MH-Z16
├── DHT11
└── GPS/GNSS
```

Cuando exista GPS se podrán llenar:

``` text
nodes.last_reported_latitude
nodes.last_reported_longitude
```

y posteriormente desarrollar el mapa y el historial de ubicación.

------------------------------------------------------------------------

# 25. Estado actual de Laravel

Confirmado:

``` text
Laravel Framework 13.33.0
```

Proyecto:

``` text
C:\laragon\www\airsense-cefa
```

También se confirmó que:

``` bash
php artisan migrate:status
```

funciona correctamente y las migraciones están en buen estado.

La base de datos es nueva, con datos principalmente en:

``` text
users
roles
```

Las tablas IoT todavía no tienen registros.

------------------------------------------------------------------------

# 26. Lo que NO se debe hacer todavía

No se debe:

-   Crear tablas duplicadas para sensores.
-   Crear una tabla llamada `registros_sensores`.
-   Inventar coordenadas GPS.
-   Integrar el GPS antes de tener el módulo.
-   Meter el token real del ESP32 directamente en `device_token_hash`.
-   Programar toda la lógica del ESP32 antes de comprobar la API
    Laravel.
-   Crear una arquitectura paralela a la existente.
-   Sobrescribir modelos que ya existan sin revisar primero el proyecto.

------------------------------------------------------------------------

# 27. Próximo paso recomendado

El siguiente trabajo debe ser revisar la estructura actual de Laravel:

``` text
app/
├── Models/
├── Http/
│   └── Controllers/
└── ...

resources/
└── views/
    └── ...

routes/
```

Especialmente:

``` text
app/Models
app/Http/Controllers
resources/views
routes
```

También debe revisarse cómo está implementado actualmente:

``` text
sidebaradmincefa.blade.php
```

para integrar correctamente:

``` text
Configuración & IoT
└── Nodos IoT (ESP32)
```

sin romper el diseño existente.

Después se puede crear:

``` text
Node model
Node controller
Node routes
Node views
```

y posteriormente el registro/provisionamiento seguro del ESP32.

------------------------------------------------------------------------

# 28. Objetivo final de la primera fase

La primera fase estará terminada cuando se pueda hacer:

``` text
Administrador
     │
     ▼
Nodos IoT
     │
     ▼
Registrar ESP32-001
     │
     ▼
Asignarlo a un ambiente
     │
     ▼
ESP32 conectado por Wi-Fi
     │
     ▼
Lee MH-Z16 + DHT11
     │
     ▼
POST /api/iot/readings
     │
     ▼
Laravel valida token
     │
     ▼
sensor_readings
     │
     ▼
sensor_measurements
     │
     ▼
MySQL
     │
     ▼
Laravel muestra:
     │
     ├── CO₂
     ├── Temperatura
     └── Humedad
```

------------------------------------------------------------------------

# 29. Resumen para continuar el proyecto

Cuando se retome este proyecto, el contexto clave es:

**Proyecto:** AirSense CEFA

**Backend:** Laravel 13.33.0

**Entorno:** Laragon

**Base de datos:** MySQL

**Hardware:**

``` text
ESP32
MH-Z16
DHT11
```

**GPS:** todavía no disponible.

**Tablas IoT principales:**

``` text
environments
nodes
sensor_readings
sensor_measurements
node_categories
node_location_changes
```

**Autenticación del dispositivo:**

``` text
device_uid
+
device_token
```

**Token almacenado en BD:**

``` text
device_token_hash
```

**Endpoint previsto:**

``` text
POST /api/iot/readings
```

**Variables:**

``` text
co2         → ppm
temperature → °C
humidity    → %
```

**Primer módulo visual a implementar:**

``` text
Configuración & IoT
└── Nodos IoT (ESP32)
```

**Objetivo inmediato:**

Registrar el ESP32 desde Laravel, asociarlo posteriormente a un
ambiente, autenticarlo mediante token y preparar el flujo para que las
lecturas del MH-Z16 y DHT11 lleguen a MySQL.

------------------------------------------------------------------------

# 30. Nota sobre los documentos del proyecto

En conversaciones anteriores se utilizaron las historias de usuario y el
proceso de elicitación de AirSense CEFA para orientar la organización de
los módulos administrativos y la arquitectura funcional.

Los requisitos mencionados en este contexto incluyen, entre otros,
gestión de nodos IoT, autenticidad mediante identificador/token,
asignación de ambientes, monitoreo de conectividad, ubicación de nodos,
visualización de variables, históricos, umbrales y módulo predictivo.

Este archivo debe utilizarse como **contexto de continuidad de la
implementación técnica**. Cuando se retome el proyecto, primero se debe
revisar el estado real del código actual antes de crear o reemplazar
archivos.
