-- Completa curso.horas_catedra = 0 (o NULL) con las horas de su disposición.
-- No pisa valores que ya estén cargados.

UPDATE curso
INNER JOIN disposicion ON disposicion.id = curso.disposicion
SET curso.horas_catedra = disposicion.horas_catedra
WHERE (curso.horas_catedra IS NULL OR curso.horas_catedra = 0)
  AND disposicion.horas_catedra > 0;
