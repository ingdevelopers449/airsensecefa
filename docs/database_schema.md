# Documentación del esquema de base de datos

Este documento describe cada tabla presente en la base de datos de **AirSense‑CEFA**, su propósito y los campos principales. Las descripciones se basan en las migraciones del proyecto.

---

## `environments`
- **Propósito**: Define los entornos físicos (CEFA) donde se despliegan los nodos.
- **Columnas principales**:
  - `id` – PK autoincremental.
  - `code` – Código único del entorno.
  - `name` – Nombre descriptivo.
  - `description` – Texto opcional.
  - `current_semaphore_state` – Estado del semáforo (`green`, `yellow`, `red`, `no_data`).
  - `current_state_updated_at` – Timestamp del último cambio de estado.
  - `is_active` – Flag de activación.
  - `created_at` / `updated_at`.

---

## `node_categories`
- **Propósito**: Categorías para clasificar los nodos (p. ej., sensor, cámara).
- **Columnas**: `id`, `name` (único), `description`, `is_active`, timestamps.

---

## `nodes`
- **Propósito**: Registra cada dispositivo IoT instalado.
- **Columnas**: `id`, `environment_id` (FK), `device_uid` (único), `device_token_hash`, `token_version`, `name`, `is_active`, `connectivity_status` (`online`, `offline`, `unknown`), `last_seen_at`, `last_keep_alive_at`, `last_reported_latitude`, `last_reported_longitude`, `token_rotated_at`, `first_seen_at`, timestamps, índices de rendimiento.

---

## `map_points`
- **Propósito**: Ubicaciones geográficas de los nodos en el mapa.
- **Columnas**: `id`, `node_id` (FK, único), `category_id` (FK), `place_name`, `latitude`, `longitude`, `is_active`, timestamps.

---

## `node_location_changes`
- **Propósito**: Historial de cambios de ubicación de un nodo.
- **Columnas**: `id`, `node_id` (FK), `previous_place_name`, `previous_latitude`, `previous_longitude`, `new_place_name`, `new_latitude`, `new_longitude`, `changed_by` (FK a `users`), `confirmed_at`, `created_at`.

---

## `environment_assignments`
- **Propósito**: Asigna usuarios a entornos.
- **Columnas**: `id`, `environment_id` (FK), `user_id` (FK), `assigned_at`, `revoked_at` (nullable), `is_active`.

---

## `attendance_confirmations`
- **Propósito**: Confirmaciones de asistencia de usuarios a un entorno.
- **Columnas**: `id`, `environment_id` (FK), `user_id` (FK), `confirmed_at`, `status` (`present`, `absent`).

---

## `occupancy_records`
- **Propósito**: Registro de ocupación de un entorno en periodos de tiempo.
- **Columnas**: `id`, `environment_id` (FK), `recorded_at`, `occupancy_count`.

---

## `environment_change_events`
- **Propósito**: Eventos de cambio de estado del entorno (p. ej., alarma).
- **Columnas**: `id`, `environment_id` (FK), `event_type`, `description`, `occurred_at`.

---

## `audit_logs`
- **Propósito**: Registro de acciones importantes para auditoría.
- **Columnas**: `id`, `user_id` (FK), `action`, `model_type`, `model_id`, `old_values`, `new_values`, `created_at`.

---

## `system_settings`
- **Propósito**: Configuraciones globales del sistema.
- **Columnas**: `id`, `key`, `value`, `description`, `created_at`, `updated_at`.

---

## `environmental_thresholds`
- **Propósito**: Umbrales de parámetros ambientales (CO₂, temperatura, etc.) por entorno.
- **Columnas**: `id`, `environment_id` (FK), `parameter`, `min_value`, `max_value`.

---

## `users`
- **Propósito**: Usuarios del portal (administradores, operadores).
- **Columnas**: `id`, `name`, `email` (único), `password`, `email_verified_at`, `remember_token`, `is_active`, timestamps.

---

## `password_reset_tokens`
- **Propósito**: Tokens para restablecer contraseñas.
- **Columnas**: `email`, `token`, `created_at`.

---

## `sessions`
- **Propósito**: Información de sesiones autenticadas.
- **Columnas**: `id`, `user_id` (FK), `ip_address`, `user_agent`, `payload`, `last_activity`.

---

## `roles`
- **Propósito**: Roles de autorización (admin, supervisor, operador).
- **Columnas**: `id`, `name`, `guard_name`, `created_at`, `updated_at`.

---

## `permissions`
- **Propósito**: Permisos granulares asignables a roles.
- **Columnas**: `id`, `name`, `guard_name`, `created_at`, `updated_at`.

---

## `role_permissions`
- **Propósito**: Relación many‑to‑many entre `roles` y `permissions`.
- **Columnas**: `role_id`, `permission_id`.

---

## `jobs`
- **Propósito**: Tabla de trabajos encolados por Laravel Queue.
- **Columnas**: `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`.

---

## `job_batches`
- **Propósito**: Agrupa varios `jobs` en lotes.
- **Columnas**: `id`, `name`, `total_jobs`, `processed_jobs`, `failed_jobs`, `cancelled_at`, `created_at`, `finished_at`.

---

## `failed_jobs`
- **Propósito**: Registro de trabajos que fallaron.
- **Columnas**: `id`, `connection`, `queue`, `payload`, `exception`, `failed_at`.

---

## `cache`
- **Propósito**: Almacén de caché genérico.
- **Columnas**: `key`, `value`, `expiration`.

---

## `cache_locks`
- **Propósito**: Bloqueos de caché para sincronización.
- **Columnas**: `key`, `owner`, `expiration`.

---

## `contingency_protocols`
- **Propósito**: Protocolos de contingencia para situaciones de emergencia.
- **Columnas**: `id`, `name`, `description`, `is_active`, timestamps.

---

## `maintenance_windows`
- **Propósito**: Ventanas de mantenimiento programado.
- **Columnas**: `id`, `start_at`, `end_at`, `description`, `is_active`.

---

## `alerts`
- **Propósito**: Alertas generadas por el sistema (p. ej., umbral excedido).
- **Columnas**: `id`, `environment_id` (FK), `type`, `severity`, `message`, `generated_at`, `is_resolved`.

---

## `notifications`
- **Propósito**: Notificaciones enviadas a usuarios.
- **Columnas**: `id`, `user_id` (FK), `title`, `body`, `data` (JSON), `read_at`, `created_at`.

---

## `system_events`
- **Propósito**: Eventos internos del sistema (arranque, actualización).
- **Columnas**: `id`, `event_type`, `description`, `occurred_at`.

---

## `mqtt_publications`
- **Propósito**: Mensajes publicados vía MQTT.
- **Columnas**: `id`, `topic`, `payload`, `qos`, `published_at`.

---

## `system_performance_metrics`
- **Propósito**: Métricas de rendimiento (CPU, RAM, latencia).
- **Columnas**: `id`, `metric_name`, `value`, `recorded_at`.

---

## `risk_categories`
- **Propósito**: Categorías de riesgo para análisis predictivo.
- **Columnas**: `id`, `name`, `description`.

---

## `manuals`
- **Propósito**: Manuales operativos.
- **Columnas**: `id`, `title`, `version`, `published_at`.

---

## `manual_sections`
- **Propósito**: Secciones de un manual.
- **Columnas**: `id`, `manual_id` (FK), `title`, `content` (text).

---

## `protocols`
- **Propósito**: Protocolos de operación.
- **Columnas**: `id`, `name`, `description`.

---

## `predictions`
- **Propósito**: Predicciones generadas por el módulo IA.
- **Columnas**: `id`, `node_id` (FK), `prediction_type`, `value`, `predicted_at`.

---

## `prediction_points`
- **Propósito**: Puntos de datos usados para entrenar predicciones.
- **Columnas**: `id`, `prediction_id` (FK), `latitude`, `longitude`, `timestamp`.

---

## `sensor_readings`
- **Propósito**: Lecturas crudas de sensores (CO₂, temperatura, humedad).
- **Columnas**: `id`, `node_id` (FK), `sensor_type`, `value`, `recorded_at`.

---

## `sensor_measurements`
- **Propósito**: Medidas derivadas de las lecturas (promedios, estadísticos).
- **Columnas**: `id`, `reading_id` (FK), `metric`, `value`.

---

*Este documento puede ser convertido a PDF mediante `pandoc` o `wkhtmltopdf` para su distribución.*
