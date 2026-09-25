CREATE DATABASE IF NOT EXISTS airsensecefa
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE air_sense_cefa;

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    code VARCHAR(50) NOT NULL,
    description VARCHAR(255) NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_roles_code (code),
    UNIQUE KEY uq_roles_name (name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) NOT NULL,
    name VARCHAR(150) NOT NULL,
    module VARCHAR(80) NOT NULL,
    description VARCHAR(255) NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_permissions_code (code)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_role_permissions_permission
        FOREIGN KEY (permission_id) REFERENCES permissions(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(180) NOT NULL,
    email VARCHAR(180) NOT NULL,
    email_verified_at DATETIME NULL,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    remember_token VARCHAR(100) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_role_active (role_id, is_active),
    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS auth_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    last_activity_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    expires_at DATETIME(6) NULL,
    revoked_at DATETIME(6) NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_auth_sessions_token (token_hash),
    KEY idx_auth_sessions_user_active (user_id, revoked_at, expires_at),
    CONSTRAINT fk_auth_sessions_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME(6) NOT NULL,
    used_at DATETIME(6) NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_password_reset_token (token_hash),
    KEY idx_password_reset_user (user_id, expires_at),
    CONSTRAINT fk_password_reset_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 2. AUDITORÍA Y CONFIGURACIÓN GLOBAL
-- HU-004, HU-006, HU-031, HU-036, HU-038, HU-046, HU-051, HU-056
-- ============================================================

CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    category VARCHAR(100) NOT NULL,
    action VARCHAR(150) NOT NULL,
    success TINYINT(1) NOT NULL DEFAULT 1,
    target_type VARCHAR(100) NULL,
    target_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    attempted_email VARCHAR(180) NULL,
    description TEXT NULL,
    metadata JSON NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_audit_created (created_at),
    KEY idx_audit_category_action (category, action, created_at),
    KEY idx_audit_user_created (user_id, created_at),
    KEY idx_audit_failed_auth (category, success, created_at),
    CONSTRAINT fk_audit_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS system_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(120) NOT NULL,
    setting_value VARCHAR(255) NOT NULL,
    value_type ENUM('integer','decimal','boolean','string','json') NOT NULL DEFAULT 'string',
    description VARCHAR(255) NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_system_settings_key (setting_key),
    CONSTRAINT fk_system_settings_user
        FOREIGN KEY (updated_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS environmental_thresholds (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    variable_type ENUM('co2','temperature','humidity') NOT NULL,
    operational_min DECIMAL(12,4) NULL,
    operational_max DECIMAL(12,4) NULL,
    warning_min DECIMAL(12,4) NULL,
    warning_max DECIMAL(12,4) NULL,
    critical_min DECIMAL(12,4) NULL,
    critical_max DECIMAL(12,4) NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_threshold_variable (variable_type),
    CONSTRAINT fk_threshold_updated_by
        FOREIGN KEY (updated_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- 3. AMBIENTES, NODOS Y MAPA
-- HU-007 a HU-009, HU-035 a HU-042, HU-045, HU-046, HU-048, HU-056
-- ============================================================

CREATE TABLE IF NOT EXISTS environments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(60) NOT NULL,
    name VARCHAR(180) NOT NULL,
    description TEXT NULL,
    current_semaphore_state ENUM('green','yellow','red','no_data') NOT NULL DEFAULT 'no_data',
    current_state_updated_at DATETIME(6) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_environments_code (code),
    UNIQUE KEY uq_environments_name (name),
    KEY idx_environments_active (is_active)
) ENGINE=InnoDB;

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

CREATE TABLE IF NOT EXISTS map_points (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    node_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NULL,
    place_name VARCHAR(180) NOT NULL,
    latitude DECIMAL(10,7) NOT NULL,
    longitude DECIMAL(10,7) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_map_points_node (node_id),
    KEY idx_map_points_category (category_id),
    KEY idx_map_points_coordinates (latitude, longitude),
    CONSTRAINT fk_map_points_node
        FOREIGN KEY (node_id) REFERENCES nodes(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_map_points_category
        FOREIGN KEY (category_id) REFERENCES node_categories(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS node_location_changes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    node_id BIGINT UNSIGNED NOT NULL,
    previous_place_name VARCHAR(180) NULL,
    previous_latitude DECIMAL(10,7) NOT NULL,
    previous_longitude DECIMAL(10,7) NOT NULL,
    new_place_name VARCHAR(180) NOT NULL,
    new_latitude DECIMAL(10,7) NOT NULL,
    new_longitude DECIMAL(10,7) NOT NULL,
    changed_by BIGINT UNSIGNED NOT NULL,
    confirmed_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_location_changes_node_date (node_id, confirmed_at),
    KEY idx_location_changes_user_date (changed_by, confirmed_at),
    CONSTRAINT fk_location_changes_node
        FOREIGN KEY (node_id) REFERENCES nodes(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_location_changes_user
        FOREIGN KEY (changed_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- 4. ASIGNACIÓN DE AMBIENTES E INICIO DE JORNADA
-- HU-042, HU-043, HU-044, HU-013, HU-032
-- ============================================================

CREATE TABLE IF NOT EXISTS environment_assignments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    environment_id BIGINT UNSIGNED NOT NULL,
    instructor_user_id BIGINT UNSIGNED NOT NULL,
    assigned_by BIGINT UNSIGNED NULL,
    assigned_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    ended_at DATETIME(6) NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_assignment_environment_current (environment_id, is_current),
    UNIQUE KEY uq_assignment_instructor_current (instructor_user_id, is_current),
    KEY idx_assignment_environment_dates (environment_id, assigned_at, ended_at),
    CONSTRAINT fk_assignment_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_assignment_instructor
        FOREIGN KEY (instructor_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_assignment_admin
        FOREIGN KEY (assigned_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS attendance_confirmations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    instructor_user_id BIGINT UNSIGNED NOT NULL,
    assigned_environment_id BIGINT UNSIGNED NOT NULL,
    actual_environment_id BIGINT UNSIGNED NOT NULL,
    confirmation_date DATE NOT NULL,
    confirmed_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_attendance_confirmation_day (instructor_user_id, confirmation_date),
    KEY idx_attendance_environment_date (actual_environment_id, confirmation_date),
    CONSTRAINT fk_attendance_instructor
        FOREIGN KEY (instructor_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_attendance_assigned_environment
        FOREIGN KEY (assigned_environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_attendance_actual_environment
        FOREIGN KEY (actual_environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Este registro es deliberadamente ANÓNIMO respecto al instructor.
-- No contiene instructor_user_id ni attendance_confirmation_id.
CREATE TABLE IF NOT EXISTS occupancy_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    environment_id BIGINT UNSIGNED NOT NULL,
    observed_at DATETIME(6) NOT NULL,
    occupant_count INT UNSIGNED NOT NULL,
    source ENUM('manual','other') NOT NULL DEFAULT 'manual',
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_occupancy_environment_time (environment_id, observed_at),
    CONSTRAINT fk_occupancy_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS environment_change_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    instructor_user_id BIGINT UNSIGNED NOT NULL,
    assigned_environment_id BIGINT UNSIGNED NOT NULL,
    actual_environment_id BIGINT UNSIGNED NOT NULL,
    student_count INT UNSIGNED NOT NULL,
    occurred_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    notification_status ENUM('pending','sent','failed','retrying') NOT NULL DEFAULT 'pending',
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_environment_changes_instructor_date (instructor_user_id, occurred_at),
    KEY idx_environment_changes_date (occurred_at),
    CONSTRAINT fk_environment_change_instructor
        FOREIGN KEY (instructor_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_environment_change_assigned
        FOREIGN KEY (assigned_environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_environment_change_actual
        FOREIGN KEY (actual_environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- 5. LECTURAS DE SENSORES
-- HU-015, HU-016, HU-017, HU-028, HU-029, HU-034, HU-049
-- ============================================================

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

CREATE TABLE IF NOT EXISTS sensor_measurements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reading_id BIGINT UNSIGNED NOT NULL,
    variable_type ENUM('co2','temperature','humidity') NOT NULL,
    value DECIMAL(14,4) NOT NULL,
    unit VARCHAR(20) NOT NULL,
    is_valid TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_measurement_reading_variable (reading_id, variable_type),
    KEY idx_measurement_variable_time (variable_type, created_at),
    KEY idx_measurement_reading (reading_id),
    CONSTRAINT fk_measurements_reading
        FOREIGN KEY (reading_id) REFERENCES sensor_readings(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 6. ALERTAS Y NOTIFICACIONES
-- HU-010, HU-021, HU-025, HU-027, HU-030, HU-044
-- ============================================================

CREATE TABLE IF NOT EXISTS alerts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    environment_id BIGINT UNSIGNED NOT NULL,
    node_id BIGINT UNSIGNED NULL,
    reading_id BIGINT UNSIGNED NULL,
    prediction_id BIGINT UNSIGNED NULL,
    protocol_id BIGINT UNSIGNED NULL,
    alert_type ENUM('critical','predictive') NOT NULL,
    severity ENUM('warning','critical') NOT NULL,
    variable_type ENUM('co2','temperature','humidity','combined') NOT NULL,
    measured_value DECIMAL(14,4) NULL,
    threshold_value DECIMAL(14,4) NULL,
    estimated_minutes_to_critical DECIMAL(10,2) NULL,
    confidence DECIMAL(7,4) NULL,
    status ENUM('active','resolved','discarded','unconfirmed') NOT NULL DEFAULT 'active',
    triggered_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    resolved_at DATETIME(6) NULL,
    discarded_at DATETIME(6) NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_alerts_environment_status_time (environment_id, status, triggered_at),
    KEY idx_alerts_type_status (alert_type, status, triggered_at),
    KEY idx_alerts_node_time (node_id, triggered_at),
    CONSTRAINT fk_alerts_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_alerts_node
        FOREIGN KEY (node_id) REFERENCES nodes(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_alerts_reading
        FOREIGN KEY (reading_id) REFERENCES sensor_readings(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipient_user_id BIGINT UNSIGNED NOT NULL,
    alert_id BIGINT UNSIGNED NULL,
    environment_change_event_id BIGINT UNSIGNED NULL,
    maintenance_window_id BIGINT UNSIGNED NULL,
    notification_type ENUM('critical_alert','predictive_alert','environment_change','maintenance','system') NOT NULL,
    channel ENUM('visual','email') NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    delivery_status ENUM('pending','sent','failed','read') NOT NULL DEFAULT 'pending',
    sent_at DATETIME(6) NULL,
    read_at DATETIME(6) NULL,
    error_message TEXT NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_notifications_user_status (recipient_user_id, delivery_status, created_at),
    KEY idx_notifications_alert (alert_id, created_at),
    CONSTRAINT fk_notifications_user
        FOREIGN KEY (recipient_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_notifications_alert
        FOREIGN KEY (alert_id) REFERENCES alerts(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_notifications_environment_change
        FOREIGN KEY (environment_change_event_id) REFERENCES environment_change_events(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 7. MANUAL / PROTOCOLOS SST
-- HU-011, HU-018, HU-050
-- ============================================================

CREATE TABLE IF NOT EXISTS risk_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(60) NOT NULL,
    name VARCHAR(120) NOT NULL,
    description VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    UNIQUE KEY uq_risk_categories_code (code),
    UNIQUE KEY uq_risk_categories_name (name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS manuals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    version VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 0,
    published_by BIGINT UNSIGNED NULL,
    published_at DATETIME(6) NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    KEY idx_manuals_current (is_current, published_at),
    CONSTRAINT fk_manuals_published_by
        FOREIGN KEY (published_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS manual_sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manual_id BIGINT UNSIGNED NOT NULL,
    risk_category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    KEY idx_manual_sections_manual_order (manual_id, sort_order),
    CONSTRAINT fk_manual_sections_manual
        FOREIGN KEY (manual_id) REFERENCES manuals(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_manual_sections_category
        FOREIGN KEY (risk_category_id) REFERENCES risk_categories(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS protocols (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    risk_category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    instructions LONGTEXT NOT NULL,
    trigger_variable ENUM('co2','temperature','humidity','combined','occupancy','system') NOT NULL,
    trigger_state ENUM('green','yellow','red','predictive','any') NOT NULL DEFAULT 'any',
    version VARCHAR(50) NOT NULL DEFAULT '1.0',
    approved_by BIGINT UNSIGNED NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    KEY idx_protocols_trigger (trigger_variable, trigger_state, is_active),
    CONSTRAINT fk_protocols_category
        FOREIGN KEY (risk_category_id) REFERENCES risk_categories(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_protocols_approved_by
        FOREIGN KEY (approved_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- 8. PREDICCIONES / INTELIGENCIA ARTIFICIAL
-- HU-014, HU-019, HU-020, HU-021, HU-022, HU-033, HU-052
-- ============================================================

CREATE TABLE IF NOT EXISTS predictions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    environment_id BIGINT UNSIGNED NOT NULL,
    model_version VARCHAR(100) NOT NULL,
    status ENUM('learning','completed','failed','superseded') NOT NULL DEFAULT 'learning',
    sample_count INT UNSIGNED NOT NULL DEFAULT 0,
    confidence DECIMAL(7,4) NULL,
    risk_level ENUM('low','moderate','high') NULL,
    justification TEXT NULL,
    generated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    valid_until DATETIME(6) NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_predictions_environment_current (environment_id, is_current, generated_at),
    KEY idx_predictions_status (status, generated_at),
    CONSTRAINT fk_predictions_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS prediction_points (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prediction_id BIGINT UNSIGNED NOT NULL,
    variable_type ENUM('co2','temperature','humidity','combined') NOT NULL,
    predicted_at DATETIME(6) NOT NULL,
    predicted_value DECIMAL(14,4) NULL,
    unit VARCHAR(20) NOT NULL,
    confidence DECIMAL(7,4) NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_prediction_points_curve (prediction_id, variable_type, predicted_at),
    CONSTRAINT fk_prediction_points_prediction
        FOREIGN KEY (prediction_id) REFERENCES predictions(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 9. TRAZABILIDAD AUTOMÁTICA Y COMUNICACIÓN CON NODOS
-- HU-030, HU-036, HU-051, HU-056
-- ============================================================

CREATE TABLE IF NOT EXISTS system_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(100) NOT NULL,
    environment_id BIGINT UNSIGNED NULL,
    node_id BIGINT UNSIGNED NULL,
    reading_id BIGINT UNSIGNED NULL,
    alert_id BIGINT UNSIGNED NULL,
    prediction_id BIGINT UNSIGNED NULL,
    variable_type ENUM('co2','temperature','humidity','combined','system') NULL,
    event_value DECIMAL(14,4) NULL,
    previous_state ENUM('green','yellow','red','no_data') NULL,
    new_state ENUM('green','yellow','red','no_data') NULL,
    occurred_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    details JSON NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_system_events_environment_time (environment_id, occurred_at),
    KEY idx_system_events_node_time (node_id, occurred_at),
    KEY idx_system_events_type_time (event_type, occurred_at),
    CONSTRAINT fk_system_events_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_system_events_node
        FOREIGN KEY (node_id) REFERENCES nodes(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_system_events_reading
        FOREIGN KEY (reading_id) REFERENCES sensor_readings(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_system_events_alert
        FOREIGN KEY (alert_id) REFERENCES alerts(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_system_events_prediction
        FOREIGN KEY (prediction_id) REFERENCES predictions(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mqtt_publications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    node_id BIGINT UNSIGNED NOT NULL,
    environment_id BIGINT UNSIGNED NOT NULL,
    semaphore_state ENUM('green','yellow','red') NOT NULL,
    priority ENUM('normal','high') NOT NULL DEFAULT 'normal',
    topic VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    published_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    ack_received_at DATETIME(6) NULL,
    status ENUM('pending','published','confirmed','failed') NOT NULL DEFAULT 'pending',
    error_message TEXT NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_mqtt_node_time (node_id, published_at),
    KEY idx_mqtt_status_time (status, published_at),
    CONSTRAINT fk_mqtt_node
        FOREIGN KEY (node_id) REFERENCES nodes(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_mqtt_environment
        FOREIGN KEY (environment_id) REFERENCES environments(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- 10. MANTENIMIENTO Y RENDIMIENTO
-- HU-053, HU-057
-- ============================================================

CREATE TABLE IF NOT EXISTS maintenance_windows (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    scheduled_by BIGINT UNSIGNED NOT NULL,
    starts_at DATETIME(6) NOT NULL,
    ends_at DATETIME(6) NOT NULL,
    estimated_duration_minutes SMALLINT UNSIGNED NOT NULL,
    notice_created_at DATETIME(6) NULL,
    message TEXT NOT NULL,
    status ENUM('scheduled','active','completed','cancelled') NOT NULL DEFAULT 'scheduled',
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
        ON UPDATE CURRENT_TIMESTAMP(6),
    KEY idx_maintenance_schedule (starts_at, ends_at, status),
    CONSTRAINT fk_maintenance_scheduled_by
        FOREIGN KEY (scheduled_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS system_performance_metrics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    measured_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    active_nodes INT UNSIGNED NOT NULL DEFAULT 0,
    concurrent_users INT UNSIGNED NOT NULL DEFAULT 0,
    alert_latency_ms INT UNSIGNED NULL,
    dashboard_update_latency_ms INT UNSIGNED NULL,
    status ENUM('normal','degraded') NOT NULL DEFAULT 'normal',
    details JSON NULL,
    created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    KEY idx_performance_measured (measured_at),
    KEY idx_performance_status_time (status, measured_at)
) ENGINE=InnoDB;

-- ============================================================
-- RELACIONES DIFERIDAS (las tablas referenciadas ya existen aquí)
-- ============================================================

ALTER TABLE alerts
    ADD CONSTRAINT fk_alerts_prediction
        FOREIGN KEY (prediction_id) REFERENCES predictions(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT fk_alerts_protocol
        FOREIGN KEY (protocol_id) REFERENCES protocols(id)
        ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE notifications
    ADD CONSTRAINT fk_notifications_maintenance
        FOREIGN KEY (maintenance_window_id) REFERENCES maintenance_windows(id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- ============================================================
-- 11. DATOS INICIALES
-- ============================================================

INSERT INTO roles (name, code, description)
VALUES
    ('Administrador', 'ADMIN', 'Gestión completa de la plataforma.'),
    ('Funcionario de SST', 'SST', 'Supervisión de Seguridad y Salud en el Trabajo.'),
    ('Instructor', 'INSTRUCTOR', 'Consulta de monitoreo, ambiente asignado y registro de aforo.') AS new
ON DUPLICATE KEY UPDATE
    description = new.description;

INSERT INTO permissions (code, name, module, description)
VALUES
    ('dashboard.view', 'Ver Dashboard', 'dashboard', 'Consultar el dashboard y mapa de monitoreo.'),
    ('users.manage', 'Gestionar usuarios', 'users', 'Crear, consultar, actualizar y desactivar usuarios.'),
    ('audit.view', 'Consultar auditoría', 'audit', 'Consultar auditoría y autenticaciones fallidas.'),
    ('thresholds.manage', 'Gestionar umbrales', 'configuration', 'Gestionar umbrales ambientales.'),
    ('nodes.view', 'Consultar nodos', 'nodes', 'Consultar nodos registrados y no registrados.'),
    ('nodes.manage', 'Gestionar nodos', 'nodes', 'Registrar y editar nodos IoT.'),
    ('map.manage', 'Gestionar mapa', 'map', 'Gestionar puntos y ubicaciones de nodos.'),
    ('environments.manage', 'Gestionar ambientes', 'configuration', 'Gestionar ambientes de formación.'),
    ('assignments.manage', 'Gestionar asignaciones', 'configuration', 'Asignar instructores a ambientes.'),
    ('monitoring.view', 'Consultar monitoreo', 'monitoring', 'Consultar variables ambientales.'),
    ('alerts.view', 'Consultar alertas', 'alerts', 'Consultar alertas críticas y predictivas.'),
    ('history.view', 'Consultar históricos', 'history', 'Consultar históricos ambientales.'),
    ('history.export', 'Exportar históricos', 'history', 'Exportar históricos a Excel/PDF.'),
    ('occupancy.create', 'Registrar aforo', 'occupancy', 'Registrar cantidad anónima de ocupantes.'),
    ('protocols.view', 'Consultar protocolos', 'sst', 'Consultar recomendaciones institucionales.'),
    ('protocols.manage', 'Gestionar protocolos', 'sst', 'Gestionar recomendaciones/protocolos SST.'),
    ('manual.view', 'Consultar manual', 'sst', 'Consultar manual de contingencia.'),
    ('manual.export', 'Exportar manual', 'sst', 'Exportar manual a PDF.'),
    ('manual.manage', 'Gestionar manual', 'sst', 'Gestionar contenido del manual.'),
    ('predictions.view', 'Consultar predicciones', 'ai', 'Consultar predicciones y curvas.'),
    ('system_events.view', 'Consultar eventos automáticos', 'audit', 'Consultar trazabilidad automática.'),
    ('report.nodes.view', 'Consultar reporte de nodos', 'reports', 'Consultar reporte de nodos.'),
    ('report.nodes.export', 'Exportar reporte de nodos', 'reports', 'Exportar reporte de nodos.'),
    ('maintenance.manage', 'Gestionar mantenimiento', 'maintenance', 'Programar mantenimiento.'),
    ('maintenance.view', 'Consultar mantenimiento', 'maintenance', 'Consultar avisos de mantenimiento.'),
    ('performance.view', 'Consultar rendimiento', 'performance', 'Consultar capacidad y rendimiento.') AS new
ON DUPLICATE KEY UPDATE
    name = new.name,
    module = new.module,
    description = new.description;

-- ADMIN: todos los permisos definidos arriba.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
CROSS JOIN permissions p
WHERE r.code = 'ADMIN';

-- SST: permisos operativos y de supervisión.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.code = 'SST'
  AND p.code IN (
      'dashboard.view','audit.view','nodes.view','nodes.manage','map.manage',
      'environments.manage','assignments.manage','monitoring.view',
      'alerts.view','history.view','history.export','protocols.view',
      'protocols.manage','manual.view','manual.export','manual.manage',
      'predictions.view','system_events.view','report.nodes.view',
      'report.nodes.export','maintenance.view'
  );

-- INSTRUCTOR: monitoreo, predicciones, ambiente/aforo y manual.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.code = 'INSTRUCTOR'
  AND p.code IN (
      'dashboard.view','monitoring.view','alerts.view','occupancy.create',
      'protocols.view','manual.view','manual.export','predictions.view'
  );

INSERT INTO environmental_thresholds
    (variable_type, operational_min, operational_max, warning_min, warning_max,
     critical_min, critical_max)
VALUES
    ('co2', 400.0000, 5000.0000, NULL, 800.0000, NULL, 1000.0000),
    ('temperature', NULL, NULL, NULL, NULL, NULL, NULL),
    ('humidity', NULL, NULL, NULL, NULL, NULL, NULL) AS new
ON DUPLICATE KEY UPDATE
    operational_min = new.operational_min,
    operational_max = new.operational_max,
    warning_min = new.warning_min,
    warning_max = new.warning_max,
    critical_min = new.critical_min,
    critical_max = new.critical_max;

INSERT INTO risk_categories (code, name, description)
VALUES
    ('AIR_QUALITY', 'Calidad de aire', 'Protocolos relacionados con gases y calidad del aire.'),
    ('CLIMATE', 'Clima', 'Protocolos relacionados con temperatura y humedad.'),
    ('OCCUPANCY', 'Aforo', 'Protocolos relacionados con ocupación de ambientes.') AS new
ON DUPLICATE KEY UPDATE
    description = new.description;

INSERT INTO system_settings (setting_key, setting_value, value_type, description)
VALUES
    ('dashboard_refresh_seconds', '60', 'integer', 'Intervalo máximo de actualización del dashboard.'),
    ('critical_alert_max_latency_ms', '2000', 'integer', 'Tiempo máximo esperado para reflejar alertas críticas.'),
    ('sensor_offline_after_seconds', '120', 'integer', 'Tiempo sin señal tras el cual un nodo puede considerarse fuera de línea.'),
    ('predictive_min_historical_readings', '100', 'integer', 'Mínimo de lecturas históricas continuas para el modelo.'),
    ('admin_sst_inactivity_minutes', '10', 'integer', 'Tiempo de inactividad para Administrador y SST.'),
    ('instructor_inactivity_minutes', '30', 'integer', 'Tiempo de inactividad para Instructor.'),
    ('historical_retention_days', '365', 'integer', 'Retención mínima aproximada de históricos ambientales.'),
    ('maintenance_notice_hours', '24', 'integer', 'Anticipación mínima recomendada para mantenimiento.'),
    ('maintenance_max_duration_minutes', '120', 'integer', 'Duración máxima de una ventana de mantenimiento.') AS new
ON DUPLICATE KEY UPDATE
    setting_value = new.setting_value,
    value_type = new.value_type,
    description = new.description;
