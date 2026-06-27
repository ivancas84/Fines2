CREATE TABLE IF NOT EXISTS fines_app_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    establecimiento_id BIGINT UNSIGNED NULL,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NULL,
    rol ENUM('admin', 'operador', 'consulta') NOT NULL DEFAULT 'consulta',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_login_en DATETIME NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY fines_app_users_email_unique (email),
    KEY fines_app_users_establecimiento_index (establecimiento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE fines_app_users
    ADD COLUMN IF NOT EXISTS establecimiento_id BIGINT UNSIGNED NULL AFTER id,
    ADD KEY IF NOT EXISTS fines_app_users_establecimiento_index (establecimiento_id);

CREATE TABLE IF NOT EXISTS fines_app_audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    accion VARCHAR(80) NOT NULL,
    entidad VARCHAR(80) NULL,
    entidad_id VARCHAR(80) NULL,
    detalle JSON NULL,
    ip VARCHAR(45) NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY fines_app_audit_logs_user_id_index (user_id),
    KEY fines_app_audit_logs_entidad_index (entidad, entidad_id),
    CONSTRAINT fines_app_audit_logs_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES fines_app_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

