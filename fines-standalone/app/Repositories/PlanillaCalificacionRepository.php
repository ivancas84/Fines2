<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use FinesApp\Support\PersonaName;
use PDO;

final class PlanillaCalificacionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Preview o persistencia de una fila de planilla.
     *
     * @param array{nombres: string, apellidos: string, numero_documento: string, nota: int} $data
     * @return array{
     *   ok: bool,
     *   label: string,
     *   actions: list<string>,
     *   has_changes: bool,
     *   persona_id?: string|null,
     *   error?: string
     * }
     */
    public function processRow(
        array $curso,
        array $data,
        ?string $observaciones,
        bool $persist,
    ): array {
        $label = trim($data['apellidos'] . ', ' . $data['nombres'] . ' (' . $data['numero_documento'] . ') nota ' . $data['nota']);
        $actions = [];
        $hasChanges = false;
        $personaExistenteId = null;

        $cursoId = (string) ($curso['curso_id'] ?? '');
        $comisionId = (string) ($curso['comision_id'] ?? '');
        $disposicionId = (string) ($curso['disposicion_id'] ?? '');
        $planId = (string) ($curso['plan_id'] ?? '');

        if ($cursoId === '' || $comisionId === '' || $disposicionId === '') {
            throw new \RuntimeException('El curso no tiene comisión o disposición asociada.');
        }

        try {
            if ($persist) {
                $this->pdo->beginTransaction();
            }

            $personaStmt = $this->pdo->prepare('
                SELECT id, nombres, apellidos, numero_documento
                FROM persona
                WHERE numero_documento = :dni
                LIMIT 1
            ');
            $personaStmt->execute(['dni' => $data['numero_documento']]);
            $persona = $personaStmt->fetch(PDO::FETCH_ASSOC);

            if ($persona === false) {
                $personaId = uniqid();
                $actions[] = 'Crear persona';
                $hasChanges = true;
                if ($persist) {
                    $insertPersona = $this->pdo->prepare('
                        INSERT INTO persona (id, nombres, apellidos, numero_documento)
                        VALUES (:id, :nombres, :apellidos, :numero_documento)
                    ');
                    $insertPersona->execute([
                        'id' => $personaId,
                        'nombres' => $data['nombres'],
                        'apellidos' => $data['apellidos'],
                        'numero_documento' => $data['numero_documento'],
                    ]);
                }
                $personaCreated = true;
            } else {
                $personaId = (string) $persona['id'];
                $personaExistenteId = $personaId;
                // Misma regla que Fines2\Model\Persona_::nombreParecido (prefijo 5 en tokens).
                if (!PersonaName::nombreParecido($persona, $data, 5)) {
                    throw new \RuntimeException(
                        'El nombre registrado de la persona es diferente: '
                        . trim(($persona['apellidos'] ?? '') . ', ' . ($persona['nombres'] ?? '')),
                    );
                }
                $actions[] = 'Persona existente';
                $personaCreated = false;
            }

            $alumnoStmt = $this->pdo->prepare('SELECT id, plan FROM alumno WHERE persona = :persona LIMIT 1');
            $alumnoStmt->execute(['persona' => $personaId]);
            $alumno = $alumnoStmt->fetch(PDO::FETCH_ASSOC);
            $alumnoCreated = false;

            if ($alumno === false) {
                $alumnoId = uniqid();
                $actions[] = 'Crear alumno' . ($planId !== '' ? ' (plan ' . $planId . ')' : '');
                $hasChanges = true;
                $alumnoCreated = true;
                if ($persist) {
                    $insertAlumno = $this->pdo->prepare('
                        INSERT INTO alumno (id, persona, plan)
                        VALUES (:id, :persona, :plan)
                    ');
                    $insertAlumno->execute([
                        'id' => $alumnoId,
                        'persona' => $personaId,
                        'plan' => $planId !== '' ? $planId : null,
                    ]);
                }
            } else {
                $alumnoId = (string) $alumno['id'];
                $actions[] = 'Alumno existente';
                if ($planId !== '' && empty($alumno['plan'])) {
                    $actions[] = 'Asignar plan al alumno';
                    $hasChanges = true;
                    if ($persist) {
                        $updateAlumno = $this->pdo->prepare('UPDATE alumno SET plan = :plan WHERE id = :id');
                        $updateAlumno->execute(['plan' => $planId, 'id' => $alumnoId]);
                    }
                }
            }

            $relationStmt = $this->pdo->prepare('
                SELECT id FROM alumno_comision
                WHERE alumno = :alumno AND comision = :comision
                LIMIT 1
            ');
            $relationStmt->execute(['alumno' => $alumnoId, 'comision' => $comisionId]);
            $relationId = $relationStmt->fetchColumn();

            if ($relationId === false) {
                $estado = $alumnoCreated ? 'Ingresante' : 'Incorporado';
                $actions[] = 'Vincular alumno-comisión (' . $estado . ')';
                $hasChanges = true;
                if ($persist) {
                    $insertRelation = $this->pdo->prepare('
                        INSERT INTO alumno_comision (id, alumno, comision, estado, activo, observaciones)
                        VALUES (:id, :alumno, :comision, :estado, 1, :observaciones)
                    ');
                    $insertRelation->execute([
                        'id' => uniqid(),
                        'alumno' => $alumnoId,
                        'comision' => $comisionId,
                        'estado' => $estado,
                        'observaciones' => 'Importado desde planilla de calificaciones',
                    ]);
                }
            } else {
                $actions[] = 'Alumno ya en la comisión';
            }

            $califStmt = $this->pdo->prepare('
                SELECT id, nota_final, crec, curso, observaciones
                FROM calificacion
                WHERE alumno = :alumno AND disposicion = :disposicion
                LIMIT 1
            ');
            $califStmt->execute(['alumno' => $alumnoId, 'disposicion' => $disposicionId]);
            $calificacion = $califStmt->fetch(PDO::FETCH_ASSOC);

            // Solo se aceptan notas >= 7 en el parser; se guarda en nota_final.
            $notaFinal = $data['nota'];
            $crec = null;

            if ($calificacion === false) {
                $actions[] = 'Crear calificación aprobada (nota_final=' . $notaFinal . ')';
                $hasChanges = true;
                if ($persist) {
                    $insertCalif = $this->pdo->prepare('
                        INSERT INTO calificacion (
                            id, alumno, disposicion, curso, nota_final, crec, observaciones, archivado
                        ) VALUES (
                            :id, :alumno, :disposicion, :curso, :nota_final, :crec, :observaciones, 0
                        )
                    ');
                    $insertCalif->execute([
                        'id' => uniqid(),
                        'alumno' => $alumnoId,
                        'disposicion' => $disposicionId,
                        'curso' => $cursoId,
                        'nota_final' => $notaFinal,
                        'crec' => $crec,
                        'observaciones' => $this->nullableText($observaciones),
                    ]);
                }
            } else {
                $needsUpdate = (float) ($calificacion['nota_final'] ?? 0) !== (float) $notaFinal
                    || (string) ($calificacion['curso'] ?? '') !== $cursoId
                    || (
                        $observaciones !== null
                        && trim($observaciones) !== ''
                        && trim((string) ($calificacion['observaciones'] ?? '')) !== trim($observaciones)
                    );

                if ($needsUpdate) {
                    $actions[] = 'Actualizar calificación (nota_final=' . $notaFinal . ', curso=' . $cursoId . ')';
                    $hasChanges = true;
                    if ($persist) {
                        $updateCalif = $this->pdo->prepare('
                            UPDATE calificacion
                            SET nota_final = :nota_final,
                                crec = :crec,
                                curso = :curso,
                                observaciones = COALESCE(:observaciones, observaciones)
                            WHERE id = :id
                        ');
                        $updateCalif->execute([
                            'id' => $calificacion['id'],
                            'nota_final' => $notaFinal,
                            'crec' => $crec,
                            'curso' => $cursoId,
                            'observaciones' => $this->nullableText($observaciones),
                        ]);
                    }
                } else {
                    $actions[] = 'Calificación ya registrada sin cambios';
                }
            }

            if ($persist) {
                $this->pdo->commit();
            }

            return [
                'ok' => true,
                'label' => $label,
                'actions' => $actions,
                'has_changes' => $hasChanges,
                'persona_id' => $personaExistenteId,
            ];
        } catch (\Throwable $throwable) {
            if ($persist && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                'ok' => false,
                'label' => $label,
                'actions' => $actions,
                'has_changes' => false,
                'persona_id' => $personaExistenteId,
                'error' => $throwable->getMessage(),
            ];
        }
    }

    private function nullableText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
