CREATE TABLE IF NOT EXISTS fines_app_establecimientos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(190) NOT NULL,
    cue VARCHAR(40) NULL,
    direccion VARCHAR(255) NULL,
    logo_path VARCHAR(255) NULL,
    firma_director_path VARCHAR(255) NULL,
    sello_oval_path VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS fines_app_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    establecimiento_id BIGINT UNSIGNED NULL,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'operador', 'consulta') NOT NULL DEFAULT 'consulta',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_login_en DATETIME NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY fines_app_users_email_unique (email),
    KEY fines_app_users_establecimiento_index (establecimiento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Usuario inicial recomendado para desarrollo local:
-- email: admin@example.com
-- contrasena: admin123
INSERT INTO fines_app_users (nombre, email, password_hash, rol, activo)
VALUES ('Administrador', 'admin@example.com', '$2y$10$s2iepUUHI7oi7K7bf35IkuJ9ENiPVng6706jjtKFYa.A6RIxWlHsu', 'admin', 1)
ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), rol = VALUES(rol), activo = VALUES(activo);
