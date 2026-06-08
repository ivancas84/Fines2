CREATE TABLE IF NOT EXISTS fines_app_establecimientos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(190) NOT NULL,
    localidad VARCHAR(120) NULL DEFAULT 'La Plata',
    modalidad_principal VARCHAR(190) NULL DEFAULT 'Programa Fines 2 Trayecto Secundario',
    orientacion_principal VARCHAR(190) NULL DEFAULT 'Ciencias Sociales',
    resolucion_principal VARCHAR(80) NULL DEFAULT '2993/22',
    cue VARCHAR(40) NULL,
    direccion VARCHAR(255) NULL,
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
    rol ENUM('admin', 'operador', 'consulta') NOT NULL DEFAULT 'consulta',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_login_en DATETIME NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY fines_app_users_email_unique (email),
    KEY fines_app_users_establecimiento_index (establecimiento_id),
    CONSTRAINT fines_app_users_establecimiento_fk
        FOREIGN KEY (establecimiento_id)
        REFERENCES fines_app_establecimientos (id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS fines_app_constancias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    establecimiento_id BIGINT UNSIGNED NULL,
    tipo VARCHAR(60) NOT NULL,
    titulo VARCHAR(190) NOT NULL,
    descripcion TEXT NOT NULL,
    clave VARCHAR(128) NOT NULL,
    nombres VARCHAR(120) NOT NULL,
    apellidos VARCHAR(120) NOT NULL,
    numero_documento VARCHAR(40) NOT NULL,
    datos_json JSON NULL,
    archivo_path VARCHAR(255) NULL,
    archivo_nombre VARCHAR(190) NULL,
    mime_type VARCHAR(120) NOT NULL DEFAULT 'application/pdf',
    creado_por INT UNSIGNED NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    anulado_en DATETIME NULL,
    UNIQUE KEY fines_app_constancias_clave_unique (clave),
    KEY fines_app_constancias_documento_index (numero_documento),
    KEY fines_app_constancias_establecimiento_index (establecimiento_id),
    KEY fines_app_constancias_validacion_index (id, clave, anulado_en),
    KEY fines_app_constancias_creado_por_index (creado_por),
    CONSTRAINT fines_app_constancias_establecimiento_fk
        FOREIGN KEY (establecimiento_id)
        REFERENCES fines_app_establecimientos (id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT fines_app_constancias_creado_por_fk
        FOREIGN KEY (creado_por)
        REFERENCES fines_app_users (id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO fines_app_users (nombre, email, rol, activo)
VALUES ('Administrador', 'admin@example.com', 'admin', 1)
ON DUPLICATE KEY UPDATE rol = VALUES(rol), activo = VALUES(activo);
