-- Reverso: esos campos no pertenecen a planilla_docente.
ALTER TABLE planilla_docente
    DROP COLUMN IF EXISTS semestre,
    DROP COLUMN IF EXISTS anio;
