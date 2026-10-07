# 🗺️ Mapa Completo de Conexiones de Base de Datos (AirSense CEFA)

Este documento detalla **todas las conexiones a la base de datos MySQL**, especificando qué tabla lee cada controlador, qué modelo la gestiona, qué ruta la expone y qué vista Blade la muestra en pantalla.

---

## ⚙️ 1. Configuración Global de Conexión (`.env`)

Toda la aplicación se conecta a la base de datos MySQL configurada en el archivo [`.env`](file:///c:/laragon/www/airsense-cefa/.env#L23-L28):

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=airsensecefa
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📡 2. Módulo de Gestión de Nodos IoT (`ESP32 ➔ Ambientes`)

Vincula los dispositivos físicos de hardware (ESP32) con los ambientes de formación del CEFA.

### 🔗 Diagrama de Conexión:
`Navegador` ➔ `routes/web.php` ➔ `NodoController@index` ➔ `Node.php` ➔ `Tabla nodes` (MySQL) ➔ `index.blade.php`

### 📄 Archivos y Tablas Involucrados:

| Componente | Archivo / Tabla | Función y Conexión |
| --- | --- | --- |
| **Tabla MySQL** | `nodes` | Guarda la lista de dispositivos IoT, su token hash, coordenadas (Lat/Long) y la FK `environment_id`. |
| **Tabla MySQL** | `environments` | Guarda las aulas de formación (Hangar, Laboratorio, Aula 101, etc.). |
| **Modelo Eloquent** | [`app/Models/Node.php`](file:///c:/laragon/www/airsense-cefa/app/Models/Node.php) | Define `protected $table = 'nodes';` y la relación `belongsTo(Environment::class)`. |
| **Controlador** | [`app/Http/Controllers/Admin/NodoController.php`](file:///c:/laragon/www/airsense-cefa/app/Http/Controllers/Admin/NodoController.php#L15-L31) | Consulta: `Node::with('environment')->get();` y `Environment::where('is_active', true)->get();`. |
| **Ruta Web** | [`routes/web.php`](file:///c:/laragon/www/airsense-cefa/routes/web.php#L57) | `GET /admin/nodos` (`admin.nodos`) y `POST /admin/nodos/asignar-ambiente`. |
| **Vista Blade** | [`resources/views/admin/nodos/index.blade.php`](file:///c:/laragon/www/airsense-cefa/resources/views/admin/nodos/index.blade.php) | Renderiza las tarjetas de nodos y el modal de asignación de ambiente. |

---

## 👨‍🏫 3. Módulo de Asignación de Ambientes a Instructores (`Instructores ➔ Ambientes`)

Vincula a los docentes e instructores institucionales con su aula de formación correspondiente.

### 🔗 Diagrama de Conexión:
`Navegador` ➔ `routes/web.php` ➔ `AsignacionController@index` ➔ `EnvironmentAssignment.php` ➔ `Tabla environment_assignments` (MySQL) ➔ `index.blade.php`

### 📄 Archivos y Tablas Involucrados:

| Componente | Archivo / Tabla | Función y Conexión |
| --- | --- | --- |
| **Tabla MySQL** | `environment_assignments` | Almacena el historial y vínculo vigente (`is_current = true`) entre instructor y aula. |
| **Tabla MySQL** | `users` | Almacena los datos del personal (Instructores y Administradores). |
| **Tabla MySQL** | `environments` | Almacena las aulas de formación activas. |
| **Modelo Eloquent** | [`app/Models/EnvironmentAssignment.php`](file:///c:/laragon/www/airsense-cefa/app/Models/EnvironmentAssignment.php) | Define `protected $table = 'environment_assignments';` y las relaciones `environment()`, `instructor()` y `assignedBy()`. |
| **Controlador** | [`app/Http/Controllers/Admin/AsignacionController.php`](file:///c:/laragon/www/airsense-cefa/app/Http/Controllers/Admin/AsignacionController.php#L15-L48) | Consulta: `User::where('role', 'INSTRUCTOR')`, `Environment::all()` y `EnvironmentAssignment::where('is_current', true)`. |
| **Ruta Web** | [`routes/web.php`](file:///c:/laragon/www/airsense-cefa/routes/web.php#L60-L62) | `GET /admin/asignaciones`, `POST /admin/asignaciones` y `DELETE /admin/asignaciones/{id}`. |
| **Vista Blade** | [`resources/views/admin/asignaciones/index.blade.php`](file:///c:/laragon/www/airsense-cefa/resources/views/admin/asignaciones/index.blade.php) | Renderiza la lista de instructores, estado de asignación y el modal de selección de aula. |

---

## 👥 4. Módulo de Usuarios y Roles (RBAC)

Gestiona la autenticación y los permisos por perfil (Administrador, SST e Instructor).

### 📄 Archivos y Tablas Involucrados:

| Componente | Archivo / Tabla | Función y Conexión |
| --- | --- | --- |
| **Tabla MySQL** | `roles` | Registra los roles del sistema (`ADMIN`, `SST`, `INSTRUCTOR`). |
| **Tabla MySQL** | `users` | Guarda las cuentas de los usuarios con la FK `role_id`. |
| **Modelo Eloquent** | [`app/Models/User.php`](file:///c:/laragon/www/airsense-cefa/app/Models/User.php) | Define el modelo de usuarios con la relación `role()`. |
| **Modelo Eloquent** | [`app/Models/Role.php`](file:///c:/laragon/www/airsense-cefa/app/Models/Role.php) | Define el modelo de roles. |
| **Vista Sidebar** | [`resources/views/layouts/sidebaradmincefa.blade.php`](file:///c:/laragon/www/airsense-cefa/resources/views/layouts/sidebaradmincefa.blade.php) | Menú de navegación condicionado por el rol del usuario autenticado. |

---

## 📊 5. Módulo de Telemetría y Lectura de Sensores

Recibe los datos ambientales del hardware ESP32 (CO₂, Temperatura, Humedad).

### 📄 Archivos y Tablas Involucrados:

| Componente | Archivo / Tabla | Función y Conexión |
| --- | --- | --- |
| **Tabla MySQL** | `sensor_readings` | Registra cada paquete de telemetría entrante por nodo. |
| **Tabla MySQL** | `sensor_measurements` | Guarda los valores numéricos individuales de CO₂, temperatura y humedad. |
| **Modelo Eloquent** | [`app/Models/SensorReading.php`](file:///c:/laragon/www/airsense-cefa/app/Models/SensorReading.php) | Gestiona las lecturas de telemetría. |
| **Modelo Eloquent** | [`app/Models/SensorMeasurement.php`](file:///c:/laragon/www/airsense-cefa/app/Models/SensorMeasurement.php) | Gestiona los valores individuales de las variables. |
| **Controlador API** | `app/Http/Controllers/Api/v1/IoTTelemetryController.php` | Recibe la solicitud HTTP `POST` del ESP32 vía Wi-Fi y guarda en MySQL. |

---

## 🔍 Resumen de URLs y Rutas del Sistema

- **Página de Inicio / Login:** `http://127.0.0.1:8000/`
- **Dashboard Administrador:** `http://127.0.0.1:8000/admin/dashboard`
- **Gestión de Nodos IoT:** `http://127.0.0.1:8000/admin/nodos`
- **Asignación de Ambientes a Instructores:** `http://127.0.0.1:8000/admin/asignaciones`
