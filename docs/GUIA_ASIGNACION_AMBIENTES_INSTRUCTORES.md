# 📘 Guía Completa de Sustentación: Asignación de Ambientes a Instructores (AirSense CEFA)

Esta guía contiene la explicación detallada, paso a paso y sin tecnicismos complejos, de todo lo que se construyó en el módulo de **Asignación de Ambientes a Instructores** (`User ➔ Environment`). Sirve como documento de consulta para entender las conexiones entre archivos, cómo viajan los datos y cómo sustentar el proyecto en una presentación o comité.

---

## 🎯 1. ¿Qué es y para qué sirve este módulo?

El módulo de **Asignación de Ambientes a Instructores** responde a las **Historias de Usuario HU-005 y HU-013**.

- **Diferencia clave con la Gestión de Nodos IoT:**
  - **Nodos IoT (ESP32):** Conecta un dispositivo físico con un aula de formación (`Node ➔ Environment`).
  - **Asignación de Ambientes (Instructores):** Asigna un **Docente/Instructor** con su **Aula de Formación** (`User (Instructor) ➔ Environment`).

- **Propósito en el sistema:**
  Permite que el Administrador defina qué instructor está dictando clase en qué aula (ejemplo: *Instructor Juan Pérez ➔ Hangar de Ganadería*). Así, al iniciar sesión el instructor, el sistema le muestra directamente las variables de calidad del aire y la toma de aforo anónimo de su aula asignada.

---

## 🧭 2. Mapa de Conexión entre Archivos (¿Cómo viajan los datos?)

```
[ NAVEGADOR ]
     │
     │  1. El usuario hace clic en "Personal ➔ Asignar Ambientes" (http://127.0.0.1:8000/admin/asignaciones)
     ▼
[ RUTAS: routes/web.php ]
     │  Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('asignaciones.index');
     ▼
[ CONTROLADOR: app/Http/Controllers/Admin/AsignacionController.php ]
     │  Ejecuta el método index()
     │
     ├─► Consulta 1: User::where('role', 'INSTRUCTOR') ──► (Trae los instructores activos)
     ├─► Consulta 2: Environment::where('is_active', true) ──► (Trae los ambientes de formación)
     ├─► Consulta 3: EnvironmentAssignment::where('is_current', true) ──► (Trae asignaciones vigentes)
     │
     ▼  Envía las variables a la vista mediante compact()
[ VISTA BLADE: resources/views/admin/asignaciones/index.blade.php ]
     │
     │  Renderiza la lista de tarjetas y construye un modal por cada instructor
     │
     │  2. El Administrador selecciona un ambiente en el <select> y presiona "Guardar Asignación"
     ▼
[ RUTAS: routes/web.php ]
     │  Route::post('/asignaciones', [AsignacionController::class, 'store'])->name('asignaciones.store');
     ▼
[ CONTROLADOR: AsignacionController@store ]
     │
     ├─► 1. Valida los datos recibidos ($request->validate)
     ├─► 2. Desactiva asignaciones anteriores del instructor (is_current = false, ended_at = now())
     ├─► 3. Desactiva asignaciones previas del ambiente si existían
     ├─► 4. Crea el nuevo registro en MySQL (EnvironmentAssignment::create)
     │
     ▼  Redirige a 'admin.asignaciones.index' con un aviso de éxito (.with('success'))
[ NAVEGADOR ]
     │  Muestra la lista de asignaciones actualizada + El banner verde de confirmación.
```

---

## 📄 3. Explicación Detallada Archivo por Archivo

### 1. El Modelo de Eloquent ([`app/Models/EnvironmentAssignment.php`](file:///c:/laragon/www/airsense-cefa/app/Models/EnvironmentAssignment.php))
Representa la tabla `environment_assignments` en MySQL.
- **Campos principales (`$fillable`):** `environment_id`, `instructor_user_id`, `assigned_by`, `assigned_at`, `ended_at`, `is_current`.
- **Relaciones Eloquent:**
  - `environment()`: Obtiene los datos del ambiente (`Environment`).
  - `instructor()`: Obtiene los datos del docente (`User`).
  - `assignedBy()`: Obtiene los datos del Administrador que realizó la asignación (`User`).

### 2. El Controlador ([`app/Http/Controllers/Admin/AsignacionController.php`](file:///c:/laragon/www/airsense-cefa/app/Http/Controllers/Admin/AsignacionController.php))
- **`index()`**: Prepara las listas de instructores, ambientes activos y asignaciones vigentes (`is_current = true`).
- **`store(Request $request)`**: Recibe el formulario, finaliza la asignación previa que tenía el instructor o el ambiente y crea la nueva asignación activa asociando la ID del Administrador en sesión (`Auth::id()`).
- **`destroy($id)`**: Permite desvincular un ambiente finalizando la asignación activa (`is_current = false`).

### 3. Las Rutas ([`routes/web.php`](file:///c:/laragon/www/airsense-cefa/routes/web.php#L60-L62))
Ubicadas dentro del grupo con autenticación y prefijo `admin`:
- `GET /admin/asignaciones` ➔ `AsignacionController@index` (`admin.asignaciones.index`)
- `POST /admin/asignaciones` ➔ `AsignacionController@store` (`admin.asignaciones.store`)
- `DELETE /admin/asignaciones/{id}` ➔ `AsignacionController@destroy` (`admin.asignaciones.destroy`)

### 4. La Vista Blade ([`resources/views/admin/asignaciones/index.blade.php`](file:///c:/laragon/www/airsense-cefa/resources/views/admin/asignaciones/index.blade.php))
- **Tarjetas de instructores:** Muestra la foto/avatar del docente, correo, ambiente asignado o insignia *"Sin ambiente asignado"*.
- **Filtros en tiempo real (AlpineJS):** Pestañas para filtrar por *Todos*, *Con ambiente* o *Sin ambiente*, más barra de búsqueda por texto.
- **Modal de asignación:** Formulario emergente con desplegable `<select>` cargado dinámicamente con las aulas de formación activas.

### 5. El Menú Lateral ([`resources/views/layouts/sidebaradmincefa.blade.php`](file:///c:/laragon/www/airsense-cefa/resources/views/layouts/sidebaradmincefa.blade.php#L87))
Enlaza la opción de menú **Personal ➔ Asignar Ambientes** apuntando a `route('admin.asignaciones.index')` con resaltado activo.

---

## 🗄️ 4. Estructura en la Base de Datos (`environment_assignments`)

| Columna | Tipo | Descripción |
| --- | --- | --- |
| `id` | BigInt (PK) | Identificador único del registro. |
| `environment_id` | Foreign Key | ID del ambiente de formación asignado (`environments.id`). |
| `instructor_user_id` | Foreign Key | ID del instructor asignado (`users.id`). |
| `assigned_by` | Foreign Key | ID del administrador que hizo la asignación (`users.id`). |
| `assigned_at` | DateTime | Fecha y hora exacta de la asignación. |
| `ended_at` | DateTime (Nullable) | Fecha y hora en que finalizó la asignación. |
| `is_current` | Boolean | `true` si es la asignación vigente, `false` si ya finalizó. |

---

## ❓ 5. Preguntas Frecuentes para Sustentar

1. **¿Qué pasa si a un instructor se le asigna un nuevo ambiente?**
   - El controlador encuentra la asignación anterior con `is_current = true`, la marca como `false` registrando la fecha de cierre en `ended_at`, y crea una nueva asignación activa (`is_current = true`). De esta manera se conserva todo el historial para auditorías.

2. **¿Por qué se guarda `assigned_by`?**
   - Para garantizar la trazabilidad de seguridad exigida por la normativa SST y auditoría, dejando registro de cuál usuario administrador realizó el cambio.

3. **¿Cómo sabe la aplicación qué ambiente mostrarle a un instructor al iniciar sesión?**
   - Al iniciar sesión con rol `INSTRUCTOR`, el sistema consulta la tabla `environment_assignments` buscando el registro donde `instructor_user_id` coincida con la ID del usuario en sesión y `is_current = true`.
