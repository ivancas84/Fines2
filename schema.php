<?php

namespace Fines2;

use SqlOrganize\Sql\EntityMetadata;
use SqlOrganize\Sql\Field;

use SqlOrganize\Sql\EntityTree;
use SqlOrganize\Sql\EntityRelation;
use SqlOrganize\Sql\EntityRef;

/**
 * Esquema de la base de datos
 * Esta clase fue generada por una herramienta, no debe ser modificada.
 */
class Schema
{
    public static function getEntities()
    {
        $entities = [];
        $entities['alumno'] = EntityMetadata::getInstance('alumno', 'alum');
        $entities['alumno']->pk = ['id'];
        $entities['alumno']->fk = ['persona', 'plan', 'resolucion_inscripcion'];
        $entities['alumno']->unique = ['libro_folio', 'persona'];
        $entities['alumno']->notNull = ['confirmado_direccion', 'creado', 'id', 'persona', 'previas_completas', 'tiene_certificado', 'tiene_constancia', 'tiene_dni', 'tiene_partida'];

        $entities['alumno']->tree = [];
        $entities['alumno']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['alumno']->tree['persona']->children = [];
        $entities['alumno']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['alumno']->tree['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $entities['alumno']->tree['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');

        $entities['alumno']->relations = [];
        $entities['alumno']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $entities['alumno']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['alumno']->relations['domicilio']->parentId = 'persona';

        $entities['alumno']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');

        $entities['alumno']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');

        $entities['alumno']->om = [];
        $entities['alumno']->om['AlumnoComision_'] = EntityRef::getInstance('alumno', 'alumno_comision');
        $entities['alumno']->om['Calificacion_'] = EntityRef::getInstance('alumno', 'calificacion');
        $entities['alumno']->om['DisposicionPendiente_'] = EntityRef::getInstance('alumno', 'disposicion_pendiente');
        $entities['alumno']->fields['adeuda_deudores'] = Field::getInstance('alumno', 'adeuda_deudores', 'varchar', 'string');
        $entities['alumno']->fields['adeuda_deudores']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['adeuda_deudores']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['adeuda_legajo'] = Field::getInstance('alumno', 'adeuda_legajo', 'varchar', 'string');
        $entities['alumno']->fields['adeuda_legajo']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['adeuda_legajo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['anio_ingreso'] = Field::getInstance('alumno', 'anio_ingreso', 'varchar', 'string');
        $entities['alumno']->fields['anio_ingreso']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['anio_ingreso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['anio_inscripcion'] = Field::getInstance('alumno', 'anio_inscripcion', 'smallint', 'int');
        $entities['alumno']->fields['anio_inscripcion']->checks = [
            'type' => 'int',
        ];
        $entities['alumno']->fields['anio_inscripcion_completo'] = Field::getInstance('alumno', 'anio_inscripcion_completo', 'tinyint', 'bool');
        $entities['alumno']->fields['anio_inscripcion_completo']->checks = [
            'type' => 'bool',
        ];
        $entities['alumno']->fields['comentarios'] = Field::getInstance('alumno', 'comentarios', 'text', 'string');
        $entities['alumno']->fields['comentarios']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['comentarios']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['confirmado_direccion'] = Field::getInstance('alumno', 'confirmado_direccion', 'tinyint', 'bool');
        $entities['alumno']->fields['confirmado_direccion']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['alumno']->fields['creado'] = Field::getInstance('alumno', 'creado', 'timestamp', 'DateTime');
        $entities['alumno']->fields['creado']->defaultValue = 'current_timestamp()';
        $entities['alumno']->fields['creado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['alumno']->fields['documentacion_inscripcion'] = Field::getInstance('alumno', 'documentacion_inscripcion', 'varchar', 'string');
        $entities['alumno']->fields['documentacion_inscripcion']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['documentacion_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['establecimiento_inscripcion'] = Field::getInstance('alumno', 'establecimiento_inscripcion', 'varchar', 'string');
        $entities['alumno']->fields['establecimiento_inscripcion']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['establecimiento_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['estado_inscripcion'] = Field::getInstance('alumno', 'estado_inscripcion', 'varchar', 'string');
        $entities['alumno']->fields['estado_inscripcion']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['estado_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['fecha_titulacion'] = Field::getInstance('alumno', 'fecha_titulacion', 'date', 'DateTime');
        $entities['alumno']->fields['fecha_titulacion']->checks = [
            'type' => 'DateTime',
        ];
        $entities['alumno']->fields['folio'] = Field::getInstance('alumno', 'folio', 'varchar', 'string');
        $entities['alumno']->fields['folio']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['folio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['id'] = Field::getInstance('alumno', 'id', 'varchar', 'string');
        $entities['alumno']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['alumno']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['alumno']->fields['libro'] = Field::getInstance('alumno', 'libro', 'varchar', 'string');
        $entities['alumno']->fields['libro']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['libro']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['libro_folio'] = Field::getInstance('alumno', 'libro_folio', 'varchar', 'string');
        $entities['alumno']->fields['libro_folio']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['libro_folio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['observaciones'] = Field::getInstance('alumno', 'observaciones', 'text', 'string');
        $entities['alumno']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['persona'] = Field::getInstance('alumno', 'persona', 'varchar', 'string');
        $entities['alumno']->fields['persona']->alias = 'per';
        $entities['alumno']->fields['persona']->refEntityName = 'persona';
        $entities['alumno']->fields['persona']->refFieldName = 'id';
        $entities['alumno']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['alumno']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['alumno']->fields['plan'] = Field::getInstance('alumno', 'plan', 'varchar', 'string');
        $entities['alumno']->fields['plan']->alias = 'pla';
        $entities['alumno']->fields['plan']->refEntityName = 'plan';
        $entities['alumno']->fields['plan']->refFieldName = 'id';
        $entities['alumno']->fields['plan']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['plan']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['previas_completas'] = Field::getInstance('alumno', 'previas_completas', 'tinyint', 'bool');
        $entities['alumno']->fields['previas_completas']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['alumno']->fields['resolucion_inscripcion'] = Field::getInstance('alumno', 'resolucion_inscripcion', 'varchar', 'string');
        $entities['alumno']->fields['resolucion_inscripcion']->alias = 'res';
        $entities['alumno']->fields['resolucion_inscripcion']->refEntityName = 'resolucion';
        $entities['alumno']->fields['resolucion_inscripcion']->refFieldName = 'id';
        $entities['alumno']->fields['resolucion_inscripcion']->checks = [
            'type' => 'string',
        ];
        $entities['alumno']->fields['resolucion_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno']->fields['semestre_ingreso'] = Field::getInstance('alumno', 'semestre_ingreso', 'smallint', 'int');
        $entities['alumno']->fields['semestre_ingreso']->checks = [
            'type' => 'int',
        ];
        $entities['alumno']->fields['semestre_inscripcion'] = Field::getInstance('alumno', 'semestre_inscripcion', 'smallint', 'int');
        $entities['alumno']->fields['semestre_inscripcion']->checks = [
            'type' => 'int',
        ];
        $entities['alumno']->fields['tiene_certificado'] = Field::getInstance('alumno', 'tiene_certificado', 'tinyint', 'bool');
        $entities['alumno']->fields['tiene_certificado']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['alumno']->fields['tiene_constancia'] = Field::getInstance('alumno', 'tiene_constancia', 'tinyint', 'bool');
        $entities['alumno']->fields['tiene_constancia']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['alumno']->fields['tiene_dni'] = Field::getInstance('alumno', 'tiene_dni', 'tinyint', 'bool');
        $entities['alumno']->fields['tiene_dni']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['alumno']->fields['tiene_partida'] = Field::getInstance('alumno', 'tiene_partida', 'tinyint', 'bool');
        $entities['alumno']->fields['tiene_partida']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['alumno_comision'] = EntityMetadata::getInstance('alumno_comision', 'alu1');
        $entities['alumno_comision']->pk = ['id'];
        $entities['alumno_comision']->fk = ['alumno', 'comision'];
        $entities['alumno_comision']->notNull = ['alumno', 'creado', 'id'];

        $entities['alumno_comision']->tree = [];
        $entities['alumno_comision']->tree['alumno'] = EntityTree::getInstance('alumno', 'alumno', 'id');
        $entities['alumno_comision']->tree['alumno']->children = [];
        $entities['alumno_comision']->tree['alumno']->children['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['alumno_comision']->tree['alumno']->children['persona']->children = [];
        $entities['alumno_comision']->tree['alumno']->children['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['alumno_comision']->tree['alumno']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $entities['alumno_comision']->tree['alumno']->children['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');


        $entities['alumno_comision']->tree['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $entities['alumno_comision']->tree['comision']->children = [];
        $entities['alumno_comision']->tree['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['alumno_comision']->tree['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['alumno_comision']->tree['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['alumno_comision']->tree['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['alumno_comision']->tree['comision']->children['planificacion']->children = [];
        $entities['alumno_comision']->tree['comision']->children['planificacion']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['alumno_comision']->tree['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['alumno_comision']->tree['comision']->children['sede']->children = [];
        $entities['alumno_comision']->tree['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['alumno_comision']->tree['comision']->children['sede']->children['centro_educativo']->children = [];
        $entities['alumno_comision']->tree['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['alumno_comision']->tree['comision']->children['sede']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['alumno_comision']->tree['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['alumno_comision']->tree['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $entities['alumno_comision']->relations = [];
        $entities['alumno_comision']->relations['alumno'] = EntityRelation::getInstance('alumno', 'alumno', 'id');

        $entities['alumno_comision']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');
        $entities['alumno_comision']->relations['persona']->parentId = 'alumno';

        $entities['alumno_comision']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['alumno_comision']->relations['domicilio']->parentId = 'persona';

        $entities['alumno_comision']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['alumno_comision']->relations['plan']->parentId = 'alumno';

        $entities['alumno_comision']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');
        $entities['alumno_comision']->relations['resolucion_inscripcion']->parentId = 'alumno';

        $entities['alumno_comision']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');

        $entities['alumno_comision']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $entities['alumno_comision']->relations['calendario']->parentId = 'comision';

        $entities['alumno_comision']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $entities['alumno_comision']->relations['comision_siguiente']->parentId = 'comision';

        $entities['alumno_comision']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $entities['alumno_comision']->relations['modalidad']->parentId = 'comision';

        $entities['alumno_comision']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['alumno_comision']->relations['planificacion']->parentId = 'comision';

        $entities['alumno_comision']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['alumno_comision']->relations['plan_pla']->parentId = 'planificacion';

        $entities['alumno_comision']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $entities['alumno_comision']->relations['sede']->parentId = 'comision';

        $entities['alumno_comision']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['alumno_comision']->relations['centro_educativo']->parentId = 'sede';

        $entities['alumno_comision']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['alumno_comision']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['alumno_comision']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['alumno_comision']->relations['domicilio_sed']->parentId = 'sede';

        $entities['alumno_comision']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['alumno_comision']->relations['organizacion']->parentId = 'sede';

        $entities['alumno_comision']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['alumno_comision']->relations['tipo_sede']->parentId = 'sede';

        $entities['alumno_comision']->fields['activo'] = Field::getInstance('alumno_comision', 'activo', 'tinyint', 'bool');
        $entities['alumno_comision']->fields['activo']->checks = [
            'type' => 'bool',
        ];
        $entities['alumno_comision']->fields['alumno'] = Field::getInstance('alumno_comision', 'alumno', 'varchar', 'string');
        $entities['alumno_comision']->fields['alumno']->alias = 'alu';
        $entities['alumno_comision']->fields['alumno']->refEntityName = 'alumno';
        $entities['alumno_comision']->fields['alumno']->refFieldName = 'id';
        $entities['alumno_comision']->fields['alumno']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['alumno_comision']->fields['alumno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['alumno_comision']->fields['comision'] = Field::getInstance('alumno_comision', 'comision', 'varchar', 'string');
        $entities['alumno_comision']->fields['comision']->alias = 'com';
        $entities['alumno_comision']->fields['comision']->refEntityName = 'comision';
        $entities['alumno_comision']->fields['comision']->refFieldName = 'id';
        $entities['alumno_comision']->fields['comision']->checks = [
            'type' => 'string',
        ];
        $entities['alumno_comision']->fields['comision']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno_comision']->fields['creado'] = Field::getInstance('alumno_comision', 'creado', 'timestamp', 'DateTime');
        $entities['alumno_comision']->fields['creado']->defaultValue = 'current_timestamp()';
        $entities['alumno_comision']->fields['creado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['alumno_comision']->fields['estado'] = Field::getInstance('alumno_comision', 'estado', 'varchar', 'string');
        $entities['alumno_comision']->fields['estado']->defaultValue = 'Activo';
        $entities['alumno_comision']->fields['estado']->checks = [
            'type' => 'string',
        ];
        $entities['alumno_comision']->fields['estado']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno_comision']->fields['id'] = Field::getInstance('alumno_comision', 'id', 'varchar', 'string');
        $entities['alumno_comision']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['alumno_comision']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['alumno_comision']->fields['observaciones'] = Field::getInstance('alumno_comision', 'observaciones', 'text', 'string');
        $entities['alumno_comision']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['alumno_comision']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['alumno_comision']->fields['pfid'] = Field::getInstance('alumno_comision', 'pfid', 'int', 'int');
        $entities['alumno_comision']->fields['pfid']->checks = [
            'type' => 'int',
        ];
        $entities['asignatura'] = EntityMetadata::getInstance('asignatura', 'asig');
        $entities['asignatura']->pk = ['id'];
        $entities['asignatura']->unique = ['nombre'];
        $entities['asignatura']->notNull = ['id', 'nombre'];

        $entities['asignatura']->om = [];
        $entities['asignatura']->om['Curso_'] = EntityRef::getInstance('asignatura', 'curso');
        $entities['asignatura']->om['Disposicion_'] = EntityRef::getInstance('asignatura', 'disposicion');
        $entities['asignatura']->fields['clasificacion'] = Field::getInstance('asignatura', 'clasificacion', 'varchar', 'string');
        $entities['asignatura']->fields['clasificacion']->checks = [
            'type' => 'string',
        ];
        $entities['asignatura']->fields['clasificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['asignatura']->fields['codigo'] = Field::getInstance('asignatura', 'codigo', 'varchar', 'string');
        $entities['asignatura']->fields['codigo']->checks = [
            'type' => 'string',
        ];
        $entities['asignatura']->fields['codigo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['asignatura']->fields['formacion'] = Field::getInstance('asignatura', 'formacion', 'varchar', 'string');
        $entities['asignatura']->fields['formacion']->checks = [
            'type' => 'string',
        ];
        $entities['asignatura']->fields['formacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['asignatura']->fields['id'] = Field::getInstance('asignatura', 'id', 'varchar', 'string');
        $entities['asignatura']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['asignatura']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['asignatura']->fields['nombre'] = Field::getInstance('asignatura', 'nombre', 'varchar', 'string');
        $entities['asignatura']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['asignatura']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['asignatura']->fields['perfil'] = Field::getInstance('asignatura', 'perfil', 'varchar', 'string');
        $entities['asignatura']->fields['perfil']->checks = [
            'type' => 'string',
        ];
        $entities['asignatura']->fields['perfil']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['calendario'] = EntityMetadata::getInstance('calendario', 'cale');
        $entities['calendario']->pk = ['id'];
        $entities['calendario']->notNull = ['anio', 'id', 'insertado', 'semestre'];

        $entities['calendario']->om = [];
        $entities['calendario']->om['Comision_'] = EntityRef::getInstance('calendario', 'comision');
        $entities['calendario']->fields['anio'] = Field::getInstance('calendario', 'anio', 'year', 'int');
        $entities['calendario']->fields['anio']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['calendario']->fields['descripcion'] = Field::getInstance('calendario', 'descripcion', 'varchar', 'string');
        $entities['calendario']->fields['descripcion']->checks = [
            'type' => 'string',
        ];
        $entities['calendario']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['calendario']->fields['fin'] = Field::getInstance('calendario', 'fin', 'date', 'DateTime');
        $entities['calendario']->fields['fin']->checks = [
            'type' => 'DateTime',
        ];
        $entities['calendario']->fields['id'] = Field::getInstance('calendario', 'id', 'varchar', 'string');
        $entities['calendario']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['calendario']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['calendario']->fields['inicio'] = Field::getInstance('calendario', 'inicio', 'date', 'DateTime');
        $entities['calendario']->fields['inicio']->checks = [
            'type' => 'DateTime',
        ];
        $entities['calendario']->fields['insertado'] = Field::getInstance('calendario', 'insertado', 'timestamp', 'DateTime');
        $entities['calendario']->fields['insertado']->defaultValue = 'current_timestamp()';
        $entities['calendario']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['calendario']->fields['semestre'] = Field::getInstance('calendario', 'semestre', 'smallint', 'int');
        $entities['calendario']->fields['semestre']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['calificacion'] = EntityMetadata::getInstance('calificacion', 'cali');
        $entities['calificacion']->pk = ['id'];
        $entities['calificacion']->fk = ['alumno', 'curso', 'disposicion'];
        $entities['calificacion']->notNull = ['alumno', 'archivado', 'disposicion', 'id'];

        $entities['calificacion']->tree = [];
        $entities['calificacion']->tree['alumno'] = EntityTree::getInstance('alumno', 'alumno', 'id');
        $entities['calificacion']->tree['alumno']->children = [];
        $entities['calificacion']->tree['alumno']->children['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['calificacion']->tree['alumno']->children['persona']->children = [];
        $entities['calificacion']->tree['alumno']->children['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['calificacion']->tree['alumno']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $entities['calificacion']->tree['alumno']->children['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');


        $entities['calificacion']->tree['curso'] = EntityTree::getInstance('curso', 'curso', 'id');
        $entities['calificacion']->tree['curso']->children = [];
        $entities['calificacion']->tree['curso']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['calificacion']->tree['curso']->children['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $entities['calificacion']->tree['curso']->children['comision']->children = [];
        $entities['calificacion']->tree['curso']->children['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['calificacion']->tree['curso']->children['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['calificacion']->tree['curso']->children['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['calificacion']->tree['curso']->children['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['calificacion']->tree['curso']->children['comision']->children['planificacion']->children = [];
        $entities['calificacion']->tree['curso']->children['comision']->children['planificacion']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['calificacion']->tree['curso']->children['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['calificacion']->tree['curso']->children['comision']->children['sede']->children = [];
        $entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children = [];
        $entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $entities['calificacion']->tree['curso']->children['disposicion_cur'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $entities['calificacion']->tree['curso']->children['disposicion_cur']->children = [];
        $entities['calificacion']->tree['curso']->children['disposicion_cur']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['calificacion']->tree['curso']->children['disposicion_cur']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['calificacion']->tree['curso']->children['disposicion_cur']->children['planificacion_dis']->children = [];
        $entities['calificacion']->tree['curso']->children['disposicion_cur']->children['planificacion_dis']->children['plan_pla1'] = EntityTree::getInstance('plan', 'plan', 'id');




        $entities['calificacion']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $entities['calificacion']->tree['disposicion']->children = [];
        $entities['calificacion']->tree['disposicion']->children['asignatura_dis1'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['calificacion']->tree['disposicion']->children['planificacion_dis1'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['calificacion']->tree['disposicion']->children['planificacion_dis1']->children = [];
        $entities['calificacion']->tree['disposicion']->children['planificacion_dis1']->children['plan_pla2'] = EntityTree::getInstance('plan', 'plan', 'id');



        $entities['calificacion']->relations = [];
        $entities['calificacion']->relations['alumno'] = EntityRelation::getInstance('alumno', 'alumno', 'id');

        $entities['calificacion']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');
        $entities['calificacion']->relations['persona']->parentId = 'alumno';

        $entities['calificacion']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['calificacion']->relations['domicilio']->parentId = 'persona';

        $entities['calificacion']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['calificacion']->relations['plan']->parentId = 'alumno';

        $entities['calificacion']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');
        $entities['calificacion']->relations['resolucion_inscripcion']->parentId = 'alumno';

        $entities['calificacion']->relations['curso'] = EntityRelation::getInstance('curso', 'curso', 'id');

        $entities['calificacion']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['calificacion']->relations['asignatura']->parentId = 'curso';

        $entities['calificacion']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');
        $entities['calificacion']->relations['comision']->parentId = 'curso';

        $entities['calificacion']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $entities['calificacion']->relations['calendario']->parentId = 'comision';

        $entities['calificacion']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $entities['calificacion']->relations['comision_siguiente']->parentId = 'comision';

        $entities['calificacion']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $entities['calificacion']->relations['modalidad']->parentId = 'comision';

        $entities['calificacion']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['calificacion']->relations['planificacion']->parentId = 'comision';

        $entities['calificacion']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['calificacion']->relations['plan_pla']->parentId = 'planificacion';

        $entities['calificacion']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $entities['calificacion']->relations['sede']->parentId = 'comision';

        $entities['calificacion']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['calificacion']->relations['centro_educativo']->parentId = 'sede';

        $entities['calificacion']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['calificacion']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['calificacion']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['calificacion']->relations['domicilio_sed']->parentId = 'sede';

        $entities['calificacion']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['calificacion']->relations['organizacion']->parentId = 'sede';

        $entities['calificacion']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['calificacion']->relations['tipo_sede']->parentId = 'sede';

        $entities['calificacion']->relations['disposicion_cur'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');
        $entities['calificacion']->relations['disposicion_cur']->parentId = 'curso';

        $entities['calificacion']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['calificacion']->relations['asignatura_dis']->parentId = 'disposicion_cur';

        $entities['calificacion']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['calificacion']->relations['planificacion_dis']->parentId = 'disposicion_cur';

        $entities['calificacion']->relations['plan_pla1'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['calificacion']->relations['plan_pla1']->parentId = 'planificacion_dis';

        $entities['calificacion']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $entities['calificacion']->relations['asignatura_dis1'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['calificacion']->relations['asignatura_dis1']->parentId = 'disposicion';

        $entities['calificacion']->relations['planificacion_dis1'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['calificacion']->relations['planificacion_dis1']->parentId = 'disposicion';

        $entities['calificacion']->relations['plan_pla2'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['calificacion']->relations['plan_pla2']->parentId = 'planificacion_dis1';

        $entities['calificacion']->fields['alumno'] = Field::getInstance('calificacion', 'alumno', 'varchar', 'string');
        $entities['calificacion']->fields['alumno']->alias = 'alu';
        $entities['calificacion']->fields['alumno']->refEntityName = 'alumno';
        $entities['calificacion']->fields['alumno']->refFieldName = 'id';
        $entities['calificacion']->fields['alumno']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['calificacion']->fields['alumno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['calificacion']->fields['archivado'] = Field::getInstance('calificacion', 'archivado', 'tinyint', 'bool');
        $entities['calificacion']->fields['archivado']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['calificacion']->fields['crec'] = Field::getInstance('calificacion', 'crec', 'decimal', 'float');
        $entities['calificacion']->fields['crec']->checks = [
            'type' => 'float',
        ];
        $entities['calificacion']->fields['curso'] = Field::getInstance('calificacion', 'curso', 'varchar', 'string');
        $entities['calificacion']->fields['curso']->alias = 'cur';
        $entities['calificacion']->fields['curso']->refEntityName = 'curso';
        $entities['calificacion']->fields['curso']->refFieldName = 'id';
        $entities['calificacion']->fields['curso']->checks = [
            'type' => 'string',
        ];
        $entities['calificacion']->fields['curso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['calificacion']->fields['disposicion'] = Field::getInstance('calificacion', 'disposicion', 'varchar', 'string');
        $entities['calificacion']->fields['disposicion']->alias = 'dis';
        $entities['calificacion']->fields['disposicion']->refEntityName = 'disposicion';
        $entities['calificacion']->fields['disposicion']->refFieldName = 'id';
        $entities['calificacion']->fields['disposicion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['calificacion']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['calificacion']->fields['division'] = Field::getInstance('calificacion', 'division', 'varchar', 'string');
        $entities['calificacion']->fields['division']->checks = [
            'type' => 'string',
        ];
        $entities['calificacion']->fields['division']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['calificacion']->fields['fecha'] = Field::getInstance('calificacion', 'fecha', 'date', 'DateTime');
        $entities['calificacion']->fields['fecha']->checks = [
            'type' => 'DateTime',
        ];
        $entities['calificacion']->fields['id'] = Field::getInstance('calificacion', 'id', 'varchar', 'string');
        $entities['calificacion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['calificacion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['calificacion']->fields['nota1'] = Field::getInstance('calificacion', 'nota1', 'decimal', 'float');
        $entities['calificacion']->fields['nota1']->checks = [
            'type' => 'float',
        ];
        $entities['calificacion']->fields['nota2'] = Field::getInstance('calificacion', 'nota2', 'decimal', 'float');
        $entities['calificacion']->fields['nota2']->checks = [
            'type' => 'float',
        ];
        $entities['calificacion']->fields['nota3'] = Field::getInstance('calificacion', 'nota3', 'decimal', 'float');
        $entities['calificacion']->fields['nota3']->checks = [
            'type' => 'float',
        ];
        $entities['calificacion']->fields['nota_final'] = Field::getInstance('calificacion', 'nota_final', 'decimal', 'float');
        $entities['calificacion']->fields['nota_final']->checks = [
            'type' => 'float',
        ];
        $entities['calificacion']->fields['observaciones'] = Field::getInstance('calificacion', 'observaciones', 'text', 'string');
        $entities['calificacion']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['calificacion']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['calificacion']->fields['porcentaje_asistencia'] = Field::getInstance('calificacion', 'porcentaje_asistencia', 'int', 'int');
        $entities['calificacion']->fields['porcentaje_asistencia']->checks = [
            'type' => 'int',
        ];
        $entities['cargo'] = EntityMetadata::getInstance('cargo', 'carg');
        $entities['cargo']->pk = ['id'];
        $entities['cargo']->unique = ['descripcion'];
        $entities['cargo']->notNull = ['descripcion', 'id'];

        $entities['cargo']->om = [];
        $entities['cargo']->om['Designacion_'] = EntityRef::getInstance('cargo', 'designacion');
        $entities['cargo']->fields['descripcion'] = Field::getInstance('cargo', 'descripcion', 'varchar', 'string');
        $entities['cargo']->fields['descripcion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['cargo']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['cargo']->fields['id'] = Field::getInstance('cargo', 'id', 'varchar', 'string');
        $entities['cargo']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['cargo']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['centro_educativo'] = EntityMetadata::getInstance('centro_educativo', 'cent');
        $entities['centro_educativo']->pk = ['id'];
        $entities['centro_educativo']->fk = ['domicilio'];
        $entities['centro_educativo']->unique = ['cue'];
        $entities['centro_educativo']->notNull = ['id', 'nombre'];

        $entities['centro_educativo']->tree = [];
        $entities['centro_educativo']->tree['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['centro_educativo']->relations = [];
        $entities['centro_educativo']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');

        $entities['centro_educativo']->om = [];
        $entities['centro_educativo']->om['Sede_'] = EntityRef::getInstance('centro_educativo', 'sede');
        $entities['centro_educativo']->fields['cue'] = Field::getInstance('centro_educativo', 'cue', 'varchar', 'string');
        $entities['centro_educativo']->fields['cue']->checks = [
            'type' => 'string',
        ];
        $entities['centro_educativo']->fields['cue']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['centro_educativo']->fields['domicilio'] = Field::getInstance('centro_educativo', 'domicilio', 'varchar', 'string');
        $entities['centro_educativo']->fields['domicilio']->alias = 'dom';
        $entities['centro_educativo']->fields['domicilio']->refEntityName = 'domicilio';
        $entities['centro_educativo']->fields['domicilio']->refFieldName = 'id';
        $entities['centro_educativo']->fields['domicilio']->checks = [
            'type' => 'string',
        ];
        $entities['centro_educativo']->fields['domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['centro_educativo']->fields['id'] = Field::getInstance('centro_educativo', 'id', 'varchar', 'string');
        $entities['centro_educativo']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['centro_educativo']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['centro_educativo']->fields['nombre'] = Field::getInstance('centro_educativo', 'nombre', 'varchar', 'string');
        $entities['centro_educativo']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['centro_educativo']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['centro_educativo']->fields['observaciones'] = Field::getInstance('centro_educativo', 'observaciones', 'text', 'string');
        $entities['centro_educativo']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['centro_educativo']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision'] = EntityMetadata::getInstance('comision', 'comi');
        $entities['comision']->pk = ['id'];
        $entities['comision']->fk = ['calendario', 'comision_siguiente', 'modalidad', 'planificacion', 'sede'];
        $entities['comision']->notNull = ['alta', 'apertura', 'autorizada', 'calendario', 'division', 'id', 'modalidad', 'publicada', 'sede'];

        $entities['comision']->tree = [];
        $entities['comision']->tree['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['comision']->tree['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['comision']->tree['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['comision']->tree['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['comision']->tree['planificacion']->children = [];
        $entities['comision']->tree['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['comision']->tree['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['comision']->tree['sede']->children = [];
        $entities['comision']->tree['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['comision']->tree['sede']->children['centro_educativo']->children = [];
        $entities['comision']->tree['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['comision']->tree['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['comision']->tree['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['comision']->tree['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');


        $entities['comision']->relations = [];
        $entities['comision']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');

        $entities['comision']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');

        $entities['comision']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');

        $entities['comision']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');

        $entities['comision']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['comision']->relations['plan']->parentId = 'planificacion';

        $entities['comision']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');

        $entities['comision']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['comision']->relations['centro_educativo']->parentId = 'sede';

        $entities['comision']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['comision']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['comision']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['comision']->relations['domicilio']->parentId = 'sede';

        $entities['comision']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['comision']->relations['organizacion']->parentId = 'sede';

        $entities['comision']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['comision']->relations['tipo_sede']->parentId = 'sede';

        $entities['comision']->om = [];
        $entities['comision']->om['AlumnoComision_'] = EntityRef::getInstance('comision', 'alumno_comision');
        $entities['comision']->om['Comision_comision_siguiente_'] = EntityRef::getInstance('comision_siguiente', 'comision');
        $entities['comision']->om['ComisionRelacionada_'] = EntityRef::getInstance('comision', 'comision_relacionada');
        $entities['comision']->om['ComisionRelacionada_relacion_'] = EntityRef::getInstance('relacion', 'comision_relacionada');
        $entities['comision']->om['Curso_'] = EntityRef::getInstance('comision', 'curso');
        $entities['comision']->fields['alta'] = Field::getInstance('comision', 'alta', 'timestamp', 'DateTime');
        $entities['comision']->fields['alta']->defaultValue = 'current_timestamp()';
        $entities['comision']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['comision']->fields['apertura'] = Field::getInstance('comision', 'apertura', 'tinyint', 'bool');
        $entities['comision']->fields['apertura']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['comision']->fields['autorizada'] = Field::getInstance('comision', 'autorizada', 'tinyint', 'bool');
        $entities['comision']->fields['autorizada']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['comision']->fields['calendario'] = Field::getInstance('comision', 'calendario', 'varchar', 'string');
        $entities['comision']->fields['calendario']->alias = 'cal';
        $entities['comision']->fields['calendario']->refEntityName = 'calendario';
        $entities['comision']->fields['calendario']->refFieldName = 'id';
        $entities['comision']->fields['calendario']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision']->fields['calendario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['comision']->fields['comentario'] = Field::getInstance('comision', 'comentario', 'text', 'string');
        $entities['comision']->fields['comentario']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['comentario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['comision_siguiente'] = Field::getInstance('comision', 'comision_siguiente', 'varchar', 'string');
        $entities['comision']->fields['comision_siguiente']->alias = 'com';
        $entities['comision']->fields['comision_siguiente']->refEntityName = 'comision';
        $entities['comision']->fields['comision_siguiente']->refFieldName = 'id';
        $entities['comision']->fields['comision_siguiente']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['comision_siguiente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['configuracion'] = Field::getInstance('comision', 'configuracion', 'varchar', 'string');
        $entities['comision']->fields['configuracion']->defaultValue = 'Histórica';
        $entities['comision']->fields['configuracion']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['configuracion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['division'] = Field::getInstance('comision', 'division', 'varchar', 'string');
        $entities['comision']->fields['division']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision']->fields['division']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['comision']->fields['estado'] = Field::getInstance('comision', 'estado', 'varchar', 'string');
        $entities['comision']->fields['estado']->defaultValue = 'Confirma';
        $entities['comision']->fields['estado']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['estado']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['id'] = Field::getInstance('comision', 'id', 'varchar', 'string');
        $entities['comision']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['comision']->fields['identificacion'] = Field::getInstance('comision', 'identificacion', 'varchar', 'string');
        $entities['comision']->fields['identificacion']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['identificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['modalidad'] = Field::getInstance('comision', 'modalidad', 'varchar', 'string');
        $entities['comision']->fields['modalidad']->alias = 'mod';
        $entities['comision']->fields['modalidad']->refEntityName = 'modalidad';
        $entities['comision']->fields['modalidad']->refFieldName = 'id';
        $entities['comision']->fields['modalidad']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision']->fields['modalidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['comision']->fields['observaciones'] = Field::getInstance('comision', 'observaciones', 'text', 'string');
        $entities['comision']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['pfid'] = Field::getInstance('comision', 'pfid', 'varchar', 'string');
        $entities['comision']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['planificacion'] = Field::getInstance('comision', 'planificacion', 'varchar', 'string');
        $entities['comision']->fields['planificacion']->alias = 'pla';
        $entities['comision']->fields['planificacion']->refEntityName = 'planificacion';
        $entities['comision']->fields['planificacion']->refFieldName = 'id';
        $entities['comision']->fields['planificacion']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['planificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision']->fields['publicada'] = Field::getInstance('comision', 'publicada', 'tinyint', 'bool');
        $entities['comision']->fields['publicada']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['comision']->fields['sede'] = Field::getInstance('comision', 'sede', 'varchar', 'string');
        $entities['comision']->fields['sede']->alias = 'sed';
        $entities['comision']->fields['sede']->refEntityName = 'sede';
        $entities['comision']->fields['sede']->refFieldName = 'id';
        $entities['comision']->fields['sede']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision']->fields['sede']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['comision']->fields['turno'] = Field::getInstance('comision', 'turno', 'varchar', 'string');
        $entities['comision']->fields['turno']->checks = [
            'type' => 'string',
        ];
        $entities['comision']->fields['turno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['comision_relacionada'] = EntityMetadata::getInstance('comision_relacionada', 'com1');
        $entities['comision_relacionada']->pk = ['id'];
        $entities['comision_relacionada']->fk = ['comision', 'relacion'];
        $entities['comision_relacionada']->notNull = ['comision', 'id', 'relacion'];

        $entities['comision_relacionada']->tree = [];
        $entities['comision_relacionada']->tree['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $entities['comision_relacionada']->tree['comision']->children = [];
        $entities['comision_relacionada']->tree['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['comision_relacionada']->tree['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['comision_relacionada']->tree['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['comision_relacionada']->tree['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['comision_relacionada']->tree['comision']->children['planificacion']->children = [];
        $entities['comision_relacionada']->tree['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['comision_relacionada']->tree['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['comision_relacionada']->tree['comision']->children['sede']->children = [];
        $entities['comision_relacionada']->tree['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['comision_relacionada']->tree['comision']->children['sede']->children['centro_educativo']->children = [];
        $entities['comision_relacionada']->tree['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['comision_relacionada']->tree['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['comision_relacionada']->tree['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['comision_relacionada']->tree['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $entities['comision_relacionada']->tree['relacion'] = EntityTree::getInstance('relacion', 'comision', 'id');
        $entities['comision_relacionada']->tree['relacion']->children = [];
        $entities['comision_relacionada']->tree['relacion']->children['calendario_rel'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['comision_relacionada']->tree['relacion']->children['comision_siguiente_rel'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['comision_relacionada']->tree['relacion']->children['modalidad_rel'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['comision_relacionada']->tree['relacion']->children['planificacion_rel'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['comision_relacionada']->tree['relacion']->children['planificacion_rel']->children = [];
        $entities['comision_relacionada']->tree['relacion']->children['planificacion_rel']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['comision_relacionada']->tree['relacion']->children['sede_rel'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children = [];
        $entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['centro_educativo_sed'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['centro_educativo_sed']->children = [];
        $entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['centro_educativo_sed']->children['domicilio_cen1'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['organizacion_sed'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['tipo_sede_sed'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $entities['comision_relacionada']->relations = [];
        $entities['comision_relacionada']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');

        $entities['comision_relacionada']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $entities['comision_relacionada']->relations['calendario']->parentId = 'comision';

        $entities['comision_relacionada']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $entities['comision_relacionada']->relations['comision_siguiente']->parentId = 'comision';

        $entities['comision_relacionada']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $entities['comision_relacionada']->relations['modalidad']->parentId = 'comision';

        $entities['comision_relacionada']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['comision_relacionada']->relations['planificacion']->parentId = 'comision';

        $entities['comision_relacionada']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['comision_relacionada']->relations['plan']->parentId = 'planificacion';

        $entities['comision_relacionada']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $entities['comision_relacionada']->relations['sede']->parentId = 'comision';

        $entities['comision_relacionada']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['comision_relacionada']->relations['centro_educativo']->parentId = 'sede';

        $entities['comision_relacionada']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['comision_relacionada']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['comision_relacionada']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['comision_relacionada']->relations['domicilio']->parentId = 'sede';

        $entities['comision_relacionada']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['comision_relacionada']->relations['organizacion']->parentId = 'sede';

        $entities['comision_relacionada']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['comision_relacionada']->relations['tipo_sede']->parentId = 'sede';

        $entities['comision_relacionada']->relations['relacion'] = EntityRelation::getInstance('relacion', 'comision', 'id');

        $entities['comision_relacionada']->relations['calendario_rel'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $entities['comision_relacionada']->relations['calendario_rel']->parentId = 'relacion';

        $entities['comision_relacionada']->relations['comision_siguiente_rel'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $entities['comision_relacionada']->relations['comision_siguiente_rel']->parentId = 'relacion';

        $entities['comision_relacionada']->relations['modalidad_rel'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $entities['comision_relacionada']->relations['modalidad_rel']->parentId = 'relacion';

        $entities['comision_relacionada']->relations['planificacion_rel'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['comision_relacionada']->relations['planificacion_rel']->parentId = 'relacion';

        $entities['comision_relacionada']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['comision_relacionada']->relations['plan_pla']->parentId = 'planificacion_rel';

        $entities['comision_relacionada']->relations['sede_rel'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $entities['comision_relacionada']->relations['sede_rel']->parentId = 'relacion';

        $entities['comision_relacionada']->relations['centro_educativo_sed'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['comision_relacionada']->relations['centro_educativo_sed']->parentId = 'sede_rel';

        $entities['comision_relacionada']->relations['domicilio_cen1'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['comision_relacionada']->relations['domicilio_cen1']->parentId = 'centro_educativo_sed';

        $entities['comision_relacionada']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['comision_relacionada']->relations['domicilio_sed']->parentId = 'sede_rel';

        $entities['comision_relacionada']->relations['organizacion_sed'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['comision_relacionada']->relations['organizacion_sed']->parentId = 'sede_rel';

        $entities['comision_relacionada']->relations['tipo_sede_sed'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['comision_relacionada']->relations['tipo_sede_sed']->parentId = 'sede_rel';

        $entities['comision_relacionada']->fields['comision'] = Field::getInstance('comision_relacionada', 'comision', 'varchar', 'string');
        $entities['comision_relacionada']->fields['comision']->alias = 'com';
        $entities['comision_relacionada']->fields['comision']->refEntityName = 'comision';
        $entities['comision_relacionada']->fields['comision']->refFieldName = 'id';
        $entities['comision_relacionada']->fields['comision']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision_relacionada']->fields['comision']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['comision_relacionada']->fields['id'] = Field::getInstance('comision_relacionada', 'id', 'varchar', 'string');
        $entities['comision_relacionada']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision_relacionada']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['comision_relacionada']->fields['relacion'] = Field::getInstance('comision_relacionada', 'relacion', 'varchar', 'string');
        $entities['comision_relacionada']->fields['relacion']->alias = 'co1';
        $entities['comision_relacionada']->fields['relacion']->refEntityName = 'comision';
        $entities['comision_relacionada']->fields['relacion']->refFieldName = 'id';
        $entities['comision_relacionada']->fields['relacion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['comision_relacionada']->fields['relacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['contralor'] = EntityMetadata::getInstance('contralor', 'cont');
        $entities['contralor']->pk = ['id'];
        $entities['contralor']->fk = ['planilla_docente'];
        $entities['contralor']->notNull = ['id', 'insertado', 'planilla_docente'];

        $entities['contralor']->tree = [];
        $entities['contralor']->tree['planilla_docente'] = EntityTree::getInstance('planilla_docente', 'planilla_docente', 'id');

        $entities['contralor']->relations = [];
        $entities['contralor']->relations['planilla_docente'] = EntityRelation::getInstance('planilla_docente', 'planilla_docente', 'id');

        $entities['contralor']->fields['fecha_consejo'] = Field::getInstance('contralor', 'fecha_consejo', 'date', 'DateTime');
        $entities['contralor']->fields['fecha_consejo']->checks = [
            'type' => 'DateTime',
        ];
        $entities['contralor']->fields['fecha_contralor'] = Field::getInstance('contralor', 'fecha_contralor', 'date', 'DateTime');
        $entities['contralor']->fields['fecha_contralor']->checks = [
            'type' => 'DateTime',
        ];
        $entities['contralor']->fields['id'] = Field::getInstance('contralor', 'id', 'varchar', 'string');
        $entities['contralor']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['contralor']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['contralor']->fields['insertado'] = Field::getInstance('contralor', 'insertado', 'timestamp', 'DateTime');
        $entities['contralor']->fields['insertado']->defaultValue = 'current_timestamp()';
        $entities['contralor']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['contralor']->fields['planilla_docente'] = Field::getInstance('contralor', 'planilla_docente', 'varchar', 'string');
        $entities['contralor']->fields['planilla_docente']->alias = 'pla';
        $entities['contralor']->fields['planilla_docente']->refEntityName = 'planilla_docente';
        $entities['contralor']->fields['planilla_docente']->refFieldName = 'id';
        $entities['contralor']->fields['planilla_docente']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['contralor']->fields['planilla_docente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['curso'] = EntityMetadata::getInstance('curso', 'curs');
        $entities['curso']->pk = ['id'];
        $entities['curso']->fk = ['asignatura', 'comision', 'disposicion'];
        $entities['curso']->notNull = ['alta', 'comision', 'horas_catedra', 'id'];

        $entities['curso']->tree = [];
        $entities['curso']->tree['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['curso']->tree['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $entities['curso']->tree['comision']->children = [];
        $entities['curso']->tree['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['curso']->tree['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['curso']->tree['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['curso']->tree['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['curso']->tree['comision']->children['planificacion']->children = [];
        $entities['curso']->tree['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['curso']->tree['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['curso']->tree['comision']->children['sede']->children = [];
        $entities['curso']->tree['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['curso']->tree['comision']->children['sede']->children['centro_educativo']->children = [];
        $entities['curso']->tree['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['curso']->tree['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['curso']->tree['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['curso']->tree['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $entities['curso']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $entities['curso']->tree['disposicion']->children = [];
        $entities['curso']->tree['disposicion']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['curso']->tree['disposicion']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['curso']->tree['disposicion']->children['planificacion_dis']->children = [];
        $entities['curso']->tree['disposicion']->children['planificacion_dis']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');



        $entities['curso']->relations = [];
        $entities['curso']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');

        $entities['curso']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');

        $entities['curso']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $entities['curso']->relations['calendario']->parentId = 'comision';

        $entities['curso']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $entities['curso']->relations['comision_siguiente']->parentId = 'comision';

        $entities['curso']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $entities['curso']->relations['modalidad']->parentId = 'comision';

        $entities['curso']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['curso']->relations['planificacion']->parentId = 'comision';

        $entities['curso']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['curso']->relations['plan']->parentId = 'planificacion';

        $entities['curso']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $entities['curso']->relations['sede']->parentId = 'comision';

        $entities['curso']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['curso']->relations['centro_educativo']->parentId = 'sede';

        $entities['curso']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['curso']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['curso']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['curso']->relations['domicilio']->parentId = 'sede';

        $entities['curso']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['curso']->relations['organizacion']->parentId = 'sede';

        $entities['curso']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['curso']->relations['tipo_sede']->parentId = 'sede';

        $entities['curso']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $entities['curso']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['curso']->relations['asignatura_dis']->parentId = 'disposicion';

        $entities['curso']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['curso']->relations['planificacion_dis']->parentId = 'disposicion';

        $entities['curso']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['curso']->relations['plan_pla']->parentId = 'planificacion_dis';

        $entities['curso']->om = [];
        $entities['curso']->om['Calificacion_'] = EntityRef::getInstance('curso', 'calificacion');
        $entities['curso']->om['Horario_'] = EntityRef::getInstance('curso', 'horario');
        $entities['curso']->om['Toma_'] = EntityRef::getInstance('curso', 'toma');
        $entities['curso']->fields['alta'] = Field::getInstance('curso', 'alta', 'timestamp', 'DateTime');
        $entities['curso']->fields['alta']->defaultValue = 'current_timestamp()';
        $entities['curso']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['curso']->fields['asignatura'] = Field::getInstance('curso', 'asignatura', 'varchar', 'string');
        $entities['curso']->fields['asignatura']->alias = 'asi';
        $entities['curso']->fields['asignatura']->refEntityName = 'asignatura';
        $entities['curso']->fields['asignatura']->refFieldName = 'id';
        $entities['curso']->fields['asignatura']->checks = [
            'type' => 'string',
        ];
        $entities['curso']->fields['asignatura']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['curso']->fields['codigo'] = Field::getInstance('curso', 'codigo', 'varchar', 'string');
        $entities['curso']->fields['codigo']->checks = [
            'type' => 'string',
        ];
        $entities['curso']->fields['codigo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['curso']->fields['comision'] = Field::getInstance('curso', 'comision', 'varchar', 'string');
        $entities['curso']->fields['comision']->alias = 'com';
        $entities['curso']->fields['comision']->refEntityName = 'comision';
        $entities['curso']->fields['comision']->refFieldName = 'id';
        $entities['curso']->fields['comision']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['curso']->fields['comision']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['curso']->fields['descripcion_horario'] = Field::getInstance('curso', 'descripcion_horario', 'varchar', 'string');
        $entities['curso']->fields['descripcion_horario']->checks = [
            'type' => 'string',
        ];
        $entities['curso']->fields['descripcion_horario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['curso']->fields['disposicion'] = Field::getInstance('curso', 'disposicion', 'varchar', 'string');
        $entities['curso']->fields['disposicion']->alias = 'dis';
        $entities['curso']->fields['disposicion']->refEntityName = 'disposicion';
        $entities['curso']->fields['disposicion']->refFieldName = 'id';
        $entities['curso']->fields['disposicion']->checks = [
            'type' => 'string',
        ];
        $entities['curso']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['curso']->fields['horas_catedra'] = Field::getInstance('curso', 'horas_catedra', 'int', 'int');
        $entities['curso']->fields['horas_catedra']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['curso']->fields['id'] = Field::getInstance('curso', 'id', 'varchar', 'string');
        $entities['curso']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['curso']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['curso']->fields['ige'] = Field::getInstance('curso', 'ige', 'varchar', 'string');
        $entities['curso']->fields['ige']->checks = [
            'type' => 'string',
        ];
        $entities['curso']->fields['ige']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['curso']->fields['observaciones'] = Field::getInstance('curso', 'observaciones', 'varchar', 'string');
        $entities['curso']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['curso']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['designacion'] = EntityMetadata::getInstance('designacion', 'desi');
        $entities['designacion']->pk = ['id'];
        $entities['designacion']->fk = ['cargo', 'persona', 'sede'];
        $entities['designacion']->notNull = ['alta', 'cargo', 'id', 'persona', 'sede'];

        $entities['designacion']->tree = [];
        $entities['designacion']->tree['cargo'] = EntityTree::getInstance('cargo', 'cargo', 'id');

        $entities['designacion']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['designacion']->tree['persona']->children = [];
        $entities['designacion']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['designacion']->tree['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['designacion']->tree['sede']->children = [];
        $entities['designacion']->tree['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['designacion']->tree['sede']->children['centro_educativo']->children = [];
        $entities['designacion']->tree['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['designacion']->tree['sede']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['designacion']->tree['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['designacion']->tree['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');


        $entities['designacion']->relations = [];
        $entities['designacion']->relations['cargo'] = EntityRelation::getInstance('cargo', 'cargo', 'id');

        $entities['designacion']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $entities['designacion']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['designacion']->relations['domicilio']->parentId = 'persona';

        $entities['designacion']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');

        $entities['designacion']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['designacion']->relations['centro_educativo']->parentId = 'sede';

        $entities['designacion']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['designacion']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['designacion']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['designacion']->relations['domicilio_sed']->parentId = 'sede';

        $entities['designacion']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['designacion']->relations['organizacion']->parentId = 'sede';

        $entities['designacion']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['designacion']->relations['tipo_sede']->parentId = 'sede';

        $entities['designacion']->fields['alta'] = Field::getInstance('designacion', 'alta', 'timestamp', 'DateTime');
        $entities['designacion']->fields['alta']->defaultValue = 'current_timestamp()';
        $entities['designacion']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['designacion']->fields['cargo'] = Field::getInstance('designacion', 'cargo', 'varchar', 'string');
        $entities['designacion']->fields['cargo']->alias = 'car';
        $entities['designacion']->fields['cargo']->refEntityName = 'cargo';
        $entities['designacion']->fields['cargo']->refFieldName = 'id';
        $entities['designacion']->fields['cargo']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['designacion']->fields['cargo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['designacion']->fields['desde'] = Field::getInstance('designacion', 'desde', 'date', 'DateTime');
        $entities['designacion']->fields['desde']->checks = [
            'type' => 'DateTime',
        ];
        $entities['designacion']->fields['hasta'] = Field::getInstance('designacion', 'hasta', 'date', 'DateTime');
        $entities['designacion']->fields['hasta']->checks = [
            'type' => 'DateTime',
        ];
        $entities['designacion']->fields['id'] = Field::getInstance('designacion', 'id', 'varchar', 'string');
        $entities['designacion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['designacion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['designacion']->fields['persona'] = Field::getInstance('designacion', 'persona', 'varchar', 'string');
        $entities['designacion']->fields['persona']->alias = 'per';
        $entities['designacion']->fields['persona']->refEntityName = 'persona';
        $entities['designacion']->fields['persona']->refFieldName = 'id';
        $entities['designacion']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['designacion']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['designacion']->fields['pfid'] = Field::getInstance('designacion', 'pfid', 'varchar', 'string');
        $entities['designacion']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $entities['designacion']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['designacion']->fields['sede'] = Field::getInstance('designacion', 'sede', 'varchar', 'string');
        $entities['designacion']->fields['sede']->alias = 'sed';
        $entities['designacion']->fields['sede']->refEntityName = 'sede';
        $entities['designacion']->fields['sede']->refFieldName = 'id';
        $entities['designacion']->fields['sede']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['designacion']->fields['sede']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['detalle_persona'] = EntityMetadata::getInstance('detalle_persona', 'deta');
        $entities['detalle_persona']->pk = ['id'];
        $entities['detalle_persona']->fk = ['archivo', 'persona'];
        $entities['detalle_persona']->notNull = ['creado', 'descripcion', 'id', 'persona'];

        $entities['detalle_persona']->tree = [];
        $entities['detalle_persona']->tree['archivo'] = EntityTree::getInstance('archivo', 'file', 'id');

        $entities['detalle_persona']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['detalle_persona']->tree['persona']->children = [];
        $entities['detalle_persona']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['detalle_persona']->relations = [];
        $entities['detalle_persona']->relations['archivo'] = EntityRelation::getInstance('archivo', 'file', 'id');

        $entities['detalle_persona']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $entities['detalle_persona']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['detalle_persona']->relations['domicilio']->parentId = 'persona';

        $entities['detalle_persona']->fields['archivo'] = Field::getInstance('detalle_persona', 'archivo', 'varchar', 'string');
        $entities['detalle_persona']->fields['archivo']->alias = 'fil';
        $entities['detalle_persona']->fields['archivo']->refEntityName = 'file';
        $entities['detalle_persona']->fields['archivo']->refFieldName = 'id';
        $entities['detalle_persona']->fields['archivo']->checks = [
            'type' => 'string',
        ];
        $entities['detalle_persona']->fields['archivo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['detalle_persona']->fields['asunto'] = Field::getInstance('detalle_persona', 'asunto', 'varchar', 'string');
        $entities['detalle_persona']->fields['asunto']->checks = [
            'type' => 'string',
        ];
        $entities['detalle_persona']->fields['asunto']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['detalle_persona']->fields['creado'] = Field::getInstance('detalle_persona', 'creado', 'timestamp', 'DateTime');
        $entities['detalle_persona']->fields['creado']->defaultValue = 'current_timestamp()';
        $entities['detalle_persona']->fields['creado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['detalle_persona']->fields['descripcion'] = Field::getInstance('detalle_persona', 'descripcion', 'text', 'string');
        $entities['detalle_persona']->fields['descripcion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['detalle_persona']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['detalle_persona']->fields['fecha'] = Field::getInstance('detalle_persona', 'fecha', 'date', 'DateTime');
        $entities['detalle_persona']->fields['fecha']->defaultValue = 'curdate()';
        $entities['detalle_persona']->fields['fecha']->checks = [
            'type' => 'DateTime',
        ];
        $entities['detalle_persona']->fields['id'] = Field::getInstance('detalle_persona', 'id', 'varchar', 'string');
        $entities['detalle_persona']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['detalle_persona']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['detalle_persona']->fields['persona'] = Field::getInstance('detalle_persona', 'persona', 'varchar', 'string');
        $entities['detalle_persona']->fields['persona']->alias = 'per';
        $entities['detalle_persona']->fields['persona']->refEntityName = 'persona';
        $entities['detalle_persona']->fields['persona']->refFieldName = 'id';
        $entities['detalle_persona']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['detalle_persona']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['detalle_persona']->fields['tipo'] = Field::getInstance('detalle_persona', 'tipo', 'varchar', 'string');
        $entities['detalle_persona']->fields['tipo']->checks = [
            'type' => 'string',
        ];
        $entities['detalle_persona']->fields['tipo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['dia'] = EntityMetadata::getInstance('dia', 'dia');
        $entities['dia']->pk = ['id'];
        $entities['dia']->unique = ['dia', 'numero'];
        $entities['dia']->notNull = ['dia', 'id', 'numero'];

        $entities['dia']->om = [];
        $entities['dia']->om['Horario_'] = EntityRef::getInstance('dia', 'horario');
        $entities['dia']->fields['dia'] = Field::getInstance('dia', 'dia', 'varchar', 'string');
        $entities['dia']->fields['dia']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['dia']->fields['dia']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['dia']->fields['id'] = Field::getInstance('dia', 'id', 'varchar', 'string');
        $entities['dia']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['dia']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['dia']->fields['numero'] = Field::getInstance('dia', 'numero', 'smallint', 'int');
        $entities['dia']->fields['numero']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['disposicion'] = EntityMetadata::getInstance('disposicion', 'disp');
        $entities['disposicion']->pk = ['id'];
        $entities['disposicion']->fk = ['asignatura', 'planificacion'];
        $entities['disposicion']->notNull = ['asignatura', 'horas_catedra', 'id', 'planificacion'];

        $entities['disposicion']->tree = [];
        $entities['disposicion']->tree['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['disposicion']->tree['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['disposicion']->tree['planificacion']->children = [];
        $entities['disposicion']->tree['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['disposicion']->relations = [];
        $entities['disposicion']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');

        $entities['disposicion']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');

        $entities['disposicion']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['disposicion']->relations['plan']->parentId = 'planificacion';

        $entities['disposicion']->om = [];
        $entities['disposicion']->om['Calificacion_'] = EntityRef::getInstance('disposicion', 'calificacion');
        $entities['disposicion']->om['Curso_'] = EntityRef::getInstance('disposicion', 'curso');
        $entities['disposicion']->om['DisposicionPendiente_'] = EntityRef::getInstance('disposicion', 'disposicion_pendiente');
        $entities['disposicion']->om['DistribucionHoraria_'] = EntityRef::getInstance('disposicion', 'distribucion_horaria');
        $entities['disposicion']->fields['asignatura'] = Field::getInstance('disposicion', 'asignatura', 'varchar', 'string');
        $entities['disposicion']->fields['asignatura']->alias = 'asi';
        $entities['disposicion']->fields['asignatura']->refEntityName = 'asignatura';
        $entities['disposicion']->fields['asignatura']->refFieldName = 'id';
        $entities['disposicion']->fields['asignatura']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['disposicion']->fields['asignatura']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['disposicion']->fields['horas_catedra'] = Field::getInstance('disposicion', 'horas_catedra', 'int', 'int');
        $entities['disposicion']->fields['horas_catedra']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['disposicion']->fields['id'] = Field::getInstance('disposicion', 'id', 'varchar', 'string');
        $entities['disposicion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['disposicion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['disposicion']->fields['orden_informe_coordinacion_distrital'] = Field::getInstance('disposicion', 'orden_informe_coordinacion_distrital', 'int', 'int');
        $entities['disposicion']->fields['orden_informe_coordinacion_distrital']->checks = [
            'type' => 'int',
        ];
        $entities['disposicion']->fields['planificacion'] = Field::getInstance('disposicion', 'planificacion', 'varchar', 'string');
        $entities['disposicion']->fields['planificacion']->alias = 'pla';
        $entities['disposicion']->fields['planificacion']->refEntityName = 'planificacion';
        $entities['disposicion']->fields['planificacion']->refFieldName = 'id';
        $entities['disposicion']->fields['planificacion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['disposicion']->fields['planificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['disposicion_pendiente'] = EntityMetadata::getInstance('disposicion_pendiente', 'dis1');
        $entities['disposicion_pendiente']->pk = ['id'];
        $entities['disposicion_pendiente']->fk = ['alumno', 'disposicion'];
        $entities['disposicion_pendiente']->notNull = ['alumno', 'disposicion', 'id'];

        $entities['disposicion_pendiente']->tree = [];
        $entities['disposicion_pendiente']->tree['alumno'] = EntityTree::getInstance('alumno', 'alumno', 'id');
        $entities['disposicion_pendiente']->tree['alumno']->children = [];
        $entities['disposicion_pendiente']->tree['alumno']->children['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['disposicion_pendiente']->tree['alumno']->children['persona']->children = [];
        $entities['disposicion_pendiente']->tree['alumno']->children['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['disposicion_pendiente']->tree['alumno']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $entities['disposicion_pendiente']->tree['alumno']->children['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');


        $entities['disposicion_pendiente']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $entities['disposicion_pendiente']->tree['disposicion']->children = [];
        $entities['disposicion_pendiente']->tree['disposicion']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['disposicion_pendiente']->tree['disposicion']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['disposicion_pendiente']->tree['disposicion']->children['planificacion']->children = [];
        $entities['disposicion_pendiente']->tree['disposicion']->children['planificacion']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');



        $entities['disposicion_pendiente']->relations = [];
        $entities['disposicion_pendiente']->relations['alumno'] = EntityRelation::getInstance('alumno', 'alumno', 'id');

        $entities['disposicion_pendiente']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');
        $entities['disposicion_pendiente']->relations['persona']->parentId = 'alumno';

        $entities['disposicion_pendiente']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['disposicion_pendiente']->relations['domicilio']->parentId = 'persona';

        $entities['disposicion_pendiente']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['disposicion_pendiente']->relations['plan']->parentId = 'alumno';

        $entities['disposicion_pendiente']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');
        $entities['disposicion_pendiente']->relations['resolucion_inscripcion']->parentId = 'alumno';

        $entities['disposicion_pendiente']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $entities['disposicion_pendiente']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['disposicion_pendiente']->relations['asignatura']->parentId = 'disposicion';

        $entities['disposicion_pendiente']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['disposicion_pendiente']->relations['planificacion']->parentId = 'disposicion';

        $entities['disposicion_pendiente']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['disposicion_pendiente']->relations['plan_pla']->parentId = 'planificacion';

        $entities['disposicion_pendiente']->fields['alumno'] = Field::getInstance('disposicion_pendiente', 'alumno', 'varchar', 'string');
        $entities['disposicion_pendiente']->fields['alumno']->alias = 'alu';
        $entities['disposicion_pendiente']->fields['alumno']->refEntityName = 'alumno';
        $entities['disposicion_pendiente']->fields['alumno']->refFieldName = 'id';
        $entities['disposicion_pendiente']->fields['alumno']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['disposicion_pendiente']->fields['alumno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['disposicion_pendiente']->fields['disposicion'] = Field::getInstance('disposicion_pendiente', 'disposicion', 'varchar', 'string');
        $entities['disposicion_pendiente']->fields['disposicion']->alias = 'dis';
        $entities['disposicion_pendiente']->fields['disposicion']->refEntityName = 'disposicion';
        $entities['disposicion_pendiente']->fields['disposicion']->refFieldName = 'id';
        $entities['disposicion_pendiente']->fields['disposicion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['disposicion_pendiente']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['disposicion_pendiente']->fields['id'] = Field::getInstance('disposicion_pendiente', 'id', 'varchar', 'string');
        $entities['disposicion_pendiente']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['disposicion_pendiente']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['disposicion_pendiente']->fields['modo'] = Field::getInstance('disposicion_pendiente', 'modo', 'varchar', 'string');
        $entities['disposicion_pendiente']->fields['modo']->checks = [
            'type' => 'string',
        ];
        $entities['disposicion_pendiente']->fields['modo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['distribucion_horaria'] = EntityMetadata::getInstance('distribucion_horaria', 'dist');
        $entities['distribucion_horaria']->pk = ['id'];
        $entities['distribucion_horaria']->fk = ['disposicion'];
        $entities['distribucion_horaria']->notNull = ['dia', 'horas_catedra', 'id'];

        $entities['distribucion_horaria']->tree = [];
        $entities['distribucion_horaria']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $entities['distribucion_horaria']->tree['disposicion']->children = [];
        $entities['distribucion_horaria']->tree['disposicion']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['distribucion_horaria']->tree['disposicion']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['distribucion_horaria']->tree['disposicion']->children['planificacion']->children = [];
        $entities['distribucion_horaria']->tree['disposicion']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');



        $entities['distribucion_horaria']->relations = [];
        $entities['distribucion_horaria']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $entities['distribucion_horaria']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['distribucion_horaria']->relations['asignatura']->parentId = 'disposicion';

        $entities['distribucion_horaria']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['distribucion_horaria']->relations['planificacion']->parentId = 'disposicion';

        $entities['distribucion_horaria']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['distribucion_horaria']->relations['plan']->parentId = 'planificacion';

        $entities['distribucion_horaria']->fields['dia'] = Field::getInstance('distribucion_horaria', 'dia', 'int', 'int');
        $entities['distribucion_horaria']->fields['dia']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['distribucion_horaria']->fields['disposicion'] = Field::getInstance('distribucion_horaria', 'disposicion', 'varchar', 'string');
        $entities['distribucion_horaria']->fields['disposicion']->alias = 'dis';
        $entities['distribucion_horaria']->fields['disposicion']->refEntityName = 'disposicion';
        $entities['distribucion_horaria']->fields['disposicion']->refFieldName = 'id';
        $entities['distribucion_horaria']->fields['disposicion']->checks = [
            'type' => 'string',
        ];
        $entities['distribucion_horaria']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['distribucion_horaria']->fields['horas_catedra'] = Field::getInstance('distribucion_horaria', 'horas_catedra', 'int', 'int');
        $entities['distribucion_horaria']->fields['horas_catedra']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['distribucion_horaria']->fields['id'] = Field::getInstance('distribucion_horaria', 'id', 'varchar', 'string');
        $entities['distribucion_horaria']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['distribucion_horaria']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['domicilio'] = EntityMetadata::getInstance('domicilio', 'domi');
        $entities['domicilio']->pk = ['id'];
        $entities['domicilio']->notNull = ['calle', 'id', 'localidad', 'numero'];

        $entities['domicilio']->om = [];
        $entities['domicilio']->om['CentroEducativo_'] = EntityRef::getInstance('domicilio', 'centro_educativo');
        $entities['domicilio']->om['Persona_'] = EntityRef::getInstance('domicilio', 'persona');
        $entities['domicilio']->om['Sede_'] = EntityRef::getInstance('domicilio', 'sede');
        $entities['domicilio']->fields['barrio'] = Field::getInstance('domicilio', 'barrio', 'varchar', 'string');
        $entities['domicilio']->fields['barrio']->checks = [
            'type' => 'string',
        ];
        $entities['domicilio']->fields['barrio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['domicilio']->fields['calle'] = Field::getInstance('domicilio', 'calle', 'varchar', 'string');
        $entities['domicilio']->fields['calle']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['domicilio']->fields['calle']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['domicilio']->fields['departamento'] = Field::getInstance('domicilio', 'departamento', 'varchar', 'string');
        $entities['domicilio']->fields['departamento']->checks = [
            'type' => 'string',
        ];
        $entities['domicilio']->fields['departamento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['domicilio']->fields['entre'] = Field::getInstance('domicilio', 'entre', 'varchar', 'string');
        $entities['domicilio']->fields['entre']->checks = [
            'type' => 'string',
        ];
        $entities['domicilio']->fields['entre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['domicilio']->fields['id'] = Field::getInstance('domicilio', 'id', 'varchar', 'string');
        $entities['domicilio']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['domicilio']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['domicilio']->fields['localidad'] = Field::getInstance('domicilio', 'localidad', 'varchar', 'string');
        $entities['domicilio']->fields['localidad']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['domicilio']->fields['localidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['domicilio']->fields['numero'] = Field::getInstance('domicilio', 'numero', 'varchar', 'string');
        $entities['domicilio']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['domicilio']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['domicilio']->fields['piso'] = Field::getInstance('domicilio', 'piso', 'varchar', 'string');
        $entities['domicilio']->fields['piso']->checks = [
            'type' => 'string',
        ];
        $entities['domicilio']->fields['piso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['email'] = EntityMetadata::getInstance('email', 'emai');
        $entities['email']->pk = ['id'];
        $entities['email']->fk = ['persona'];
        $entities['email']->notNull = ['email', 'id', 'insertado', 'persona', 'verificado'];

        $entities['email']->tree = [];
        $entities['email']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['email']->tree['persona']->children = [];
        $entities['email']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['email']->relations = [];
        $entities['email']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $entities['email']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['email']->relations['domicilio']->parentId = 'persona';

        $entities['email']->fields['eliminado'] = Field::getInstance('email', 'eliminado', 'timestamp', 'DateTime');
        $entities['email']->fields['eliminado']->checks = [
            'type' => 'DateTime',
        ];
        $entities['email']->fields['email'] = Field::getInstance('email', 'email', 'varchar', 'string');
        $entities['email']->fields['email']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['email']->fields['email']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['email']->fields['id'] = Field::getInstance('email', 'id', 'varchar', 'string');
        $entities['email']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['email']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['email']->fields['insertado'] = Field::getInstance('email', 'insertado', 'timestamp', 'DateTime');
        $entities['email']->fields['insertado']->defaultValue = 'current_timestamp()';
        $entities['email']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['email']->fields['persona'] = Field::getInstance('email', 'persona', 'varchar', 'string');
        $entities['email']->fields['persona']->alias = 'per';
        $entities['email']->fields['persona']->refEntityName = 'persona';
        $entities['email']->fields['persona']->refFieldName = 'id';
        $entities['email']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['email']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['email']->fields['verificado'] = Field::getInstance('email', 'verificado', 'tinyint', 'bool');
        $entities['email']->fields['verificado']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['file'] = EntityMetadata::getInstance('file', 'file');
        $entities['file']->pk = ['id'];
        $entities['file']->notNull = ['content', 'created', 'id', 'name', 'size', 'type'];

        $entities['file']->om = [];
        $entities['file']->om['DetallePersona_archivo_'] = EntityRef::getInstance('archivo', 'detalle_persona');
        $entities['file']->fields['content'] = Field::getInstance('file', 'content', 'varchar', 'string');
        $entities['file']->fields['content']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['file']->fields['content']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['file']->fields['created'] = Field::getInstance('file', 'created', 'timestamp', 'DateTime');
        $entities['file']->fields['created']->defaultValue = 'current_timestamp()';
        $entities['file']->fields['created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['file']->fields['id'] = Field::getInstance('file', 'id', 'varchar', 'string');
        $entities['file']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['file']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['file']->fields['name'] = Field::getInstance('file', 'name', 'varchar', 'string');
        $entities['file']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['file']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['file']->fields['size'] = Field::getInstance('file', 'size', 'int', 'int');
        $entities['file']->fields['size']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['file']->fields['type'] = Field::getInstance('file', 'type', 'varchar', 'string');
        $entities['file']->fields['type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['file']->fields['type']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['horario'] = EntityMetadata::getInstance('horario', 'hora');
        $entities['horario']->pk = ['id'];
        $entities['horario']->fk = ['curso', 'dia'];
        $entities['horario']->notNull = ['curso', 'dia', 'hora_fin', 'hora_inicio', 'id'];

        $entities['horario']->tree = [];
        $entities['horario']->tree['curso'] = EntityTree::getInstance('curso', 'curso', 'id');
        $entities['horario']->tree['curso']->children = [];
        $entities['horario']->tree['curso']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['horario']->tree['curso']->children['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $entities['horario']->tree['curso']->children['comision']->children = [];
        $entities['horario']->tree['curso']->children['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['horario']->tree['curso']->children['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['horario']->tree['curso']->children['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['horario']->tree['curso']->children['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['horario']->tree['curso']->children['comision']->children['planificacion']->children = [];
        $entities['horario']->tree['curso']->children['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['horario']->tree['curso']->children['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['horario']->tree['curso']->children['comision']->children['sede']->children = [];
        $entities['horario']->tree['curso']->children['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['horario']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children = [];
        $entities['horario']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['horario']->tree['curso']->children['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['horario']->tree['curso']->children['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['horario']->tree['curso']->children['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $entities['horario']->tree['curso']->children['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $entities['horario']->tree['curso']->children['disposicion']->children = [];
        $entities['horario']->tree['curso']->children['disposicion']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['horario']->tree['curso']->children['disposicion']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['horario']->tree['curso']->children['disposicion']->children['planificacion_dis']->children = [];
        $entities['horario']->tree['curso']->children['disposicion']->children['planificacion_dis']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');




        $entities['horario']->tree['dia'] = EntityTree::getInstance('dia', 'dia', 'id');

        $entities['horario']->relations = [];
        $entities['horario']->relations['curso'] = EntityRelation::getInstance('curso', 'curso', 'id');

        $entities['horario']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['horario']->relations['asignatura']->parentId = 'curso';

        $entities['horario']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');
        $entities['horario']->relations['comision']->parentId = 'curso';

        $entities['horario']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $entities['horario']->relations['calendario']->parentId = 'comision';

        $entities['horario']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $entities['horario']->relations['comision_siguiente']->parentId = 'comision';

        $entities['horario']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $entities['horario']->relations['modalidad']->parentId = 'comision';

        $entities['horario']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['horario']->relations['planificacion']->parentId = 'comision';

        $entities['horario']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['horario']->relations['plan']->parentId = 'planificacion';

        $entities['horario']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $entities['horario']->relations['sede']->parentId = 'comision';

        $entities['horario']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['horario']->relations['centro_educativo']->parentId = 'sede';

        $entities['horario']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['horario']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['horario']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['horario']->relations['domicilio']->parentId = 'sede';

        $entities['horario']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['horario']->relations['organizacion']->parentId = 'sede';

        $entities['horario']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['horario']->relations['tipo_sede']->parentId = 'sede';

        $entities['horario']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');
        $entities['horario']->relations['disposicion']->parentId = 'curso';

        $entities['horario']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['horario']->relations['asignatura_dis']->parentId = 'disposicion';

        $entities['horario']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['horario']->relations['planificacion_dis']->parentId = 'disposicion';

        $entities['horario']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['horario']->relations['plan_pla']->parentId = 'planificacion_dis';

        $entities['horario']->relations['dia'] = EntityRelation::getInstance('dia', 'dia', 'id');

        $entities['horario']->fields['curso'] = Field::getInstance('horario', 'curso', 'varchar', 'string');
        $entities['horario']->fields['curso']->alias = 'cur';
        $entities['horario']->fields['curso']->refEntityName = 'curso';
        $entities['horario']->fields['curso']->refFieldName = 'id';
        $entities['horario']->fields['curso']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['horario']->fields['curso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['horario']->fields['dia'] = Field::getInstance('horario', 'dia', 'varchar', 'string');
        $entities['horario']->fields['dia']->alias = 'dia';
        $entities['horario']->fields['dia']->refEntityName = 'dia';
        $entities['horario']->fields['dia']->refFieldName = 'id';
        $entities['horario']->fields['dia']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['horario']->fields['dia']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['horario']->fields['hora_fin'] = Field::getInstance('horario', 'hora_fin', 'time', 'DateTime');
        $entities['horario']->fields['hora_fin']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['horario']->fields['hora_inicio'] = Field::getInstance('horario', 'hora_inicio', 'time', 'DateTime');
        $entities['horario']->fields['hora_inicio']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['horario']->fields['id'] = Field::getInstance('horario', 'id', 'varchar', 'string');
        $entities['horario']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['horario']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['modalidad'] = EntityMetadata::getInstance('modalidad', 'moda');
        $entities['modalidad']->pk = ['id'];
        $entities['modalidad']->unique = ['nombre'];
        $entities['modalidad']->notNull = ['id', 'nombre'];

        $entities['modalidad']->om = [];
        $entities['modalidad']->om['Comision_'] = EntityRef::getInstance('modalidad', 'comision');
        $entities['modalidad']->fields['id'] = Field::getInstance('modalidad', 'id', 'varchar', 'string');
        $entities['modalidad']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['modalidad']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['modalidad']->fields['nombre'] = Field::getInstance('modalidad', 'nombre', 'varchar', 'string');
        $entities['modalidad']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['modalidad']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['modalidad']->fields['pfid'] = Field::getInstance('modalidad', 'pfid', 'varchar', 'string');
        $entities['modalidad']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $entities['modalidad']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona'] = EntityMetadata::getInstance('persona', 'pers');
        $entities['persona']->pk = ['id'];
        $entities['persona']->fk = ['domicilio'];
        $entities['persona']->unique = ['cuil', 'email_abc', 'numero_documento'];
        $entities['persona']->notNull = ['alta', 'email_verificado', 'id', 'info_verificada', 'nombres', 'numero_documento', 'telefono_verificado'];

        $entities['persona']->tree = [];
        $entities['persona']->tree['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['persona']->relations = [];
        $entities['persona']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');

        $entities['persona']->oo = [];
        $entities['persona']->oo['Alumno_']  = EntityRef::getInstance('persona', 'alumno');

        $entities['persona']->om = [];
        $entities['persona']->om['Designacion_'] = EntityRef::getInstance('persona', 'designacion');
        $entities['persona']->om['DetallePersona_'] = EntityRef::getInstance('persona', 'detalle_persona');
        $entities['persona']->om['Email_'] = EntityRef::getInstance('persona', 'email');
        $entities['persona']->om['Telefono_'] = EntityRef::getInstance('persona', 'telefono');
        $entities['persona']->om['Toma_docente_'] = EntityRef::getInstance('docente', 'toma');
        $entities['persona']->om['Toma_reemplazo_'] = EntityRef::getInstance('reemplazo', 'toma');
        $entities['persona']->fields['alta'] = Field::getInstance('persona', 'alta', 'timestamp', 'DateTime');
        $entities['persona']->fields['alta']->defaultValue = 'current_timestamp()';
        $entities['persona']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['persona']->fields['anio_nacimiento'] = Field::getInstance('persona', 'anio_nacimiento', 'smallint', 'int');
        $entities['persona']->fields['anio_nacimiento']->checks = [
            'type' => 'int',
        ];
        $entities['persona']->fields['apellidos'] = Field::getInstance('persona', 'apellidos', 'varchar', 'string');
        $entities['persona']->fields['apellidos']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['apellidos']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['apodo'] = Field::getInstance('persona', 'apodo', 'varchar', 'string');
        $entities['persona']->fields['apodo']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['apodo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['codigo_area'] = Field::getInstance('persona', 'codigo_area', 'varchar', 'string');
        $entities['persona']->fields['codigo_area']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['codigo_area']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['cuil'] = Field::getInstance('persona', 'cuil', 'varchar', 'string');
        $entities['persona']->fields['cuil']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['cuil']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['cuil1'] = Field::getInstance('persona', 'cuil1', 'tinyint', 'int');
        $entities['persona']->fields['cuil1']->checks = [
            'type' => 'int',
        ];
        $entities['persona']->fields['cuil2'] = Field::getInstance('persona', 'cuil2', 'tinyint', 'int');
        $entities['persona']->fields['cuil2']->checks = [
            'type' => 'int',
        ];
        $entities['persona']->fields['departamento'] = Field::getInstance('persona', 'departamento', 'varchar', 'string');
        $entities['persona']->fields['departamento']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['departamento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['descripcion_domicilio'] = Field::getInstance('persona', 'descripcion_domicilio', 'varchar', 'string');
        $entities['persona']->fields['descripcion_domicilio']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['descripcion_domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['dia_nacimiento'] = Field::getInstance('persona', 'dia_nacimiento', 'tinyint', 'int');
        $entities['persona']->fields['dia_nacimiento']->checks = [
            'type' => 'int',
        ];
        $entities['persona']->fields['domicilio'] = Field::getInstance('persona', 'domicilio', 'varchar', 'string');
        $entities['persona']->fields['domicilio']->alias = 'dom';
        $entities['persona']->fields['domicilio']->refEntityName = 'domicilio';
        $entities['persona']->fields['domicilio']->refFieldName = 'id';
        $entities['persona']->fields['domicilio']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['email'] = Field::getInstance('persona', 'email', 'varchar', 'string');
        $entities['persona']->fields['email']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['email']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['email_abc'] = Field::getInstance('persona', 'email_abc', 'varchar', 'string');
        $entities['persona']->fields['email_abc']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['email_abc']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['email_verificado'] = Field::getInstance('persona', 'email_verificado', 'tinyint', 'bool');
        $entities['persona']->fields['email_verificado']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['persona']->fields['fecha_nacimiento'] = Field::getInstance('persona', 'fecha_nacimiento', 'date', 'DateTime');
        $entities['persona']->fields['fecha_nacimiento']->checks = [
            'type' => 'DateTime',
        ];
        $entities['persona']->fields['genero'] = Field::getInstance('persona', 'genero', 'varchar', 'string');
        $entities['persona']->fields['genero']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['genero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['id'] = Field::getInstance('persona', 'id', 'varchar', 'string');
        $entities['persona']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['persona']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['persona']->fields['info_verificada'] = Field::getInstance('persona', 'info_verificada', 'tinyint', 'bool');
        $entities['persona']->fields['info_verificada']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['persona']->fields['localidad'] = Field::getInstance('persona', 'localidad', 'varchar', 'string');
        $entities['persona']->fields['localidad']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['localidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['lugar_nacimiento'] = Field::getInstance('persona', 'lugar_nacimiento', 'varchar', 'string');
        $entities['persona']->fields['lugar_nacimiento']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['lugar_nacimiento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['mes_nacimiento'] = Field::getInstance('persona', 'mes_nacimiento', 'tinyint', 'int');
        $entities['persona']->fields['mes_nacimiento']->checks = [
            'type' => 'int',
        ];
        $entities['persona']->fields['nacionalidad'] = Field::getInstance('persona', 'nacionalidad', 'varchar', 'string');
        $entities['persona']->fields['nacionalidad']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['nacionalidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['nombres'] = Field::getInstance('persona', 'nombres', 'varchar', 'string');
        $entities['persona']->fields['nombres']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['persona']->fields['nombres']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['persona']->fields['numero_documento'] = Field::getInstance('persona', 'numero_documento', 'varchar', 'string');
        $entities['persona']->fields['numero_documento']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['persona']->fields['numero_documento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['persona']->fields['partido'] = Field::getInstance('persona', 'partido', 'varchar', 'string');
        $entities['persona']->fields['partido']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['partido']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['sexo'] = Field::getInstance('persona', 'sexo', 'tinyint', 'int');
        $entities['persona']->fields['sexo']->checks = [
            'type' => 'int',
        ];
        $entities['persona']->fields['telefono'] = Field::getInstance('persona', 'telefono', 'varchar', 'string');
        $entities['persona']->fields['telefono']->checks = [
            'type' => 'string',
        ];
        $entities['persona']->fields['telefono']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['persona']->fields['telefono_verificado'] = Field::getInstance('persona', 'telefono_verificado', 'tinyint', 'bool');
        $entities['persona']->fields['telefono_verificado']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['plan'] = EntityMetadata::getInstance('plan', 'plan');
        $entities['plan']->pk = ['id'];
        $entities['plan']->notNull = ['id', 'orientacion'];

        $entities['plan']->om = [];
        $entities['plan']->om['Alumno_'] = EntityRef::getInstance('plan', 'alumno');
        $entities['plan']->om['Planificacion_'] = EntityRef::getInstance('plan', 'planificacion');
        $entities['plan']->fields['distribucion_horaria'] = Field::getInstance('plan', 'distribucion_horaria', 'varchar', 'string');
        $entities['plan']->fields['distribucion_horaria']->checks = [
            'type' => 'string',
        ];
        $entities['plan']->fields['distribucion_horaria']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['plan']->fields['id'] = Field::getInstance('plan', 'id', 'varchar', 'string');
        $entities['plan']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['plan']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['plan']->fields['orientacion'] = Field::getInstance('plan', 'orientacion', 'varchar', 'string');
        $entities['plan']->fields['orientacion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['plan']->fields['orientacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['plan']->fields['pfid'] = Field::getInstance('plan', 'pfid', 'varchar', 'string');
        $entities['plan']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $entities['plan']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['plan']->fields['resolucion'] = Field::getInstance('plan', 'resolucion', 'varchar', 'string');
        $entities['plan']->fields['resolucion']->checks = [
            'type' => 'string',
        ];
        $entities['plan']->fields['resolucion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['planificacion'] = EntityMetadata::getInstance('planificacion', 'pla1');
        $entities['planificacion']->pk = ['id'];
        $entities['planificacion']->fk = ['plan'];
        $entities['planificacion']->notNull = ['anio', 'id', 'plan', 'semestre'];

        $entities['planificacion']->tree = [];
        $entities['planificacion']->tree['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $entities['planificacion']->relations = [];
        $entities['planificacion']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');

        $entities['planificacion']->om = [];
        $entities['planificacion']->om['Comision_'] = EntityRef::getInstance('planificacion', 'comision');
        $entities['planificacion']->om['Disposicion_'] = EntityRef::getInstance('planificacion', 'disposicion');
        $entities['planificacion']->fields['anio'] = Field::getInstance('planificacion', 'anio', 'varchar', 'string');
        $entities['planificacion']->fields['anio']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['planificacion']->fields['anio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['planificacion']->fields['id'] = Field::getInstance('planificacion', 'id', 'varchar', 'string');
        $entities['planificacion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['planificacion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['planificacion']->fields['pfid'] = Field::getInstance('planificacion', 'pfid', 'varchar', 'string');
        $entities['planificacion']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $entities['planificacion']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['planificacion']->fields['plan'] = Field::getInstance('planificacion', 'plan', 'varchar', 'string');
        $entities['planificacion']->fields['plan']->alias = 'pla';
        $entities['planificacion']->fields['plan']->refEntityName = 'plan';
        $entities['planificacion']->fields['plan']->refFieldName = 'id';
        $entities['planificacion']->fields['plan']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['planificacion']->fields['plan']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['planificacion']->fields['semestre'] = Field::getInstance('planificacion', 'semestre', 'varchar', 'string');
        $entities['planificacion']->fields['semestre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['planificacion']->fields['semestre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['planilla_docente'] = EntityMetadata::getInstance('planilla_docente', 'pla2');
        $entities['planilla_docente']->pk = ['id'];
        $entities['planilla_docente']->notNull = ['id', 'insertado', 'numero'];

        $entities['planilla_docente']->om = [];
        $entities['planilla_docente']->om['Contralor_'] = EntityRef::getInstance('planilla_docente', 'contralor');
        $entities['planilla_docente']->om['Toma_'] = EntityRef::getInstance('planilla_docente', 'toma');
        $entities['planilla_docente']->fields['fecha_consejo'] = Field::getInstance('planilla_docente', 'fecha_consejo', 'date', 'DateTime');
        $entities['planilla_docente']->fields['fecha_consejo']->checks = [
            'type' => 'DateTime',
        ];
        $entities['planilla_docente']->fields['fecha_contralor'] = Field::getInstance('planilla_docente', 'fecha_contralor', 'date', 'DateTime');
        $entities['planilla_docente']->fields['fecha_contralor']->checks = [
            'type' => 'DateTime',
        ];
        $entities['planilla_docente']->fields['id'] = Field::getInstance('planilla_docente', 'id', 'varchar', 'string');
        $entities['planilla_docente']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['planilla_docente']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['planilla_docente']->fields['insertado'] = Field::getInstance('planilla_docente', 'insertado', 'timestamp', 'DateTime');
        $entities['planilla_docente']->fields['insertado']->defaultValue = 'current_timestamp()';
        $entities['planilla_docente']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['planilla_docente']->fields['numero'] = Field::getInstance('planilla_docente', 'numero', 'varchar', 'string');
        $entities['planilla_docente']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['planilla_docente']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['planilla_docente']->fields['observaciones'] = Field::getInstance('planilla_docente', 'observaciones', 'text', 'string');
        $entities['planilla_docente']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['planilla_docente']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['resolucion'] = EntityMetadata::getInstance('resolucion', 'reso');
        $entities['resolucion']->pk = ['id'];
        $entities['resolucion']->notNull = ['id', 'numero'];

        $entities['resolucion']->om = [];
        $entities['resolucion']->om['Alumno_resolucion_inscripcion_'] = EntityRef::getInstance('resolucion_inscripcion', 'alumno');
        $entities['resolucion']->fields['anio'] = Field::getInstance('resolucion', 'anio', 'year', 'int');
        $entities['resolucion']->fields['anio']->checks = [
            'type' => 'int',
        ];
        $entities['resolucion']->fields['id'] = Field::getInstance('resolucion', 'id', 'varchar', 'string');
        $entities['resolucion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['resolucion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['resolucion']->fields['numero'] = Field::getInstance('resolucion', 'numero', 'varchar', 'string');
        $entities['resolucion']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['resolucion']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['resolucion']->fields['tipo'] = Field::getInstance('resolucion', 'tipo', 'varchar', 'string');
        $entities['resolucion']->fields['tipo']->checks = [
            'type' => 'string',
        ];
        $entities['resolucion']->fields['tipo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['sede'] = EntityMetadata::getInstance('sede', 'sede');
        $entities['sede']->pk = ['id'];
        $entities['sede']->fk = ['centro_educativo', 'domicilio', 'organizacion', 'tipo_sede'];
        $entities['sede']->unique = ['nombre'];
        $entities['sede']->notNull = ['alta', 'id', 'nombre', 'numero'];

        $entities['sede']->tree = [];
        $entities['sede']->tree['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['sede']->tree['centro_educativo']->children = [];
        $entities['sede']->tree['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['sede']->tree['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['sede']->tree['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['sede']->tree['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');

        $entities['sede']->relations = [];
        $entities['sede']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');

        $entities['sede']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['sede']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['sede']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');

        $entities['sede']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');

        $entities['sede']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');

        $entities['sede']->om = [];
        $entities['sede']->om['Comision_'] = EntityRef::getInstance('sede', 'comision');
        $entities['sede']->om['Designacion_'] = EntityRef::getInstance('sede', 'designacion');
        $entities['sede']->om['Sede_organizacion_'] = EntityRef::getInstance('organizacion', 'sede');
        $entities['sede']->fields['alta'] = Field::getInstance('sede', 'alta', 'timestamp', 'DateTime');
        $entities['sede']->fields['alta']->defaultValue = 'current_timestamp()';
        $entities['sede']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['sede']->fields['baja'] = Field::getInstance('sede', 'baja', 'timestamp', 'DateTime');
        $entities['sede']->fields['baja']->checks = [
            'type' => 'DateTime',
        ];
        $entities['sede']->fields['centro_educativo'] = Field::getInstance('sede', 'centro_educativo', 'varchar', 'string');
        $entities['sede']->fields['centro_educativo']->alias = 'cen';
        $entities['sede']->fields['centro_educativo']->refEntityName = 'centro_educativo';
        $entities['sede']->fields['centro_educativo']->refFieldName = 'id';
        $entities['sede']->fields['centro_educativo']->checks = [
            'type' => 'string',
        ];
        $entities['sede']->fields['centro_educativo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['sede']->fields['domicilio'] = Field::getInstance('sede', 'domicilio', 'varchar', 'string');
        $entities['sede']->fields['domicilio']->alias = 'dom';
        $entities['sede']->fields['domicilio']->refEntityName = 'domicilio';
        $entities['sede']->fields['domicilio']->refFieldName = 'id';
        $entities['sede']->fields['domicilio']->checks = [
            'type' => 'string',
        ];
        $entities['sede']->fields['domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['sede']->fields['fecha_traspaso'] = Field::getInstance('sede', 'fecha_traspaso', 'date', 'DateTime');
        $entities['sede']->fields['fecha_traspaso']->checks = [
            'type' => 'DateTime',
        ];
        $entities['sede']->fields['id'] = Field::getInstance('sede', 'id', 'varchar', 'string');
        $entities['sede']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['sede']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['sede']->fields['nombre'] = Field::getInstance('sede', 'nombre', 'varchar', 'string');
        $entities['sede']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['sede']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['sede']->fields['numero'] = Field::getInstance('sede', 'numero', 'varchar', 'string');
        $entities['sede']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['sede']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['sede']->fields['observaciones'] = Field::getInstance('sede', 'observaciones', 'text', 'string');
        $entities['sede']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['sede']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['sede']->fields['organizacion'] = Field::getInstance('sede', 'organizacion', 'varchar', 'string');
        $entities['sede']->fields['organizacion']->alias = 'sed';
        $entities['sede']->fields['organizacion']->refEntityName = 'sede';
        $entities['sede']->fields['organizacion']->refFieldName = 'id';
        $entities['sede']->fields['organizacion']->checks = [
            'type' => 'string',
        ];
        $entities['sede']->fields['organizacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['sede']->fields['pfid'] = Field::getInstance('sede', 'pfid', 'varchar', 'string');
        $entities['sede']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $entities['sede']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['sede']->fields['pfid_organizacion'] = Field::getInstance('sede', 'pfid_organizacion', 'varchar', 'string');
        $entities['sede']->fields['pfid_organizacion']->checks = [
            'type' => 'string',
        ];
        $entities['sede']->fields['pfid_organizacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['sede']->fields['tipo_sede'] = Field::getInstance('sede', 'tipo_sede', 'varchar', 'string');
        $entities['sede']->fields['tipo_sede']->alias = 'tip';
        $entities['sede']->fields['tipo_sede']->refEntityName = 'tipo_sede';
        $entities['sede']->fields['tipo_sede']->refFieldName = 'id';
        $entities['sede']->fields['tipo_sede']->checks = [
            'type' => 'string',
        ];
        $entities['sede']->fields['tipo_sede']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['telefono'] = EntityMetadata::getInstance('telefono', 'tele');
        $entities['telefono']->pk = ['id'];
        $entities['telefono']->fk = ['persona'];
        $entities['telefono']->notNull = ['id', 'insertado', 'numero', 'persona'];

        $entities['telefono']->tree = [];
        $entities['telefono']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $entities['telefono']->tree['persona']->children = [];
        $entities['telefono']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['telefono']->relations = [];
        $entities['telefono']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $entities['telefono']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['telefono']->relations['domicilio']->parentId = 'persona';

        $entities['telefono']->fields['eliminado'] = Field::getInstance('telefono', 'eliminado', 'timestamp', 'DateTime');
        $entities['telefono']->fields['eliminado']->checks = [
            'type' => 'DateTime',
        ];
        $entities['telefono']->fields['id'] = Field::getInstance('telefono', 'id', 'varchar', 'string');
        $entities['telefono']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['telefono']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['telefono']->fields['insertado'] = Field::getInstance('telefono', 'insertado', 'timestamp', 'DateTime');
        $entities['telefono']->fields['insertado']->defaultValue = 'current_timestamp()';
        $entities['telefono']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['telefono']->fields['numero'] = Field::getInstance('telefono', 'numero', 'varchar', 'string');
        $entities['telefono']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['telefono']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['telefono']->fields['persona'] = Field::getInstance('telefono', 'persona', 'varchar', 'string');
        $entities['telefono']->fields['persona']->alias = 'per';
        $entities['telefono']->fields['persona']->refEntityName = 'persona';
        $entities['telefono']->fields['persona']->refFieldName = 'id';
        $entities['telefono']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['telefono']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['telefono']->fields['prefijo'] = Field::getInstance('telefono', 'prefijo', 'varchar', 'string');
        $entities['telefono']->fields['prefijo']->checks = [
            'type' => 'string',
        ];
        $entities['telefono']->fields['prefijo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['telefono']->fields['tipo'] = Field::getInstance('telefono', 'tipo', 'varchar', 'string');
        $entities['telefono']->fields['tipo']->checks = [
            'type' => 'string',
        ];
        $entities['telefono']->fields['tipo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tipo_sede'] = EntityMetadata::getInstance('tipo_sede', 'tipo');
        $entities['tipo_sede']->pk = ['id'];
        $entities['tipo_sede']->unique = ['descripcion'];
        $entities['tipo_sede']->notNull = ['descripcion', 'id'];

        $entities['tipo_sede']->om = [];
        $entities['tipo_sede']->om['Sede_'] = EntityRef::getInstance('tipo_sede', 'sede');
        $entities['tipo_sede']->fields['descripcion'] = Field::getInstance('tipo_sede', 'descripcion', 'varchar', 'string');
        $entities['tipo_sede']->fields['descripcion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['tipo_sede']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['tipo_sede']->fields['id'] = Field::getInstance('tipo_sede', 'id', 'varchar', 'string');
        $entities['tipo_sede']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['tipo_sede']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['toma'] = EntityMetadata::getInstance('toma', 'toma');
        $entities['toma']->pk = ['id'];
        $entities['toma']->fk = ['curso', 'docente', 'planilla_docente', 'reemplazo'];
        $entities['toma']->notNull = ['alta', 'confirmada', 'curso', 'id', 'reclamo', 'sin_planillas', 'tipo_movimiento'];

        $entities['toma']->tree = [];
        $entities['toma']->tree['curso'] = EntityTree::getInstance('curso', 'curso', 'id');
        $entities['toma']->tree['curso']->children = [];
        $entities['toma']->tree['curso']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['toma']->tree['curso']->children['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $entities['toma']->tree['curso']->children['comision']->children = [];
        $entities['toma']->tree['curso']->children['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $entities['toma']->tree['curso']->children['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $entities['toma']->tree['curso']->children['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $entities['toma']->tree['curso']->children['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['toma']->tree['curso']->children['comision']->children['planificacion']->children = [];
        $entities['toma']->tree['curso']->children['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $entities['toma']->tree['curso']->children['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $entities['toma']->tree['curso']->children['comision']->children['sede']->children = [];
        $entities['toma']->tree['curso']->children['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['toma']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children = [];
        $entities['toma']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['toma']->tree['curso']->children['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $entities['toma']->tree['curso']->children['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $entities['toma']->tree['curso']->children['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $entities['toma']->tree['curso']->children['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $entities['toma']->tree['curso']->children['disposicion']->children = [];
        $entities['toma']->tree['curso']->children['disposicion']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $entities['toma']->tree['curso']->children['disposicion']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $entities['toma']->tree['curso']->children['disposicion']->children['planificacion_dis']->children = [];
        $entities['toma']->tree['curso']->children['disposicion']->children['planificacion_dis']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');




        $entities['toma']->tree['docente'] = EntityTree::getInstance('docente', 'persona', 'id');
        $entities['toma']->tree['docente']->children = [];
        $entities['toma']->tree['docente']->children['domicilio_doc'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['toma']->tree['planilla_docente'] = EntityTree::getInstance('planilla_docente', 'planilla_docente', 'id');

        $entities['toma']->tree['reemplazo'] = EntityTree::getInstance('reemplazo', 'persona', 'id');
        $entities['toma']->tree['reemplazo']->children = [];
        $entities['toma']->tree['reemplazo']->children['domicilio_ree'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $entities['toma']->relations = [];
        $entities['toma']->relations['curso'] = EntityRelation::getInstance('curso', 'curso', 'id');

        $entities['toma']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['toma']->relations['asignatura']->parentId = 'curso';

        $entities['toma']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');
        $entities['toma']->relations['comision']->parentId = 'curso';

        $entities['toma']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $entities['toma']->relations['calendario']->parentId = 'comision';

        $entities['toma']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $entities['toma']->relations['comision_siguiente']->parentId = 'comision';

        $entities['toma']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $entities['toma']->relations['modalidad']->parentId = 'comision';

        $entities['toma']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['toma']->relations['planificacion']->parentId = 'comision';

        $entities['toma']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['toma']->relations['plan']->parentId = 'planificacion';

        $entities['toma']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $entities['toma']->relations['sede']->parentId = 'comision';

        $entities['toma']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $entities['toma']->relations['centro_educativo']->parentId = 'sede';

        $entities['toma']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['toma']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $entities['toma']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['toma']->relations['domicilio']->parentId = 'sede';

        $entities['toma']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $entities['toma']->relations['organizacion']->parentId = 'sede';

        $entities['toma']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $entities['toma']->relations['tipo_sede']->parentId = 'sede';

        $entities['toma']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');
        $entities['toma']->relations['disposicion']->parentId = 'curso';

        $entities['toma']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $entities['toma']->relations['asignatura_dis']->parentId = 'disposicion';

        $entities['toma']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $entities['toma']->relations['planificacion_dis']->parentId = 'disposicion';

        $entities['toma']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $entities['toma']->relations['plan_pla']->parentId = 'planificacion_dis';

        $entities['toma']->relations['docente'] = EntityRelation::getInstance('docente', 'persona', 'id');

        $entities['toma']->relations['domicilio_doc'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['toma']->relations['domicilio_doc']->parentId = 'docente';

        $entities['toma']->relations['planilla_docente'] = EntityRelation::getInstance('planilla_docente', 'planilla_docente', 'id');

        $entities['toma']->relations['reemplazo'] = EntityRelation::getInstance('reemplazo', 'persona', 'id');

        $entities['toma']->relations['domicilio_ree'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $entities['toma']->relations['domicilio_ree']->parentId = 'reemplazo';

        $entities['toma']->fields['alta'] = Field::getInstance('toma', 'alta', 'timestamp', 'DateTime');
        $entities['toma']->fields['alta']->defaultValue = 'current_timestamp()';
        $entities['toma']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['toma']->fields['archivo'] = Field::getInstance('toma', 'archivo', 'varchar', 'string');
        $entities['toma']->fields['archivo']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['archivo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['comentario'] = Field::getInstance('toma', 'comentario', 'varchar', 'string');
        $entities['toma']->fields['comentario']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['comentario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['confirmada'] = Field::getInstance('toma', 'confirmada', 'tinyint', 'bool');
        $entities['toma']->fields['confirmada']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['toma']->fields['curso'] = Field::getInstance('toma', 'curso', 'varchar', 'string');
        $entities['toma']->fields['curso']->alias = 'cur';
        $entities['toma']->fields['curso']->refEntityName = 'curso';
        $entities['toma']->fields['curso']->refFieldName = 'id';
        $entities['toma']->fields['curso']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['toma']->fields['curso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['toma']->fields['docente'] = Field::getInstance('toma', 'docente', 'varchar', 'string');
        $entities['toma']->fields['docente']->alias = 'per';
        $entities['toma']->fields['docente']->refEntityName = 'persona';
        $entities['toma']->fields['docente']->refFieldName = 'id';
        $entities['toma']->fields['docente']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['docente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['estado'] = Field::getInstance('toma', 'estado', 'varchar', 'string');
        $entities['toma']->fields['estado']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['estado']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['estado_contralor'] = Field::getInstance('toma', 'estado_contralor', 'varchar', 'string');
        $entities['toma']->fields['estado_contralor']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['estado_contralor']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['fecha_toma'] = Field::getInstance('toma', 'fecha_toma', 'date', 'DateTime');
        $entities['toma']->fields['fecha_toma']->checks = [
            'type' => 'DateTime',
        ];
        $entities['toma']->fields['id'] = Field::getInstance('toma', 'id', 'varchar', 'string');
        $entities['toma']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['toma']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['toma']->fields['observaciones'] = Field::getInstance('toma', 'observaciones', 'text', 'string');
        $entities['toma']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['planilla_docente'] = Field::getInstance('toma', 'planilla_docente', 'varchar', 'string');
        $entities['toma']->fields['planilla_docente']->alias = 'pla';
        $entities['toma']->fields['planilla_docente']->refEntityName = 'planilla_docente';
        $entities['toma']->fields['planilla_docente']->refFieldName = 'id';
        $entities['toma']->fields['planilla_docente']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['planilla_docente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['reclamo'] = Field::getInstance('toma', 'reclamo', 'tinyint', 'bool');
        $entities['toma']->fields['reclamo']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['toma']->fields['reemplazo'] = Field::getInstance('toma', 'reemplazo', 'varchar', 'string');
        $entities['toma']->fields['reemplazo']->alias = 'pe1';
        $entities['toma']->fields['reemplazo']->refEntityName = 'persona';
        $entities['toma']->fields['reemplazo']->refFieldName = 'id';
        $entities['toma']->fields['reemplazo']->checks = [
            'type' => 'string',
        ];
        $entities['toma']->fields['reemplazo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['toma']->fields['sin_planillas'] = Field::getInstance('toma', 'sin_planillas', 'tinyint', 'bool');
        $entities['toma']->fields['sin_planillas']->checks = [
            'type' => 'bool',
            'required' => '1',
        ];
        $entities['toma']->fields['tipo_movimiento'] = Field::getInstance('toma', 'tipo_movimiento', 'varchar', 'string');
        $entities['toma']->fields['tipo_movimiento']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['toma']->fields['tipo_movimiento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        return $entities;
    }
}
