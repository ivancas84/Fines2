<?php

namespace SqlOrganize\Sql\Fines2;

require_once MAIN_PATH . 'SqlOrganize/Sql/ISchema.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/EntityMetadata.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/Field.php';
use SqlOrganize\Sql\ISchema;
use SqlOrganize\Sql\EntityMetadata;
use SqlOrganize\Sql\Field;

/**
 * Esquema de la base de datos
 * Esta clase fue generada por una herramienta, no debe ser modificada.
 */
class Schema extends ISchema
{
    public function __construct()
    {
        $e = new EntityMetadata();
        $e->name = 'alumno';
        $e->alias = 'alum';
        $e->pk = ['id'];
        $e->fk = ['persona', 'plan', 'resolucion_inscripcion'];
        $e->unique = ['libro_folio', 'persona'];
        $e->notNull = ['confirmado_direccion', 'creado', 'id', 'persona', 'previas_completas', 'tiene_certificado', 'tiene_constancia', 'tiene_dni', 'tiene_partida'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'persona';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'persona';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio'] = $child;
        $e->tree['persona'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'plan';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'plan';
        $e->tree['plan'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'resolucion_inscripcion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'resolucion';
        $e->tree['resolucion_inscripcion'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'resolucion_inscripcion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'resolucion';
        $e->relations['resolucion_inscripcion'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'alumno';
        $om->entityName = 'alumno_comision';
        $e->om['alumnoComision_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'alumno';
        $om->entityName = 'calificacion';
        $e->om['calificacion_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'alumno';
        $om->entityName = 'disposicion_pendiente';
        $e->om['disposicionPendiente_'] = $om;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'adeuda_deudores';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['adeuda_deudores'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'adeuda_legajo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['adeuda_legajo'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'anio_ingreso';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['anio_ingreso'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'anio_inscripcion';
        $f->dataType = 'smallint';
        $f->type = 'short';
        $f->checks = [
            'type' => 'short',
        ];
        $e->fields['anio_inscripcion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'anio_inscripcion_completo';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
        ];
        $e->fields['anio_inscripcion_completo'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'comentarios';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['comentarios'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'confirmado_direccion';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['confirmado_direccion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'creado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['creado'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'documentacion_inscripcion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['documentacion_inscripcion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'establecimiento_inscripcion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['establecimiento_inscripcion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'estado_inscripcion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['estado_inscripcion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'fecha_titulacion';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_titulacion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'folio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['folio'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'libro';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['libro'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'libro_folio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['libro_folio'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'persona';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'per';
        $f->refEntityName = 'persona';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['persona'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'plan';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'pla';
        $f->refEntityName = 'plan';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['plan'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'previas_completas';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['previas_completas'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'resolucion_inscripcion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'res';
        $f->refEntityName = 'resolucion';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['resolucion_inscripcion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'semestre_ingreso';
        $f->dataType = 'smallint';
        $f->type = 'short';
        $f->checks = [
            'type' => 'short',
        ];
        $e->fields['semestre_ingreso'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'semestre_inscripcion';
        $f->dataType = 'smallint';
        $f->type = 'short';
        $f->checks = [
            'type' => 'short',
        ];
        $e->fields['semestre_inscripcion'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'tiene_certificado';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['tiene_certificado'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'tiene_constancia';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['tiene_constancia'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'tiene_dni';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['tiene_dni'] = $f;

        $f = new Field();
        $f->entityName = 'alumno';
        $f->name = 'tiene_partida';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['tiene_partida'] = $f;

        $this->entities['alumno'] = $e;

        $e = new EntityMetadata();
        $e->name = 'alumno_comision';
        $e->alias = 'alu1';
        $e->pk = ['id'];
        $e->fk = ['alumno', 'comision'];
        $e->notNull = ['alumno', 'creado', 'id'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'alumno';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'alumno';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'persona';
        $child->refFieldName = 'id';
        $child->refEntityName = 'persona';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio'] = $child;
        $tree->children['persona'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'plan';
        $child->refFieldName = 'id';
        $child->refEntityName = 'plan';
        $tree->children['plan'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'resolucion_inscripcion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'resolucion';
        $tree->children['resolucion_inscripcion'] = $child;
        $e->tree['alumno'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'comision';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'comision';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'calendario';
        $child->refFieldName = 'id';
        $child->refEntityName = 'calendario';
        $tree->children['calendario'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'comision_siguiente';
        $child->refFieldName = 'id';
        $child->refEntityName = 'comision';
        $tree->children['comision_siguiente'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'modalidad';
        $child->refFieldName = 'id';
        $child->refEntityName = 'modalidad';
        $tree->children['modalidad'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan_pla'] = $child;
        $tree->children['planificacion'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'sede';
        $child->refFieldName = 'id';
        $child->refEntityName = 'sede';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'centro_educativo';
                $child->refFieldName = 'id';
                $child->refEntityName = 'centro_educativo';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'domicilio';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'domicilio';
                        $tree->children['domicilio_cen'] = $child;
                $tree->children['centro_educativo'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio_sed'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'organizacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'sede';
                $tree->children['organizacion'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'tipo_sede';
                $child->refFieldName = 'id';
                $child->refEntityName = 'tipo_sede';
                $tree->children['tipo_sede'] = $child;
        $tree->children['sede'] = $child;
        $e->tree['comision'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'alumno';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'alumno';
        $e->relations['alumno'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $relation->parentId = 'alumno';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'alumno';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'resolucion_inscripcion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'resolucion';
        $relation->parentId = 'alumno';
        $e->relations['resolucion_inscripcion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $e->relations['comision'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'comision';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'comision';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'comision';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan_pla'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'comision';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio_sed'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'activo';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
        ];
        $e->fields['activo'] = $f;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'alumno';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'alu';
        $f->refEntityName = 'alumno';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['alumno'] = $f;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'comision';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'com';
        $f->refEntityName = 'comision';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['comision'] = $f;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'creado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['creado'] = $f;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'estado';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->defaultValue = 'Activo';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['estado'] = $f;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $f = new Field();
        $f->entityName = 'alumno_comision';
        $f->name = 'pfid';
        $f->dataType = 'int';
        $f->type = 'uint';
        $f->checks = [
            'type' => 'uint',
        ];
        $e->fields['pfid'] = $f;

        $this->entities['alumno_comision'] = $e;

        $e = new EntityMetadata();
        $e->name = 'asignacion_planilla_docente';
        $e->alias = 'asig';
        $e->pk = ['id'];
        $e->fk = ['planilla_docente', 'toma'];
        $e->notNull = ['id', 'insertado', 'planilla_docente', 'reclamo', 'toma'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'planilla_docente';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'planilla_docente';
        $e->tree['planilla_docente'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'toma';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'toma';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'curso';
        $child->refFieldName = 'id';
        $child->refEntityName = 'curso';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'asignatura';
                $child->refFieldName = 'id';
                $child->refEntityName = 'asignatura';
                $tree->children['asignatura'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'comision';
                $child->refFieldName = 'id';
                $child->refEntityName = 'comision';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'calendario';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'calendario';
                        $tree->children['calendario'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'comision_siguiente';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'comision';
                        $tree->children['comision_siguiente'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'modalidad';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'modalidad';
                        $tree->children['modalidad'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'planificacion';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'planificacion';
                                $tree->children = [];
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'plan';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'plan';
                                $tree->children['plan'] = $child;
                        $tree->children['planificacion'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'sede';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'sede';
                                $tree->children = [];
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'centro_educativo';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'centro_educativo';
                                        $tree->children = [];
                                        $child = new \SqlOrganize\Sql\EntityTree();
                                        $child->fieldName = 'domicilio';
                                        $child->refFieldName = 'id';
                                        $child->refEntityName = 'domicilio';
                                        $tree->children['domicilio_cen'] = $child;
                                $tree->children['centro_educativo'] = $child;
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'domicilio';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'domicilio';
                                $tree->children['domicilio'] = $child;
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'organizacion';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'sede';
                                $tree->children['organizacion'] = $child;
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'tipo_sede';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'tipo_sede';
                                $tree->children['tipo_sede'] = $child;
                        $tree->children['sede'] = $child;
                $tree->children['comision'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'disposicion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'disposicion';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'asignatura';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'asignatura';
                        $tree->children['asignatura_dis'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'planificacion';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'planificacion';
                                $tree->children = [];
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'plan';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'plan';
                                $tree->children['plan_pla'] = $child;
                        $tree->children['planificacion_dis'] = $child;
                $tree->children['disposicion'] = $child;
        $tree->children['curso'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'docente';
        $child->refFieldName = 'id';
        $child->refEntityName = 'persona';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio_doc'] = $child;
        $tree->children['docente'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planilla_docente';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planilla_docente';
        $tree->children['planilla_docente_tom'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'reemplazo';
        $child->refFieldName = 'id';
        $child->refEntityName = 'persona';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio_ree'] = $child;
        $tree->children['reemplazo'] = $child;
        $e->tree['toma'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planilla_docente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planilla_docente';
        $e->relations['planilla_docente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'toma';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'toma';
        $e->relations['toma'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'curso';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'curso';
        $relation->parentId = 'toma';
        $e->relations['curso'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'curso';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'curso';
        $e->relations['comision'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'comision';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'comision';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'comision';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'comision';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $relation->parentId = 'curso';
        $e->relations['disposicion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion';
        $e->relations['asignatura_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion';
        $e->relations['planificacion_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion_dis';
        $e->relations['plan_pla'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'docente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $relation->parentId = 'toma';
        $e->relations['docente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'docente';
        $e->relations['domicilio_doc'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planilla_docente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planilla_docente';
        $relation->parentId = 'toma';
        $e->relations['planilla_docente_tom'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'reemplazo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $relation->parentId = 'toma';
        $e->relations['reemplazo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'reemplazo';
        $e->relations['domicilio_ree'] = $relation;

        $f = new Field();
        $f->entityName = 'asignacion_planilla_docente';
        $f->name = 'comentario';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['comentario'] = $f;

        $f = new Field();
        $f->entityName = 'asignacion_planilla_docente';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'asignacion_planilla_docente';
        $f->name = 'insertado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['insertado'] = $f;

        $f = new Field();
        $f->entityName = 'asignacion_planilla_docente';
        $f->name = 'planilla_docente';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'pla';
        $f->refEntityName = 'planilla_docente';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['planilla_docente'] = $f;

        $f = new Field();
        $f->entityName = 'asignacion_planilla_docente';
        $f->name = 'reclamo';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['reclamo'] = $f;

        $f = new Field();
        $f->entityName = 'asignacion_planilla_docente';
        $f->name = 'toma';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'tom';
        $f->refEntityName = 'toma';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['toma'] = $f;

        $this->entities['asignacion_planilla_docente'] = $e;

        $e = new EntityMetadata();
        $e->name = 'asignatura';
        $e->alias = 'asi1';
        $e->pk = ['id'];
        $e->unique = ['nombre'];
        $e->notNull = ['id', 'nombre'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'asignatura';
        $om->entityName = 'curso';
        $e->om['curso_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'asignatura';
        $om->entityName = 'disposicion';
        $e->om['disposicion_'] = $om;

        $f = new Field();
        $f->entityName = 'asignatura';
        $f->name = 'clasificacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['clasificacion'] = $f;

        $f = new Field();
        $f->entityName = 'asignatura';
        $f->name = 'codigo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['codigo'] = $f;

        $f = new Field();
        $f->entityName = 'asignatura';
        $f->name = 'formacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['formacion'] = $f;

        $f = new Field();
        $f->entityName = 'asignatura';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'asignatura';
        $f->name = 'nombre';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['nombre'] = $f;

        $f = new Field();
        $f->entityName = 'asignatura';
        $f->name = 'perfil';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['perfil'] = $f;

        $this->entities['asignatura'] = $e;

        $e = new EntityMetadata();
        $e->name = 'calendario';
        $e->alias = 'cale';
        $e->pk = ['id'];
        $e->notNull = ['anio', 'id', 'insertado', 'semestre'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'calendario';
        $om->entityName = 'comision';
        $e->om['comision_'] = $om;

        $f = new Field();
        $f->entityName = 'calendario';
        $f->name = 'anio';
        $f->dataType = 'year';
        $f->type = 'short';
        $f->checks = [
            'type' => 'short',
            'required' => '1',
        ];
        $e->fields['anio'] = $f;

        $f = new Field();
        $f->entityName = 'calendario';
        $f->name = 'descripcion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['descripcion'] = $f;

        $f = new Field();
        $f->entityName = 'calendario';
        $f->name = 'fin';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fin'] = $f;

        $f = new Field();
        $f->entityName = 'calendario';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'calendario';
        $f->name = 'inicio';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['inicio'] = $f;

        $f = new Field();
        $f->entityName = 'calendario';
        $f->name = 'insertado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['insertado'] = $f;

        $f = new Field();
        $f->entityName = 'calendario';
        $f->name = 'semestre';
        $f->dataType = 'smallint';
        $f->type = 'short';
        $f->checks = [
            'type' => 'short',
            'required' => '1',
        ];
        $e->fields['semestre'] = $f;

        $this->entities['calendario'] = $e;

        $e = new EntityMetadata();
        $e->name = 'calificacion';
        $e->alias = 'cali';
        $e->pk = ['id'];
        $e->fk = ['alumno', 'curso', 'disposicion'];
        $e->notNull = ['alumno', 'archivado', 'disposicion', 'id'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'alumno';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'alumno';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'persona';
        $child->refFieldName = 'id';
        $child->refEntityName = 'persona';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio'] = $child;
        $tree->children['persona'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'plan';
        $child->refFieldName = 'id';
        $child->refEntityName = 'plan';
        $tree->children['plan'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'resolucion_inscripcion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'resolucion';
        $tree->children['resolucion_inscripcion'] = $child;
        $e->tree['alumno'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'curso';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'curso';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'asignatura';
        $child->refFieldName = 'id';
        $child->refEntityName = 'asignatura';
        $tree->children['asignatura'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'comision';
        $child->refFieldName = 'id';
        $child->refEntityName = 'comision';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'calendario';
                $child->refFieldName = 'id';
                $child->refEntityName = 'calendario';
                $tree->children['calendario'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'comision_siguiente';
                $child->refFieldName = 'id';
                $child->refEntityName = 'comision';
                $tree->children['comision_siguiente'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'modalidad';
                $child->refFieldName = 'id';
                $child->refEntityName = 'modalidad';
                $tree->children['modalidad'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'planificacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'planificacion';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'plan';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'plan';
                        $tree->children['plan_pla'] = $child;
                $tree->children['planificacion'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'sede';
                $child->refFieldName = 'id';
                $child->refEntityName = 'sede';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'centro_educativo';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'centro_educativo';
                                $tree->children = [];
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'domicilio';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'domicilio';
                                $tree->children['domicilio_cen'] = $child;
                        $tree->children['centro_educativo'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'domicilio';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'domicilio';
                        $tree->children['domicilio_sed'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'organizacion';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'sede';
                        $tree->children['organizacion'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'tipo_sede';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'tipo_sede';
                        $tree->children['tipo_sede'] = $child;
                $tree->children['sede'] = $child;
        $tree->children['comision'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'disposicion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'disposicion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'asignatura';
                $child->refFieldName = 'id';
                $child->refEntityName = 'asignatura';
                $tree->children['asignatura_dis'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'planificacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'planificacion';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'plan';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'plan';
                        $tree->children['plan_pla1'] = $child;
                $tree->children['planificacion_dis'] = $child;
        $tree->children['disposicion_cur'] = $child;
        $e->tree['curso'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'disposicion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'disposicion';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'asignatura';
        $child->refFieldName = 'id';
        $child->refEntityName = 'asignatura';
        $tree->children['asignatura_dis1'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan_pla2'] = $child;
        $tree->children['planificacion_dis1'] = $child;
        $e->tree['disposicion'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'alumno';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'alumno';
        $e->relations['alumno'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $relation->parentId = 'alumno';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'alumno';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'resolucion_inscripcion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'resolucion';
        $relation->parentId = 'alumno';
        $e->relations['resolucion_inscripcion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'curso';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'curso';
        $e->relations['curso'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'curso';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'curso';
        $e->relations['comision'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'comision';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'comision';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'comision';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan_pla'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'comision';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio_sed'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $relation->parentId = 'curso';
        $e->relations['disposicion_cur'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion_cur';
        $e->relations['asignatura_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion_cur';
        $e->relations['planificacion_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion_dis';
        $e->relations['plan_pla1'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $e->relations['disposicion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion';
        $e->relations['asignatura_dis1'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion';
        $e->relations['planificacion_dis1'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion_dis1';
        $e->relations['plan_pla2'] = $relation;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'alumno';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'alu';
        $f->refEntityName = 'alumno';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['alumno'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'archivado';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['archivado'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'crec';
        $f->dataType = 'decimal';
        $f->type = 'decimal';
        $f->checks = [
            'type' => 'decimal',
        ];
        $e->fields['crec'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'curso';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'cur';
        $f->refEntityName = 'curso';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['curso'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'disposicion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dis';
        $f->refEntityName = 'disposicion';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['disposicion'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'division';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['division'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'fecha';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'nota1';
        $f->dataType = 'decimal';
        $f->type = 'decimal';
        $f->checks = [
            'type' => 'decimal',
        ];
        $e->fields['nota1'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'nota2';
        $f->dataType = 'decimal';
        $f->type = 'decimal';
        $f->checks = [
            'type' => 'decimal',
        ];
        $e->fields['nota2'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'nota3';
        $f->dataType = 'decimal';
        $f->type = 'decimal';
        $f->checks = [
            'type' => 'decimal',
        ];
        $e->fields['nota3'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'nota_final';
        $f->dataType = 'decimal';
        $f->type = 'decimal';
        $f->checks = [
            'type' => 'decimal',
        ];
        $e->fields['nota_final'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $f = new Field();
        $f->entityName = 'calificacion';
        $f->name = 'porcentaje_asistencia';
        $f->dataType = 'int';
        $f->type = 'int';
        $f->checks = [
            'type' => 'int',
        ];
        $e->fields['porcentaje_asistencia'] = $f;

        $this->entities['calificacion'] = $e;

        $e = new EntityMetadata();
        $e->name = 'cargo';
        $e->alias = 'carg';
        $e->pk = ['id'];
        $e->unique = ['descripcion'];
        $e->notNull = ['descripcion', 'id'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'cargo';
        $om->entityName = 'designacion';
        $e->om['designacion_'] = $om;

        $f = new Field();
        $f->entityName = 'cargo';
        $f->name = 'descripcion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['descripcion'] = $f;

        $f = new Field();
        $f->entityName = 'cargo';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $this->entities['cargo'] = $e;

        $e = new EntityMetadata();
        $e->name = 'centro_educativo';
        $e->alias = 'cent';
        $e->pk = ['id'];
        $e->fk = ['domicilio'];
        $e->unique = ['cue'];
        $e->notNull = ['id', 'nombre'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'domicilio';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'domicilio';
        $e->tree['domicilio'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $e->relations['domicilio'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'centro_educativo';
        $om->entityName = 'sede';
        $e->om['sede_'] = $om;

        $f = new Field();
        $f->entityName = 'centro_educativo';
        $f->name = 'cue';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['cue'] = $f;

        $f = new Field();
        $f->entityName = 'centro_educativo';
        $f->name = 'domicilio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dom';
        $f->refEntityName = 'domicilio';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['domicilio'] = $f;

        $f = new Field();
        $f->entityName = 'centro_educativo';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'centro_educativo';
        $f->name = 'nombre';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['nombre'] = $f;

        $f = new Field();
        $f->entityName = 'centro_educativo';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $this->entities['centro_educativo'] = $e;

        $e = new EntityMetadata();
        $e->name = 'comision';
        $e->alias = 'comi';
        $e->pk = ['id'];
        $e->fk = ['calendario', 'comision_siguiente', 'modalidad', 'planificacion', 'sede'];
        $e->notNull = ['alta', 'apertura', 'autorizada', 'calendario', 'division', 'id', 'modalidad', 'publicada', 'sede'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'calendario';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'calendario';
        $e->tree['calendario'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'comision_siguiente';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'comision';
        $e->tree['comision_siguiente'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'modalidad';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'modalidad';
        $e->tree['modalidad'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'planificacion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'planificacion';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'plan';
        $child->refFieldName = 'id';
        $child->refEntityName = 'plan';
        $tree->children['plan'] = $child;
        $e->tree['planificacion'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'sede';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'sede';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'centro_educativo';
        $child->refFieldName = 'id';
        $child->refEntityName = 'centro_educativo';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio_cen'] = $child;
        $tree->children['centro_educativo'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'organizacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'sede';
        $tree->children['organizacion'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'tipo_sede';
        $child->refFieldName = 'id';
        $child->refEntityName = 'tipo_sede';
        $tree->children['tipo_sede'] = $child;
        $e->tree['sede'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'comision';
        $om->entityName = 'alumno_comision';
        $e->om['alumnoComision_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'comision_siguiente';
        $om->entityName = 'comision';
        $e->om['comision_comision_siguiente_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'comision';
        $om->entityName = 'comision_relacionada';
        $e->om['comisionRelacionada_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'relacion';
        $om->entityName = 'comision_relacionada';
        $e->om['comisionRelacionada_relacion_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'comision';
        $om->entityName = 'curso';
        $e->om['curso_'] = $om;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'alta';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['alta'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'apertura';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['apertura'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'autorizada';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['autorizada'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'calendario';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'cal';
        $f->refEntityName = 'calendario';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['calendario'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'comentario';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['comentario'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'comision_siguiente';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'com';
        $f->refEntityName = 'comision';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['comision_siguiente'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'configuracion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->defaultValue = 'Histórica';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['configuracion'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'division';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['division'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'estado';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->defaultValue = 'Confirma';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['estado'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'identificacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['identificacion'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'modalidad';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'mod';
        $f->refEntityName = 'modalidad';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['modalidad'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'pfid';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['pfid'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'planificacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'pla';
        $f->refEntityName = 'planificacion';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['planificacion'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'publicada';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['publicada'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'sede';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'sed';
        $f->refEntityName = 'sede';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['sede'] = $f;

        $f = new Field();
        $f->entityName = 'comision';
        $f->name = 'turno';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['turno'] = $f;

        $this->entities['comision'] = $e;

        $e = new EntityMetadata();
        $e->name = 'comision_relacionada';
        $e->alias = 'com1';
        $e->pk = ['id'];
        $e->fk = ['comision', 'relacion'];
        $e->notNull = ['comision', 'id', 'relacion'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'comision';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'comision';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'calendario';
        $child->refFieldName = 'id';
        $child->refEntityName = 'calendario';
        $tree->children['calendario'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'comision_siguiente';
        $child->refFieldName = 'id';
        $child->refEntityName = 'comision';
        $tree->children['comision_siguiente'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'modalidad';
        $child->refFieldName = 'id';
        $child->refEntityName = 'modalidad';
        $tree->children['modalidad'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan'] = $child;
        $tree->children['planificacion'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'sede';
        $child->refFieldName = 'id';
        $child->refEntityName = 'sede';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'centro_educativo';
                $child->refFieldName = 'id';
                $child->refEntityName = 'centro_educativo';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'domicilio';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'domicilio';
                        $tree->children['domicilio_cen'] = $child;
                $tree->children['centro_educativo'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'organizacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'sede';
                $tree->children['organizacion'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'tipo_sede';
                $child->refFieldName = 'id';
                $child->refEntityName = 'tipo_sede';
                $tree->children['tipo_sede'] = $child;
        $tree->children['sede'] = $child;
        $e->tree['comision'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'relacion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'comision';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'calendario';
        $child->refFieldName = 'id';
        $child->refEntityName = 'calendario';
        $tree->children['calendario_rel'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'comision_siguiente';
        $child->refFieldName = 'id';
        $child->refEntityName = 'comision';
        $tree->children['comision_siguiente_rel'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'modalidad';
        $child->refFieldName = 'id';
        $child->refEntityName = 'modalidad';
        $tree->children['modalidad_rel'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan_pla'] = $child;
        $tree->children['planificacion_rel'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'sede';
        $child->refFieldName = 'id';
        $child->refEntityName = 'sede';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'centro_educativo';
                $child->refFieldName = 'id';
                $child->refEntityName = 'centro_educativo';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'domicilio';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'domicilio';
                        $tree->children['domicilio_cen1'] = $child;
                $tree->children['centro_educativo_sed'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio_sed'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'organizacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'sede';
                $tree->children['organizacion_sed'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'tipo_sede';
                $child->refFieldName = 'id';
                $child->refEntityName = 'tipo_sede';
                $tree->children['tipo_sede_sed'] = $child;
        $tree->children['sede_rel'] = $child;
        $e->tree['relacion'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $e->relations['comision'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'comision';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'comision';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'comision';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'comision';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'relacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $e->relations['relacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'relacion';
        $e->relations['calendario_rel'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'relacion';
        $e->relations['comision_siguiente_rel'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'relacion';
        $e->relations['modalidad_rel'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'relacion';
        $e->relations['planificacion_rel'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion_rel';
        $e->relations['plan_pla'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'relacion';
        $e->relations['sede_rel'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede_rel';
        $e->relations['centro_educativo_sed'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo_sed';
        $e->relations['domicilio_cen1'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede_rel';
        $e->relations['domicilio_sed'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede_rel';
        $e->relations['organizacion_sed'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede_rel';
        $e->relations['tipo_sede_sed'] = $relation;

        $f = new Field();
        $f->entityName = 'comision_relacionada';
        $f->name = 'comision';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'com';
        $f->refEntityName = 'comision';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['comision'] = $f;

        $f = new Field();
        $f->entityName = 'comision_relacionada';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'comision_relacionada';
        $f->name = 'relacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'rel';
        $f->refEntityName = 'comision';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['relacion'] = $f;

        $this->entities['comision_relacionada'] = $e;

        $e = new EntityMetadata();
        $e->name = 'contralor';
        $e->alias = 'cont';
        $e->pk = ['id'];
        $e->fk = ['planilla_docente'];
        $e->notNull = ['id', 'insertado', 'planilla_docente'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'planilla_docente';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'planilla_docente';
        $e->tree['planilla_docente'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planilla_docente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planilla_docente';
        $e->relations['planilla_docente'] = $relation;

        $f = new Field();
        $f->entityName = 'contralor';
        $f->name = 'fecha_consejo';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_consejo'] = $f;

        $f = new Field();
        $f->entityName = 'contralor';
        $f->name = 'fecha_contralor';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_contralor'] = $f;

        $f = new Field();
        $f->entityName = 'contralor';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'contralor';
        $f->name = 'insertado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['insertado'] = $f;

        $f = new Field();
        $f->entityName = 'contralor';
        $f->name = 'planilla_docente';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'pla';
        $f->refEntityName = 'planilla_docente';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['planilla_docente'] = $f;

        $this->entities['contralor'] = $e;

        $e = new EntityMetadata();
        $e->name = 'curso';
        $e->alias = 'curs';
        $e->pk = ['id'];
        $e->fk = ['asignatura', 'comision', 'disposicion'];
        $e->notNull = ['alta', 'comision', 'horas_catedra', 'id'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'asignatura';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'asignatura';
        $e->tree['asignatura'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'comision';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'comision';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'calendario';
        $child->refFieldName = 'id';
        $child->refEntityName = 'calendario';
        $tree->children['calendario'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'comision_siguiente';
        $child->refFieldName = 'id';
        $child->refEntityName = 'comision';
        $tree->children['comision_siguiente'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'modalidad';
        $child->refFieldName = 'id';
        $child->refEntityName = 'modalidad';
        $tree->children['modalidad'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan'] = $child;
        $tree->children['planificacion'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'sede';
        $child->refFieldName = 'id';
        $child->refEntityName = 'sede';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'centro_educativo';
                $child->refFieldName = 'id';
                $child->refEntityName = 'centro_educativo';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'domicilio';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'domicilio';
                        $tree->children['domicilio_cen'] = $child;
                $tree->children['centro_educativo'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'organizacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'sede';
                $tree->children['organizacion'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'tipo_sede';
                $child->refFieldName = 'id';
                $child->refEntityName = 'tipo_sede';
                $tree->children['tipo_sede'] = $child;
        $tree->children['sede'] = $child;
        $e->tree['comision'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'disposicion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'disposicion';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'asignatura';
        $child->refFieldName = 'id';
        $child->refEntityName = 'asignatura';
        $tree->children['asignatura_dis'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan_pla'] = $child;
        $tree->children['planificacion_dis'] = $child;
        $e->tree['disposicion'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $e->relations['comision'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'comision';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'comision';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'comision';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'comision';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $e->relations['disposicion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion';
        $e->relations['asignatura_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion';
        $e->relations['planificacion_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion_dis';
        $e->relations['plan_pla'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'curso';
        $om->entityName = 'calificacion';
        $e->om['calificacion_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'curso';
        $om->entityName = 'horario';
        $e->om['horario_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'curso';
        $om->entityName = 'toma';
        $e->om['toma_'] = $om;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'alta';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['alta'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'asignatura';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'asi';
        $f->refEntityName = 'asignatura';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['asignatura'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'codigo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['codigo'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'comision';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'com';
        $f->refEntityName = 'comision';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['comision'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'descripcion_horario';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['descripcion_horario'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'disposicion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dis';
        $f->refEntityName = 'disposicion';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['disposicion'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'horas_catedra';
        $f->dataType = 'int';
        $f->type = 'int';
        $f->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $e->fields['horas_catedra'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'ige';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['ige'] = $f;

        $f = new Field();
        $f->entityName = 'curso';
        $f->name = 'observaciones';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $this->entities['curso'] = $e;

        $e = new EntityMetadata();
        $e->name = 'designacion';
        $e->alias = 'desi';
        $e->pk = ['id'];
        $e->fk = ['cargo', 'persona', 'sede'];
        $e->notNull = ['alta', 'cargo', 'id', 'persona', 'sede'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'cargo';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'cargo';
        $e->tree['cargo'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'persona';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'persona';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio'] = $child;
        $e->tree['persona'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'sede';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'sede';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'centro_educativo';
        $child->refFieldName = 'id';
        $child->refEntityName = 'centro_educativo';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio_cen'] = $child;
        $tree->children['centro_educativo'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio_sed'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'organizacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'sede';
        $tree->children['organizacion'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'tipo_sede';
        $child->refFieldName = 'id';
        $child->refEntityName = 'tipo_sede';
        $tree->children['tipo_sede'] = $child;
        $e->tree['sede'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'cargo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'cargo';
        $e->relations['cargo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio_sed'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'alta';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['alta'] = $f;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'cargo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'car';
        $f->refEntityName = 'cargo';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['cargo'] = $f;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'desde';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['desde'] = $f;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'hasta';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['hasta'] = $f;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'persona';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'per';
        $f->refEntityName = 'persona';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['persona'] = $f;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'pfid';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['pfid'] = $f;

        $f = new Field();
        $f->entityName = 'designacion';
        $f->name = 'sede';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'sed';
        $f->refEntityName = 'sede';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['sede'] = $f;

        $this->entities['designacion'] = $e;

        $e = new EntityMetadata();
        $e->name = 'detalle_persona';
        $e->alias = 'deta';
        $e->pk = ['id'];
        $e->fk = ['archivo', 'persona'];
        $e->notNull = ['creado', 'descripcion', 'id', 'persona'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'archivo';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'file';
        $e->tree['archivo'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'persona';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'persona';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio'] = $child;
        $e->tree['persona'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'archivo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'file';
        $e->relations['archivo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'archivo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'arc';
        $f->refEntityName = 'file';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['archivo'] = $f;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'asunto';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['asunto'] = $f;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'creado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['creado'] = $f;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'descripcion';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['descripcion'] = $f;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'fecha';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->defaultValue = 'curdate()';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha'] = $f;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'persona';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'per';
        $f->refEntityName = 'persona';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['persona'] = $f;

        $f = new Field();
        $f->entityName = 'detalle_persona';
        $f->name = 'tipo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['tipo'] = $f;

        $this->entities['detalle_persona'] = $e;

        $e = new EntityMetadata();
        $e->name = 'dia';
        $e->alias = 'dia';
        $e->pk = ['id'];
        $e->unique = ['dia', 'numero'];
        $e->notNull = ['dia', 'id', 'numero'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'dia';
        $om->entityName = 'horario';
        $e->om['horario_'] = $om;

        $f = new Field();
        $f->entityName = 'dia';
        $f->name = 'dia';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['dia'] = $f;

        $f = new Field();
        $f->entityName = 'dia';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'dia';
        $f->name = 'numero';
        $f->dataType = 'smallint';
        $f->type = 'short';
        $f->checks = [
            'type' => 'short',
            'required' => '1',
        ];
        $e->fields['numero'] = $f;

        $this->entities['dia'] = $e;

        $e = new EntityMetadata();
        $e->name = 'disposicion';
        $e->alias = 'disp';
        $e->pk = ['id'];
        $e->fk = ['asignatura', 'planificacion'];
        $e->notNull = ['asignatura', 'horas_catedra', 'id', 'planificacion'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'asignatura';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'asignatura';
        $e->tree['asignatura'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'planificacion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'planificacion';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'plan';
        $child->refFieldName = 'id';
        $child->refEntityName = 'plan';
        $tree->children['plan'] = $child;
        $e->tree['planificacion'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'disposicion';
        $om->entityName = 'calificacion';
        $e->om['calificacion_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'disposicion';
        $om->entityName = 'curso';
        $e->om['curso_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'disposicion';
        $om->entityName = 'disposicion_pendiente';
        $e->om['disposicionPendiente_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'disposicion';
        $om->entityName = 'distribucion_horaria';
        $e->om['distribucionHoraria_'] = $om;

        $f = new Field();
        $f->entityName = 'disposicion';
        $f->name = 'asignatura';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'asi';
        $f->refEntityName = 'asignatura';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['asignatura'] = $f;

        $f = new Field();
        $f->entityName = 'disposicion';
        $f->name = 'horas_catedra';
        $f->dataType = 'int';
        $f->type = 'int';
        $f->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $e->fields['horas_catedra'] = $f;

        $f = new Field();
        $f->entityName = 'disposicion';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'disposicion';
        $f->name = 'orden_informe_coordinacion_distrital';
        $f->dataType = 'int';
        $f->type = 'int';
        $f->checks = [
            'type' => 'int',
        ];
        $e->fields['orden_informe_coordinacion_distrital'] = $f;

        $f = new Field();
        $f->entityName = 'disposicion';
        $f->name = 'planificacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'pla';
        $f->refEntityName = 'planificacion';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['planificacion'] = $f;

        $this->entities['disposicion'] = $e;

        $e = new EntityMetadata();
        $e->name = 'disposicion_pendiente';
        $e->alias = 'dis1';
        $e->pk = ['id'];
        $e->fk = ['alumno', 'disposicion'];
        $e->notNull = ['alumno', 'disposicion', 'id'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'alumno';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'alumno';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'persona';
        $child->refFieldName = 'id';
        $child->refEntityName = 'persona';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'domicilio';
                $child->refFieldName = 'id';
                $child->refEntityName = 'domicilio';
                $tree->children['domicilio'] = $child;
        $tree->children['persona'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'plan';
        $child->refFieldName = 'id';
        $child->refEntityName = 'plan';
        $tree->children['plan'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'resolucion_inscripcion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'resolucion';
        $tree->children['resolucion_inscripcion'] = $child;
        $e->tree['alumno'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'disposicion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'disposicion';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'asignatura';
        $child->refFieldName = 'id';
        $child->refEntityName = 'asignatura';
        $tree->children['asignatura'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan_pla'] = $child;
        $tree->children['planificacion'] = $child;
        $e->tree['disposicion'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'alumno';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'alumno';
        $e->relations['alumno'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $relation->parentId = 'alumno';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'alumno';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'resolucion_inscripcion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'resolucion';
        $relation->parentId = 'alumno';
        $e->relations['resolucion_inscripcion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $e->relations['disposicion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan_pla'] = $relation;

        $f = new Field();
        $f->entityName = 'disposicion_pendiente';
        $f->name = 'alumno';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'alu';
        $f->refEntityName = 'alumno';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['alumno'] = $f;

        $f = new Field();
        $f->entityName = 'disposicion_pendiente';
        $f->name = 'disposicion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dis';
        $f->refEntityName = 'disposicion';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['disposicion'] = $f;

        $f = new Field();
        $f->entityName = 'disposicion_pendiente';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'disposicion_pendiente';
        $f->name = 'modo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['modo'] = $f;

        $this->entities['disposicion_pendiente'] = $e;

        $e = new EntityMetadata();
        $e->name = 'distribucion_horaria';
        $e->alias = 'dist';
        $e->pk = ['id'];
        $e->fk = ['disposicion'];
        $e->notNull = ['dia', 'horas_catedra', 'id'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'disposicion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'disposicion';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'asignatura';
        $child->refFieldName = 'id';
        $child->refEntityName = 'asignatura';
        $tree->children['asignatura'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'planificacion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'planificacion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'plan';
                $child->refFieldName = 'id';
                $child->refEntityName = 'plan';
                $tree->children['plan'] = $child;
        $tree->children['planificacion'] = $child;
        $e->tree['disposicion'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $e->relations['disposicion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $f = new Field();
        $f->entityName = 'distribucion_horaria';
        $f->name = 'dia';
        $f->dataType = 'int';
        $f->type = 'int';
        $f->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $e->fields['dia'] = $f;

        $f = new Field();
        $f->entityName = 'distribucion_horaria';
        $f->name = 'disposicion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dis';
        $f->refEntityName = 'disposicion';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['disposicion'] = $f;

        $f = new Field();
        $f->entityName = 'distribucion_horaria';
        $f->name = 'horas_catedra';
        $f->dataType = 'int';
        $f->type = 'int';
        $f->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $e->fields['horas_catedra'] = $f;

        $f = new Field();
        $f->entityName = 'distribucion_horaria';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $this->entities['distribucion_horaria'] = $e;

        $e = new EntityMetadata();
        $e->name = 'domicilio';
        $e->alias = 'domi';
        $e->pk = ['id'];
        $e->notNull = ['calle', 'id', 'localidad', 'numero'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'domicilio';
        $om->entityName = 'centro_educativo';
        $e->om['centroEducativo_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'domicilio';
        $om->entityName = 'persona';
        $e->om['persona_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'domicilio';
        $om->entityName = 'sede';
        $e->om['sede_'] = $om;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'barrio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['barrio'] = $f;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'calle';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['calle'] = $f;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'departamento';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['departamento'] = $f;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'entre';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['entre'] = $f;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'localidad';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['localidad'] = $f;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'numero';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['numero'] = $f;

        $f = new Field();
        $f->entityName = 'domicilio';
        $f->name = 'piso';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['piso'] = $f;

        $this->entities['domicilio'] = $e;

        $e = new EntityMetadata();
        $e->name = 'email';
        $e->alias = 'emai';
        $e->pk = ['id'];
        $e->fk = ['persona'];
        $e->notNull = ['email', 'id', 'insertado', 'persona', 'verificado'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'persona';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'persona';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio'] = $child;
        $e->tree['persona'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $f = new Field();
        $f->entityName = 'email';
        $f->name = 'eliminado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['eliminado'] = $f;

        $f = new Field();
        $f->entityName = 'email';
        $f->name = 'email';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['email'] = $f;

        $f = new Field();
        $f->entityName = 'email';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'email';
        $f->name = 'insertado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['insertado'] = $f;

        $f = new Field();
        $f->entityName = 'email';
        $f->name = 'persona';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'per';
        $f->refEntityName = 'persona';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['persona'] = $f;

        $f = new Field();
        $f->entityName = 'email';
        $f->name = 'verificado';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['verificado'] = $f;

        $this->entities['email'] = $e;

        $e = new EntityMetadata();
        $e->name = 'file';
        $e->alias = 'file';
        $e->pk = ['id'];
        $e->notNull = ['content', 'created', 'id', 'name', 'size', 'type'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'archivo';
        $om->entityName = 'detalle_persona';
        $e->om['detallePersona_archivo_'] = $om;

        $f = new Field();
        $f->entityName = 'file';
        $f->name = 'content';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['content'] = $f;

        $f = new Field();
        $f->entityName = 'file';
        $f->name = 'created';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['created'] = $f;

        $f = new Field();
        $f->entityName = 'file';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'file';
        $f->name = 'name';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['name'] = $f;

        $f = new Field();
        $f->entityName = 'file';
        $f->name = 'size';
        $f->dataType = 'int';
        $f->type = 'uint';
        $f->checks = [
            'type' => 'uint',
            'required' => '1',
        ];
        $e->fields['size'] = $f;

        $f = new Field();
        $f->entityName = 'file';
        $f->name = 'type';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['type'] = $f;

        $this->entities['file'] = $e;

        $e = new EntityMetadata();
        $e->name = 'horario';
        $e->alias = 'hora';
        $e->pk = ['id'];
        $e->fk = ['curso', 'dia'];
        $e->notNull = ['curso', 'dia', 'hora_fin', 'hora_inicio', 'id'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'curso';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'curso';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'asignatura';
        $child->refFieldName = 'id';
        $child->refEntityName = 'asignatura';
        $tree->children['asignatura'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'comision';
        $child->refFieldName = 'id';
        $child->refEntityName = 'comision';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'calendario';
                $child->refFieldName = 'id';
                $child->refEntityName = 'calendario';
                $tree->children['calendario'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'comision_siguiente';
                $child->refFieldName = 'id';
                $child->refEntityName = 'comision';
                $tree->children['comision_siguiente'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'modalidad';
                $child->refFieldName = 'id';
                $child->refEntityName = 'modalidad';
                $tree->children['modalidad'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'planificacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'planificacion';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'plan';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'plan';
                        $tree->children['plan'] = $child;
                $tree->children['planificacion'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'sede';
                $child->refFieldName = 'id';
                $child->refEntityName = 'sede';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'centro_educativo';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'centro_educativo';
                                $tree->children = [];
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'domicilio';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'domicilio';
                                $tree->children['domicilio_cen'] = $child;
                        $tree->children['centro_educativo'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'domicilio';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'domicilio';
                        $tree->children['domicilio'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'organizacion';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'sede';
                        $tree->children['organizacion'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'tipo_sede';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'tipo_sede';
                        $tree->children['tipo_sede'] = $child;
                $tree->children['sede'] = $child;
        $tree->children['comision'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'disposicion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'disposicion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'asignatura';
                $child->refFieldName = 'id';
                $child->refEntityName = 'asignatura';
                $tree->children['asignatura_dis'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'planificacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'planificacion';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'plan';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'plan';
                        $tree->children['plan_pla'] = $child;
                $tree->children['planificacion_dis'] = $child;
        $tree->children['disposicion'] = $child;
        $e->tree['curso'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'dia';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'dia';
        $e->tree['dia'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'curso';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'curso';
        $e->relations['curso'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'curso';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'curso';
        $e->relations['comision'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'comision';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'comision';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'comision';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'comision';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $relation->parentId = 'curso';
        $e->relations['disposicion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion';
        $e->relations['asignatura_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion';
        $e->relations['planificacion_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion_dis';
        $e->relations['plan_pla'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'dia';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'dia';
        $e->relations['dia'] = $relation;

        $f = new Field();
        $f->entityName = 'horario';
        $f->name = 'curso';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'cur';
        $f->refEntityName = 'curso';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['curso'] = $f;

        $f = new Field();
        $f->entityName = 'horario';
        $f->name = 'dia';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dia';
        $f->refEntityName = 'dia';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['dia'] = $f;

        $f = new Field();
        $f->entityName = 'horario';
        $f->name = 'hora_fin';
        $f->dataType = 'time';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['hora_fin'] = $f;

        $f = new Field();
        $f->entityName = 'horario';
        $f->name = 'hora_inicio';
        $f->dataType = 'time';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['hora_inicio'] = $f;

        $f = new Field();
        $f->entityName = 'horario';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $this->entities['horario'] = $e;

        $e = new EntityMetadata();
        $e->name = 'modalidad';
        $e->alias = 'moda';
        $e->pk = ['id'];
        $e->unique = ['nombre'];
        $e->notNull = ['id', 'nombre'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'modalidad';
        $om->entityName = 'comision';
        $e->om['comision_'] = $om;

        $f = new Field();
        $f->entityName = 'modalidad';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'modalidad';
        $f->name = 'nombre';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['nombre'] = $f;

        $f = new Field();
        $f->entityName = 'modalidad';
        $f->name = 'pfid';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['pfid'] = $f;

        $this->entities['modalidad'] = $e;

        $e = new EntityMetadata();
        $e->name = 'persona';
        $e->alias = 'pers';
        $e->pk = ['id'];
        $e->fk = ['domicilio'];
        $e->unique = ['cuil', 'email_abc', 'numero_documento'];
        $e->notNull = ['alta', 'email_verificado', 'id', 'info_verificada', 'nombres', 'numero_documento', 'telefono_verificado'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'domicilio';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'domicilio';
        $e->tree['domicilio'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $e->relations['domicilio'] = $relation;

        $e->oo = [];
        $oo = new \SqlOrganize\Model\EntityRef();
        $oo->fieldName = 'persona';
        $oo->entityName = 'alumno';
        $e->oo['alumno_'] = $oo;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'persona';
        $om->entityName = 'designacion';
        $e->om['designacion_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'persona';
        $om->entityName = 'detalle_persona';
        $e->om['detallePersona_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'persona';
        $om->entityName = 'email';
        $e->om['email_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'persona';
        $om->entityName = 'telefono';
        $e->om['telefono_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'docente';
        $om->entityName = 'toma';
        $e->om['toma_docente_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'reemplazo';
        $om->entityName = 'toma';
        $e->om['toma_reemplazo_'] = $om;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'alta';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['alta'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'anio_nacimiento';
        $f->dataType = 'smallint';
        $f->type = 'ushort';
        $f->checks = [
            'type' => 'ushort',
        ];
        $e->fields['anio_nacimiento'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'apellidos';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['apellidos'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'apodo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['apodo'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'codigo_area';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['codigo_area'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'cuil';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['cuil'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'cuil1';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
        ];
        $e->fields['cuil1'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'cuil2';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
        ];
        $e->fields['cuil2'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'departamento';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['departamento'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'descripcion_domicilio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['descripcion_domicilio'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'dia_nacimiento';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
        ];
        $e->fields['dia_nacimiento'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'domicilio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dom';
        $f->refEntityName = 'domicilio';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['domicilio'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'email';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['email'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'email_abc';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['email_abc'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'email_verificado';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['email_verificado'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'fecha_nacimiento';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_nacimiento'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'genero';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['genero'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'info_verificada';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['info_verificada'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'localidad';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['localidad'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'lugar_nacimiento';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['lugar_nacimiento'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'mes_nacimiento';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
        ];
        $e->fields['mes_nacimiento'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'nacionalidad';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['nacionalidad'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'nombres';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['nombres'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'numero_documento';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['numero_documento'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'partido';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['partido'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'sexo';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
        ];
        $e->fields['sexo'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'telefono';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['telefono'] = $f;

        $f = new Field();
        $f->entityName = 'persona';
        $f->name = 'telefono_verificado';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['telefono_verificado'] = $f;

        $this->entities['persona'] = $e;

        $e = new EntityMetadata();
        $e->name = 'plan';
        $e->alias = 'plan';
        $e->pk = ['id'];
        $e->notNull = ['id', 'orientacion'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'plan';
        $om->entityName = 'alumno';
        $e->om['alumno_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'plan';
        $om->entityName = 'planificacion';
        $e->om['planificacion_'] = $om;

        $f = new Field();
        $f->entityName = 'plan';
        $f->name = 'distribucion_horaria';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['distribucion_horaria'] = $f;

        $f = new Field();
        $f->entityName = 'plan';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'plan';
        $f->name = 'orientacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['orientacion'] = $f;

        $f = new Field();
        $f->entityName = 'plan';
        $f->name = 'pfid';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['pfid'] = $f;

        $f = new Field();
        $f->entityName = 'plan';
        $f->name = 'resolucion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['resolucion'] = $f;

        $this->entities['plan'] = $e;

        $e = new EntityMetadata();
        $e->name = 'planificacion';
        $e->alias = 'pla1';
        $e->pk = ['id'];
        $e->fk = ['plan'];
        $e->notNull = ['anio', 'id', 'plan', 'semestre'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'plan';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'plan';
        $e->tree['plan'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $e->relations['plan'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'planificacion';
        $om->entityName = 'comision';
        $e->om['comision_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'planificacion';
        $om->entityName = 'disposicion';
        $e->om['disposicion_'] = $om;

        $f = new Field();
        $f->entityName = 'planificacion';
        $f->name = 'anio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['anio'] = $f;

        $f = new Field();
        $f->entityName = 'planificacion';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'planificacion';
        $f->name = 'pfid';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['pfid'] = $f;

        $f = new Field();
        $f->entityName = 'planificacion';
        $f->name = 'plan';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'pla';
        $f->refEntityName = 'plan';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['plan'] = $f;

        $f = new Field();
        $f->entityName = 'planificacion';
        $f->name = 'semestre';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['semestre'] = $f;

        $this->entities['planificacion'] = $e;

        $e = new EntityMetadata();
        $e->name = 'planilla_docente';
        $e->alias = 'pla2';
        $e->pk = ['id'];
        $e->notNull = ['id', 'insertado', 'numero'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'planilla_docente';
        $om->entityName = 'asignacion_planilla_docente';
        $e->om['asignacionPlanillaDocente_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'planilla_docente';
        $om->entityName = 'contralor';
        $e->om['contralor_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'planilla_docente';
        $om->entityName = 'toma';
        $e->om['toma_'] = $om;

        $f = new Field();
        $f->entityName = 'planilla_docente';
        $f->name = 'fecha_consejo';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_consejo'] = $f;

        $f = new Field();
        $f->entityName = 'planilla_docente';
        $f->name = 'fecha_contralor';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_contralor'] = $f;

        $f = new Field();
        $f->entityName = 'planilla_docente';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'planilla_docente';
        $f->name = 'insertado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['insertado'] = $f;

        $f = new Field();
        $f->entityName = 'planilla_docente';
        $f->name = 'numero';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['numero'] = $f;

        $f = new Field();
        $f->entityName = 'planilla_docente';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $this->entities['planilla_docente'] = $e;

        $e = new EntityMetadata();
        $e->name = 'resolucion';
        $e->alias = 'reso';
        $e->pk = ['id'];
        $e->notNull = ['id', 'numero'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'resolucion_inscripcion';
        $om->entityName = 'alumno';
        $e->om['alumno_resolucion_inscripcion_'] = $om;

        $f = new Field();
        $f->entityName = 'resolucion';
        $f->name = 'anio';
        $f->dataType = 'year';
        $f->type = 'short';
        $f->checks = [
            'type' => 'short',
        ];
        $e->fields['anio'] = $f;

        $f = new Field();
        $f->entityName = 'resolucion';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'resolucion';
        $f->name = 'numero';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['numero'] = $f;

        $f = new Field();
        $f->entityName = 'resolucion';
        $f->name = 'tipo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['tipo'] = $f;

        $this->entities['resolucion'] = $e;

        $e = new EntityMetadata();
        $e->name = 'sede';
        $e->alias = 'sede';
        $e->pk = ['id'];
        $e->fk = ['centro_educativo', 'domicilio', 'organizacion', 'tipo_sede'];
        $e->unique = ['nombre'];
        $e->notNull = ['alta', 'id', 'nombre', 'numero'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'centro_educativo';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'centro_educativo';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio_cen'] = $child;
        $e->tree['centro_educativo'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'domicilio';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'domicilio';
        $e->tree['domicilio'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'organizacion';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'sede';
        $e->tree['organizacion'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'tipo_sede';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'tipo_sede';
        $e->tree['tipo_sede'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $e->relations['tipo_sede'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'sede';
        $om->entityName = 'comision';
        $e->om['comision_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'sede';
        $om->entityName = 'designacion';
        $e->om['designacion_'] = $om;

        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'organizacion';
        $om->entityName = 'sede';
        $e->om['sede_organizacion_'] = $om;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'alta';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['alta'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'baja';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['baja'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'centro_educativo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'cen';
        $f->refEntityName = 'centro_educativo';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['centro_educativo'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'domicilio';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'dom';
        $f->refEntityName = 'domicilio';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['domicilio'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'fecha_traspaso';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_traspaso'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'nombre';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['nombre'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'numero';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['numero'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'organizacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'org';
        $f->refEntityName = 'sede';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['organizacion'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'pfid';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['pfid'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'pfid_organizacion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['pfid_organizacion'] = $f;

        $f = new Field();
        $f->entityName = 'sede';
        $f->name = 'tipo_sede';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'tip';
        $f->refEntityName = 'tipo_sede';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['tipo_sede'] = $f;

        $this->entities['sede'] = $e;

        $e = new EntityMetadata();
        $e->name = 'telefono';
        $e->alias = 'tele';
        $e->pk = ['id'];
        $e->fk = ['persona'];
        $e->notNull = ['id', 'insertado', 'numero', 'persona'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'persona';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'persona';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio'] = $child;
        $e->tree['persona'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'persona';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $e->relations['persona'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'persona';
        $e->relations['domicilio'] = $relation;

        $f = new Field();
        $f->entityName = 'telefono';
        $f->name = 'eliminado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['eliminado'] = $f;

        $f = new Field();
        $f->entityName = 'telefono';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'telefono';
        $f->name = 'insertado';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['insertado'] = $f;

        $f = new Field();
        $f->entityName = 'telefono';
        $f->name = 'numero';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['numero'] = $f;

        $f = new Field();
        $f->entityName = 'telefono';
        $f->name = 'persona';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'per';
        $f->refEntityName = 'persona';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['persona'] = $f;

        $f = new Field();
        $f->entityName = 'telefono';
        $f->name = 'prefijo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['prefijo'] = $f;

        $f = new Field();
        $f->entityName = 'telefono';
        $f->name = 'tipo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['tipo'] = $f;

        $this->entities['telefono'] = $e;

        $e = new EntityMetadata();
        $e->name = 'tipo_sede';
        $e->alias = 'tipo';
        $e->pk = ['id'];
        $e->unique = ['descripcion'];
        $e->notNull = ['descripcion', 'id'];
        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'tipo_sede';
        $om->entityName = 'sede';
        $e->om['sede_'] = $om;

        $f = new Field();
        $f->entityName = 'tipo_sede';
        $f->name = 'descripcion';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['descripcion'] = $f;

        $f = new Field();
        $f->entityName = 'tipo_sede';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $this->entities['tipo_sede'] = $e;

        $e = new EntityMetadata();
        $e->name = 'toma';
        $e->alias = 'toma';
        $e->pk = ['id'];
        $e->fk = ['curso', 'docente', 'planilla_docente', 'reemplazo'];
        $e->notNull = ['alta', 'asistencia', 'calificacion', 'confirmada', 'curso', 'id', 'sin_planillas', 'temas_tratados', 'tipo_movimiento'];
        $e->tree = [];
        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'curso';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'curso';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'asignatura';
        $child->refFieldName = 'id';
        $child->refEntityName = 'asignatura';
        $tree->children['asignatura'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'comision';
        $child->refFieldName = 'id';
        $child->refEntityName = 'comision';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'calendario';
                $child->refFieldName = 'id';
                $child->refEntityName = 'calendario';
                $tree->children['calendario'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'comision_siguiente';
                $child->refFieldName = 'id';
                $child->refEntityName = 'comision';
                $tree->children['comision_siguiente'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'modalidad';
                $child->refFieldName = 'id';
                $child->refEntityName = 'modalidad';
                $tree->children['modalidad'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'planificacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'planificacion';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'plan';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'plan';
                        $tree->children['plan'] = $child;
                $tree->children['planificacion'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'sede';
                $child->refFieldName = 'id';
                $child->refEntityName = 'sede';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'centro_educativo';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'centro_educativo';
                                $tree->children = [];
                                $child = new \SqlOrganize\Sql\EntityTree();
                                $child->fieldName = 'domicilio';
                                $child->refFieldName = 'id';
                                $child->refEntityName = 'domicilio';
                                $tree->children['domicilio_cen'] = $child;
                        $tree->children['centro_educativo'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'domicilio';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'domicilio';
                        $tree->children['domicilio'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'organizacion';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'sede';
                        $tree->children['organizacion'] = $child;
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'tipo_sede';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'tipo_sede';
                        $tree->children['tipo_sede'] = $child;
                $tree->children['sede'] = $child;
        $tree->children['comision'] = $child;
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'disposicion';
        $child->refFieldName = 'id';
        $child->refEntityName = 'disposicion';
                $tree->children = [];
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'asignatura';
                $child->refFieldName = 'id';
                $child->refEntityName = 'asignatura';
                $tree->children['asignatura_dis'] = $child;
                $child = new \SqlOrganize\Sql\EntityTree();
                $child->fieldName = 'planificacion';
                $child->refFieldName = 'id';
                $child->refEntityName = 'planificacion';
                        $tree->children = [];
                        $child = new \SqlOrganize\Sql\EntityTree();
                        $child->fieldName = 'plan';
                        $child->refFieldName = 'id';
                        $child->refEntityName = 'plan';
                        $tree->children['plan_pla'] = $child;
                $tree->children['planificacion_dis'] = $child;
        $tree->children['disposicion'] = $child;
        $e->tree['curso'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'docente';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'persona';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio_doc'] = $child;
        $e->tree['docente'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'planilla_docente';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'planilla_docente';
        $e->tree['planilla_docente'] = $tree;

        $tree = new \SqlOrganize\Sql\EntityTree();
        $tree->fieldName = 'reemplazo';
        $tree->refFieldName = 'id';
        $tree->refEntityName = 'persona';
        $tree->children = [];
        $child = new \SqlOrganize\Sql\EntityTree();
        $child->fieldName = 'domicilio';
        $child->refFieldName = 'id';
        $child->refEntityName = 'domicilio';
        $tree->children['domicilio_ree'] = $child;
        $e->tree['reemplazo'] = $tree;

        $e->relations = [];
        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'curso';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'curso';
        $e->relations['curso'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'curso';
        $e->relations['asignatura'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'curso';
        $e->relations['comision'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'calendario';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'calendario';
        $relation->parentId = 'comision';
        $e->relations['calendario'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'comision_siguiente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'comision';
        $relation->parentId = 'comision';
        $e->relations['comision_siguiente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'modalidad';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'modalidad';
        $relation->parentId = 'comision';
        $e->relations['modalidad'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'comision';
        $e->relations['planificacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion';
        $e->relations['plan'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'comision';
        $e->relations['sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'centro_educativo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'centro_educativo';
        $relation->parentId = 'sede';
        $e->relations['centro_educativo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'centro_educativo';
        $e->relations['domicilio_cen'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'sede';
        $e->relations['domicilio'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'organizacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'sede';
        $relation->parentId = 'sede';
        $e->relations['organizacion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'tipo_sede';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'tipo_sede';
        $relation->parentId = 'sede';
        $e->relations['tipo_sede'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'disposicion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'disposicion';
        $relation->parentId = 'curso';
        $e->relations['disposicion'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'asignatura';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'asignatura';
        $relation->parentId = 'disposicion';
        $e->relations['asignatura_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planificacion';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planificacion';
        $relation->parentId = 'disposicion';
        $e->relations['planificacion_dis'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'plan';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'plan';
        $relation->parentId = 'planificacion_dis';
        $e->relations['plan_pla'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'docente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $e->relations['docente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'docente';
        $e->relations['domicilio_doc'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'planilla_docente';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'planilla_docente';
        $e->relations['planilla_docente'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'reemplazo';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'persona';
        $e->relations['reemplazo'] = $relation;

        $relation = new \SqlOrganize\Sql\EntityRelation();
        $relation->fieldName = 'domicilio';
        $relation->refFieldName = 'id';
        $relation->refEntityName = 'domicilio';
        $relation->parentId = 'reemplazo';
        $e->relations['domicilio_ree'] = $relation;

        $e->om = [];
        $om = new \SqlOrganize\Model\EntityRef();
        $om->fieldName = 'toma';
        $om->entityName = 'asignacion_planilla_docente';
        $e->om['asignacionPlanillaDocente_'] = $om;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'alta';
        $f->dataType = 'timestamp';
        $f->type = 'DateTime';
        $f->defaultValue = 'current_timestamp()';
        $f->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $e->fields['alta'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'archivo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['archivo'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'asistencia';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['asistencia'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'calificacion';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['calificacion'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'comentario';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['comentario'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'confirmada';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['confirmada'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'curso';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'cur';
        $f->refEntityName = 'curso';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['curso'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'docente';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'doc';
        $f->refEntityName = 'persona';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['docente'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'estado';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['estado'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'estado_contralor';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['estado_contralor'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'fecha_toma';
        $f->dataType = 'date';
        $f->type = 'DateTime';
        $f->checks = [
            'type' => 'DateTime',
        ];
        $e->fields['fecha_toma'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'id';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['id'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'observaciones';
        $f->dataType = 'text';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['observaciones'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'planilla_docente';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'pla';
        $f->refEntityName = 'planilla_docente';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['planilla_docente'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'reemplazo';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->alias = 'ree';
        $f->refEntityName = 'persona';
        $f->refFieldName = 'id';
        $f->checks = [
            'type' => 'string',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $e->fields['reemplazo'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'sin_planillas';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['sin_planillas'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'temas_tratados';
        $f->dataType = 'tinyint';
        $f->type = 'byte';
        $f->checks = [
            'type' => 'byte',
            'required' => '1',
        ];
        $e->fields['temas_tratados'] = $f;

        $f = new Field();
        $f->entityName = 'toma';
        $f->name = 'tipo_movimiento';
        $f->dataType = 'varchar';
        $f->type = 'string';
        $f->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $f->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $e->fields['tipo_movimiento'] = $f;

        $this->entities['toma'] = $e;

    }
}
