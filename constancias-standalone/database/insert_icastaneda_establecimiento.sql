START TRANSACTION;

INSERT INTO fines_app_establecimientos (
    nombre,
    localidad,
    modalidad_principal,
    orientacion_principal,
    resolucion_principal,
    cue,
    direccion,
    firma_director_path,
    sello_oval_path,
    activo
) SELECT
    'CENS 462',
    'La Plata',
    'Programa Fines 2 Trayecto Secundario',
    'Ciencias Sociales',
    '2993/22',
    NULL,
    'HOLA',
    '_assets/establecimientos/3/firma_director.png',
    '_assets/establecimientos/3/sello_oval.png',
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM fines_app_establecimientos
    WHERE nombre = 'CENS 462'
      AND localidad = 'La Plata'
    LIMIT 1
);

SELECT @establecimiento_id := id
FROM fines_app_establecimientos
WHERE nombre = 'CENS 462'
  AND localidad = 'La Plata'
ORDER BY id
LIMIT 1;

UPDATE fines_app_establecimientos
SET modalidad_principal = 'Programa Fines 2 Trayecto Secundario',
    orientacion_principal = 'Ciencias Sociales',
    resolucion_principal = '2993/22',
    cue = NULL,
    direccion = 'HOLA',
    firma_director_path = '_assets/establecimientos/3/firma_director.png',
    sello_oval_path = '_assets/establecimientos/3/sello_oval.png',
    activo = 1
WHERE id = @establecimiento_id;

INSERT INTO fines_app_users (
    establecimiento_id,
    nombre,
    email,
    rol,
    activo
) VALUES (
    @establecimiento_id,
    'Ivan Castaneda',
    'icastaneda@abc.gov.ar',
    'admin',
    1
)
ON DUPLICATE KEY UPDATE
    establecimiento_id = VALUES(establecimiento_id),
    nombre = VALUES(nombre),
    rol = VALUES(rol),
    activo = VALUES(activo);

COMMIT;
