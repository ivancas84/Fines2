<?php

namespace SqlOrganize\Sql\Fines2;

require_once MAIN_PATH . 'SqlOrganize/Sql/ISchema.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/EntityMetadata.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/Field.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/EntityTree.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/EntityRelation.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/EntityRef.php';

use SqlOrganize\Sql\ISchema;
use SqlOrganize\Sql\EntityMetadata;
use SqlOrganize\Sql\Field;

use SqlOrganize\Sql\EntityTree;
use SqlOrganize\Sql\EntityRelation;
use SqlOrganize\Sql\EntityRef;

/**
 * Esquema de la base de datos
 * Esta clase fue generada por una herramienta, no debe ser modificada.
 */
class Schema extends ISchema
{
    public function __construct()
    {
        $this->entities['alumno'] = EntityMetadata::getInstance('alumno', 'alum');
        $this->entities['alumno']->pk = ['id'];
        $this->entities['alumno']->fk = ['persona', 'plan', 'resolucion_inscripcion'];
        $this->entities['alumno']->unique = ['libro_folio', 'persona'];
        $this->entities['alumno']->notNull = ['confirmado_direccion', 'creado', 'id', 'persona', 'previas_completas', 'tiene_certificado', 'tiene_constancia', 'tiene_dni', 'tiene_partida'];

        $this->entities['alumno']->tree = [];
        $this->entities['alumno']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['alumno']->tree['persona']->children = [];
        $this->entities['alumno']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['alumno']->tree['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $this->entities['alumno']->tree['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');

        $this->entities['alumno']->relations = [];
        $this->entities['alumno']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $this->entities['alumno']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['alumno']->relations['domicilio']->parentId = 'persona';

        $this->entities['alumno']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');

        $this->entities['alumno']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');

        $this->entities['alumno']->om = [];
        $this->entities['alumno']->om['AlumnoComision_'] = EntityRef::getInstance('alumno', 'alumno_comision');
        $this->entities['alumno']->om['Calificacion_'] = EntityRef::getInstance('alumno', 'calificacion');
        $this->entities['alumno']->om['DisposicionPendiente_'] = EntityRef::getInstance('alumno', 'disposicion_pendiente');
        $this->entities['alumno']->fields['adeuda_deudores'] = Field::getInstance('alumno', 'adeuda_deudores', 'varchar', 'string');
        $this->entities['alumno']->fields['adeuda_deudores']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['adeuda_deudores']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['adeuda_legajo'] = Field::getInstance('alumno', 'adeuda_legajo', 'varchar', 'string');
        $this->entities['alumno']->fields['adeuda_legajo']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['adeuda_legajo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['anio_ingreso'] = Field::getInstance('alumno', 'anio_ingreso', 'varchar', 'string');
        $this->entities['alumno']->fields['anio_ingreso']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['anio_ingreso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['anio_inscripcion'] = Field::getInstance('alumno', 'anio_inscripcion', 'smallint', 'int');
        $this->entities['alumno']->fields['anio_inscripcion']->checks = [
            'type' => 'int',
        ];
        $this->entities['alumno']->fields['anio_inscripcion_completo'] = Field::getInstance('alumno', 'anio_inscripcion_completo', 'tinyint', 'int');
        $this->entities['alumno']->fields['anio_inscripcion_completo']->checks = [
            'type' => 'int',
        ];
        $this->entities['alumno']->fields['comentarios'] = Field::getInstance('alumno', 'comentarios', 'text', 'string');
        $this->entities['alumno']->fields['comentarios']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['comentarios']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['confirmado_direccion'] = Field::getInstance('alumno', 'confirmado_direccion', 'tinyint', 'int');
        $this->entities['alumno']->fields['confirmado_direccion']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['creado'] = Field::getInstance('alumno', 'creado', 'timestamp', 'DateTime');
        $this->entities['alumno']->fields['creado']->defaultValue = 'current_timestamp()';
        $this->entities['alumno']->fields['creado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['documentacion_inscripcion'] = Field::getInstance('alumno', 'documentacion_inscripcion', 'varchar', 'string');
        $this->entities['alumno']->fields['documentacion_inscripcion']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['documentacion_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['establecimiento_inscripcion'] = Field::getInstance('alumno', 'establecimiento_inscripcion', 'varchar', 'string');
        $this->entities['alumno']->fields['establecimiento_inscripcion']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['establecimiento_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['estado_inscripcion'] = Field::getInstance('alumno', 'estado_inscripcion', 'varchar', 'string');
        $this->entities['alumno']->fields['estado_inscripcion']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['estado_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['fecha_titulacion'] = Field::getInstance('alumno', 'fecha_titulacion', 'date', 'DateTime');
        $this->entities['alumno']->fields['fecha_titulacion']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['alumno']->fields['folio'] = Field::getInstance('alumno', 'folio', 'varchar', 'string');
        $this->entities['alumno']->fields['folio']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['folio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['id'] = Field::getInstance('alumno', 'id', 'varchar', 'string');
        $this->entities['alumno']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['alumno']->fields['libro'] = Field::getInstance('alumno', 'libro', 'varchar', 'string');
        $this->entities['alumno']->fields['libro']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['libro']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['libro_folio'] = Field::getInstance('alumno', 'libro_folio', 'varchar', 'string');
        $this->entities['alumno']->fields['libro_folio']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['libro_folio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['observaciones'] = Field::getInstance('alumno', 'observaciones', 'text', 'string');
        $this->entities['alumno']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['persona'] = Field::getInstance('alumno', 'persona', 'varchar', 'string');
        $this->entities['alumno']->fields['persona']->alias = 'per';
        $this->entities['alumno']->fields['persona']->refEntityName = 'persona';
        $this->entities['alumno']->fields['persona']->refFieldName = 'id';
        $this->entities['alumno']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['alumno']->fields['plan'] = Field::getInstance('alumno', 'plan', 'varchar', 'string');
        $this->entities['alumno']->fields['plan']->alias = 'pla';
        $this->entities['alumno']->fields['plan']->refEntityName = 'plan';
        $this->entities['alumno']->fields['plan']->refFieldName = 'id';
        $this->entities['alumno']->fields['plan']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['plan']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['previas_completas'] = Field::getInstance('alumno', 'previas_completas', 'tinyint', 'int');
        $this->entities['alumno']->fields['previas_completas']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['resolucion_inscripcion'] = Field::getInstance('alumno', 'resolucion_inscripcion', 'varchar', 'string');
        $this->entities['alumno']->fields['resolucion_inscripcion']->alias = 'res';
        $this->entities['alumno']->fields['resolucion_inscripcion']->refEntityName = 'resolucion';
        $this->entities['alumno']->fields['resolucion_inscripcion']->refFieldName = 'id';
        $this->entities['alumno']->fields['resolucion_inscripcion']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno']->fields['resolucion_inscripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno']->fields['semestre_ingreso'] = Field::getInstance('alumno', 'semestre_ingreso', 'smallint', 'int');
        $this->entities['alumno']->fields['semestre_ingreso']->checks = [
            'type' => 'int',
        ];
        $this->entities['alumno']->fields['semestre_inscripcion'] = Field::getInstance('alumno', 'semestre_inscripcion', 'smallint', 'int');
        $this->entities['alumno']->fields['semestre_inscripcion']->checks = [
            'type' => 'int',
        ];
        $this->entities['alumno']->fields['tiene_certificado'] = Field::getInstance('alumno', 'tiene_certificado', 'tinyint', 'int');
        $this->entities['alumno']->fields['tiene_certificado']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['tiene_constancia'] = Field::getInstance('alumno', 'tiene_constancia', 'tinyint', 'int');
        $this->entities['alumno']->fields['tiene_constancia']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['tiene_dni'] = Field::getInstance('alumno', 'tiene_dni', 'tinyint', 'int');
        $this->entities['alumno']->fields['tiene_dni']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['alumno']->fields['tiene_partida'] = Field::getInstance('alumno', 'tiene_partida', 'tinyint', 'int');
        $this->entities['alumno']->fields['tiene_partida']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['alumno_comision'] = EntityMetadata::getInstance('alumno_comision', 'alu1');
        $this->entities['alumno_comision']->pk = ['id'];
        $this->entities['alumno_comision']->fk = ['alumno', 'comision'];
        $this->entities['alumno_comision']->notNull = ['alumno', 'creado', 'id'];

        $this->entities['alumno_comision']->tree = [];
        $this->entities['alumno_comision']->tree['alumno'] = EntityTree::getInstance('alumno', 'alumno', 'id');
        $this->entities['alumno_comision']->tree['alumno']->children = [];
        $this->entities['alumno_comision']->tree['alumno']->children['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['alumno_comision']->tree['alumno']->children['persona']->children = [];
        $this->entities['alumno_comision']->tree['alumno']->children['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['alumno_comision']->tree['alumno']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $this->entities['alumno_comision']->tree['alumno']->children['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');


        $this->entities['alumno_comision']->tree['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $this->entities['alumno_comision']->tree['comision']->children = [];
        $this->entities['alumno_comision']->tree['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['alumno_comision']->tree['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['alumno_comision']->tree['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['alumno_comision']->tree['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['alumno_comision']->tree['comision']->children['planificacion']->children = [];
        $this->entities['alumno_comision']->tree['comision']->children['planificacion']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['alumno_comision']->tree['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['alumno_comision']->tree['comision']->children['sede']->children = [];
        $this->entities['alumno_comision']->tree['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['alumno_comision']->tree['comision']->children['sede']->children['centro_educativo']->children = [];
        $this->entities['alumno_comision']->tree['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['alumno_comision']->tree['comision']->children['sede']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['alumno_comision']->tree['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['alumno_comision']->tree['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['alumno_comision']->relations = [];
        $this->entities['alumno_comision']->relations['alumno'] = EntityRelation::getInstance('alumno', 'alumno', 'id');

        $this->entities['alumno_comision']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');
        $this->entities['alumno_comision']->relations['persona']->parentId = 'alumno';

        $this->entities['alumno_comision']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['alumno_comision']->relations['domicilio']->parentId = 'persona';

        $this->entities['alumno_comision']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['alumno_comision']->relations['plan']->parentId = 'alumno';

        $this->entities['alumno_comision']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');
        $this->entities['alumno_comision']->relations['resolucion_inscripcion']->parentId = 'alumno';

        $this->entities['alumno_comision']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');

        $this->entities['alumno_comision']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['alumno_comision']->relations['calendario']->parentId = 'comision';

        $this->entities['alumno_comision']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['alumno_comision']->relations['comision_siguiente']->parentId = 'comision';

        $this->entities['alumno_comision']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['alumno_comision']->relations['modalidad']->parentId = 'comision';

        $this->entities['alumno_comision']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['alumno_comision']->relations['planificacion']->parentId = 'comision';

        $this->entities['alumno_comision']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['alumno_comision']->relations['plan_pla']->parentId = 'planificacion';

        $this->entities['alumno_comision']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['alumno_comision']->relations['sede']->parentId = 'comision';

        $this->entities['alumno_comision']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['alumno_comision']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['alumno_comision']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['alumno_comision']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['alumno_comision']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['alumno_comision']->relations['domicilio_sed']->parentId = 'sede';

        $this->entities['alumno_comision']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['alumno_comision']->relations['organizacion']->parentId = 'sede';

        $this->entities['alumno_comision']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['alumno_comision']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['alumno_comision']->fields['activo'] = Field::getInstance('alumno_comision', 'activo', 'tinyint', 'int');
        $this->entities['alumno_comision']->fields['activo']->checks = [
            'type' => 'int',
        ];
        $this->entities['alumno_comision']->fields['alumno'] = Field::getInstance('alumno_comision', 'alumno', 'varchar', 'string');
        $this->entities['alumno_comision']->fields['alumno']->alias = 'alu';
        $this->entities['alumno_comision']->fields['alumno']->refEntityName = 'alumno';
        $this->entities['alumno_comision']->fields['alumno']->refFieldName = 'id';
        $this->entities['alumno_comision']->fields['alumno']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['alumno_comision']->fields['alumno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['alumno_comision']->fields['comision'] = Field::getInstance('alumno_comision', 'comision', 'varchar', 'string');
        $this->entities['alumno_comision']->fields['comision']->alias = 'com';
        $this->entities['alumno_comision']->fields['comision']->refEntityName = 'comision';
        $this->entities['alumno_comision']->fields['comision']->refFieldName = 'id';
        $this->entities['alumno_comision']->fields['comision']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno_comision']->fields['comision']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno_comision']->fields['creado'] = Field::getInstance('alumno_comision', 'creado', 'timestamp', 'DateTime');
        $this->entities['alumno_comision']->fields['creado']->defaultValue = 'current_timestamp()';
        $this->entities['alumno_comision']->fields['creado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['alumno_comision']->fields['estado'] = Field::getInstance('alumno_comision', 'estado', 'varchar', 'string');
        $this->entities['alumno_comision']->fields['estado']->defaultValue = 'Activo';
        $this->entities['alumno_comision']->fields['estado']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno_comision']->fields['estado']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno_comision']->fields['id'] = Field::getInstance('alumno_comision', 'id', 'varchar', 'string');
        $this->entities['alumno_comision']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['alumno_comision']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['alumno_comision']->fields['observaciones'] = Field::getInstance('alumno_comision', 'observaciones', 'text', 'string');
        $this->entities['alumno_comision']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['alumno_comision']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['alumno_comision']->fields['pfid'] = Field::getInstance('alumno_comision', 'pfid', 'int', 'int');
        $this->entities['alumno_comision']->fields['pfid']->checks = [
            'type' => 'int',
        ];
        $this->entities['asignacion_planilla_docente'] = EntityMetadata::getInstance('asignacion_planilla_docente', 'asig');
        $this->entities['asignacion_planilla_docente']->pk = ['id'];
        $this->entities['asignacion_planilla_docente']->fk = ['planilla_docente', 'toma'];
        $this->entities['asignacion_planilla_docente']->notNull = ['id', 'insertado', 'planilla_docente', 'reclamo', 'toma'];

        $this->entities['asignacion_planilla_docente']->tree = [];
        $this->entities['asignacion_planilla_docente']->tree['planilla_docente'] = EntityTree::getInstance('planilla_docente', 'planilla_docente', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma'] = EntityTree::getInstance('toma', 'toma', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso'] = EntityTree::getInstance('curso', 'curso', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['planificacion']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede']->children['centro_educativo']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['disposicion']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['disposicion']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['disposicion']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['disposicion']->children['planificacion_dis']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['curso']->children['disposicion']->children['planificacion_dis']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');




        $this->entities['asignacion_planilla_docente']->tree['toma']->children['docente'] = EntityTree::getInstance('docente', 'persona', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['docente']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['docente']->children['domicilio_doc'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['asignacion_planilla_docente']->tree['toma']->children['planilla_docente_tom'] = EntityTree::getInstance('planilla_docente', 'planilla_docente', 'id');

        $this->entities['asignacion_planilla_docente']->tree['toma']->children['reemplazo'] = EntityTree::getInstance('reemplazo', 'persona', 'id');
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['reemplazo']->children = [];
        $this->entities['asignacion_planilla_docente']->tree['toma']->children['reemplazo']->children['domicilio_ree'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');



        $this->entities['asignacion_planilla_docente']->relations = [];
        $this->entities['asignacion_planilla_docente']->relations['planilla_docente'] = EntityRelation::getInstance('planilla_docente', 'planilla_docente', 'id');

        $this->entities['asignacion_planilla_docente']->relations['toma'] = EntityRelation::getInstance('toma', 'toma', 'id');

        $this->entities['asignacion_planilla_docente']->relations['curso'] = EntityRelation::getInstance('curso', 'curso', 'id');
        $this->entities['asignacion_planilla_docente']->relations['curso']->parentId = 'toma';

        $this->entities['asignacion_planilla_docente']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['asignacion_planilla_docente']->relations['asignatura']->parentId = 'curso';

        $this->entities['asignacion_planilla_docente']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');
        $this->entities['asignacion_planilla_docente']->relations['comision']->parentId = 'curso';

        $this->entities['asignacion_planilla_docente']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['asignacion_planilla_docente']->relations['calendario']->parentId = 'comision';

        $this->entities['asignacion_planilla_docente']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['asignacion_planilla_docente']->relations['comision_siguiente']->parentId = 'comision';

        $this->entities['asignacion_planilla_docente']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['asignacion_planilla_docente']->relations['modalidad']->parentId = 'comision';

        $this->entities['asignacion_planilla_docente']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['asignacion_planilla_docente']->relations['planificacion']->parentId = 'comision';

        $this->entities['asignacion_planilla_docente']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['asignacion_planilla_docente']->relations['plan']->parentId = 'planificacion';

        $this->entities['asignacion_planilla_docente']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['asignacion_planilla_docente']->relations['sede']->parentId = 'comision';

        $this->entities['asignacion_planilla_docente']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['asignacion_planilla_docente']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['asignacion_planilla_docente']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['asignacion_planilla_docente']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['asignacion_planilla_docente']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['asignacion_planilla_docente']->relations['domicilio']->parentId = 'sede';

        $this->entities['asignacion_planilla_docente']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['asignacion_planilla_docente']->relations['organizacion']->parentId = 'sede';

        $this->entities['asignacion_planilla_docente']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['asignacion_planilla_docente']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['asignacion_planilla_docente']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['asignacion_planilla_docente']->relations['disposicion']->parentId = 'curso';

        $this->entities['asignacion_planilla_docente']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['asignacion_planilla_docente']->relations['asignatura_dis']->parentId = 'disposicion';

        $this->entities['asignacion_planilla_docente']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['asignacion_planilla_docente']->relations['planificacion_dis']->parentId = 'disposicion';

        $this->entities['asignacion_planilla_docente']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['asignacion_planilla_docente']->relations['plan_pla']->parentId = 'planificacion_dis';

        $this->entities['asignacion_planilla_docente']->relations['docente'] = EntityRelation::getInstance('docente', 'persona', 'id');
        $this->entities['asignacion_planilla_docente']->relations['docente']->parentId = 'toma';

        $this->entities['asignacion_planilla_docente']->relations['domicilio_doc'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['asignacion_planilla_docente']->relations['domicilio_doc']->parentId = 'docente';

        $this->entities['asignacion_planilla_docente']->relations['planilla_docente_tom'] = EntityRelation::getInstance('planilla_docente', 'planilla_docente', 'id');
        $this->entities['asignacion_planilla_docente']->relations['planilla_docente_tom']->parentId = 'toma';

        $this->entities['asignacion_planilla_docente']->relations['reemplazo'] = EntityRelation::getInstance('reemplazo', 'persona', 'id');
        $this->entities['asignacion_planilla_docente']->relations['reemplazo']->parentId = 'toma';

        $this->entities['asignacion_planilla_docente']->relations['domicilio_ree'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['asignacion_planilla_docente']->relations['domicilio_ree']->parentId = 'reemplazo';

        $this->entities['asignacion_planilla_docente']->fields['comentario'] = Field::getInstance('asignacion_planilla_docente', 'comentario', 'varchar', 'string');
        $this->entities['asignacion_planilla_docente']->fields['comentario']->checks = [
            'type' => 'string',
        ];
        $this->entities['asignacion_planilla_docente']->fields['comentario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['asignacion_planilla_docente']->fields['id'] = Field::getInstance('asignacion_planilla_docente', 'id', 'varchar', 'string');
        $this->entities['asignacion_planilla_docente']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['asignacion_planilla_docente']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['asignacion_planilla_docente']->fields['insertado'] = Field::getInstance('asignacion_planilla_docente', 'insertado', 'timestamp', 'DateTime');
        $this->entities['asignacion_planilla_docente']->fields['insertado']->defaultValue = 'current_timestamp()';
        $this->entities['asignacion_planilla_docente']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['asignacion_planilla_docente']->fields['planilla_docente'] = Field::getInstance('asignacion_planilla_docente', 'planilla_docente', 'varchar', 'string');
        $this->entities['asignacion_planilla_docente']->fields['planilla_docente']->alias = 'pla';
        $this->entities['asignacion_planilla_docente']->fields['planilla_docente']->refEntityName = 'planilla_docente';
        $this->entities['asignacion_planilla_docente']->fields['planilla_docente']->refFieldName = 'id';
        $this->entities['asignacion_planilla_docente']->fields['planilla_docente']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['asignacion_planilla_docente']->fields['planilla_docente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['asignacion_planilla_docente']->fields['reclamo'] = Field::getInstance('asignacion_planilla_docente', 'reclamo', 'tinyint', 'int');
        $this->entities['asignacion_planilla_docente']->fields['reclamo']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['asignacion_planilla_docente']->fields['toma'] = Field::getInstance('asignacion_planilla_docente', 'toma', 'varchar', 'string');
        $this->entities['asignacion_planilla_docente']->fields['toma']->alias = 'tom';
        $this->entities['asignacion_planilla_docente']->fields['toma']->refEntityName = 'toma';
        $this->entities['asignacion_planilla_docente']->fields['toma']->refFieldName = 'id';
        $this->entities['asignacion_planilla_docente']->fields['toma']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['asignacion_planilla_docente']->fields['toma']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['asignatura'] = EntityMetadata::getInstance('asignatura', 'asi1');
        $this->entities['asignatura']->pk = ['id'];
        $this->entities['asignatura']->unique = ['nombre'];
        $this->entities['asignatura']->notNull = ['id', 'nombre'];

        $this->entities['asignatura']->om = [];
        $this->entities['asignatura']->om['Curso_'] = EntityRef::getInstance('asignatura', 'curso');
        $this->entities['asignatura']->om['Disposicion_'] = EntityRef::getInstance('asignatura', 'disposicion');
        $this->entities['asignatura']->fields['clasificacion'] = Field::getInstance('asignatura', 'clasificacion', 'varchar', 'string');
        $this->entities['asignatura']->fields['clasificacion']->checks = [
            'type' => 'string',
        ];
        $this->entities['asignatura']->fields['clasificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['asignatura']->fields['codigo'] = Field::getInstance('asignatura', 'codigo', 'varchar', 'string');
        $this->entities['asignatura']->fields['codigo']->checks = [
            'type' => 'string',
        ];
        $this->entities['asignatura']->fields['codigo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['asignatura']->fields['formacion'] = Field::getInstance('asignatura', 'formacion', 'varchar', 'string');
        $this->entities['asignatura']->fields['formacion']->checks = [
            'type' => 'string',
        ];
        $this->entities['asignatura']->fields['formacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['asignatura']->fields['id'] = Field::getInstance('asignatura', 'id', 'varchar', 'string');
        $this->entities['asignatura']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['asignatura']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['asignatura']->fields['nombre'] = Field::getInstance('asignatura', 'nombre', 'varchar', 'string');
        $this->entities['asignatura']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['asignatura']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['asignatura']->fields['perfil'] = Field::getInstance('asignatura', 'perfil', 'varchar', 'string');
        $this->entities['asignatura']->fields['perfil']->checks = [
            'type' => 'string',
        ];
        $this->entities['asignatura']->fields['perfil']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['calendario'] = EntityMetadata::getInstance('calendario', 'cale');
        $this->entities['calendario']->pk = ['id'];
        $this->entities['calendario']->notNull = ['anio', 'id', 'insertado', 'semestre'];

        $this->entities['calendario']->om = [];
        $this->entities['calendario']->om['Comision_'] = EntityRef::getInstance('calendario', 'comision');
        $this->entities['calendario']->fields['anio'] = Field::getInstance('calendario', 'anio', 'year', 'DateTime');
        $this->entities['calendario']->fields['anio']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['calendario']->fields['descripcion'] = Field::getInstance('calendario', 'descripcion', 'varchar', 'string');
        $this->entities['calendario']->fields['descripcion']->checks = [
            'type' => 'string',
        ];
        $this->entities['calendario']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['calendario']->fields['fin'] = Field::getInstance('calendario', 'fin', 'date', 'DateTime');
        $this->entities['calendario']->fields['fin']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['calendario']->fields['id'] = Field::getInstance('calendario', 'id', 'varchar', 'string');
        $this->entities['calendario']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['calendario']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['calendario']->fields['inicio'] = Field::getInstance('calendario', 'inicio', 'date', 'DateTime');
        $this->entities['calendario']->fields['inicio']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['calendario']->fields['insertado'] = Field::getInstance('calendario', 'insertado', 'timestamp', 'DateTime');
        $this->entities['calendario']->fields['insertado']->defaultValue = 'current_timestamp()';
        $this->entities['calendario']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['calendario']->fields['semestre'] = Field::getInstance('calendario', 'semestre', 'smallint', 'int');
        $this->entities['calendario']->fields['semestre']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['calificacion'] = EntityMetadata::getInstance('calificacion', 'cali');
        $this->entities['calificacion']->pk = ['id'];
        $this->entities['calificacion']->fk = ['alumno', 'curso', 'disposicion'];
        $this->entities['calificacion']->notNull = ['alumno', 'archivado', 'disposicion', 'id'];

        $this->entities['calificacion']->tree = [];
        $this->entities['calificacion']->tree['alumno'] = EntityTree::getInstance('alumno', 'alumno', 'id');
        $this->entities['calificacion']->tree['alumno']->children = [];
        $this->entities['calificacion']->tree['alumno']->children['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['calificacion']->tree['alumno']->children['persona']->children = [];
        $this->entities['calificacion']->tree['alumno']->children['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['calificacion']->tree['alumno']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $this->entities['calificacion']->tree['alumno']->children['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');


        $this->entities['calificacion']->tree['curso'] = EntityTree::getInstance('curso', 'curso', 'id');
        $this->entities['calificacion']->tree['curso']->children = [];
        $this->entities['calificacion']->tree['curso']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['calificacion']->tree['curso']->children['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $this->entities['calificacion']->tree['curso']->children['comision']->children = [];
        $this->entities['calificacion']->tree['curso']->children['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['calificacion']->tree['curso']->children['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['calificacion']->tree['curso']->children['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['calificacion']->tree['curso']->children['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['calificacion']->tree['curso']->children['comision']->children['planificacion']->children = [];
        $this->entities['calificacion']->tree['curso']->children['comision']->children['planificacion']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede']->children = [];
        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children = [];
        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['calificacion']->tree['curso']->children['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['calificacion']->tree['curso']->children['disposicion_cur'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['calificacion']->tree['curso']->children['disposicion_cur']->children = [];
        $this->entities['calificacion']->tree['curso']->children['disposicion_cur']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['calificacion']->tree['curso']->children['disposicion_cur']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['calificacion']->tree['curso']->children['disposicion_cur']->children['planificacion_dis']->children = [];
        $this->entities['calificacion']->tree['curso']->children['disposicion_cur']->children['planificacion_dis']->children['plan_pla1'] = EntityTree::getInstance('plan', 'plan', 'id');




        $this->entities['calificacion']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['calificacion']->tree['disposicion']->children = [];
        $this->entities['calificacion']->tree['disposicion']->children['asignatura_dis1'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['calificacion']->tree['disposicion']->children['planificacion_dis1'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['calificacion']->tree['disposicion']->children['planificacion_dis1']->children = [];
        $this->entities['calificacion']->tree['disposicion']->children['planificacion_dis1']->children['plan_pla2'] = EntityTree::getInstance('plan', 'plan', 'id');



        $this->entities['calificacion']->relations = [];
        $this->entities['calificacion']->relations['alumno'] = EntityRelation::getInstance('alumno', 'alumno', 'id');

        $this->entities['calificacion']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');
        $this->entities['calificacion']->relations['persona']->parentId = 'alumno';

        $this->entities['calificacion']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['calificacion']->relations['domicilio']->parentId = 'persona';

        $this->entities['calificacion']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['calificacion']->relations['plan']->parentId = 'alumno';

        $this->entities['calificacion']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');
        $this->entities['calificacion']->relations['resolucion_inscripcion']->parentId = 'alumno';

        $this->entities['calificacion']->relations['curso'] = EntityRelation::getInstance('curso', 'curso', 'id');

        $this->entities['calificacion']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['calificacion']->relations['asignatura']->parentId = 'curso';

        $this->entities['calificacion']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');
        $this->entities['calificacion']->relations['comision']->parentId = 'curso';

        $this->entities['calificacion']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['calificacion']->relations['calendario']->parentId = 'comision';

        $this->entities['calificacion']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['calificacion']->relations['comision_siguiente']->parentId = 'comision';

        $this->entities['calificacion']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['calificacion']->relations['modalidad']->parentId = 'comision';

        $this->entities['calificacion']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['calificacion']->relations['planificacion']->parentId = 'comision';

        $this->entities['calificacion']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['calificacion']->relations['plan_pla']->parentId = 'planificacion';

        $this->entities['calificacion']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['calificacion']->relations['sede']->parentId = 'comision';

        $this->entities['calificacion']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['calificacion']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['calificacion']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['calificacion']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['calificacion']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['calificacion']->relations['domicilio_sed']->parentId = 'sede';

        $this->entities['calificacion']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['calificacion']->relations['organizacion']->parentId = 'sede';

        $this->entities['calificacion']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['calificacion']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['calificacion']->relations['disposicion_cur'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['calificacion']->relations['disposicion_cur']->parentId = 'curso';

        $this->entities['calificacion']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['calificacion']->relations['asignatura_dis']->parentId = 'disposicion_cur';

        $this->entities['calificacion']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['calificacion']->relations['planificacion_dis']->parentId = 'disposicion_cur';

        $this->entities['calificacion']->relations['plan_pla1'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['calificacion']->relations['plan_pla1']->parentId = 'planificacion_dis';

        $this->entities['calificacion']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $this->entities['calificacion']->relations['asignatura_dis1'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['calificacion']->relations['asignatura_dis1']->parentId = 'disposicion';

        $this->entities['calificacion']->relations['planificacion_dis1'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['calificacion']->relations['planificacion_dis1']->parentId = 'disposicion';

        $this->entities['calificacion']->relations['plan_pla2'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['calificacion']->relations['plan_pla2']->parentId = 'planificacion_dis1';

        $this->entities['calificacion']->fields['alumno'] = Field::getInstance('calificacion', 'alumno', 'varchar', 'string');
        $this->entities['calificacion']->fields['alumno']->alias = 'alu';
        $this->entities['calificacion']->fields['alumno']->refEntityName = 'alumno';
        $this->entities['calificacion']->fields['alumno']->refFieldName = 'id';
        $this->entities['calificacion']->fields['alumno']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['calificacion']->fields['alumno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['calificacion']->fields['archivado'] = Field::getInstance('calificacion', 'archivado', 'tinyint', 'int');
        $this->entities['calificacion']->fields['archivado']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['calificacion']->fields['crec'] = Field::getInstance('calificacion', 'crec', 'decimal', 'float');
        $this->entities['calificacion']->fields['crec']->checks = [
            'type' => 'float',
        ];
        $this->entities['calificacion']->fields['curso'] = Field::getInstance('calificacion', 'curso', 'varchar', 'string');
        $this->entities['calificacion']->fields['curso']->alias = 'cur';
        $this->entities['calificacion']->fields['curso']->refEntityName = 'curso';
        $this->entities['calificacion']->fields['curso']->refFieldName = 'id';
        $this->entities['calificacion']->fields['curso']->checks = [
            'type' => 'string',
        ];
        $this->entities['calificacion']->fields['curso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['calificacion']->fields['disposicion'] = Field::getInstance('calificacion', 'disposicion', 'varchar', 'string');
        $this->entities['calificacion']->fields['disposicion']->alias = 'dis';
        $this->entities['calificacion']->fields['disposicion']->refEntityName = 'disposicion';
        $this->entities['calificacion']->fields['disposicion']->refFieldName = 'id';
        $this->entities['calificacion']->fields['disposicion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['calificacion']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['calificacion']->fields['division'] = Field::getInstance('calificacion', 'division', 'varchar', 'string');
        $this->entities['calificacion']->fields['division']->checks = [
            'type' => 'string',
        ];
        $this->entities['calificacion']->fields['division']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['calificacion']->fields['fecha'] = Field::getInstance('calificacion', 'fecha', 'date', 'DateTime');
        $this->entities['calificacion']->fields['fecha']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['calificacion']->fields['id'] = Field::getInstance('calificacion', 'id', 'varchar', 'string');
        $this->entities['calificacion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['calificacion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['calificacion']->fields['nota1'] = Field::getInstance('calificacion', 'nota1', 'decimal', 'float');
        $this->entities['calificacion']->fields['nota1']->checks = [
            'type' => 'float',
        ];
        $this->entities['calificacion']->fields['nota2'] = Field::getInstance('calificacion', 'nota2', 'decimal', 'float');
        $this->entities['calificacion']->fields['nota2']->checks = [
            'type' => 'float',
        ];
        $this->entities['calificacion']->fields['nota3'] = Field::getInstance('calificacion', 'nota3', 'decimal', 'float');
        $this->entities['calificacion']->fields['nota3']->checks = [
            'type' => 'float',
        ];
        $this->entities['calificacion']->fields['nota_final'] = Field::getInstance('calificacion', 'nota_final', 'decimal', 'float');
        $this->entities['calificacion']->fields['nota_final']->checks = [
            'type' => 'float',
        ];
        $this->entities['calificacion']->fields['observaciones'] = Field::getInstance('calificacion', 'observaciones', 'text', 'string');
        $this->entities['calificacion']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['calificacion']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['calificacion']->fields['porcentaje_asistencia'] = Field::getInstance('calificacion', 'porcentaje_asistencia', 'int', 'int');
        $this->entities['calificacion']->fields['porcentaje_asistencia']->checks = [
            'type' => 'int',
        ];
        $this->entities['cargo'] = EntityMetadata::getInstance('cargo', 'carg');
        $this->entities['cargo']->pk = ['id'];
        $this->entities['cargo']->unique = ['descripcion'];
        $this->entities['cargo']->notNull = ['descripcion', 'id'];

        $this->entities['cargo']->om = [];
        $this->entities['cargo']->om['Designacion_'] = EntityRef::getInstance('cargo', 'designacion');
        $this->entities['cargo']->fields['descripcion'] = Field::getInstance('cargo', 'descripcion', 'varchar', 'string');
        $this->entities['cargo']->fields['descripcion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['cargo']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['cargo']->fields['id'] = Field::getInstance('cargo', 'id', 'varchar', 'string');
        $this->entities['cargo']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['cargo']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['centro_educativo'] = EntityMetadata::getInstance('centro_educativo', 'cent');
        $this->entities['centro_educativo']->pk = ['id'];
        $this->entities['centro_educativo']->fk = ['domicilio'];
        $this->entities['centro_educativo']->unique = ['cue'];
        $this->entities['centro_educativo']->notNull = ['id', 'nombre'];

        $this->entities['centro_educativo']->tree = [];
        $this->entities['centro_educativo']->tree['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['centro_educativo']->relations = [];
        $this->entities['centro_educativo']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['centro_educativo']->om = [];
        $this->entities['centro_educativo']->om['Sede_'] = EntityRef::getInstance('centro_educativo', 'sede');
        $this->entities['centro_educativo']->fields['cue'] = Field::getInstance('centro_educativo', 'cue', 'varchar', 'string');
        $this->entities['centro_educativo']->fields['cue']->checks = [
            'type' => 'string',
        ];
        $this->entities['centro_educativo']->fields['cue']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['centro_educativo']->fields['domicilio'] = Field::getInstance('centro_educativo', 'domicilio', 'varchar', 'string');
        $this->entities['centro_educativo']->fields['domicilio']->alias = 'dom';
        $this->entities['centro_educativo']->fields['domicilio']->refEntityName = 'domicilio';
        $this->entities['centro_educativo']->fields['domicilio']->refFieldName = 'id';
        $this->entities['centro_educativo']->fields['domicilio']->checks = [
            'type' => 'string',
        ];
        $this->entities['centro_educativo']->fields['domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['centro_educativo']->fields['id'] = Field::getInstance('centro_educativo', 'id', 'varchar', 'string');
        $this->entities['centro_educativo']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['centro_educativo']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['centro_educativo']->fields['nombre'] = Field::getInstance('centro_educativo', 'nombre', 'varchar', 'string');
        $this->entities['centro_educativo']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['centro_educativo']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['centro_educativo']->fields['observaciones'] = Field::getInstance('centro_educativo', 'observaciones', 'text', 'string');
        $this->entities['centro_educativo']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['centro_educativo']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision'] = EntityMetadata::getInstance('comision', 'comi');
        $this->entities['comision']->pk = ['id'];
        $this->entities['comision']->fk = ['calendario', 'comision_siguiente', 'modalidad', 'planificacion', 'sede'];
        $this->entities['comision']->notNull = ['alta', 'apertura', 'autorizada', 'calendario', 'division', 'id', 'modalidad', 'publicada', 'sede'];

        $this->entities['comision']->tree = [];
        $this->entities['comision']->tree['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['comision']->tree['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['comision']->tree['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['comision']->tree['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['comision']->tree['planificacion']->children = [];
        $this->entities['comision']->tree['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['comision']->tree['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['comision']->tree['sede']->children = [];
        $this->entities['comision']->tree['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['comision']->tree['sede']->children['centro_educativo']->children = [];
        $this->entities['comision']->tree['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['comision']->tree['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['comision']->tree['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['comision']->tree['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');


        $this->entities['comision']->relations = [];
        $this->entities['comision']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');

        $this->entities['comision']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['comision']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['comision']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');

        $this->entities['comision']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['comision']->relations['plan']->parentId = 'planificacion';

        $this->entities['comision']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');

        $this->entities['comision']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['comision']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['comision']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['comision']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['comision']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['comision']->relations['domicilio']->parentId = 'sede';

        $this->entities['comision']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['comision']->relations['organizacion']->parentId = 'sede';

        $this->entities['comision']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['comision']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['comision']->om = [];
        $this->entities['comision']->om['AlumnoComision_'] = EntityRef::getInstance('comision', 'alumno_comision');
        $this->entities['comision']->om['Comision_comision_siguiente_'] = EntityRef::getInstance('comision_siguiente', 'comision');
        $this->entities['comision']->om['ComisionRelacionada_'] = EntityRef::getInstance('comision', 'comision_relacionada');
        $this->entities['comision']->om['ComisionRelacionada_relacion_'] = EntityRef::getInstance('relacion', 'comision_relacionada');
        $this->entities['comision']->om['Curso_'] = EntityRef::getInstance('comision', 'curso');
        $this->entities['comision']->fields['alta'] = Field::getInstance('comision', 'alta', 'timestamp', 'DateTime');
        $this->entities['comision']->fields['alta']->defaultValue = 'current_timestamp()';
        $this->entities['comision']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['comision']->fields['apertura'] = Field::getInstance('comision', 'apertura', 'tinyint', 'int');
        $this->entities['comision']->fields['apertura']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['comision']->fields['autorizada'] = Field::getInstance('comision', 'autorizada', 'tinyint', 'int');
        $this->entities['comision']->fields['autorizada']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['comision']->fields['calendario'] = Field::getInstance('comision', 'calendario', 'varchar', 'string');
        $this->entities['comision']->fields['calendario']->alias = 'cal';
        $this->entities['comision']->fields['calendario']->refEntityName = 'calendario';
        $this->entities['comision']->fields['calendario']->refFieldName = 'id';
        $this->entities['comision']->fields['calendario']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision']->fields['calendario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['comision']->fields['comentario'] = Field::getInstance('comision', 'comentario', 'text', 'string');
        $this->entities['comision']->fields['comentario']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['comentario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['comision_siguiente'] = Field::getInstance('comision', 'comision_siguiente', 'varchar', 'string');
        $this->entities['comision']->fields['comision_siguiente']->alias = 'com';
        $this->entities['comision']->fields['comision_siguiente']->refEntityName = 'comision';
        $this->entities['comision']->fields['comision_siguiente']->refFieldName = 'id';
        $this->entities['comision']->fields['comision_siguiente']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['comision_siguiente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['configuracion'] = Field::getInstance('comision', 'configuracion', 'varchar', 'string');
        $this->entities['comision']->fields['configuracion']->defaultValue = 'Histórica';
        $this->entities['comision']->fields['configuracion']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['configuracion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['division'] = Field::getInstance('comision', 'division', 'varchar', 'string');
        $this->entities['comision']->fields['division']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision']->fields['division']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['comision']->fields['estado'] = Field::getInstance('comision', 'estado', 'varchar', 'string');
        $this->entities['comision']->fields['estado']->defaultValue = 'Confirma';
        $this->entities['comision']->fields['estado']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['estado']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['id'] = Field::getInstance('comision', 'id', 'varchar', 'string');
        $this->entities['comision']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['comision']->fields['identificacion'] = Field::getInstance('comision', 'identificacion', 'varchar', 'string');
        $this->entities['comision']->fields['identificacion']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['identificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['modalidad'] = Field::getInstance('comision', 'modalidad', 'varchar', 'string');
        $this->entities['comision']->fields['modalidad']->alias = 'mod';
        $this->entities['comision']->fields['modalidad']->refEntityName = 'modalidad';
        $this->entities['comision']->fields['modalidad']->refFieldName = 'id';
        $this->entities['comision']->fields['modalidad']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision']->fields['modalidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['comision']->fields['observaciones'] = Field::getInstance('comision', 'observaciones', 'text', 'string');
        $this->entities['comision']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['pfid'] = Field::getInstance('comision', 'pfid', 'varchar', 'string');
        $this->entities['comision']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['planificacion'] = Field::getInstance('comision', 'planificacion', 'varchar', 'string');
        $this->entities['comision']->fields['planificacion']->alias = 'pla';
        $this->entities['comision']->fields['planificacion']->refEntityName = 'planificacion';
        $this->entities['comision']->fields['planificacion']->refFieldName = 'id';
        $this->entities['comision']->fields['planificacion']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['planificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision']->fields['publicada'] = Field::getInstance('comision', 'publicada', 'tinyint', 'int');
        $this->entities['comision']->fields['publicada']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['comision']->fields['sede'] = Field::getInstance('comision', 'sede', 'varchar', 'string');
        $this->entities['comision']->fields['sede']->alias = 'sed';
        $this->entities['comision']->fields['sede']->refEntityName = 'sede';
        $this->entities['comision']->fields['sede']->refFieldName = 'id';
        $this->entities['comision']->fields['sede']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision']->fields['sede']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['comision']->fields['turno'] = Field::getInstance('comision', 'turno', 'varchar', 'string');
        $this->entities['comision']->fields['turno']->checks = [
            'type' => 'string',
        ];
        $this->entities['comision']->fields['turno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['comision_relacionada'] = EntityMetadata::getInstance('comision_relacionada', 'com1');
        $this->entities['comision_relacionada']->pk = ['id'];
        $this->entities['comision_relacionada']->fk = ['comision', 'relacion'];
        $this->entities['comision_relacionada']->notNull = ['comision', 'id', 'relacion'];

        $this->entities['comision_relacionada']->tree = [];
        $this->entities['comision_relacionada']->tree['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $this->entities['comision_relacionada']->tree['comision']->children = [];
        $this->entities['comision_relacionada']->tree['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['comision_relacionada']->tree['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['comision_relacionada']->tree['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['comision_relacionada']->tree['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['comision_relacionada']->tree['comision']->children['planificacion']->children = [];
        $this->entities['comision_relacionada']->tree['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['comision_relacionada']->tree['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['comision_relacionada']->tree['comision']->children['sede']->children = [];
        $this->entities['comision_relacionada']->tree['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['comision_relacionada']->tree['comision']->children['sede']->children['centro_educativo']->children = [];
        $this->entities['comision_relacionada']->tree['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['comision_relacionada']->tree['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['comision_relacionada']->tree['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['comision_relacionada']->tree['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['comision_relacionada']->tree['relacion'] = EntityTree::getInstance('relacion', 'comision', 'id');
        $this->entities['comision_relacionada']->tree['relacion']->children = [];
        $this->entities['comision_relacionada']->tree['relacion']->children['calendario_rel'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['comision_relacionada']->tree['relacion']->children['comision_siguiente_rel'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['comision_relacionada']->tree['relacion']->children['modalidad_rel'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['comision_relacionada']->tree['relacion']->children['planificacion_rel'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['comision_relacionada']->tree['relacion']->children['planificacion_rel']->children = [];
        $this->entities['comision_relacionada']->tree['relacion']->children['planificacion_rel']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children = [];
        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['centro_educativo_sed'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['centro_educativo_sed']->children = [];
        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['centro_educativo_sed']->children['domicilio_cen1'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['organizacion_sed'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['comision_relacionada']->tree['relacion']->children['sede_rel']->children['tipo_sede_sed'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['comision_relacionada']->relations = [];
        $this->entities['comision_relacionada']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');

        $this->entities['comision_relacionada']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['comision_relacionada']->relations['calendario']->parentId = 'comision';

        $this->entities['comision_relacionada']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['comision_relacionada']->relations['comision_siguiente']->parentId = 'comision';

        $this->entities['comision_relacionada']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['comision_relacionada']->relations['modalidad']->parentId = 'comision';

        $this->entities['comision_relacionada']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['comision_relacionada']->relations['planificacion']->parentId = 'comision';

        $this->entities['comision_relacionada']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['comision_relacionada']->relations['plan']->parentId = 'planificacion';

        $this->entities['comision_relacionada']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['comision_relacionada']->relations['sede']->parentId = 'comision';

        $this->entities['comision_relacionada']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['comision_relacionada']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['comision_relacionada']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['comision_relacionada']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['comision_relacionada']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['comision_relacionada']->relations['domicilio']->parentId = 'sede';

        $this->entities['comision_relacionada']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['comision_relacionada']->relations['organizacion']->parentId = 'sede';

        $this->entities['comision_relacionada']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['comision_relacionada']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['comision_relacionada']->relations['relacion'] = EntityRelation::getInstance('relacion', 'comision', 'id');

        $this->entities['comision_relacionada']->relations['calendario_rel'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['comision_relacionada']->relations['calendario_rel']->parentId = 'relacion';

        $this->entities['comision_relacionada']->relations['comision_siguiente_rel'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['comision_relacionada']->relations['comision_siguiente_rel']->parentId = 'relacion';

        $this->entities['comision_relacionada']->relations['modalidad_rel'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['comision_relacionada']->relations['modalidad_rel']->parentId = 'relacion';

        $this->entities['comision_relacionada']->relations['planificacion_rel'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['comision_relacionada']->relations['planificacion_rel']->parentId = 'relacion';

        $this->entities['comision_relacionada']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['comision_relacionada']->relations['plan_pla']->parentId = 'planificacion_rel';

        $this->entities['comision_relacionada']->relations['sede_rel'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['comision_relacionada']->relations['sede_rel']->parentId = 'relacion';

        $this->entities['comision_relacionada']->relations['centro_educativo_sed'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['comision_relacionada']->relations['centro_educativo_sed']->parentId = 'sede_rel';

        $this->entities['comision_relacionada']->relations['domicilio_cen1'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['comision_relacionada']->relations['domicilio_cen1']->parentId = 'centro_educativo_sed';

        $this->entities['comision_relacionada']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['comision_relacionada']->relations['domicilio_sed']->parentId = 'sede_rel';

        $this->entities['comision_relacionada']->relations['organizacion_sed'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['comision_relacionada']->relations['organizacion_sed']->parentId = 'sede_rel';

        $this->entities['comision_relacionada']->relations['tipo_sede_sed'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['comision_relacionada']->relations['tipo_sede_sed']->parentId = 'sede_rel';

        $this->entities['comision_relacionada']->fields['comision'] = Field::getInstance('comision_relacionada', 'comision', 'varchar', 'string');
        $this->entities['comision_relacionada']->fields['comision']->alias = 'com';
        $this->entities['comision_relacionada']->fields['comision']->refEntityName = 'comision';
        $this->entities['comision_relacionada']->fields['comision']->refFieldName = 'id';
        $this->entities['comision_relacionada']->fields['comision']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision_relacionada']->fields['comision']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['comision_relacionada']->fields['id'] = Field::getInstance('comision_relacionada', 'id', 'varchar', 'string');
        $this->entities['comision_relacionada']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision_relacionada']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['comision_relacionada']->fields['relacion'] = Field::getInstance('comision_relacionada', 'relacion', 'varchar', 'string');
        $this->entities['comision_relacionada']->fields['relacion']->alias = 'co1';
        $this->entities['comision_relacionada']->fields['relacion']->refEntityName = 'comision';
        $this->entities['comision_relacionada']->fields['relacion']->refFieldName = 'id';
        $this->entities['comision_relacionada']->fields['relacion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['comision_relacionada']->fields['relacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['contralor'] = EntityMetadata::getInstance('contralor', 'cont');
        $this->entities['contralor']->pk = ['id'];
        $this->entities['contralor']->fk = ['planilla_docente'];
        $this->entities['contralor']->notNull = ['id', 'insertado', 'planilla_docente'];

        $this->entities['contralor']->tree = [];
        $this->entities['contralor']->tree['planilla_docente'] = EntityTree::getInstance('planilla_docente', 'planilla_docente', 'id');

        $this->entities['contralor']->relations = [];
        $this->entities['contralor']->relations['planilla_docente'] = EntityRelation::getInstance('planilla_docente', 'planilla_docente', 'id');

        $this->entities['contralor']->fields['fecha_consejo'] = Field::getInstance('contralor', 'fecha_consejo', 'date', 'DateTime');
        $this->entities['contralor']->fields['fecha_consejo']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['contralor']->fields['fecha_contralor'] = Field::getInstance('contralor', 'fecha_contralor', 'date', 'DateTime');
        $this->entities['contralor']->fields['fecha_contralor']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['contralor']->fields['id'] = Field::getInstance('contralor', 'id', 'varchar', 'string');
        $this->entities['contralor']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['contralor']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['contralor']->fields['insertado'] = Field::getInstance('contralor', 'insertado', 'timestamp', 'DateTime');
        $this->entities['contralor']->fields['insertado']->defaultValue = 'current_timestamp()';
        $this->entities['contralor']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['contralor']->fields['planilla_docente'] = Field::getInstance('contralor', 'planilla_docente', 'varchar', 'string');
        $this->entities['contralor']->fields['planilla_docente']->alias = 'pla';
        $this->entities['contralor']->fields['planilla_docente']->refEntityName = 'planilla_docente';
        $this->entities['contralor']->fields['planilla_docente']->refFieldName = 'id';
        $this->entities['contralor']->fields['planilla_docente']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['contralor']->fields['planilla_docente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['curso'] = EntityMetadata::getInstance('curso', 'curs');
        $this->entities['curso']->pk = ['id'];
        $this->entities['curso']->fk = ['asignatura', 'comision', 'disposicion'];
        $this->entities['curso']->notNull = ['alta', 'comision', 'horas_catedra', 'id'];

        $this->entities['curso']->tree = [];
        $this->entities['curso']->tree['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['curso']->tree['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $this->entities['curso']->tree['comision']->children = [];
        $this->entities['curso']->tree['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['curso']->tree['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['curso']->tree['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['curso']->tree['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['curso']->tree['comision']->children['planificacion']->children = [];
        $this->entities['curso']->tree['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['curso']->tree['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['curso']->tree['comision']->children['sede']->children = [];
        $this->entities['curso']->tree['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['curso']->tree['comision']->children['sede']->children['centro_educativo']->children = [];
        $this->entities['curso']->tree['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['curso']->tree['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['curso']->tree['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['curso']->tree['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['curso']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['curso']->tree['disposicion']->children = [];
        $this->entities['curso']->tree['disposicion']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['curso']->tree['disposicion']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['curso']->tree['disposicion']->children['planificacion_dis']->children = [];
        $this->entities['curso']->tree['disposicion']->children['planificacion_dis']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');



        $this->entities['curso']->relations = [];
        $this->entities['curso']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['curso']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');

        $this->entities['curso']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['curso']->relations['calendario']->parentId = 'comision';

        $this->entities['curso']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['curso']->relations['comision_siguiente']->parentId = 'comision';

        $this->entities['curso']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['curso']->relations['modalidad']->parentId = 'comision';

        $this->entities['curso']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['curso']->relations['planificacion']->parentId = 'comision';

        $this->entities['curso']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['curso']->relations['plan']->parentId = 'planificacion';

        $this->entities['curso']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['curso']->relations['sede']->parentId = 'comision';

        $this->entities['curso']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['curso']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['curso']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['curso']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['curso']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['curso']->relations['domicilio']->parentId = 'sede';

        $this->entities['curso']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['curso']->relations['organizacion']->parentId = 'sede';

        $this->entities['curso']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['curso']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['curso']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $this->entities['curso']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['curso']->relations['asignatura_dis']->parentId = 'disposicion';

        $this->entities['curso']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['curso']->relations['planificacion_dis']->parentId = 'disposicion';

        $this->entities['curso']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['curso']->relations['plan_pla']->parentId = 'planificacion_dis';

        $this->entities['curso']->om = [];
        $this->entities['curso']->om['Calificacion_'] = EntityRef::getInstance('curso', 'calificacion');
        $this->entities['curso']->om['Horario_'] = EntityRef::getInstance('curso', 'horario');
        $this->entities['curso']->om['Toma_'] = EntityRef::getInstance('curso', 'toma');
        $this->entities['curso']->fields['alta'] = Field::getInstance('curso', 'alta', 'timestamp', 'DateTime');
        $this->entities['curso']->fields['alta']->defaultValue = 'current_timestamp()';
        $this->entities['curso']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['curso']->fields['asignatura'] = Field::getInstance('curso', 'asignatura', 'varchar', 'string');
        $this->entities['curso']->fields['asignatura']->alias = 'asi';
        $this->entities['curso']->fields['asignatura']->refEntityName = 'asignatura';
        $this->entities['curso']->fields['asignatura']->refFieldName = 'id';
        $this->entities['curso']->fields['asignatura']->checks = [
            'type' => 'string',
        ];
        $this->entities['curso']->fields['asignatura']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['curso']->fields['codigo'] = Field::getInstance('curso', 'codigo', 'varchar', 'string');
        $this->entities['curso']->fields['codigo']->checks = [
            'type' => 'string',
        ];
        $this->entities['curso']->fields['codigo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['curso']->fields['comision'] = Field::getInstance('curso', 'comision', 'varchar', 'string');
        $this->entities['curso']->fields['comision']->alias = 'com';
        $this->entities['curso']->fields['comision']->refEntityName = 'comision';
        $this->entities['curso']->fields['comision']->refFieldName = 'id';
        $this->entities['curso']->fields['comision']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['curso']->fields['comision']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['curso']->fields['descripcion_horario'] = Field::getInstance('curso', 'descripcion_horario', 'varchar', 'string');
        $this->entities['curso']->fields['descripcion_horario']->checks = [
            'type' => 'string',
        ];
        $this->entities['curso']->fields['descripcion_horario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['curso']->fields['disposicion'] = Field::getInstance('curso', 'disposicion', 'varchar', 'string');
        $this->entities['curso']->fields['disposicion']->alias = 'dis';
        $this->entities['curso']->fields['disposicion']->refEntityName = 'disposicion';
        $this->entities['curso']->fields['disposicion']->refFieldName = 'id';
        $this->entities['curso']->fields['disposicion']->checks = [
            'type' => 'string',
        ];
        $this->entities['curso']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['curso']->fields['horas_catedra'] = Field::getInstance('curso', 'horas_catedra', 'int', 'int');
        $this->entities['curso']->fields['horas_catedra']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['curso']->fields['id'] = Field::getInstance('curso', 'id', 'varchar', 'string');
        $this->entities['curso']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['curso']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['curso']->fields['ige'] = Field::getInstance('curso', 'ige', 'varchar', 'string');
        $this->entities['curso']->fields['ige']->checks = [
            'type' => 'string',
        ];
        $this->entities['curso']->fields['ige']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['curso']->fields['observaciones'] = Field::getInstance('curso', 'observaciones', 'varchar', 'string');
        $this->entities['curso']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['curso']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['designacion'] = EntityMetadata::getInstance('designacion', 'desi');
        $this->entities['designacion']->pk = ['id'];
        $this->entities['designacion']->fk = ['cargo', 'persona', 'sede'];
        $this->entities['designacion']->notNull = ['alta', 'cargo', 'id', 'persona', 'sede'];

        $this->entities['designacion']->tree = [];
        $this->entities['designacion']->tree['cargo'] = EntityTree::getInstance('cargo', 'cargo', 'id');

        $this->entities['designacion']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['designacion']->tree['persona']->children = [];
        $this->entities['designacion']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['designacion']->tree['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['designacion']->tree['sede']->children = [];
        $this->entities['designacion']->tree['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['designacion']->tree['sede']->children['centro_educativo']->children = [];
        $this->entities['designacion']->tree['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['designacion']->tree['sede']->children['domicilio_sed'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['designacion']->tree['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['designacion']->tree['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');


        $this->entities['designacion']->relations = [];
        $this->entities['designacion']->relations['cargo'] = EntityRelation::getInstance('cargo', 'cargo', 'id');

        $this->entities['designacion']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $this->entities['designacion']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['designacion']->relations['domicilio']->parentId = 'persona';

        $this->entities['designacion']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');

        $this->entities['designacion']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['designacion']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['designacion']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['designacion']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['designacion']->relations['domicilio_sed'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['designacion']->relations['domicilio_sed']->parentId = 'sede';

        $this->entities['designacion']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['designacion']->relations['organizacion']->parentId = 'sede';

        $this->entities['designacion']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['designacion']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['designacion']->fields['alta'] = Field::getInstance('designacion', 'alta', 'timestamp', 'DateTime');
        $this->entities['designacion']->fields['alta']->defaultValue = 'current_timestamp()';
        $this->entities['designacion']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['designacion']->fields['cargo'] = Field::getInstance('designacion', 'cargo', 'varchar', 'string');
        $this->entities['designacion']->fields['cargo']->alias = 'car';
        $this->entities['designacion']->fields['cargo']->refEntityName = 'cargo';
        $this->entities['designacion']->fields['cargo']->refFieldName = 'id';
        $this->entities['designacion']->fields['cargo']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['designacion']->fields['cargo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['designacion']->fields['desde'] = Field::getInstance('designacion', 'desde', 'date', 'DateTime');
        $this->entities['designacion']->fields['desde']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['designacion']->fields['hasta'] = Field::getInstance('designacion', 'hasta', 'date', 'DateTime');
        $this->entities['designacion']->fields['hasta']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['designacion']->fields['id'] = Field::getInstance('designacion', 'id', 'varchar', 'string');
        $this->entities['designacion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['designacion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['designacion']->fields['persona'] = Field::getInstance('designacion', 'persona', 'varchar', 'string');
        $this->entities['designacion']->fields['persona']->alias = 'per';
        $this->entities['designacion']->fields['persona']->refEntityName = 'persona';
        $this->entities['designacion']->fields['persona']->refFieldName = 'id';
        $this->entities['designacion']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['designacion']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['designacion']->fields['pfid'] = Field::getInstance('designacion', 'pfid', 'varchar', 'string');
        $this->entities['designacion']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $this->entities['designacion']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['designacion']->fields['sede'] = Field::getInstance('designacion', 'sede', 'varchar', 'string');
        $this->entities['designacion']->fields['sede']->alias = 'sed';
        $this->entities['designacion']->fields['sede']->refEntityName = 'sede';
        $this->entities['designacion']->fields['sede']->refFieldName = 'id';
        $this->entities['designacion']->fields['sede']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['designacion']->fields['sede']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['detalle_persona'] = EntityMetadata::getInstance('detalle_persona', 'deta');
        $this->entities['detalle_persona']->pk = ['id'];
        $this->entities['detalle_persona']->fk = ['archivo', 'persona'];
        $this->entities['detalle_persona']->notNull = ['creado', 'descripcion', 'id', 'persona'];

        $this->entities['detalle_persona']->tree = [];
        $this->entities['detalle_persona']->tree['archivo'] = EntityTree::getInstance('archivo', 'file', 'id');

        $this->entities['detalle_persona']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['detalle_persona']->tree['persona']->children = [];
        $this->entities['detalle_persona']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['detalle_persona']->relations = [];
        $this->entities['detalle_persona']->relations['archivo'] = EntityRelation::getInstance('archivo', 'file', 'id');

        $this->entities['detalle_persona']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $this->entities['detalle_persona']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['detalle_persona']->relations['domicilio']->parentId = 'persona';

        $this->entities['detalle_persona']->fields['archivo'] = Field::getInstance('detalle_persona', 'archivo', 'varchar', 'string');
        $this->entities['detalle_persona']->fields['archivo']->alias = 'fil';
        $this->entities['detalle_persona']->fields['archivo']->refEntityName = 'file';
        $this->entities['detalle_persona']->fields['archivo']->refFieldName = 'id';
        $this->entities['detalle_persona']->fields['archivo']->checks = [
            'type' => 'string',
        ];
        $this->entities['detalle_persona']->fields['archivo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['detalle_persona']->fields['asunto'] = Field::getInstance('detalle_persona', 'asunto', 'varchar', 'string');
        $this->entities['detalle_persona']->fields['asunto']->checks = [
            'type' => 'string',
        ];
        $this->entities['detalle_persona']->fields['asunto']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['detalle_persona']->fields['creado'] = Field::getInstance('detalle_persona', 'creado', 'timestamp', 'DateTime');
        $this->entities['detalle_persona']->fields['creado']->defaultValue = 'current_timestamp()';
        $this->entities['detalle_persona']->fields['creado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['detalle_persona']->fields['descripcion'] = Field::getInstance('detalle_persona', 'descripcion', 'text', 'string');
        $this->entities['detalle_persona']->fields['descripcion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['detalle_persona']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['detalle_persona']->fields['fecha'] = Field::getInstance('detalle_persona', 'fecha', 'date', 'DateTime');
        $this->entities['detalle_persona']->fields['fecha']->defaultValue = 'curdate()';
        $this->entities['detalle_persona']->fields['fecha']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['detalle_persona']->fields['id'] = Field::getInstance('detalle_persona', 'id', 'varchar', 'string');
        $this->entities['detalle_persona']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['detalle_persona']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['detalle_persona']->fields['persona'] = Field::getInstance('detalle_persona', 'persona', 'varchar', 'string');
        $this->entities['detalle_persona']->fields['persona']->alias = 'per';
        $this->entities['detalle_persona']->fields['persona']->refEntityName = 'persona';
        $this->entities['detalle_persona']->fields['persona']->refFieldName = 'id';
        $this->entities['detalle_persona']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['detalle_persona']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['detalle_persona']->fields['tipo'] = Field::getInstance('detalle_persona', 'tipo', 'varchar', 'string');
        $this->entities['detalle_persona']->fields['tipo']->checks = [
            'type' => 'string',
        ];
        $this->entities['detalle_persona']->fields['tipo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['dia'] = EntityMetadata::getInstance('dia', 'dia');
        $this->entities['dia']->pk = ['id'];
        $this->entities['dia']->unique = ['dia', 'numero'];
        $this->entities['dia']->notNull = ['dia', 'id', 'numero'];

        $this->entities['dia']->om = [];
        $this->entities['dia']->om['Horario_'] = EntityRef::getInstance('dia', 'horario');
        $this->entities['dia']->fields['dia'] = Field::getInstance('dia', 'dia', 'varchar', 'string');
        $this->entities['dia']->fields['dia']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['dia']->fields['dia']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['dia']->fields['id'] = Field::getInstance('dia', 'id', 'varchar', 'string');
        $this->entities['dia']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['dia']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['dia']->fields['numero'] = Field::getInstance('dia', 'numero', 'smallint', 'int');
        $this->entities['dia']->fields['numero']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['disposicion'] = EntityMetadata::getInstance('disposicion', 'disp');
        $this->entities['disposicion']->pk = ['id'];
        $this->entities['disposicion']->fk = ['asignatura', 'planificacion'];
        $this->entities['disposicion']->notNull = ['asignatura', 'id', 'planificacion'];

        $this->entities['disposicion']->tree = [];
        $this->entities['disposicion']->tree['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['disposicion']->tree['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['disposicion']->tree['planificacion']->children = [];
        $this->entities['disposicion']->tree['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['disposicion']->relations = [];
        $this->entities['disposicion']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['disposicion']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');

        $this->entities['disposicion']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['disposicion']->relations['plan']->parentId = 'planificacion';

        $this->entities['disposicion']->om = [];
        $this->entities['disposicion']->om['Calificacion_'] = EntityRef::getInstance('disposicion', 'calificacion');
        $this->entities['disposicion']->om['Curso_'] = EntityRef::getInstance('disposicion', 'curso');
        $this->entities['disposicion']->om['DisposicionPendiente_'] = EntityRef::getInstance('disposicion', 'disposicion_pendiente');
        $this->entities['disposicion']->om['DistribucionHoraria_'] = EntityRef::getInstance('disposicion', 'distribucion_horaria');
        $this->entities['disposicion']->fields['asignatura'] = Field::getInstance('disposicion', 'asignatura', 'varchar', 'string');
        $this->entities['disposicion']->fields['asignatura']->alias = 'asi';
        $this->entities['disposicion']->fields['asignatura']->refEntityName = 'asignatura';
        $this->entities['disposicion']->fields['asignatura']->refFieldName = 'id';
        $this->entities['disposicion']->fields['asignatura']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['disposicion']->fields['asignatura']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['disposicion']->fields['id'] = Field::getInstance('disposicion', 'id', 'varchar', 'string');
        $this->entities['disposicion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['disposicion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['disposicion']->fields['orden_informe_coordinacion_distrital'] = Field::getInstance('disposicion', 'orden_informe_coordinacion_distrital', 'int', 'int');
        $this->entities['disposicion']->fields['orden_informe_coordinacion_distrital']->checks = [
            'type' => 'int',
        ];
        $this->entities['disposicion']->fields['planificacion'] = Field::getInstance('disposicion', 'planificacion', 'varchar', 'string');
        $this->entities['disposicion']->fields['planificacion']->alias = 'pla';
        $this->entities['disposicion']->fields['planificacion']->refEntityName = 'planificacion';
        $this->entities['disposicion']->fields['planificacion']->refFieldName = 'id';
        $this->entities['disposicion']->fields['planificacion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['disposicion']->fields['planificacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['disposicion_pendiente'] = EntityMetadata::getInstance('disposicion_pendiente', 'dis1');
        $this->entities['disposicion_pendiente']->pk = ['id'];
        $this->entities['disposicion_pendiente']->fk = ['alumno', 'disposicion'];
        $this->entities['disposicion_pendiente']->notNull = ['alumno', 'disposicion', 'id'];

        $this->entities['disposicion_pendiente']->tree = [];
        $this->entities['disposicion_pendiente']->tree['alumno'] = EntityTree::getInstance('alumno', 'alumno', 'id');
        $this->entities['disposicion_pendiente']->tree['alumno']->children = [];
        $this->entities['disposicion_pendiente']->tree['alumno']->children['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['disposicion_pendiente']->tree['alumno']->children['persona']->children = [];
        $this->entities['disposicion_pendiente']->tree['alumno']->children['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['disposicion_pendiente']->tree['alumno']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $this->entities['disposicion_pendiente']->tree['alumno']->children['resolucion_inscripcion'] = EntityTree::getInstance('resolucion_inscripcion', 'resolucion', 'id');


        $this->entities['disposicion_pendiente']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['disposicion_pendiente']->tree['disposicion']->children = [];
        $this->entities['disposicion_pendiente']->tree['disposicion']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['disposicion_pendiente']->tree['disposicion']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['disposicion_pendiente']->tree['disposicion']->children['planificacion']->children = [];
        $this->entities['disposicion_pendiente']->tree['disposicion']->children['planificacion']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');



        $this->entities['disposicion_pendiente']->relations = [];
        $this->entities['disposicion_pendiente']->relations['alumno'] = EntityRelation::getInstance('alumno', 'alumno', 'id');

        $this->entities['disposicion_pendiente']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');
        $this->entities['disposicion_pendiente']->relations['persona']->parentId = 'alumno';

        $this->entities['disposicion_pendiente']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['disposicion_pendiente']->relations['domicilio']->parentId = 'persona';

        $this->entities['disposicion_pendiente']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['disposicion_pendiente']->relations['plan']->parentId = 'alumno';

        $this->entities['disposicion_pendiente']->relations['resolucion_inscripcion'] = EntityRelation::getInstance('resolucion_inscripcion', 'resolucion', 'id');
        $this->entities['disposicion_pendiente']->relations['resolucion_inscripcion']->parentId = 'alumno';

        $this->entities['disposicion_pendiente']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $this->entities['disposicion_pendiente']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['disposicion_pendiente']->relations['asignatura']->parentId = 'disposicion';

        $this->entities['disposicion_pendiente']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['disposicion_pendiente']->relations['planificacion']->parentId = 'disposicion';

        $this->entities['disposicion_pendiente']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['disposicion_pendiente']->relations['plan_pla']->parentId = 'planificacion';

        $this->entities['disposicion_pendiente']->fields['alumno'] = Field::getInstance('disposicion_pendiente', 'alumno', 'varchar', 'string');
        $this->entities['disposicion_pendiente']->fields['alumno']->alias = 'alu';
        $this->entities['disposicion_pendiente']->fields['alumno']->refEntityName = 'alumno';
        $this->entities['disposicion_pendiente']->fields['alumno']->refFieldName = 'id';
        $this->entities['disposicion_pendiente']->fields['alumno']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['disposicion_pendiente']->fields['alumno']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['disposicion_pendiente']->fields['disposicion'] = Field::getInstance('disposicion_pendiente', 'disposicion', 'varchar', 'string');
        $this->entities['disposicion_pendiente']->fields['disposicion']->alias = 'dis';
        $this->entities['disposicion_pendiente']->fields['disposicion']->refEntityName = 'disposicion';
        $this->entities['disposicion_pendiente']->fields['disposicion']->refFieldName = 'id';
        $this->entities['disposicion_pendiente']->fields['disposicion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['disposicion_pendiente']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['disposicion_pendiente']->fields['id'] = Field::getInstance('disposicion_pendiente', 'id', 'varchar', 'string');
        $this->entities['disposicion_pendiente']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['disposicion_pendiente']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['disposicion_pendiente']->fields['modo'] = Field::getInstance('disposicion_pendiente', 'modo', 'varchar', 'string');
        $this->entities['disposicion_pendiente']->fields['modo']->checks = [
            'type' => 'string',
        ];
        $this->entities['disposicion_pendiente']->fields['modo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['distribucion_horaria'] = EntityMetadata::getInstance('distribucion_horaria', 'dist');
        $this->entities['distribucion_horaria']->pk = ['id'];
        $this->entities['distribucion_horaria']->fk = ['disposicion'];
        $this->entities['distribucion_horaria']->notNull = ['dia', 'horas_catedra', 'id'];

        $this->entities['distribucion_horaria']->tree = [];
        $this->entities['distribucion_horaria']->tree['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['distribucion_horaria']->tree['disposicion']->children = [];
        $this->entities['distribucion_horaria']->tree['disposicion']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['distribucion_horaria']->tree['disposicion']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['distribucion_horaria']->tree['disposicion']->children['planificacion']->children = [];
        $this->entities['distribucion_horaria']->tree['disposicion']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');



        $this->entities['distribucion_horaria']->relations = [];
        $this->entities['distribucion_horaria']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');

        $this->entities['distribucion_horaria']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['distribucion_horaria']->relations['asignatura']->parentId = 'disposicion';

        $this->entities['distribucion_horaria']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['distribucion_horaria']->relations['planificacion']->parentId = 'disposicion';

        $this->entities['distribucion_horaria']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['distribucion_horaria']->relations['plan']->parentId = 'planificacion';

        $this->entities['distribucion_horaria']->fields['dia'] = Field::getInstance('distribucion_horaria', 'dia', 'int', 'int');
        $this->entities['distribucion_horaria']->fields['dia']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['distribucion_horaria']->fields['disposicion'] = Field::getInstance('distribucion_horaria', 'disposicion', 'varchar', 'string');
        $this->entities['distribucion_horaria']->fields['disposicion']->alias = 'dis';
        $this->entities['distribucion_horaria']->fields['disposicion']->refEntityName = 'disposicion';
        $this->entities['distribucion_horaria']->fields['disposicion']->refFieldName = 'id';
        $this->entities['distribucion_horaria']->fields['disposicion']->checks = [
            'type' => 'string',
        ];
        $this->entities['distribucion_horaria']->fields['disposicion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['distribucion_horaria']->fields['horas_catedra'] = Field::getInstance('distribucion_horaria', 'horas_catedra', 'int', 'int');
        $this->entities['distribucion_horaria']->fields['horas_catedra']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['distribucion_horaria']->fields['id'] = Field::getInstance('distribucion_horaria', 'id', 'varchar', 'string');
        $this->entities['distribucion_horaria']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['distribucion_horaria']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['domicilio'] = EntityMetadata::getInstance('domicilio', 'domi');
        $this->entities['domicilio']->pk = ['id'];
        $this->entities['domicilio']->notNull = ['calle', 'id', 'localidad', 'numero'];

        $this->entities['domicilio']->om = [];
        $this->entities['domicilio']->om['CentroEducativo_'] = EntityRef::getInstance('domicilio', 'centro_educativo');
        $this->entities['domicilio']->om['Persona_'] = EntityRef::getInstance('domicilio', 'persona');
        $this->entities['domicilio']->om['Sede_'] = EntityRef::getInstance('domicilio', 'sede');
        $this->entities['domicilio']->fields['barrio'] = Field::getInstance('domicilio', 'barrio', 'varchar', 'string');
        $this->entities['domicilio']->fields['barrio']->checks = [
            'type' => 'string',
        ];
        $this->entities['domicilio']->fields['barrio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['domicilio']->fields['calle'] = Field::getInstance('domicilio', 'calle', 'varchar', 'string');
        $this->entities['domicilio']->fields['calle']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['domicilio']->fields['calle']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['domicilio']->fields['departamento'] = Field::getInstance('domicilio', 'departamento', 'varchar', 'string');
        $this->entities['domicilio']->fields['departamento']->checks = [
            'type' => 'string',
        ];
        $this->entities['domicilio']->fields['departamento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['domicilio']->fields['entre'] = Field::getInstance('domicilio', 'entre', 'varchar', 'string');
        $this->entities['domicilio']->fields['entre']->checks = [
            'type' => 'string',
        ];
        $this->entities['domicilio']->fields['entre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['domicilio']->fields['id'] = Field::getInstance('domicilio', 'id', 'varchar', 'string');
        $this->entities['domicilio']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['domicilio']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['domicilio']->fields['localidad'] = Field::getInstance('domicilio', 'localidad', 'varchar', 'string');
        $this->entities['domicilio']->fields['localidad']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['domicilio']->fields['localidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['domicilio']->fields['numero'] = Field::getInstance('domicilio', 'numero', 'varchar', 'string');
        $this->entities['domicilio']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['domicilio']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['domicilio']->fields['piso'] = Field::getInstance('domicilio', 'piso', 'varchar', 'string');
        $this->entities['domicilio']->fields['piso']->checks = [
            'type' => 'string',
        ];
        $this->entities['domicilio']->fields['piso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['email'] = EntityMetadata::getInstance('email', 'emai');
        $this->entities['email']->pk = ['id'];
        $this->entities['email']->fk = ['persona'];
        $this->entities['email']->notNull = ['email', 'id', 'insertado', 'persona', 'verificado'];

        $this->entities['email']->tree = [];
        $this->entities['email']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['email']->tree['persona']->children = [];
        $this->entities['email']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['email']->relations = [];
        $this->entities['email']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $this->entities['email']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['email']->relations['domicilio']->parentId = 'persona';

        $this->entities['email']->fields['eliminado'] = Field::getInstance('email', 'eliminado', 'timestamp', 'DateTime');
        $this->entities['email']->fields['eliminado']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['email']->fields['email'] = Field::getInstance('email', 'email', 'varchar', 'string');
        $this->entities['email']->fields['email']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['email']->fields['email']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['email']->fields['id'] = Field::getInstance('email', 'id', 'varchar', 'string');
        $this->entities['email']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['email']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['email']->fields['insertado'] = Field::getInstance('email', 'insertado', 'timestamp', 'DateTime');
        $this->entities['email']->fields['insertado']->defaultValue = 'current_timestamp()';
        $this->entities['email']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['email']->fields['persona'] = Field::getInstance('email', 'persona', 'varchar', 'string');
        $this->entities['email']->fields['persona']->alias = 'per';
        $this->entities['email']->fields['persona']->refEntityName = 'persona';
        $this->entities['email']->fields['persona']->refFieldName = 'id';
        $this->entities['email']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['email']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['email']->fields['verificado'] = Field::getInstance('email', 'verificado', 'tinyint', 'int');
        $this->entities['email']->fields['verificado']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['file'] = EntityMetadata::getInstance('file', 'file');
        $this->entities['file']->pk = ['id'];
        $this->entities['file']->notNull = ['content', 'created', 'id', 'name', 'size', 'type'];

        $this->entities['file']->om = [];
        $this->entities['file']->om['DetallePersona_archivo_'] = EntityRef::getInstance('archivo', 'detalle_persona');
        $this->entities['file']->fields['content'] = Field::getInstance('file', 'content', 'varchar', 'string');
        $this->entities['file']->fields['content']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['file']->fields['content']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['file']->fields['created'] = Field::getInstance('file', 'created', 'timestamp', 'DateTime');
        $this->entities['file']->fields['created']->defaultValue = 'current_timestamp()';
        $this->entities['file']->fields['created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['file']->fields['id'] = Field::getInstance('file', 'id', 'varchar', 'string');
        $this->entities['file']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['file']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['file']->fields['name'] = Field::getInstance('file', 'name', 'varchar', 'string');
        $this->entities['file']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['file']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['file']->fields['size'] = Field::getInstance('file', 'size', 'int', 'int');
        $this->entities['file']->fields['size']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['file']->fields['type'] = Field::getInstance('file', 'type', 'varchar', 'string');
        $this->entities['file']->fields['type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['file']->fields['type']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['horario'] = EntityMetadata::getInstance('horario', 'hora');
        $this->entities['horario']->pk = ['id'];
        $this->entities['horario']->fk = ['curso', 'dia'];
        $this->entities['horario']->notNull = ['curso', 'dia', 'hora_fin', 'hora_inicio', 'id'];

        $this->entities['horario']->tree = [];
        $this->entities['horario']->tree['curso'] = EntityTree::getInstance('curso', 'curso', 'id');
        $this->entities['horario']->tree['curso']->children = [];
        $this->entities['horario']->tree['curso']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['horario']->tree['curso']->children['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $this->entities['horario']->tree['curso']->children['comision']->children = [];
        $this->entities['horario']->tree['curso']->children['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['horario']->tree['curso']->children['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['horario']->tree['curso']->children['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['horario']->tree['curso']->children['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['horario']->tree['curso']->children['comision']->children['planificacion']->children = [];
        $this->entities['horario']->tree['curso']->children['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['horario']->tree['curso']->children['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['horario']->tree['curso']->children['comision']->children['sede']->children = [];
        $this->entities['horario']->tree['curso']->children['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['horario']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children = [];
        $this->entities['horario']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['horario']->tree['curso']->children['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['horario']->tree['curso']->children['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['horario']->tree['curso']->children['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['horario']->tree['curso']->children['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['horario']->tree['curso']->children['disposicion']->children = [];
        $this->entities['horario']->tree['curso']->children['disposicion']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['horario']->tree['curso']->children['disposicion']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['horario']->tree['curso']->children['disposicion']->children['planificacion_dis']->children = [];
        $this->entities['horario']->tree['curso']->children['disposicion']->children['planificacion_dis']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');




        $this->entities['horario']->tree['dia'] = EntityTree::getInstance('dia', 'dia', 'id');

        $this->entities['horario']->relations = [];
        $this->entities['horario']->relations['curso'] = EntityRelation::getInstance('curso', 'curso', 'id');

        $this->entities['horario']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['horario']->relations['asignatura']->parentId = 'curso';

        $this->entities['horario']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');
        $this->entities['horario']->relations['comision']->parentId = 'curso';

        $this->entities['horario']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['horario']->relations['calendario']->parentId = 'comision';

        $this->entities['horario']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['horario']->relations['comision_siguiente']->parentId = 'comision';

        $this->entities['horario']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['horario']->relations['modalidad']->parentId = 'comision';

        $this->entities['horario']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['horario']->relations['planificacion']->parentId = 'comision';

        $this->entities['horario']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['horario']->relations['plan']->parentId = 'planificacion';

        $this->entities['horario']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['horario']->relations['sede']->parentId = 'comision';

        $this->entities['horario']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['horario']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['horario']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['horario']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['horario']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['horario']->relations['domicilio']->parentId = 'sede';

        $this->entities['horario']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['horario']->relations['organizacion']->parentId = 'sede';

        $this->entities['horario']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['horario']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['horario']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['horario']->relations['disposicion']->parentId = 'curso';

        $this->entities['horario']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['horario']->relations['asignatura_dis']->parentId = 'disposicion';

        $this->entities['horario']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['horario']->relations['planificacion_dis']->parentId = 'disposicion';

        $this->entities['horario']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['horario']->relations['plan_pla']->parentId = 'planificacion_dis';

        $this->entities['horario']->relations['dia'] = EntityRelation::getInstance('dia', 'dia', 'id');

        $this->entities['horario']->fields['curso'] = Field::getInstance('horario', 'curso', 'varchar', 'string');
        $this->entities['horario']->fields['curso']->alias = 'cur';
        $this->entities['horario']->fields['curso']->refEntityName = 'curso';
        $this->entities['horario']->fields['curso']->refFieldName = 'id';
        $this->entities['horario']->fields['curso']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['horario']->fields['curso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['horario']->fields['dia'] = Field::getInstance('horario', 'dia', 'varchar', 'string');
        $this->entities['horario']->fields['dia']->alias = 'dia';
        $this->entities['horario']->fields['dia']->refEntityName = 'dia';
        $this->entities['horario']->fields['dia']->refFieldName = 'id';
        $this->entities['horario']->fields['dia']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['horario']->fields['dia']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['horario']->fields['hora_fin'] = Field::getInstance('horario', 'hora_fin', 'time', 'DateTime');
        $this->entities['horario']->fields['hora_fin']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['horario']->fields['hora_inicio'] = Field::getInstance('horario', 'hora_inicio', 'time', 'DateTime');
        $this->entities['horario']->fields['hora_inicio']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['horario']->fields['id'] = Field::getInstance('horario', 'id', 'varchar', 'string');
        $this->entities['horario']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['horario']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['modalidad'] = EntityMetadata::getInstance('modalidad', 'moda');
        $this->entities['modalidad']->pk = ['id'];
        $this->entities['modalidad']->unique = ['nombre'];
        $this->entities['modalidad']->notNull = ['id', 'nombre'];

        $this->entities['modalidad']->om = [];
        $this->entities['modalidad']->om['Comision_'] = EntityRef::getInstance('modalidad', 'comision');
        $this->entities['modalidad']->fields['id'] = Field::getInstance('modalidad', 'id', 'varchar', 'string');
        $this->entities['modalidad']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['modalidad']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['modalidad']->fields['nombre'] = Field::getInstance('modalidad', 'nombre', 'varchar', 'string');
        $this->entities['modalidad']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['modalidad']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['modalidad']->fields['pfid'] = Field::getInstance('modalidad', 'pfid', 'varchar', 'string');
        $this->entities['modalidad']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $this->entities['modalidad']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona'] = EntityMetadata::getInstance('persona', 'pers');
        $this->entities['persona']->pk = ['id'];
        $this->entities['persona']->fk = ['domicilio'];
        $this->entities['persona']->unique = ['cuil', 'email_abc', 'numero_documento'];
        $this->entities['persona']->notNull = ['alta', 'email_verificado', 'id', 'info_verificada', 'nombres', 'numero_documento', 'telefono_verificado'];

        $this->entities['persona']->tree = [];
        $this->entities['persona']->tree['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['persona']->relations = [];
        $this->entities['persona']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['persona']->oo = [];
        $this->entities['persona']->oo['Alumno_']  = EntityRef::getInstance('persona', 'alumno');

        $this->entities['persona']->om = [];
        $this->entities['persona']->om['Designacion_'] = EntityRef::getInstance('persona', 'designacion');
        $this->entities['persona']->om['DetallePersona_'] = EntityRef::getInstance('persona', 'detalle_persona');
        $this->entities['persona']->om['Email_'] = EntityRef::getInstance('persona', 'email');
        $this->entities['persona']->om['Telefono_'] = EntityRef::getInstance('persona', 'telefono');
        $this->entities['persona']->om['Toma_docente_'] = EntityRef::getInstance('docente', 'toma');
        $this->entities['persona']->om['Toma_reemplazo_'] = EntityRef::getInstance('reemplazo', 'toma');
        $this->entities['persona']->fields['alta'] = Field::getInstance('persona', 'alta', 'timestamp', 'DateTime');
        $this->entities['persona']->fields['alta']->defaultValue = 'current_timestamp()';
        $this->entities['persona']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['persona']->fields['anio_nacimiento'] = Field::getInstance('persona', 'anio_nacimiento', 'smallint', 'int');
        $this->entities['persona']->fields['anio_nacimiento']->checks = [
            'type' => 'int',
        ];
        $this->entities['persona']->fields['apellidos'] = Field::getInstance('persona', 'apellidos', 'varchar', 'string');
        $this->entities['persona']->fields['apellidos']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['apellidos']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['apodo'] = Field::getInstance('persona', 'apodo', 'varchar', 'string');
        $this->entities['persona']->fields['apodo']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['apodo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['codigo_area'] = Field::getInstance('persona', 'codigo_area', 'varchar', 'string');
        $this->entities['persona']->fields['codigo_area']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['codigo_area']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['cuil'] = Field::getInstance('persona', 'cuil', 'varchar', 'string');
        $this->entities['persona']->fields['cuil']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['cuil']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['cuil1'] = Field::getInstance('persona', 'cuil1', 'tinyint', 'int');
        $this->entities['persona']->fields['cuil1']->checks = [
            'type' => 'int',
        ];
        $this->entities['persona']->fields['cuil2'] = Field::getInstance('persona', 'cuil2', 'tinyint', 'int');
        $this->entities['persona']->fields['cuil2']->checks = [
            'type' => 'int',
        ];
        $this->entities['persona']->fields['departamento'] = Field::getInstance('persona', 'departamento', 'varchar', 'string');
        $this->entities['persona']->fields['departamento']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['departamento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['descripcion_domicilio'] = Field::getInstance('persona', 'descripcion_domicilio', 'varchar', 'string');
        $this->entities['persona']->fields['descripcion_domicilio']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['descripcion_domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['dia_nacimiento'] = Field::getInstance('persona', 'dia_nacimiento', 'tinyint', 'int');
        $this->entities['persona']->fields['dia_nacimiento']->checks = [
            'type' => 'int',
        ];
        $this->entities['persona']->fields['domicilio'] = Field::getInstance('persona', 'domicilio', 'varchar', 'string');
        $this->entities['persona']->fields['domicilio']->alias = 'dom';
        $this->entities['persona']->fields['domicilio']->refEntityName = 'domicilio';
        $this->entities['persona']->fields['domicilio']->refFieldName = 'id';
        $this->entities['persona']->fields['domicilio']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['email'] = Field::getInstance('persona', 'email', 'varchar', 'string');
        $this->entities['persona']->fields['email']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['email']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['email_abc'] = Field::getInstance('persona', 'email_abc', 'varchar', 'string');
        $this->entities['persona']->fields['email_abc']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['email_abc']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['email_verificado'] = Field::getInstance('persona', 'email_verificado', 'tinyint', 'int');
        $this->entities['persona']->fields['email_verificado']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['persona']->fields['fecha_nacimiento'] = Field::getInstance('persona', 'fecha_nacimiento', 'date', 'DateTime');
        $this->entities['persona']->fields['fecha_nacimiento']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['persona']->fields['genero'] = Field::getInstance('persona', 'genero', 'varchar', 'string');
        $this->entities['persona']->fields['genero']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['genero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['id'] = Field::getInstance('persona', 'id', 'varchar', 'string');
        $this->entities['persona']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['persona']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['persona']->fields['info_verificada'] = Field::getInstance('persona', 'info_verificada', 'tinyint', 'int');
        $this->entities['persona']->fields['info_verificada']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['persona']->fields['localidad'] = Field::getInstance('persona', 'localidad', 'varchar', 'string');
        $this->entities['persona']->fields['localidad']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['localidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['lugar_nacimiento'] = Field::getInstance('persona', 'lugar_nacimiento', 'varchar', 'string');
        $this->entities['persona']->fields['lugar_nacimiento']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['lugar_nacimiento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['mes_nacimiento'] = Field::getInstance('persona', 'mes_nacimiento', 'tinyint', 'int');
        $this->entities['persona']->fields['mes_nacimiento']->checks = [
            'type' => 'int',
        ];
        $this->entities['persona']->fields['nacionalidad'] = Field::getInstance('persona', 'nacionalidad', 'varchar', 'string');
        $this->entities['persona']->fields['nacionalidad']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['nacionalidad']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['nombres'] = Field::getInstance('persona', 'nombres', 'varchar', 'string');
        $this->entities['persona']->fields['nombres']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['persona']->fields['nombres']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['persona']->fields['numero_documento'] = Field::getInstance('persona', 'numero_documento', 'varchar', 'string');
        $this->entities['persona']->fields['numero_documento']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['persona']->fields['numero_documento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['persona']->fields['partido'] = Field::getInstance('persona', 'partido', 'varchar', 'string');
        $this->entities['persona']->fields['partido']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['partido']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['sexo'] = Field::getInstance('persona', 'sexo', 'tinyint', 'int');
        $this->entities['persona']->fields['sexo']->checks = [
            'type' => 'int',
        ];
        $this->entities['persona']->fields['telefono'] = Field::getInstance('persona', 'telefono', 'varchar', 'string');
        $this->entities['persona']->fields['telefono']->checks = [
            'type' => 'string',
        ];
        $this->entities['persona']->fields['telefono']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['persona']->fields['telefono_verificado'] = Field::getInstance('persona', 'telefono_verificado', 'tinyint', 'int');
        $this->entities['persona']->fields['telefono_verificado']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['plan'] = EntityMetadata::getInstance('plan', 'plan');
        $this->entities['plan']->pk = ['id'];
        $this->entities['plan']->notNull = ['id', 'orientacion'];

        $this->entities['plan']->om = [];
        $this->entities['plan']->om['Alumno_'] = EntityRef::getInstance('plan', 'alumno');
        $this->entities['plan']->om['Planificacion_'] = EntityRef::getInstance('plan', 'planificacion');
        $this->entities['plan']->fields['distribucion_horaria'] = Field::getInstance('plan', 'distribucion_horaria', 'varchar', 'string');
        $this->entities['plan']->fields['distribucion_horaria']->checks = [
            'type' => 'string',
        ];
        $this->entities['plan']->fields['distribucion_horaria']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['plan']->fields['id'] = Field::getInstance('plan', 'id', 'varchar', 'string');
        $this->entities['plan']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['plan']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['plan']->fields['orientacion'] = Field::getInstance('plan', 'orientacion', 'varchar', 'string');
        $this->entities['plan']->fields['orientacion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['plan']->fields['orientacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['plan']->fields['pfid'] = Field::getInstance('plan', 'pfid', 'varchar', 'string');
        $this->entities['plan']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $this->entities['plan']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['plan']->fields['resolucion'] = Field::getInstance('plan', 'resolucion', 'varchar', 'string');
        $this->entities['plan']->fields['resolucion']->checks = [
            'type' => 'string',
        ];
        $this->entities['plan']->fields['resolucion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['planificacion'] = EntityMetadata::getInstance('planificacion', 'pla1');
        $this->entities['planificacion']->pk = ['id'];
        $this->entities['planificacion']->fk = ['plan'];
        $this->entities['planificacion']->notNull = ['anio', 'id', 'plan', 'semestre'];

        $this->entities['planificacion']->tree = [];
        $this->entities['planificacion']->tree['plan'] = EntityTree::getInstance('plan', 'plan', 'id');

        $this->entities['planificacion']->relations = [];
        $this->entities['planificacion']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');

        $this->entities['planificacion']->om = [];
        $this->entities['planificacion']->om['Comision_'] = EntityRef::getInstance('planificacion', 'comision');
        $this->entities['planificacion']->om['Disposicion_'] = EntityRef::getInstance('planificacion', 'disposicion');
        $this->entities['planificacion']->fields['anio'] = Field::getInstance('planificacion', 'anio', 'varchar', 'string');
        $this->entities['planificacion']->fields['anio']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['planificacion']->fields['anio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['planificacion']->fields['id'] = Field::getInstance('planificacion', 'id', 'varchar', 'string');
        $this->entities['planificacion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['planificacion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['planificacion']->fields['pfid'] = Field::getInstance('planificacion', 'pfid', 'varchar', 'string');
        $this->entities['planificacion']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $this->entities['planificacion']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['planificacion']->fields['plan'] = Field::getInstance('planificacion', 'plan', 'varchar', 'string');
        $this->entities['planificacion']->fields['plan']->alias = 'pla';
        $this->entities['planificacion']->fields['plan']->refEntityName = 'plan';
        $this->entities['planificacion']->fields['plan']->refFieldName = 'id';
        $this->entities['planificacion']->fields['plan']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['planificacion']->fields['plan']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['planificacion']->fields['semestre'] = Field::getInstance('planificacion', 'semestre', 'varchar', 'string');
        $this->entities['planificacion']->fields['semestre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['planificacion']->fields['semestre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['planilla_docente'] = EntityMetadata::getInstance('planilla_docente', 'pla2');
        $this->entities['planilla_docente']->pk = ['id'];
        $this->entities['planilla_docente']->notNull = ['id', 'insertado', 'numero'];

        $this->entities['planilla_docente']->om = [];
        $this->entities['planilla_docente']->om['AsignacionPlanillaDocente_'] = EntityRef::getInstance('planilla_docente', 'asignacion_planilla_docente');
        $this->entities['planilla_docente']->om['Contralor_'] = EntityRef::getInstance('planilla_docente', 'contralor');
        $this->entities['planilla_docente']->om['Toma_'] = EntityRef::getInstance('planilla_docente', 'toma');
        $this->entities['planilla_docente']->fields['fecha_consejo'] = Field::getInstance('planilla_docente', 'fecha_consejo', 'date', 'DateTime');
        $this->entities['planilla_docente']->fields['fecha_consejo']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['planilla_docente']->fields['fecha_contralor'] = Field::getInstance('planilla_docente', 'fecha_contralor', 'date', 'DateTime');
        $this->entities['planilla_docente']->fields['fecha_contralor']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['planilla_docente']->fields['id'] = Field::getInstance('planilla_docente', 'id', 'varchar', 'string');
        $this->entities['planilla_docente']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['planilla_docente']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['planilla_docente']->fields['insertado'] = Field::getInstance('planilla_docente', 'insertado', 'timestamp', 'DateTime');
        $this->entities['planilla_docente']->fields['insertado']->defaultValue = 'current_timestamp()';
        $this->entities['planilla_docente']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['planilla_docente']->fields['numero'] = Field::getInstance('planilla_docente', 'numero', 'varchar', 'string');
        $this->entities['planilla_docente']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['planilla_docente']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['planilla_docente']->fields['observaciones'] = Field::getInstance('planilla_docente', 'observaciones', 'text', 'string');
        $this->entities['planilla_docente']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['planilla_docente']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['resolucion'] = EntityMetadata::getInstance('resolucion', 'reso');
        $this->entities['resolucion']->pk = ['id'];
        $this->entities['resolucion']->notNull = ['id', 'numero'];

        $this->entities['resolucion']->om = [];
        $this->entities['resolucion']->om['Alumno_resolucion_inscripcion_'] = EntityRef::getInstance('resolucion_inscripcion', 'alumno');
        $this->entities['resolucion']->fields['anio'] = Field::getInstance('resolucion', 'anio', 'year', 'DateTime');
        $this->entities['resolucion']->fields['anio']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['resolucion']->fields['id'] = Field::getInstance('resolucion', 'id', 'varchar', 'string');
        $this->entities['resolucion']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['resolucion']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['resolucion']->fields['numero'] = Field::getInstance('resolucion', 'numero', 'varchar', 'string');
        $this->entities['resolucion']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['resolucion']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['resolucion']->fields['tipo'] = Field::getInstance('resolucion', 'tipo', 'varchar', 'string');
        $this->entities['resolucion']->fields['tipo']->checks = [
            'type' => 'string',
        ];
        $this->entities['resolucion']->fields['tipo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['sede'] = EntityMetadata::getInstance('sede', 'sede');
        $this->entities['sede']->pk = ['id'];
        $this->entities['sede']->fk = ['centro_educativo', 'domicilio', 'organizacion', 'tipo_sede'];
        $this->entities['sede']->notNull = ['alta', 'id', 'nombre', 'numero'];

        $this->entities['sede']->tree = [];
        $this->entities['sede']->tree['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['sede']->tree['centro_educativo']->children = [];
        $this->entities['sede']->tree['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['sede']->tree['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['sede']->tree['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['sede']->tree['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');

        $this->entities['sede']->relations = [];
        $this->entities['sede']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');

        $this->entities['sede']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['sede']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['sede']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['sede']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');

        $this->entities['sede']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');

        $this->entities['sede']->om = [];
        $this->entities['sede']->om['Comision_'] = EntityRef::getInstance('sede', 'comision');
        $this->entities['sede']->om['Designacion_'] = EntityRef::getInstance('sede', 'designacion');
        $this->entities['sede']->om['Sede_organizacion_'] = EntityRef::getInstance('organizacion', 'sede');
        $this->entities['sede']->fields['alta'] = Field::getInstance('sede', 'alta', 'timestamp', 'DateTime');
        $this->entities['sede']->fields['alta']->defaultValue = 'current_timestamp()';
        $this->entities['sede']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['sede']->fields['baja'] = Field::getInstance('sede', 'baja', 'timestamp', 'DateTime');
        $this->entities['sede']->fields['baja']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['sede']->fields['centro_educativo'] = Field::getInstance('sede', 'centro_educativo', 'varchar', 'string');
        $this->entities['sede']->fields['centro_educativo']->alias = 'cen';
        $this->entities['sede']->fields['centro_educativo']->refEntityName = 'centro_educativo';
        $this->entities['sede']->fields['centro_educativo']->refFieldName = 'id';
        $this->entities['sede']->fields['centro_educativo']->checks = [
            'type' => 'string',
        ];
        $this->entities['sede']->fields['centro_educativo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['sede']->fields['domicilio'] = Field::getInstance('sede', 'domicilio', 'varchar', 'string');
        $this->entities['sede']->fields['domicilio']->alias = 'dom';
        $this->entities['sede']->fields['domicilio']->refEntityName = 'domicilio';
        $this->entities['sede']->fields['domicilio']->refFieldName = 'id';
        $this->entities['sede']->fields['domicilio']->checks = [
            'type' => 'string',
        ];
        $this->entities['sede']->fields['domicilio']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['sede']->fields['fecha_traspaso'] = Field::getInstance('sede', 'fecha_traspaso', 'date', 'DateTime');
        $this->entities['sede']->fields['fecha_traspaso']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['sede']->fields['id'] = Field::getInstance('sede', 'id', 'varchar', 'string');
        $this->entities['sede']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['sede']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['sede']->fields['nombre'] = Field::getInstance('sede', 'nombre', 'varchar', 'string');
        $this->entities['sede']->fields['nombre']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['sede']->fields['nombre']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['sede']->fields['numero'] = Field::getInstance('sede', 'numero', 'varchar', 'string');
        $this->entities['sede']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['sede']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['sede']->fields['observaciones'] = Field::getInstance('sede', 'observaciones', 'text', 'string');
        $this->entities['sede']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['sede']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['sede']->fields['organizacion'] = Field::getInstance('sede', 'organizacion', 'varchar', 'string');
        $this->entities['sede']->fields['organizacion']->alias = 'sed';
        $this->entities['sede']->fields['organizacion']->refEntityName = 'sede';
        $this->entities['sede']->fields['organizacion']->refFieldName = 'id';
        $this->entities['sede']->fields['organizacion']->checks = [
            'type' => 'string',
        ];
        $this->entities['sede']->fields['organizacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['sede']->fields['pfid'] = Field::getInstance('sede', 'pfid', 'varchar', 'string');
        $this->entities['sede']->fields['pfid']->checks = [
            'type' => 'string',
        ];
        $this->entities['sede']->fields['pfid']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['sede']->fields['pfid_organizacion'] = Field::getInstance('sede', 'pfid_organizacion', 'varchar', 'string');
        $this->entities['sede']->fields['pfid_organizacion']->checks = [
            'type' => 'string',
        ];
        $this->entities['sede']->fields['pfid_organizacion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['sede']->fields['tipo_sede'] = Field::getInstance('sede', 'tipo_sede', 'varchar', 'string');
        $this->entities['sede']->fields['tipo_sede']->alias = 'tip';
        $this->entities['sede']->fields['tipo_sede']->refEntityName = 'tipo_sede';
        $this->entities['sede']->fields['tipo_sede']->refFieldName = 'id';
        $this->entities['sede']->fields['tipo_sede']->checks = [
            'type' => 'string',
        ];
        $this->entities['sede']->fields['tipo_sede']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['telefono'] = EntityMetadata::getInstance('telefono', 'tele');
        $this->entities['telefono']->pk = ['id'];
        $this->entities['telefono']->fk = ['persona'];
        $this->entities['telefono']->notNull = ['id', 'insertado', 'numero', 'persona'];

        $this->entities['telefono']->tree = [];
        $this->entities['telefono']->tree['persona'] = EntityTree::getInstance('persona', 'persona', 'id');
        $this->entities['telefono']->tree['persona']->children = [];
        $this->entities['telefono']->tree['persona']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['telefono']->relations = [];
        $this->entities['telefono']->relations['persona'] = EntityRelation::getInstance('persona', 'persona', 'id');

        $this->entities['telefono']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['telefono']->relations['domicilio']->parentId = 'persona';

        $this->entities['telefono']->fields['eliminado'] = Field::getInstance('telefono', 'eliminado', 'timestamp', 'DateTime');
        $this->entities['telefono']->fields['eliminado']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['telefono']->fields['id'] = Field::getInstance('telefono', 'id', 'varchar', 'string');
        $this->entities['telefono']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['telefono']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['telefono']->fields['insertado'] = Field::getInstance('telefono', 'insertado', 'timestamp', 'DateTime');
        $this->entities['telefono']->fields['insertado']->defaultValue = 'current_timestamp()';
        $this->entities['telefono']->fields['insertado']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['telefono']->fields['numero'] = Field::getInstance('telefono', 'numero', 'varchar', 'string');
        $this->entities['telefono']->fields['numero']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['telefono']->fields['numero']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['telefono']->fields['persona'] = Field::getInstance('telefono', 'persona', 'varchar', 'string');
        $this->entities['telefono']->fields['persona']->alias = 'per';
        $this->entities['telefono']->fields['persona']->refEntityName = 'persona';
        $this->entities['telefono']->fields['persona']->refFieldName = 'id';
        $this->entities['telefono']->fields['persona']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['telefono']->fields['persona']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['telefono']->fields['prefijo'] = Field::getInstance('telefono', 'prefijo', 'varchar', 'string');
        $this->entities['telefono']->fields['prefijo']->checks = [
            'type' => 'string',
        ];
        $this->entities['telefono']->fields['prefijo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['telefono']->fields['tipo'] = Field::getInstance('telefono', 'tipo', 'varchar', 'string');
        $this->entities['telefono']->fields['tipo']->checks = [
            'type' => 'string',
        ];
        $this->entities['telefono']->fields['tipo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['tipo_sede'] = EntityMetadata::getInstance('tipo_sede', 'tipo');
        $this->entities['tipo_sede']->pk = ['id'];
        $this->entities['tipo_sede']->unique = ['descripcion'];
        $this->entities['tipo_sede']->notNull = ['descripcion', 'id'];

        $this->entities['tipo_sede']->om = [];
        $this->entities['tipo_sede']->om['Sede_'] = EntityRef::getInstance('tipo_sede', 'sede');
        $this->entities['tipo_sede']->fields['descripcion'] = Field::getInstance('tipo_sede', 'descripcion', 'varchar', 'string');
        $this->entities['tipo_sede']->fields['descripcion']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['tipo_sede']->fields['descripcion']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['tipo_sede']->fields['id'] = Field::getInstance('tipo_sede', 'id', 'varchar', 'string');
        $this->entities['tipo_sede']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['tipo_sede']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['toma'] = EntityMetadata::getInstance('toma', 'toma');
        $this->entities['toma']->pk = ['id'];
        $this->entities['toma']->fk = ['curso', 'docente', 'planilla_docente', 'reemplazo'];
        $this->entities['toma']->notNull = ['alta', 'asistencia', 'calificacion', 'confirmada', 'curso', 'id', 'sin_planillas', 'temas_tratados', 'tipo_movimiento'];

        $this->entities['toma']->tree = [];
        $this->entities['toma']->tree['curso'] = EntityTree::getInstance('curso', 'curso', 'id');
        $this->entities['toma']->tree['curso']->children = [];
        $this->entities['toma']->tree['curso']->children['asignatura'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['toma']->tree['curso']->children['comision'] = EntityTree::getInstance('comision', 'comision', 'id');
        $this->entities['toma']->tree['curso']->children['comision']->children = [];
        $this->entities['toma']->tree['curso']->children['comision']->children['calendario'] = EntityTree::getInstance('calendario', 'calendario', 'id');

        $this->entities['toma']->tree['curso']->children['comision']->children['comision_siguiente'] = EntityTree::getInstance('comision_siguiente', 'comision', 'id');

        $this->entities['toma']->tree['curso']->children['comision']->children['modalidad'] = EntityTree::getInstance('modalidad', 'modalidad', 'id');

        $this->entities['toma']->tree['curso']->children['comision']->children['planificacion'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['toma']->tree['curso']->children['comision']->children['planificacion']->children = [];
        $this->entities['toma']->tree['curso']->children['comision']->children['planificacion']->children['plan'] = EntityTree::getInstance('plan', 'plan', 'id');


        $this->entities['toma']->tree['curso']->children['comision']->children['sede'] = EntityTree::getInstance('sede', 'sede', 'id');
        $this->entities['toma']->tree['curso']->children['comision']->children['sede']->children = [];
        $this->entities['toma']->tree['curso']->children['comision']->children['sede']->children['centro_educativo'] = EntityTree::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['toma']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children = [];
        $this->entities['toma']->tree['curso']->children['comision']->children['sede']->children['centro_educativo']->children['domicilio_cen'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['toma']->tree['curso']->children['comision']->children['sede']->children['domicilio'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');

        $this->entities['toma']->tree['curso']->children['comision']->children['sede']->children['organizacion'] = EntityTree::getInstance('organizacion', 'sede', 'id');

        $this->entities['toma']->tree['curso']->children['comision']->children['sede']->children['tipo_sede'] = EntityTree::getInstance('tipo_sede', 'tipo_sede', 'id');



        $this->entities['toma']->tree['curso']->children['disposicion'] = EntityTree::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['toma']->tree['curso']->children['disposicion']->children = [];
        $this->entities['toma']->tree['curso']->children['disposicion']->children['asignatura_dis'] = EntityTree::getInstance('asignatura', 'asignatura', 'id');

        $this->entities['toma']->tree['curso']->children['disposicion']->children['planificacion_dis'] = EntityTree::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['toma']->tree['curso']->children['disposicion']->children['planificacion_dis']->children = [];
        $this->entities['toma']->tree['curso']->children['disposicion']->children['planificacion_dis']->children['plan_pla'] = EntityTree::getInstance('plan', 'plan', 'id');




        $this->entities['toma']->tree['docente'] = EntityTree::getInstance('docente', 'persona', 'id');
        $this->entities['toma']->tree['docente']->children = [];
        $this->entities['toma']->tree['docente']->children['domicilio_doc'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['toma']->tree['planilla_docente'] = EntityTree::getInstance('planilla_docente', 'planilla_docente', 'id');

        $this->entities['toma']->tree['reemplazo'] = EntityTree::getInstance('reemplazo', 'persona', 'id');
        $this->entities['toma']->tree['reemplazo']->children = [];
        $this->entities['toma']->tree['reemplazo']->children['domicilio_ree'] = EntityTree::getInstance('domicilio', 'domicilio', 'id');


        $this->entities['toma']->relations = [];
        $this->entities['toma']->relations['curso'] = EntityRelation::getInstance('curso', 'curso', 'id');

        $this->entities['toma']->relations['asignatura'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['toma']->relations['asignatura']->parentId = 'curso';

        $this->entities['toma']->relations['comision'] = EntityRelation::getInstance('comision', 'comision', 'id');
        $this->entities['toma']->relations['comision']->parentId = 'curso';

        $this->entities['toma']->relations['calendario'] = EntityRelation::getInstance('calendario', 'calendario', 'id');
        $this->entities['toma']->relations['calendario']->parentId = 'comision';

        $this->entities['toma']->relations['comision_siguiente'] = EntityRelation::getInstance('comision_siguiente', 'comision', 'id');
        $this->entities['toma']->relations['comision_siguiente']->parentId = 'comision';

        $this->entities['toma']->relations['modalidad'] = EntityRelation::getInstance('modalidad', 'modalidad', 'id');
        $this->entities['toma']->relations['modalidad']->parentId = 'comision';

        $this->entities['toma']->relations['planificacion'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['toma']->relations['planificacion']->parentId = 'comision';

        $this->entities['toma']->relations['plan'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['toma']->relations['plan']->parentId = 'planificacion';

        $this->entities['toma']->relations['sede'] = EntityRelation::getInstance('sede', 'sede', 'id');
        $this->entities['toma']->relations['sede']->parentId = 'comision';

        $this->entities['toma']->relations['centro_educativo'] = EntityRelation::getInstance('centro_educativo', 'centro_educativo', 'id');
        $this->entities['toma']->relations['centro_educativo']->parentId = 'sede';

        $this->entities['toma']->relations['domicilio_cen'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['toma']->relations['domicilio_cen']->parentId = 'centro_educativo';

        $this->entities['toma']->relations['domicilio'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['toma']->relations['domicilio']->parentId = 'sede';

        $this->entities['toma']->relations['organizacion'] = EntityRelation::getInstance('organizacion', 'sede', 'id');
        $this->entities['toma']->relations['organizacion']->parentId = 'sede';

        $this->entities['toma']->relations['tipo_sede'] = EntityRelation::getInstance('tipo_sede', 'tipo_sede', 'id');
        $this->entities['toma']->relations['tipo_sede']->parentId = 'sede';

        $this->entities['toma']->relations['disposicion'] = EntityRelation::getInstance('disposicion', 'disposicion', 'id');
        $this->entities['toma']->relations['disposicion']->parentId = 'curso';

        $this->entities['toma']->relations['asignatura_dis'] = EntityRelation::getInstance('asignatura', 'asignatura', 'id');
        $this->entities['toma']->relations['asignatura_dis']->parentId = 'disposicion';

        $this->entities['toma']->relations['planificacion_dis'] = EntityRelation::getInstance('planificacion', 'planificacion', 'id');
        $this->entities['toma']->relations['planificacion_dis']->parentId = 'disposicion';

        $this->entities['toma']->relations['plan_pla'] = EntityRelation::getInstance('plan', 'plan', 'id');
        $this->entities['toma']->relations['plan_pla']->parentId = 'planificacion_dis';

        $this->entities['toma']->relations['docente'] = EntityRelation::getInstance('docente', 'persona', 'id');

        $this->entities['toma']->relations['domicilio_doc'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['toma']->relations['domicilio_doc']->parentId = 'docente';

        $this->entities['toma']->relations['planilla_docente'] = EntityRelation::getInstance('planilla_docente', 'planilla_docente', 'id');

        $this->entities['toma']->relations['reemplazo'] = EntityRelation::getInstance('reemplazo', 'persona', 'id');

        $this->entities['toma']->relations['domicilio_ree'] = EntityRelation::getInstance('domicilio', 'domicilio', 'id');
        $this->entities['toma']->relations['domicilio_ree']->parentId = 'reemplazo';

        $this->entities['toma']->om = [];
        $this->entities['toma']->om['AsignacionPlanillaDocente_'] = EntityRef::getInstance('toma', 'asignacion_planilla_docente');
        $this->entities['toma']->fields['alta'] = Field::getInstance('toma', 'alta', 'timestamp', 'DateTime');
        $this->entities['toma']->fields['alta']->defaultValue = 'current_timestamp()';
        $this->entities['toma']->fields['alta']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $this->entities['toma']->fields['asistencia'] = Field::getInstance('toma', 'asistencia', 'tinyint', 'int');
        $this->entities['toma']->fields['asistencia']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['toma']->fields['calificacion'] = Field::getInstance('toma', 'calificacion', 'tinyint', 'int');
        $this->entities['toma']->fields['calificacion']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['toma']->fields['comentario'] = Field::getInstance('toma', 'comentario', 'varchar', 'string');
        $this->entities['toma']->fields['comentario']->checks = [
            'type' => 'string',
        ];
        $this->entities['toma']->fields['comentario']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['toma']->fields['confirmada'] = Field::getInstance('toma', 'confirmada', 'tinyint', 'int');
        $this->entities['toma']->fields['confirmada']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['toma']->fields['curso'] = Field::getInstance('toma', 'curso', 'varchar', 'string');
        $this->entities['toma']->fields['curso']->alias = 'cur';
        $this->entities['toma']->fields['curso']->refEntityName = 'curso';
        $this->entities['toma']->fields['curso']->refFieldName = 'id';
        $this->entities['toma']->fields['curso']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['toma']->fields['curso']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['toma']->fields['docente'] = Field::getInstance('toma', 'docente', 'varchar', 'string');
        $this->entities['toma']->fields['docente']->alias = 'per';
        $this->entities['toma']->fields['docente']->refEntityName = 'persona';
        $this->entities['toma']->fields['docente']->refFieldName = 'id';
        $this->entities['toma']->fields['docente']->checks = [
            'type' => 'string',
        ];
        $this->entities['toma']->fields['docente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['toma']->fields['estado'] = Field::getInstance('toma', 'estado', 'varchar', 'string');
        $this->entities['toma']->fields['estado']->checks = [
            'type' => 'string',
        ];
        $this->entities['toma']->fields['estado']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['toma']->fields['estado_contralor'] = Field::getInstance('toma', 'estado_contralor', 'varchar', 'string');
        $this->entities['toma']->fields['estado_contralor']->checks = [
            'type' => 'string',
        ];
        $this->entities['toma']->fields['estado_contralor']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['toma']->fields['fecha_toma'] = Field::getInstance('toma', 'fecha_toma', 'date', 'DateTime');
        $this->entities['toma']->fields['fecha_toma']->checks = [
            'type' => 'DateTime',
        ];
        $this->entities['toma']->fields['id'] = Field::getInstance('toma', 'id', 'varchar', 'string');
        $this->entities['toma']->fields['id']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['toma']->fields['id']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $this->entities['toma']->fields['observaciones'] = Field::getInstance('toma', 'observaciones', 'text', 'string');
        $this->entities['toma']->fields['observaciones']->checks = [
            'type' => 'string',
        ];
        $this->entities['toma']->fields['observaciones']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['toma']->fields['planilla_docente'] = Field::getInstance('toma', 'planilla_docente', 'varchar', 'string');
        $this->entities['toma']->fields['planilla_docente']->alias = 'pla';
        $this->entities['toma']->fields['planilla_docente']->refEntityName = 'planilla_docente';
        $this->entities['toma']->fields['planilla_docente']->refFieldName = 'id';
        $this->entities['toma']->fields['planilla_docente']->checks = [
            'type' => 'string',
        ];
        $this->entities['toma']->fields['planilla_docente']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['toma']->fields['reemplazo'] = Field::getInstance('toma', 'reemplazo', 'varchar', 'string');
        $this->entities['toma']->fields['reemplazo']->alias = 'pe1';
        $this->entities['toma']->fields['reemplazo']->refEntityName = 'persona';
        $this->entities['toma']->fields['reemplazo']->refFieldName = 'id';
        $this->entities['toma']->fields['reemplazo']->checks = [
            'type' => 'string',
        ];
        $this->entities['toma']->fields['reemplazo']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $this->entities['toma']->fields['sin_planillas'] = Field::getInstance('toma', 'sin_planillas', 'tinyint', 'int');
        $this->entities['toma']->fields['sin_planillas']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['toma']->fields['temas_tratados'] = Field::getInstance('toma', 'temas_tratados', 'tinyint', 'int');
        $this->entities['toma']->fields['temas_tratados']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $this->entities['toma']->fields['tipo_movimiento'] = Field::getInstance('toma', 'tipo_movimiento', 'varchar', 'string');
        $this->entities['toma']->fields['tipo_movimiento']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $this->entities['toma']->fields['tipo_movimiento']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
    }
}
