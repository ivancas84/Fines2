using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class  Schema : Sql.ISchema
    {
         Dictionary<string, EntityMetadata> Sql.ISchema.entities => new() {
            #region entity alumno
            {
                "alumno", new () {
                    name = "alumno",
                    alias = "alum",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "persona", "plan", "resolucion_inscripcion" ],
                    unique = [ "libro_folio", "persona" ],
                    notNull = [ "id", "persona", "tiene_dni", "tiene_constancia", "tiene_certificado", "previas_completas", "tiene_partida", "creado", "confirmado_direccion" ],
                    tree = {
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                        } },
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                        } },
                    },
                    relations = {
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "",
                        } },
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region alumno.id
                        {
                            "id", new () {
                                entityName = "alumno",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region alumno.anio_ingreso
                        {
                            "anio_ingreso", new () {
                                #region configuracion manual
                                defaultValue = "1",
                                #endregion

                                entityName = "alumno",
                                name = "anio_ingreso",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new anio_ingreso
                        }, //end pair
                        #endregion
                        #region alumno.observaciones
                        {
                            "observaciones", new () {
                                entityName = "alumno",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                        #region alumno.persona
                        {
                            "persona", new () {
                                entityName = "alumno",
                                name = "persona",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new persona
                        }, //end pair
                        #endregion
                        #region alumno.estado_inscripcion
                        {
                            "estado_inscripcion", new () {
                                entityName = "alumno",
                                name = "estado_inscripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new estado_inscripcion
                        }, //end pair
                        #endregion
                        #region alumno.fecha_titulacion
                        {
                            "fecha_titulacion", new () {
                                entityName = "alumno",
                                name = "fecha_titulacion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_titulacion
                        }, //end pair
                        #endregion
                        #region alumno.plan
                        {
                            "plan", new () {
                                entityName = "alumno",
                                name = "plan",
                                alias = "pla",
                                refEntityName = "plan",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new plan
                        }, //end pair
                        #endregion
                        #region alumno.resolucion_inscripcion
                        {
                            "resolucion_inscripcion", new () {
                                entityName = "alumno",
                                name = "resolucion_inscripcion",
                                alias = "res",
                                refEntityName = "resolucion",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new resolucion_inscripcion
                        }, //end pair
                        #endregion
                        #region alumno.anio_inscripcion
                        {
                            "anio_inscripcion", new () {
                                entityName = "alumno",
                                name = "anio_inscripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            } //end new anio_inscripcion
                        }, //end pair
                        #endregion
                        #region alumno.semestre_inscripcion
                        {
                            "semestre_inscripcion", new () {
                                entityName = "alumno",
                                name = "semestre_inscripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            } //end new semestre_inscripcion
                        }, //end pair
                        #endregion
                        #region alumno.semestre_ingreso
                        {
                            "semestre_ingreso", new () {
                                #region configuracion manual
                                defaultValue = 1,
                                #endregion
                                entityName = "alumno",
                                name = "semestre_ingreso",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            } //end new semestre_ingreso
                        }, //end pair
                        #endregion
                        #region alumno.adeuda_legajo
                        {
                            "adeuda_legajo", new () {
                                entityName = "alumno",
                                name = "adeuda_legajo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new adeuda_legajo
                        }, //end pair
                        #endregion
                        #region alumno.adeuda_deudores
                        {
                            "adeuda_deudores", new () {
                                entityName = "alumno",
                                name = "adeuda_deudores",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new adeuda_deudores
                        }, //end pair
                        #endregion
                        #region alumno.documentacion_inscripcion
                        {
                            "documentacion_inscripcion", new () {
                                entityName = "alumno",
                                name = "documentacion_inscripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new documentacion_inscripcion
                        }, //end pair
                        #endregion
                        #region alumno.anio_inscripcion_completo
                        {
                            "anio_inscripcion_completo", new () {
                                entityName = "alumno",
                                name = "anio_inscripcion_completo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                },
                            } //end new anio_inscripcion_completo
                        }, //end pair
                        #endregion
                        #region alumno.establecimiento_inscripcion
                        {
                            "establecimiento_inscripcion", new () {
                                entityName = "alumno",
                                name = "establecimiento_inscripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new establecimiento_inscripcion
                        }, //end pair
                        #endregion
                        #region alumno.libro_folio
                        {
                            "libro_folio", new () {
                                entityName = "alumno",
                                name = "libro_folio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new libro_folio
                        }, //end pair
                        #endregion
                        #region alumno.libro
                        {
                            "libro", new () {
                                entityName = "alumno",
                                name = "libro",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new libro
                        }, //end pair
                        #endregion
                        #region alumno.folio
                        {
                            "folio", new () {
                                entityName = "alumno",
                                name = "folio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new folio
                        }, //end pair
                        #endregion
                        #region alumno.comentarios
                        {
                            "comentarios", new () {
                                entityName = "alumno",
                                name = "comentarios",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new comentarios
                        }, //end pair
                        #endregion
                        #region alumno.tiene_dni
                        {
                            "tiene_dni", new () {
                                entityName = "alumno",
                                name = "tiene_dni",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new tiene_dni
                        }, //end pair
                        #endregion
                        #region alumno.tiene_constancia
                        {
                            "tiene_constancia", new () {
                                entityName = "alumno",
                                name = "tiene_constancia",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new tiene_constancia
                        }, //end pair
                        #endregion
                        #region alumno.tiene_certificado
                        {
                            "tiene_certificado", new () {
                                entityName = "alumno",
                                name = "tiene_certificado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new tiene_certificado
                        }, //end pair
                        #endregion
                        #region alumno.previas_completas
                        {
                            "previas_completas", new () {
                                entityName = "alumno",
                                name = "previas_completas",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new previas_completas
                        }, //end pair
                        #endregion
                        #region alumno.tiene_partida
                        {
                            "tiene_partida", new () {
                                entityName = "alumno",
                                name = "tiene_partida",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new tiene_partida
                        }, //end pair
                        #endregion
                        #region alumno.creado
                        {
                            "creado", new () {
                                entityName = "alumno",
                                name = "creado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new creado
                        }, //end pair
                        #endregion
                        #region alumno.confirmado_direccion
                        {
                            "confirmado_direccion", new () {
                                entityName = "alumno",
                                name = "confirmado_direccion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new confirmado_direccion
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity alumno_comision
            {
                "alumno_comision", new () {
                    #region Configuracion manual
                    uniqueMultiple = [
                      [ "alumno", "comision" ]
                    ],
                    #endregion

                    name = "alumno_comision",
                    alias = "alu1",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "comision", "alumno" ],
                    notNull = [ "id", "creado", "alumno" ],
                    tree = {
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                { "sede", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        { "tipo_sede", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        { "centro_educativo", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                { "domicilio_cen", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                            },
                                        } },
                                    },
                                } },
                                { "modalidad", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                                { "calendario", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                            },
                        } },
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            children = new() {
                                { "persona", new () {
                                    fieldName = "persona",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        { "domicilio_per", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                    },
                                } },
                                { "plan_alu", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                { "resolucion_inscripcion", new () {
                                    fieldName = "resolucion_inscripcion",
                                    refFieldName = "id",
                                    refEntityName = "resolucion",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            parentId = "",
                        } },
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "alumno",
                        } },
                        { "domicilio_per", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        { "plan_alu", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "alumno",
                        } },
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "alumno",
                        } },
                    },
                    fieldsMetadata = {
                        #region alumno_comision.id
                        {
                            "id", new () {
                                entityName = "alumno_comision",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region alumno_comision.creado
                        {
                            "creado", new () {
                                entityName = "alumno_comision",
                                name = "creado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new creado
                        }, //end pair
                        #endregion
                        #region alumno_comision.activo (OCULTADO)
                        /*{
                            "activo", new () {
                                entityName = "alumno_comision",
                                name = "activo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                },
                            } 
                        }, */
                        #endregion
                        #region alumno_comision.observaciones
                        {
                            "observaciones", new () {
                                entityName = "alumno_comision",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                        #region alumno_comision.comision
                        {
                            "comision", new () {
                                entityName = "alumno_comision",
                                name = "comision",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new comision
                        }, //end pair
                        #endregion
                        #region alumno_comision.alumno
                        {
                            "alumno", new () {
                                entityName = "alumno_comision",
                                name = "alumno",
                                alias = "alu",
                                refEntityName = "alumno",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new alumno
                        }, //end pair
                        #endregion
                        #region alumno_comision.estado
                        {
                            "estado", new () {
                                entityName = "alumno_comision",
                                name = "estado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new estado
                        }, //end pair
                        #endregion
                        #region alumno_comision.pfid
                        {
                            "pfid", new () {
                                entityName = "alumno_comision",
                                name = "pfid",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "int",
                                type = "uint",
                                checks = new () {
                                        { "type", "uint" },
                                },
                            } //end new pfid
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity asignacion_planilla_docente
            {
                "asignacion_planilla_docente", new () {
                    name = "asignacion_planilla_docente",
                    alias = "asig",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "planilla_docente", "toma" ],
                    notNull = [ "id", "planilla_docente", "toma", "insertado", "reclamo" ],
                    tree = {
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                        } },
                        { "toma", new () {
                            fieldName = "toma",
                            refFieldName = "id",
                            refEntityName = "toma",
                            children = new() {
                                { "curso", new () {
                                    fieldName = "curso",
                                    refFieldName = "id",
                                    refEntityName = "curso",
                                    children = new() {
                                        { "comision", new () {
                                            fieldName = "comision",
                                            refFieldName = "id",
                                            refEntityName = "comision",
                                            children = new() {
                                                { "sede", new () {
                                                    fieldName = "sede",
                                                    refFieldName = "id",
                                                    refEntityName = "sede",
                                                    children = new() {
                                                        { "domicilio", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                        { "tipo_sede", new () {
                                                            fieldName = "tipo_sede",
                                                            refFieldName = "id",
                                                            refEntityName = "tipo_sede",
                                                        } },
                                                        { "centro_educativo", new () {
                                                            fieldName = "centro_educativo",
                                                            refFieldName = "id",
                                                            refEntityName = "centro_educativo",
                                                            children = new() {
                                                                { "domicilio_cen", new () {
                                                                    fieldName = "domicilio",
                                                                    refFieldName = "id",
                                                                    refEntityName = "domicilio",
                                                                } },
                                                            },
                                                        } },
                                                    },
                                                } },
                                                { "modalidad", new () {
                                                    fieldName = "modalidad",
                                                    refFieldName = "id",
                                                    refEntityName = "modalidad",
                                                } },
                                                { "planificacion", new () {
                                                    fieldName = "planificacion",
                                                    refFieldName = "id",
                                                    refEntityName = "planificacion",
                                                    children = new() {
                                                        { "plan", new () {
                                                            fieldName = "plan",
                                                            refFieldName = "id",
                                                            refEntityName = "plan",
                                                        } },
                                                    },
                                                } },
                                                { "calendario", new () {
                                                    fieldName = "calendario",
                                                    refFieldName = "id",
                                                    refEntityName = "calendario",
                                                } },
                                            },
                                        } },
                                        { "disposicion", new () {
                                            fieldName = "disposicion",
                                            refFieldName = "id",
                                            refEntityName = "disposicion",
                                            children = new() {
                                                { "asignatura", new () {
                                                    fieldName = "asignatura",
                                                    refFieldName = "id",
                                                    refEntityName = "asignatura",
                                                } },
                                                { "planificacion_dis", new () {
                                                    fieldName = "planificacion",
                                                    refFieldName = "id",
                                                    refEntityName = "planificacion",
                                                    children = new() {
                                                        { "plan_pla", new () {
                                                            fieldName = "plan",
                                                            refFieldName = "id",
                                                            refEntityName = "plan",
                                                        } },
                                                    },
                                                } },
                                            },
                                        } },
                                    },
                                } },
                                { "docente", new () {
                                    fieldName = "docente",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        { "domicilio_doc", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                    },
                                } },
                                { "reemplazo", new () {
                                    fieldName = "reemplazo",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        { "domicilio_ree", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                    },
                                } },
                                { "planilla_docente_tom", new () {
                                    fieldName = "planilla_docente",
                                    refFieldName = "id",
                                    refEntityName = "planilla_docente",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "",
                        } },
                        { "toma", new () {
                            fieldName = "toma",
                            refFieldName = "id",
                            refEntityName = "toma",
                            parentId = "",
                        } },
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "toma",
                        } },
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        { "docente", new () {
                            fieldName = "docente",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "toma",
                        } },
                        { "domicilio_doc", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "docente",
                        } },
                        { "reemplazo", new () {
                            fieldName = "reemplazo",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "toma",
                        } },
                        { "domicilio_ree", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "reemplazo",
                        } },
                        { "planilla_docente_tom", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "toma",
                        } },
                    },
                    fieldsMetadata = {
                        #region asignacion_planilla_docente.id
                        {
                            "id", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region asignacion_planilla_docente.planilla_docente
                        {
                            "planilla_docente", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "planilla_docente",
                                alias = "pla",
                                refEntityName = "planilla_docente",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new planilla_docente
                        }, //end pair
                        #endregion
                        #region asignacion_planilla_docente.toma
                        {
                            "toma", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "toma",
                                alias = "tom",
                                refEntityName = "toma",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new toma
                        }, //end pair
                        #endregion
                        #region asignacion_planilla_docente.insertado
                        {
                            "insertado", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "insertado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new insertado
                        }, //end pair
                        #endregion
                        #region asignacion_planilla_docente.comentario
                        {
                            "comentario", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "comentario",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new comentario
                        }, //end pair
                        #endregion
                        #region asignacion_planilla_docente.reclamo
                        {
                            "reclamo", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "reclamo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new reclamo
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity asignatura
            {
                "asignatura", new () {
                    name = "asignatura",
                    alias = "asi1",
                    schema = "",
                    pk = [ "id" ],
                    unique = [ "nombre" ],
                    notNull = [ "id", "nombre" ],
                    fieldsMetadata = {
                        #region asignatura.id
                        {
                            "id", new () {
                                entityName = "asignatura",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region asignatura.nombre
                        {
                            "nombre", new () {
                                entityName = "asignatura",
                                name = "nombre",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new nombre
                        }, //end pair
                        #endregion
                        #region asignatura.formacion
                        {
                            "formacion", new () {
                                entityName = "asignatura",
                                name = "formacion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new formacion
                        }, //end pair
                        #endregion
                        #region asignatura.clasificacion
                        {
                            "clasificacion", new () {
                                entityName = "asignatura",
                                name = "clasificacion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new clasificacion
                        }, //end pair
                        #endregion
                        #region asignatura.codigo
                        {
                            "codigo", new () {
                                entityName = "asignatura",
                                name = "codigo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new codigo
                        }, //end pair
                        #endregion
                        #region asignatura.perfil
                        {
                            "perfil", new () {
                                entityName = "asignatura",
                                name = "perfil",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new perfil
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity calendario
            {
                "calendario", new () {
                    name = "calendario",
                    alias = "cale",
                    schema = "",
                    pk = [ "id" ],
                    notNull = [ "id", "anio", "semestre", "insertado" ],
                    fieldsMetadata = {
                        #region calendario.id
                        {
                            "id", new () {
                                entityName = "calendario",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region calendario.inicio
                        {
                            "inicio", new () {
                                entityName = "calendario",
                                name = "inicio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new inicio
                        }, //end pair
                        #endregion
                        #region calendario.fin
                        {
                            "fin", new () {
                                entityName = "calendario",
                                name = "fin",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fin
                        }, //end pair
                        #endregion
                        #region calendario.anio
                        {
                            "anio", new () {
                                #region configuracion manual
                                defaultValue = "current_year",
                                #endregion

                                entityName = "calendario",
                                name = "anio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "year",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                        { "required", "True" },
                                },
                            } //end new anio
                        }, //end pair
                        #endregion
                        #region calendario.semestre
                        {
                            "semestre", new () {
                                #region configuracion manual
                                defaultValue = "current_semester",
                                #endregion

                                entityName = "calendario",
                                name = "semestre",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                        { "required", "True" },
                                },
                            } //end new semestre
                        }, //end pair
                        #endregion
                        #region calendario.insertado
                        {
                            "insertado", new () {
                                entityName = "calendario",
                                name = "insertado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new insertado
                        }, //end pair
                        #endregion
                        #region calendario.descripcion
                        {
                            "descripcion", new () {
                                entityName = "calendario",
                                name = "descripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new descripcion
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity calificacion
            {
                "calificacion", new () {
                    #region Configuracion manual
                    uniqueMultiple = [
                      [ "disposicion", "alumno" ]
                    ],
                    #endregion

                    name = "calificacion",
                    alias = "cali",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "curso", "alumno", "disposicion" ],
                    notNull = [ "id", "alumno", "disposicion", "archivado" ],
                    tree = {
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            children = new() {
                                { "comision", new () {
                                    fieldName = "comision",
                                    refFieldName = "id",
                                    refEntityName = "comision",
                                    children = new() {
                                        { "sede", new () {
                                            fieldName = "sede",
                                            refFieldName = "id",
                                            refEntityName = "sede",
                                            children = new() {
                                                { "domicilio", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                { "tipo_sede", new () {
                                                    fieldName = "tipo_sede",
                                                    refFieldName = "id",
                                                    refEntityName = "tipo_sede",
                                                } },
                                                { "centro_educativo", new () {
                                                    fieldName = "centro_educativo",
                                                    refFieldName = "id",
                                                    refEntityName = "centro_educativo",
                                                    children = new() {
                                                        { "domicilio_cen", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                    },
                                                } },
                                            },
                                        } },
                                        { "modalidad", new () {
                                            fieldName = "modalidad",
                                            refFieldName = "id",
                                            refEntityName = "modalidad",
                                        } },
                                        { "planificacion", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                { "plan", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                            },
                                        } },
                                        { "calendario", new () {
                                            fieldName = "calendario",
                                            refFieldName = "id",
                                            refEntityName = "calendario",
                                        } },
                                    },
                                } },
                                { "disposicion_cur", new () {
                                    fieldName = "disposicion",
                                    refFieldName = "id",
                                    refEntityName = "disposicion",
                                    children = new() {
                                        { "asignatura", new () {
                                            fieldName = "asignatura",
                                            refFieldName = "id",
                                            refEntityName = "asignatura",
                                        } },
                                        { "planificacion_dis", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                { "plan_pla", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                            },
                                        } },
                                    },
                                } },
                            },
                        } },
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            children = new() {
                                { "persona", new () {
                                    fieldName = "persona",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        { "domicilio_per", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                    },
                                } },
                                { "plan_alu", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                { "resolucion_inscripcion", new () {
                                    fieldName = "resolucion_inscripcion",
                                    refFieldName = "id",
                                    refEntityName = "resolucion",
                                } },
                            },
                        } },
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                { "asignatura_dis", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                { "planificacion_dis1", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan_pla1", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "",
                        } },
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        { "disposicion_cur", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion_cur",
                        } },
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion_cur",
                        } },
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            parentId = "",
                        } },
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "alumno",
                        } },
                        { "domicilio_per", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        { "plan_alu", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "alumno",
                        } },
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "alumno",
                        } },
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        { "asignatura_dis", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        { "planificacion_dis1", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        { "plan_pla1", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis1",
                        } },
                    },
                    fieldsMetadata = {
                        #region calificacion.id
                        {
                            "id", new () {
                                entityName = "calificacion",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region calificacion.nota1
                        {
                            "nota1", new () {
                                entityName = "calificacion",
                                name = "nota1",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            } //end new nota1
                        }, //end pair
                        #endregion
                        #region calificacion.nota2
                        {
                            "nota2", new () {
                                entityName = "calificacion",
                                name = "nota2",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            } //end new nota2
                        }, //end pair
                        #endregion
                        #region calificacion.nota3
                        {
                            "nota3", new () {
                                entityName = "calificacion",
                                name = "nota3",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            } //end new nota3
                        }, //end pair
                        #endregion
                        #region calificacion.nota_final
                        {
                            "nota_final", new () {
                                entityName = "calificacion",
                                name = "nota_final",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            } //end new nota_final
                        }, //end pair
                        #endregion
                        #region calificacion.crec
                        {
                            "crec", new () {
                                entityName = "calificacion",
                                name = "crec",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            } //end new crec
                        }, //end pair
                        #endregion
                        #region calificacion.curso
                        {
                            "curso", new () {
                                entityName = "calificacion",
                                name = "curso",
                                alias = "cur",
                                refEntityName = "curso",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new curso
                        }, //end pair
                        #endregion
                        #region calificacion.porcentaje_asistencia
                        {
                            "porcentaje_asistencia", new () {
                                entityName = "calificacion",
                                name = "porcentaje_asistencia",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                },
                            } //end new porcentaje_asistencia
                        }, //end pair
                        #endregion
                        #region calificacion.observaciones
                        {
                            "observaciones", new () {
                                entityName = "calificacion",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                        #region calificacion.division
                        {
                            "division", new () {
                                entityName = "calificacion",
                                name = "division",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new division
                        }, //end pair
                        #endregion
                        #region calificacion.alumno
                        {
                            "alumno", new () {
                                entityName = "calificacion",
                                name = "alumno",
                                alias = "alu",
                                refEntityName = "alumno",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new alumno
                        }, //end pair
                        #endregion
                        #region calificacion.disposicion
                        {
                            "disposicion", new () {
                                entityName = "calificacion",
                                name = "disposicion",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new disposicion
                        }, //end pair
                        #endregion
                        #region calificacion.fecha
                        {
                            "fecha", new () {
                                entityName = "calificacion",
                                name = "fecha",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha
                        }, //end pair
                        #endregion
                        #region calificacion.archivado
                        {
                            "archivado", new () {
                                entityName = "calificacion",
                                name = "archivado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new archivado
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity cargo
            {
                "cargo", new () {
                    name = "cargo",
                    alias = "carg",
                    schema = "",
                    pk = [ "id" ],
                    unique = [ "descripcion" ],
                    notNull = [ "id", "descripcion" ],
                    fieldsMetadata = {
                        #region cargo.id
                        {
                            "id", new () {
                                entityName = "cargo",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region cargo.descripcion
                        {
                            "descripcion", new () {
                                entityName = "cargo",
                                name = "descripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new descripcion
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity centro_educativo
            {
                "centro_educativo", new () {
                    name = "centro_educativo",
                    alias = "cent",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "domicilio" ],
                    unique = [ "cue" ],
                    notNull = [ "id", "nombre" ],
                    tree = {
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                        } },
                    },
                    relations = {
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region centro_educativo.id
                        {
                            "id", new () {
                                entityName = "centro_educativo",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region centro_educativo.nombre
                        {
                            "nombre", new () {
                                entityName = "centro_educativo",
                                name = "nombre",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new nombre
                        }, //end pair
                        #endregion
                        #region centro_educativo.cue
                        {
                            "cue", new () {
                                entityName = "centro_educativo",
                                name = "cue",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new cue
                        }, //end pair
                        #endregion
                        #region centro_educativo.domicilio
                        {
                            "domicilio", new () {
                                entityName = "centro_educativo",
                                name = "domicilio",
                                alias = "dom",
                                refEntityName = "domicilio",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new domicilio
                        }, //end pair
                        #endregion
                        #region centro_educativo.observaciones
                        {
                            "observaciones", new () {
                                entityName = "centro_educativo",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity comision
            {
                "comision", new () {
                    name = "comision",
                    alias = "comi",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "sede", "modalidad", "planificacion", "comision_siguiente", "calendario" ],
                    notNull = [ "id", "division", "autorizada", "apertura", "publicada", "alta", "sede", "modalidad", "calendario" ],
                    tree = {
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            children = new() {
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                { "tipo_sede", new () {
                                    fieldName = "tipo_sede",
                                    refFieldName = "id",
                                    refEntityName = "tipo_sede",
                                } },
                                { "centro_educativo", new () {
                                    fieldName = "centro_educativo",
                                    refFieldName = "id",
                                    refEntityName = "centro_educativo",
                                    children = new() {
                                        { "domicilio_cen", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                    },
                                } },
                            },
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            children = new() {
                                { "plan", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                            },
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                        } },
                    },
                    relations = {
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region comision.id
                        {
                            "id", new () {
                                entityName = "comision",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region comision.turno
                        {
                            "turno", new () {
                                entityName = "comision",
                                name = "turno",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new turno
                        }, //end pair
                        #endregion
                        #region comision.division
                        {
                            "division", new () {
                                entityName = "comision",
                                name = "division",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new division
                        }, //end pair
                        #endregion
                        #region comision.comentario
                        {
                            "comentario", new () {
                                entityName = "comision",
                                name = "comentario",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new comentario
                        }, //end pair
                        #endregion
                        #region comision.autorizada
                        {
                            "autorizada", new () {
                                entityName = "comision",
                                name = "autorizada",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new autorizada
                        }, //end pair
                        #endregion
                        #region comision.apertura
                        {
                            "apertura", new () {
                                entityName = "comision",
                                name = "apertura",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new apertura
                        }, //end pair
                        #endregion
                        #region comision.publicada
                        {
                            "publicada", new () {
                                entityName = "comision",
                                name = "publicada",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new publicada
                        }, //end pair
                        #endregion
                        #region comision.observaciones
                        {
                            "observaciones", new () {
                                entityName = "comision",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                        #region comision.alta
                        {
                            "alta", new () {
                                entityName = "comision",
                                name = "alta",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new alta
                        }, //end pair
                        #endregion
                        #region comision.sede
                        {
                            "sede", new () {
                                entityName = "comision",
                                name = "sede",
                                alias = "sed",
                                refEntityName = "sede",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new sede
                        }, //end pair
                        #endregion
                        #region comision.modalidad
                        {
                            "modalidad", new () {
                                entityName = "comision",
                                name = "modalidad",
                                alias = "mod",
                                refEntityName = "modalidad",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new modalidad
                        }, //end pair
                        #endregion
                        #region comision.planificacion
                        {
                            "planificacion", new () {
                                entityName = "comision",
                                name = "planificacion",
                                alias = "pla",
                                refEntityName = "planificacion",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new planificacion
                        }, //end pair
                        #endregion
                        #region comision.comision_siguiente
                        {
                            "comision_siguiente", new () {
                                entityName = "comision",
                                name = "comision_siguiente",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new comision_siguiente
                        }, //end pair
                        #endregion
                        #region comision.calendario
                        {
                            "calendario", new () {
                                entityName = "comision",
                                name = "calendario",
                                alias = "cal",
                                refEntityName = "calendario",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new calendario
                        }, //end pair
                        #endregion
                        #region comision.identificacion
                        {
                            "identificacion", new () {
                                entityName = "comision",
                                name = "identificacion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new identificacion
                        }, //end pair
                        #endregion
                        #region comision.estado (OCULTADO)
                        /*{
                            "estado", new () {
                                entityName = "comision",
                                name = "estado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new estado
                        },*/
                        #endregion 
                        #region comision.configuracion (OCULTADO)
                        /* {
                            "configuracion", new () {
                                entityName = "comision",
                                name = "configuracion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new configuracion
                        }, */
                        #endregion
                        #region comision.pfid
                        {
                            "pfid", new () {
                                entityName = "comision",
                                name = "pfid",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new pfid
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity comision_relacionada
            {
                "comision_relacionada", new () {
                    name = "comision_relacionada",
                    alias = "com1",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "comision", "relacion" ],
                    notNull = [ "id", "comision", "relacion" ],
                    tree = {
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                { "sede", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        { "tipo_sede", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        { "centro_educativo", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                { "domicilio_cen", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                            },
                                        } },
                                    },
                                } },
                                { "modalidad", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                                { "calendario", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                            },
                        } },
                        { "relacion", new () {
                            fieldName = "relacion",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                { "sede_rel", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        { "domicilio_sed", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        { "tipo_sede_sed", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        { "centro_educativo_sed", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                { "domicilio_cen1", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                            },
                                        } },
                                    },
                                } },
                                { "modalidad_rel", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                { "planificacion_rel", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan_pla", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                                { "calendario_rel", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        { "relacion", new () {
                            fieldName = "relacion",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        { "sede_rel", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "relacion",
                        } },
                        { "domicilio_sed", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede_rel",
                        } },
                        { "tipo_sede_sed", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede_rel",
                        } },
                        { "centro_educativo_sed", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede_rel",
                        } },
                        { "domicilio_cen1", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo_sed",
                        } },
                        { "modalidad_rel", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "relacion",
                        } },
                        { "planificacion_rel", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "relacion",
                        } },
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_rel",
                        } },
                        { "calendario_rel", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "relacion",
                        } },
                    },
                    fieldsMetadata = {
                        #region comision_relacionada.id
                        {
                            "id", new () {
                                entityName = "comision_relacionada",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region comision_relacionada.comision
                        {
                            "comision", new () {
                                entityName = "comision_relacionada",
                                name = "comision",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new comision
                        }, //end pair
                        #endregion
                        #region comision_relacionada.relacion
                        {
                            "relacion", new () {
                                entityName = "comision_relacionada",
                                name = "relacion",
                                alias = "rel",
                                refEntityName = "comision",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new relacion
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity contralor
            {
                "contralor", new () {
                    name = "contralor",
                    alias = "cont",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "planilla_docente" ],
                    notNull = [ "id", "insertado", "planilla_docente" ],
                    tree = {
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                        } },
                    },
                    relations = {
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region contralor.id
                        {
                            "id", new () {
                                entityName = "contralor",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region contralor.fecha_contralor
                        {
                            "fecha_contralor", new () {
                                entityName = "contralor",
                                name = "fecha_contralor",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_contralor
                        }, //end pair
                        #endregion
                        #region contralor.fecha_consejo
                        {
                            "fecha_consejo", new () {
                                entityName = "contralor",
                                name = "fecha_consejo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_consejo
                        }, //end pair
                        #endregion
                        #region contralor.insertado
                        {
                            "insertado", new () {
                                entityName = "contralor",
                                name = "insertado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new insertado
                        }, //end pair
                        #endregion
                        #region contralor.planilla_docente
                        {
                            "planilla_docente", new () {
                                entityName = "contralor",
                                name = "planilla_docente",
                                alias = "pla",
                                refEntityName = "planilla_docente",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new planilla_docente
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity curso
            {
                "curso", new () {
                    name = "curso",
                    alias = "curs",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "comision", "disposicion" ],
                    notNull = [ "id", "horas_catedra", "comision", "alta" ],
                    tree = {
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                { "sede", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        { "tipo_sede", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        { "centro_educativo", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                { "domicilio_cen", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                            },
                                        } },
                                    },
                                } },
                                { "modalidad", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                                { "calendario", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                            },
                        } },
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                { "asignatura", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                { "planificacion_dis", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan_pla", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                    },
                    fieldsMetadata = {
                        #region curso.id
                        {
                            "id", new () {
                                entityName = "curso",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region curso.horas_catedra
                        {
                            "horas_catedra", new () {
                                entityName = "curso",
                                name = "horas_catedra",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                        { "required", "True" },
                                },
                            } //end new horas_catedra
                        }, //end pair
                        #endregion
                        #region curso.ige
                        {
                            "ige", new () {
                                entityName = "curso",
                                name = "ige",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new ige
                        }, //end pair
                        #endregion
                        #region curso.comision
                        {
                            "comision", new () {
                                entityName = "curso",
                                name = "comision",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new comision
                        }, //end pair
                        #endregion
                        #region curso.alta
                        {
                            "alta", new () {
                                entityName = "curso",
                                name = "alta",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new alta
                        }, //end pair
                        #endregion
                        #region curso.descripcion_horario
                        {
                            "descripcion_horario", new () {
                                entityName = "curso",
                                name = "descripcion_horario",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new descripcion_horario
                        }, //end pair
                        #endregion
                        #region curso.codigo
                        {
                            "codigo", new () {
                                entityName = "curso",
                                name = "codigo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new codigo
                        }, //end pair
                        #endregion
                        #region curso.disposicion
                        {
                            "disposicion", new () {
                                entityName = "curso",
                                name = "disposicion",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new disposicion
                        }, //end pair
                        #endregion
                        #region curso.observaciones
                        {
                            "observaciones", new () {
                                entityName = "curso",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity designacion
            {
                "designacion", new () {
                    name = "designacion",
                    alias = "desi",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "cargo", "sede", "persona" ],
                    notNull = [ "id", "cargo", "sede", "persona", "alta" ],
                    tree = {
                        { "cargo", new () {
                            fieldName = "cargo",
                            refFieldName = "id",
                            refEntityName = "cargo",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            children = new() {
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                { "tipo_sede", new () {
                                    fieldName = "tipo_sede",
                                    refFieldName = "id",
                                    refEntityName = "tipo_sede",
                                } },
                                { "centro_educativo", new () {
                                    fieldName = "centro_educativo",
                                    refFieldName = "id",
                                    refEntityName = "centro_educativo",
                                    children = new() {
                                        { "domicilio_cen", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                    },
                                } },
                            },
                        } },
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                { "domicilio_per", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "cargo", new () {
                            fieldName = "cargo",
                            refFieldName = "id",
                            refEntityName = "cargo",
                            parentId = "",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        { "domicilio_per", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                    },
                    fieldsMetadata = {
                        #region designacion.id
                        {
                            "id", new () {
                                entityName = "designacion",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region designacion.desde
                        {
                            "desde", new () {
                                entityName = "designacion",
                                name = "desde",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new desde
                        }, //end pair
                        #endregion
                        #region designacion.hasta
                        {
                            "hasta", new () {
                                entityName = "designacion",
                                name = "hasta",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new hasta
                        }, //end pair
                        #endregion
                        #region designacion.cargo
                        {
                            "cargo", new () {
                                entityName = "designacion",
                                name = "cargo",
                                alias = "car",
                                refEntityName = "cargo",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new cargo
                        }, //end pair
                        #endregion
                        #region designacion.sede
                        {
                            "sede", new () {
                                entityName = "designacion",
                                name = "sede",
                                alias = "sed",
                                refEntityName = "sede",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new sede
                        }, //end pair
                        #endregion
                        #region designacion.persona
                        {
                            "persona", new () {
                                entityName = "designacion",
                                name = "persona",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new persona
                        }, //end pair
                        #endregion
                        #region designacion.alta
                        {
                            "alta", new () {
                                entityName = "designacion",
                                name = "alta",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new alta
                        }, //end pair
                        #endregion
                        #region designacion.pfid
                        {
                            "pfid", new () {
                                entityName = "designacion",
                                name = "pfid",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new pfid
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity detalle_persona
            {
                "detalle_persona", new () {
                    name = "detalle_persona",
                    alias = "deta",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "archivo", "persona" ],
                    notNull = [ "id", "descripcion", "creado", "persona" ],
                    tree = {
                        { "archivo", new () {
                            fieldName = "archivo",
                            refFieldName = "id",
                            refEntityName = "file",
                        } },
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "archivo", new () {
                            fieldName = "archivo",
                            refFieldName = "id",
                            refEntityName = "file",
                            parentId = "",
                        } },
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                    },
                    fieldsMetadata = {
                        #region detalle_persona.id
                        {
                            "id", new () {
                                entityName = "detalle_persona",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region detalle_persona.descripcion
                        {
                            "descripcion", new () {
                                entityName = "detalle_persona",
                                name = "descripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new descripcion
                        }, //end pair
                        #endregion
                        #region detalle_persona.archivo
                        {
                            "archivo", new () {
                                entityName = "detalle_persona",
                                name = "archivo",
                                alias = "arc",
                                refEntityName = "file",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new archivo
                        }, //end pair
                        #endregion
                        #region detalle_persona.creado
                        {
                            "creado", new () {
                                entityName = "detalle_persona",
                                name = "creado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new creado
                        }, //end pair
                        #endregion
                        #region detalle_persona.persona
                        {
                            "persona", new () {
                                entityName = "detalle_persona",
                                name = "persona",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new persona
                        }, //end pair
                        #endregion
                        #region detalle_persona.fecha
                        {
                            "fecha", new () {
                                entityName = "detalle_persona",
                                name = "fecha",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha
                        }, //end pair
                        #endregion
                        #region detalle_persona.tipo
                        {
                            "tipo", new () {
                                entityName = "detalle_persona",
                                name = "tipo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new tipo
                        }, //end pair
                        #endregion
                        #region detalle_persona.asunto
                        {
                            "asunto", new () {
                                entityName = "detalle_persona",
                                name = "asunto",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new asunto
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity dia
            {
                "dia", new () {
                    name = "dia",
                    alias = "dia",
                    schema = "",
                    pk = [ "id" ],
                    unique = [ "dia", "numero" ],
                    notNull = [ "id", "numero", "dia" ],
                    fieldsMetadata = {
                        #region dia.id
                        {
                            "id", new () {
                                entityName = "dia",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region dia.numero
                        {
                            "numero", new () {
                                entityName = "dia",
                                name = "numero",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                        { "required", "True" },
                                },
                            } //end new numero
                        }, //end pair
                        #endregion
                        #region dia.dia
                        {
                            "dia", new () {
                                entityName = "dia",
                                name = "dia",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new dia
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity disposicion
            {
                "disposicion", new () {
                    name = "disposicion",
                    alias = "disp",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "asignatura", "planificacion" ],
                    notNull = [ "id", "asignatura", "planificacion" ],
                    tree = {
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            children = new() {
                                { "plan", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                    },
                    fieldsMetadata = {
                        #region disposicion.id
                        {
                            "id", new () {
                                entityName = "disposicion",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region disposicion.asignatura
                        {
                            "asignatura", new () {
                                entityName = "disposicion",
                                name = "asignatura",
                                alias = "asi",
                                refEntityName = "asignatura",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new asignatura
                        }, //end pair
                        #endregion
                        #region disposicion.planificacion
                        {
                            "planificacion", new () {
                                entityName = "disposicion",
                                name = "planificacion",
                                alias = "pla",
                                refEntityName = "planificacion",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new planificacion
                        }, //end pair
                        #endregion
                        #region disposicion.orden_informe_coordinacion_distrital
                        {
                            "orden_informe_coordinacion_distrital", new () {
                                entityName = "disposicion",
                                name = "orden_informe_coordinacion_distrital",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                },
                            } //end new orden_informe_coordinacion_distrital
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity disposicion_pendiente
            {
                "disposicion_pendiente", new () {
                    name = "disposicion_pendiente",
                    alias = "dis1",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "disposicion", "alumno" ],
                    notNull = [ "id", "disposicion", "alumno" ],
                    tree = {
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                { "asignatura", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                            },
                        } },
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            children = new() {
                                { "persona", new () {
                                    fieldName = "persona",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                    },
                                } },
                                { "plan_alu", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                { "resolucion_inscripcion", new () {
                                    fieldName = "resolucion_inscripcion",
                                    refFieldName = "id",
                                    refEntityName = "resolucion",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            parentId = "",
                        } },
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "alumno",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        { "plan_alu", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "alumno",
                        } },
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "alumno",
                        } },
                    },
                    fieldsMetadata = {
                        #region disposicion_pendiente.id
                        {
                            "id", new () {
                                entityName = "disposicion_pendiente",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region disposicion_pendiente.disposicion
                        {
                            "disposicion", new () {
                                entityName = "disposicion_pendiente",
                                name = "disposicion",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new disposicion
                        }, //end pair
                        #endregion
                        #region disposicion_pendiente.alumno
                        {
                            "alumno", new () {
                                entityName = "disposicion_pendiente",
                                name = "alumno",
                                alias = "alu",
                                refEntityName = "alumno",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new alumno
                        }, //end pair
                        #endregion
                        #region disposicion_pendiente.modo
                        {
                            "modo", new () {
                                entityName = "disposicion_pendiente",
                                name = "modo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new modo
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity distribucion_horaria
            {
                "distribucion_horaria", new () {
                    name = "distribucion_horaria",
                    alias = "dist",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "disposicion" ],
                    notNull = [ "id", "horas_catedra", "dia" ],
                    tree = {
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                { "asignatura", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                    },
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                    },
                    fieldsMetadata = {
                        #region distribucion_horaria.id
                        {
                            "id", new () {
                                entityName = "distribucion_horaria",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region distribucion_horaria.horas_catedra
                        {
                            "horas_catedra", new () {
                                entityName = "distribucion_horaria",
                                name = "horas_catedra",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                        { "required", "True" },
                                },
                            } //end new horas_catedra
                        }, //end pair
                        #endregion
                        #region distribucion_horaria.dia
                        {
                            "dia", new () {
                                entityName = "distribucion_horaria",
                                name = "dia",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                        { "required", "True" },
                                },
                            } //end new dia
                        }, //end pair
                        #endregion
                        #region distribucion_horaria.disposicion
                        {
                            "disposicion", new () {
                                entityName = "distribucion_horaria",
                                name = "disposicion",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new disposicion
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity domicilio
            {
                "domicilio", new () {
                    name = "domicilio",
                    alias = "domi",
                    schema = "",
                    pk = [ "id" ],
                    notNull = [ "id", "calle", "numero", "localidad" ],
                    fieldsMetadata = {
                        #region domicilio.id
                        {
                            "id", new () {
                                entityName = "domicilio",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region domicilio.calle
                        {
                            "calle", new () {
                                entityName = "domicilio",
                                name = "calle",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new calle
                        }, //end pair
                        #endregion
                        #region domicilio.entre
                        {
                            "entre", new () {
                                entityName = "domicilio",
                                name = "entre",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new entre
                        }, //end pair
                        #endregion
                        #region domicilio.numero
                        {
                            "numero", new () {
                                entityName = "domicilio",
                                name = "numero",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new numero
                        }, //end pair
                        #endregion
                        #region domicilio.piso
                        {
                            "piso", new () {
                                entityName = "domicilio",
                                name = "piso",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new piso
                        }, //end pair
                        #endregion
                        #region domicilio.departamento
                        {
                            "departamento", new () {
                                entityName = "domicilio",
                                name = "departamento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new departamento
                        }, //end pair
                        #endregion
                        #region domicilio.barrio
                        {
                            "barrio", new () {
                                entityName = "domicilio",
                                name = "barrio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new barrio
                        }, //end pair
                        #endregion
                        #region domicilio.localidad
                        {
                            "localidad", new () {
                                entityName = "domicilio",
                                name = "localidad",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new localidad
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity email
            {
                "email", new () {
                    name = "email",
                    alias = "emai",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "persona" ],
                    notNull = [ "id", "email", "verificado", "insertado", "persona" ],
                    tree = {
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                    },
                    fieldsMetadata = {
                        #region email.id
                        {
                            "id", new () {
                                entityName = "email",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region email.email
                        {
                            "email", new () {
                                entityName = "email",
                                name = "email",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new email
                        }, //end pair
                        #endregion
                        #region email.verificado
                        {
                            "verificado", new () {
                                entityName = "email",
                                name = "verificado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new verificado
                        }, //end pair
                        #endregion
                        #region email.insertado
                        {
                            "insertado", new () {
                                entityName = "email",
                                name = "insertado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new insertado
                        }, //end pair
                        #endregion
                        #region email.eliminado
                        {
                            "eliminado", new () {
                                entityName = "email",
                                name = "eliminado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new eliminado
                        }, //end pair
                        #endregion
                        #region email.persona
                        {
                            "persona", new () {
                                entityName = "email",
                                name = "persona",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new persona
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity file
            {
                "file", new () {
                    name = "file",
                    alias = "file",
                    schema = "",
                    pk = [ "id" ],
                    notNull = [ "id", "name", "type", "content", "size", "created" ],
                    fieldsMetadata = {
                        #region file.id
                        {
                            "id", new () {
                                entityName = "file",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region file.name
                        {
                            "name", new () {
                                entityName = "file",
                                name = "name",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new name
                        }, //end pair
                        #endregion
                        #region file.type
                        {
                            "type", new () {
                                entityName = "file",
                                name = "type",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new type
                        }, //end pair
                        #endregion
                        #region file.content
                        {
                            "content", new () {
                                entityName = "file",
                                name = "content",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new content
                        }, //end pair
                        #endregion
                        #region file.size
                        {
                            "size", new () {
                                entityName = "file",
                                name = "size",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "int",
                                type = "uint",
                                checks = new () {
                                        { "type", "uint" },
                                        { "required", "True" },
                                },
                            } //end new size
                        }, //end pair
                        #endregion
                        #region file.created
                        {
                            "created", new () {
                                entityName = "file",
                                name = "created",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new created
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity horario
            {
                "horario", new () {
                    name = "horario",
                    alias = "hora",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "curso", "dia" ],
                    notNull = [ "id", "hora_inicio", "hora_fin", "curso", "dia" ],
                    tree = {
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            children = new() {
                                { "comision", new () {
                                    fieldName = "comision",
                                    refFieldName = "id",
                                    refEntityName = "comision",
                                    children = new() {
                                        { "sede", new () {
                                            fieldName = "sede",
                                            refFieldName = "id",
                                            refEntityName = "sede",
                                            children = new() {
                                                { "domicilio", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                { "tipo_sede", new () {
                                                    fieldName = "tipo_sede",
                                                    refFieldName = "id",
                                                    refEntityName = "tipo_sede",
                                                } },
                                                { "centro_educativo", new () {
                                                    fieldName = "centro_educativo",
                                                    refFieldName = "id",
                                                    refEntityName = "centro_educativo",
                                                    children = new() {
                                                        { "domicilio_cen", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                    },
                                                } },
                                            },
                                        } },
                                        { "modalidad", new () {
                                            fieldName = "modalidad",
                                            refFieldName = "id",
                                            refEntityName = "modalidad",
                                        } },
                                        { "planificacion", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                { "plan", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                            },
                                        } },
                                        { "calendario", new () {
                                            fieldName = "calendario",
                                            refFieldName = "id",
                                            refEntityName = "calendario",
                                        } },
                                    },
                                } },
                                { "disposicion", new () {
                                    fieldName = "disposicion",
                                    refFieldName = "id",
                                    refEntityName = "disposicion",
                                    children = new() {
                                        { "asignatura", new () {
                                            fieldName = "asignatura",
                                            refFieldName = "id",
                                            refEntityName = "asignatura",
                                        } },
                                        { "planificacion_dis", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                { "plan_pla", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                            },
                                        } },
                                    },
                                } },
                            },
                        } },
                        { "dia", new () {
                            fieldName = "dia",
                            refFieldName = "id",
                            refEntityName = "dia",
                        } },
                    },
                    relations = {
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "",
                        } },
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        { "dia", new () {
                            fieldName = "dia",
                            refFieldName = "id",
                            refEntityName = "dia",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region horario.id
                        {
                            "id", new () {
                                entityName = "horario",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region horario.hora_inicio
                        {
                            "hora_inicio", new () {
                                entityName = "horario",
                                name = "hora_inicio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "time",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new hora_inicio
                        }, //end pair
                        #endregion
                        #region horario.hora_fin
                        {
                            "hora_fin", new () {
                                entityName = "horario",
                                name = "hora_fin",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "time",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new hora_fin
                        }, //end pair
                        #endregion
                        #region horario.curso
                        {
                            "curso", new () {
                                entityName = "horario",
                                name = "curso",
                                alias = "cur",
                                refEntityName = "curso",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new curso
                        }, //end pair
                        #endregion
                        #region horario.dia
                        {
                            "dia", new () {
                                entityName = "horario",
                                name = "dia",
                                alias = "dia",
                                refEntityName = "dia",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new dia
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity modalidad
            {
                "modalidad", new () {
                    name = "modalidad",
                    alias = "moda",
                    schema = "",
                    pk = [ "id" ],
                    unique = [ "nombre" ],
                    notNull = [ "id", "nombre" ],
                    fieldsMetadata = {
                        #region modalidad.id
                        {
                            "id", new () {
                                entityName = "modalidad",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region modalidad.nombre
                        {
                            "nombre", new () {
                                entityName = "modalidad",
                                name = "nombre",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new nombre
                        }, //end pair
                        #endregion
                        #region modalidad.pfid
                        {
                            "pfid", new () {
                                entityName = "modalidad",
                                name = "pfid",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new pfid
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity persona
            {
                "persona", new () {
                    name = "persona",
                    alias = "pers",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "domicilio" ],
                    unique = [ "cuil", "email_abc", "numero_documento" ],
                    notNull = [ "id", "nombres", "numero_documento", "alta", "telefono_verificado", "email_verificado", "info_verificada" ],
                    tree = {
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                        } },
                    },
                    relations = {
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region persona.id
                        {
                            "id", new () {
                                entityName = "persona",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region persona.nombres
                        {
                            "nombres", new () {
                                entityName = "persona",
                                name = "nombres",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new nombres
                        }, //end pair
                        #endregion
                        #region persona.apellidos
                        {
                            "apellidos", new () {
                                entityName = "persona",
                                name = "apellidos",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new apellidos
                        }, //end pair
                        #endregion
                        #region persona.fecha_nacimiento
                        {
                            "fecha_nacimiento", new () {
                                entityName = "persona",
                                name = "fecha_nacimiento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_nacimiento
                        }, //end pair
                        #endregion
                        #region persona.numero_documento
                        {
                            "numero_documento", new () {
                                entityName = "persona",
                                name = "numero_documento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new numero_documento
                        }, //end pair
                        #endregion
                        #region persona.cuil
                        {
                            "cuil", new () {
                                entityName = "persona",
                                name = "cuil",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new cuil
                        }, //end pair
                        #endregion
                        #region persona.genero
                        {
                            "genero", new () {
                                entityName = "persona",
                                name = "genero",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new genero
                        }, //end pair
                        #endregion
                        #region persona.apodo
                        {
                            "apodo", new () {
                                entityName = "persona",
                                name = "apodo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new apodo
                        }, //end pair
                        #endregion
                        #region persona.telefono
                        {
                            "telefono", new () {
                                entityName = "persona",
                                name = "telefono",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new telefono
                        }, //end pair
                        #endregion
                        #region persona.email
                        {
                            "email", new () {
                                entityName = "persona",
                                name = "email",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new email
                        }, //end pair
                        #endregion
                        #region persona.email_abc
                        {
                            "email_abc", new () {
                                entityName = "persona",
                                name = "email_abc",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new email_abc
                        }, //end pair
                        #endregion
                        #region persona.alta
                        {
                            "alta", new () {
                                entityName = "persona",
                                name = "alta",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new alta
                        }, //end pair
                        #endregion
                        #region persona.domicilio
                        {
                            "domicilio", new () {
                                entityName = "persona",
                                name = "domicilio",
                                alias = "dom",
                                refEntityName = "domicilio",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new domicilio
                        }, //end pair
                        #endregion
                        #region persona.lugar_nacimiento
                        {
                            "lugar_nacimiento", new () {
                                entityName = "persona",
                                name = "lugar_nacimiento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new lugar_nacimiento
                        }, //end pair
                        #endregion
                        #region persona.telefono_verificado
                        {
                            "telefono_verificado", new () {
                                entityName = "persona",
                                name = "telefono_verificado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new telefono_verificado
                        }, //end pair
                        #endregion
                        #region persona.email_verificado
                        {
                            "email_verificado", new () {
                                entityName = "persona",
                                name = "email_verificado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new email_verificado
                        }, //end pair
                        #endregion
                        #region persona.info_verificada
                        {
                            "info_verificada", new () {
                                entityName = "persona",
                                name = "info_verificada",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new info_verificada
                        }, //end pair
                        #endregion
                        #region persona.descripcion_domicilio
                        {
                            "descripcion_domicilio", new () {
                                entityName = "persona",
                                name = "descripcion_domicilio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new descripcion_domicilio
                        }, //end pair
                        #endregion
                        #region persona.cuil1
                        {
                            "cuil1", new () {
                                entityName = "persona",
                                name = "cuil1",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            } //end new cuil1
                        }, //end pair
                        #endregion
                        #region persona.cuil2
                        {
                            "cuil2", new () {
                                entityName = "persona",
                                name = "cuil2",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            } //end new cuil2
                        }, //end pair
                        #endregion
                        #region persona.departamento
                        {
                            "departamento", new () {
                                entityName = "persona",
                                name = "departamento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new departamento
                        }, //end pair
                        #endregion
                        #region persona.localidad
                        {
                            "localidad", new () {
                                entityName = "persona",
                                name = "localidad",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new localidad
                        }, //end pair
                        #endregion
                        #region persona.partido
                        {
                            "partido", new () {
                                entityName = "persona",
                                name = "partido",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new partido
                        }, //end pair
                        #endregion
                        #region persona.codigo_area
                        {
                            "codigo_area", new () {
                                entityName = "persona",
                                name = "codigo_area",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new codigo_area
                        }, //end pair
                        #endregion
                        #region persona.nacionalidad
                        {
                            "nacionalidad", new () {
                                entityName = "persona",
                                name = "nacionalidad",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new nacionalidad
                        }, //end pair
                        #endregion
                        #region persona.sexo
                        {
                            "sexo", new () {
                                entityName = "persona",
                                name = "sexo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            } //end new sexo
                        }, //end pair
                        #endregion
                        #region persona.dia_nacimiento
                        {
                            "dia_nacimiento", new () {
                                entityName = "persona",
                                name = "dia_nacimiento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            } //end new dia_nacimiento
                        }, //end pair
                        #endregion
                        #region persona.mes_nacimiento
                        {
                            "mes_nacimiento", new () {
                                entityName = "persona",
                                name = "mes_nacimiento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            } //end new mes_nacimiento
                        }, //end pair
                        #endregion
                        #region persona.anio_nacimiento
                        {
                            "anio_nacimiento", new () {
                                entityName = "persona",
                                name = "anio_nacimiento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "smallint",
                                type = "ushort",
                                checks = new () {
                                        { "type", "ushort" },
                                },
                            } //end new anio_nacimiento
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity plan
            {
                "plan", new () {
                    name = "plan",
                    alias = "plan",
                    schema = "",
                    pk = [ "id" ],
                    notNull = [ "id", "orientacion" ],
                    fieldsMetadata = {
                        #region plan.id
                        {
                            "id", new () {
                                entityName = "plan",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region plan.orientacion
                        {
                            "orientacion", new () {
                                entityName = "plan",
                                name = "orientacion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new orientacion
                        }, //end pair
                        #endregion
                        #region plan.resolucion
                        {
                            "resolucion", new () {
                                entityName = "plan",
                                name = "resolucion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new resolucion
                        }, //end pair
                        #endregion
                        #region plan.distribucion_horaria
                        {
                            "distribucion_horaria", new () {
                                entityName = "plan",
                                name = "distribucion_horaria",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new distribucion_horaria
                        }, //end pair
                        #endregion
                        #region plan.pfid
                        {
                            "pfid", new () {
                                entityName = "plan",
                                name = "pfid",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new pfid
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity planificacion
            {
                "planificacion", new () {
                    name = "planificacion",
                    alias = "pla1",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "plan" ],
                    notNull = [ "id", "anio", "semestre", "plan" ],
                    tree = {
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                        } },
                    },
                    relations = {
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region planificacion.id
                        {
                            "id", new () {
                                entityName = "planificacion",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region planificacion.anio
                        {
                            "anio", new () {
                                entityName = "planificacion",
                                name = "anio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new anio
                        }, //end pair
                        #endregion
                        #region planificacion.semestre
                        {
                            "semestre", new () {
                                entityName = "planificacion",
                                name = "semestre",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new semestre
                        }, //end pair
                        #endregion
                        #region planificacion.plan
                        {
                            "plan", new () {
                                entityName = "planificacion",
                                name = "plan",
                                alias = "pla",
                                refEntityName = "plan",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new plan
                        }, //end pair
                        #endregion
                        #region planificacion.pfid
                        {
                            "pfid", new () {
                                entityName = "planificacion",
                                name = "pfid",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new pfid
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity planilla_docente
            {
                "planilla_docente", new () {
                    name = "planilla_docente",
                    alias = "pla2",
                    schema = "",
                    pk = [ "id" ],
                    notNull = [ "id", "numero", "insertado" ],
                    fieldsMetadata = {
                        #region planilla_docente.id
                        {
                            "id", new () {
                                entityName = "planilla_docente",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region planilla_docente.numero
                        {
                            "numero", new () {
                                entityName = "planilla_docente",
                                name = "numero",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new numero
                        }, //end pair
                        #endregion
                        #region planilla_docente.insertado
                        {
                            "insertado", new () {
                                entityName = "planilla_docente",
                                name = "insertado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new insertado
                        }, //end pair
                        #endregion
                        #region planilla_docente.fecha_contralor
                        {
                            "fecha_contralor", new () {
                                entityName = "planilla_docente",
                                name = "fecha_contralor",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_contralor
                        }, //end pair
                        #endregion
                        #region planilla_docente.fecha_consejo
                        {
                            "fecha_consejo", new () {
                                entityName = "planilla_docente",
                                name = "fecha_consejo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_consejo
                        }, //end pair
                        #endregion
                        #region planilla_docente.observaciones
                        {
                            "observaciones", new () {
                                entityName = "planilla_docente",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity resolucion
            {
                "resolucion", new () {
                    name = "resolucion",
                    alias = "reso",
                    schema = "",
                    pk = [ "id" ],
                    notNull = [ "id", "numero" ],
                    fieldsMetadata = {
                        #region resolucion.id
                        {
                            "id", new () {
                                entityName = "resolucion",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region resolucion.numero
                        {
                            "numero", new () {
                                entityName = "resolucion",
                                name = "numero",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new numero
                        }, //end pair
                        #endregion
                        #region resolucion.anio
                        {
                            "anio", new () {
                                entityName = "resolucion",
                                name = "anio",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "year",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            } //end new anio
                        }, //end pair
                        #endregion
                        #region resolucion.tipo
                        {
                            "tipo", new () {
                                entityName = "resolucion",
                                name = "tipo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new tipo
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity sede
            {
                "sede", new () {
                    name = "sede",
                    alias = "sede",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "domicilio", "tipo_sede", "centro_educativo", "organizacion" ],
                    notNull = [ "id", "numero", "nombre", "alta" ],
                    tree = {
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            children = new() {
                                { "domicilio_cen", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                    },
                    fieldsMetadata = {
                        #region sede.id
                        {
                            "id", new () {
                                entityName = "sede",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region sede.numero
                        {
                            "numero", new () {
                                entityName = "sede",
                                name = "numero",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new numero
                        }, //end pair
                        #endregion
                        #region sede.nombre
                        {
                            "nombre", new () {
                                entityName = "sede",
                                name = "nombre",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new nombre
                        }, //end pair
                        #endregion
                        #region sede.observaciones
                        {
                            "observaciones", new () {
                                entityName = "sede",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                        #region sede.alta
                        {
                            "alta", new () {
                                entityName = "sede",
                                name = "alta",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new alta
                        }, //end pair
                        #endregion
                        #region sede.baja
                        {
                            "baja", new () {
                                entityName = "sede",
                                name = "baja",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new baja
                        }, //end pair
                        #endregion
                        #region sede.domicilio
                        {
                            "domicilio", new () {
                                entityName = "sede",
                                name = "domicilio",
                                alias = "dom",
                                refEntityName = "domicilio",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new domicilio
                        }, //end pair
                        #endregion
                        #region sede.tipo_sede (OCULTADO)
                        /*{
                            "tipo_sede", new () {
                                entityName = "sede",
                                name = "tipo_sede",
                                alias = "tip",
                                refEntityName = "tipo_sede",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new tipo_sede
                        }, //end pair*/
                        #endregion
                        #region sede.centro_educativo
                        {
                            "centro_educativo", new () {
                                entityName = "sede",
                                name = "centro_educativo",
                                alias = "cen",
                                refEntityName = "centro_educativo",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new centro_educativo
                        }, //end pair
                        #endregion
                        #region sede.fecha_traspaso
                        {
                            "fecha_traspaso", new () {
                                entityName = "sede",
                                name = "fecha_traspaso",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_traspaso
                        }, //end pair
                        #endregion
                        #region sede.organizacion
                        {
                            "organizacion", new () {
                                entityName = "sede",
                                name = "organizacion",
                                alias = "org",
                                refEntityName = "sede",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new organizacion
                        }, //end pair
                        #endregion
                        #region sede.pfid
                        {
                            "pfid", new () {
                                entityName = "sede",
                                name = "pfid",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new pfid
                        }, //end pair
                        #endregion
                        #region sede.pfid_organizacion
                        {
                            "pfid_organizacion", new () {
                                entityName = "sede",
                                name = "pfid_organizacion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new pfid_organizacion
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity telefono
            {
                "telefono", new () {
                    name = "telefono",
                    alias = "tele",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "persona" ],
                    notNull = [ "id", "numero", "insertado", "persona" ],
                    tree = {
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                    },
                    relations = {
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                    },
                    fieldsMetadata = {
                        #region telefono.id
                        {
                            "id", new () {
                                entityName = "telefono",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region telefono.tipo
                        {
                            "tipo", new () {
                                entityName = "telefono",
                                name = "tipo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new tipo
                        }, //end pair
                        #endregion
                        #region telefono.prefijo
                        {
                            "prefijo", new () {
                                entityName = "telefono",
                                name = "prefijo",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new prefijo
                        }, //end pair
                        #endregion
                        #region telefono.numero
                        {
                            "numero", new () {
                                entityName = "telefono",
                                name = "numero",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new numero
                        }, //end pair
                        #endregion
                        #region telefono.insertado
                        {
                            "insertado", new () {
                                entityName = "telefono",
                                name = "insertado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new insertado
                        }, //end pair
                        #endregion
                        #region telefono.eliminado
                        {
                            "eliminado", new () {
                                entityName = "telefono",
                                name = "eliminado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new eliminado
                        }, //end pair
                        #endregion
                        #region telefono.persona
                        {
                            "persona", new () {
                                entityName = "telefono",
                                name = "persona",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new persona
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity tipo_sede
            {
                "tipo_sede", new () {
                    name = "tipo_sede",
                    alias = "tipo",
                    schema = "",
                    pk = [ "id" ],
                    unique = [ "descripcion" ],
                    notNull = [ "id", "descripcion" ],
                    fieldsMetadata = {
                        #region tipo_sede.id
                        {
                            "id", new () {
                                entityName = "tipo_sede",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region tipo_sede.descripcion
                        {
                            "descripcion", new () {
                                entityName = "tipo_sede",
                                name = "descripcion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new descripcion
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
            #region entity toma
            {
                "toma", new () {
                    name = "toma",
                    alias = "toma",
                    schema = "",
                    pk = [ "id" ],
                    fk = [ "curso", "docente", "reemplazo", "planilla_docente" ],
                    notNull = [ "id", "tipo_movimiento", "alta", "curso", "calificacion", "temas_tratados", "asistencia", "sin_planillas", "confirmada" ],
                    tree = {
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            children = new() {
                                { "comision", new () {
                                    fieldName = "comision",
                                    refFieldName = "id",
                                    refEntityName = "comision",
                                    children = new() {
                                        { "sede", new () {
                                            fieldName = "sede",
                                            refFieldName = "id",
                                            refEntityName = "sede",
                                            children = new() {
                                                { "domicilio", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                { "tipo_sede", new () {
                                                    fieldName = "tipo_sede",
                                                    refFieldName = "id",
                                                    refEntityName = "tipo_sede",
                                                } },
                                                { "centro_educativo", new () {
                                                    fieldName = "centro_educativo",
                                                    refFieldName = "id",
                                                    refEntityName = "centro_educativo",
                                                    children = new() {
                                                        { "domicilio_cen", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                    },
                                                } },
                                            },
                                        } },
                                        { "modalidad", new () {
                                            fieldName = "modalidad",
                                            refFieldName = "id",
                                            refEntityName = "modalidad",
                                        } },
                                        { "planificacion", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                { "plan", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                            },
                                        } },
                                        { "calendario", new () {
                                            fieldName = "calendario",
                                            refFieldName = "id",
                                            refEntityName = "calendario",
                                        } },
                                    },
                                } },
                                { "disposicion", new () {
                                    fieldName = "disposicion",
                                    refFieldName = "id",
                                    refEntityName = "disposicion",
                                    children = new() {
                                        { "asignatura", new () {
                                            fieldName = "asignatura",
                                            refFieldName = "id",
                                            refEntityName = "asignatura",
                                        } },
                                        { "planificacion_dis", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                { "plan_pla", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                            },
                                        } },
                                    },
                                } },
                            },
                        } },
                        { "docente", new () {
                            fieldName = "docente",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                { "domicilio_doc", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                        { "reemplazo", new () {
                            fieldName = "reemplazo",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                { "domicilio_ree", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                            },
                        } },
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                        } },
                    },
                    relations = {
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "",
                        } },
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        { "docente", new () {
                            fieldName = "docente",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        { "domicilio_doc", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "docente",
                        } },
                        { "reemplazo", new () {
                            fieldName = "reemplazo",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        { "domicilio_ree", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "reemplazo",
                        } },
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "",
                        } },
                    },
                    fieldsMetadata = {
                        #region toma.id
                        {
                            "id", new () {
                                entityName = "toma",
                                name = "id",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new id
                        }, //end pair
                        #endregion
                        #region toma.fecha_toma
                        {
                            "fecha_toma", new () {
                                entityName = "toma",
                                name = "fecha_toma",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            } //end new fecha_toma
                        }, //end pair
                        #endregion
                        #region toma.estado
                        {
                            "estado", new () {
                                entityName = "toma",
                                name = "estado",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new estado
                        }, //end pair
                        #endregion
                        #region toma.observaciones
                        {
                            "observaciones", new () {
                                entityName = "toma",
                                name = "observaciones",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "text",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new observaciones
                        }, //end pair
                        #endregion
                        #region toma.comentario
                        {
                            "comentario", new () {
                                entityName = "toma",
                                name = "comentario",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new comentario
                        }, //end pair
                        #endregion
                        #region toma.tipo_movimiento
                        {
                            "tipo_movimiento", new () {
                                entityName = "toma",
                                name = "tipo_movimiento",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new tipo_movimiento
                        }, //end pair
                        #endregion
                        #region toma.estado_contralor
                        {
                            "estado_contralor", new () {
                                entityName = "toma",
                                name = "estado_contralor",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new estado_contralor
                        }, //end pair
                        #endregion
                        #region toma.alta
                        {
                            "alta", new () {
                                entityName = "toma",
                                name = "alta",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            } //end new alta
                        }, //end pair
                        #endregion
                        #region toma.curso
                        {
                            "curso", new () {
                                entityName = "toma",
                                name = "curso",
                                alias = "cur",
                                refEntityName = "curso",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            } //end new curso
                        }, //end pair
                        #endregion
                        #region toma.docente
                        {
                            "docente", new () {
                                entityName = "toma",
                                name = "docente",
                                alias = "doc",
                                refEntityName = "persona",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new docente
                        }, //end pair
                        #endregion
                        #region toma.reemplazo
                        {
                            "reemplazo", new () {
                                entityName = "toma",
                                name = "reemplazo",
                                alias = "ree",
                                refEntityName = "persona",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new reemplazo
                        }, //end pair
                        #endregion
                        #region toma.planilla_docente
                        {
                            "planilla_docente", new () {
                                entityName = "toma",
                                name = "planilla_docente",
                                alias = "pla",
                                refEntityName = "planilla_docente",
                                refFieldName = "id",
                                dataType = "varchar",
                                type = "string",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            } //end new planilla_docente
                        }, //end pair
                        #endregion
                        #region toma.calificacion
                        {
                            "calificacion", new () {
                                entityName = "toma",
                                name = "calificacion",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new calificacion
                        }, //end pair
                        #endregion
                        #region toma.temas_tratados
                        {
                            "temas_tratados", new () {
                                entityName = "toma",
                                name = "temas_tratados",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new temas_tratados
                        }, //end pair
                        #endregion
                        #region toma.asistencia
                        {
                            "asistencia", new () {
                                entityName = "toma",
                                name = "asistencia",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new asistencia
                        }, //end pair
                        #endregion
                        #region toma.sin_planillas
                        {
                            "sin_planillas", new () {
                                entityName = "toma",
                                name = "sin_planillas",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new sin_planillas
                        }, //end pair
                        #endregion
                        #region toma.confirmada
                        {
                            "confirmada", new () {
                                entityName = "toma",
                                name = "confirmada",
                                alias = "",
                                refEntityName = "",
                                refFieldName = "",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            } //end new confirmada
                        }, //end pair
                        #endregion
                    },
                }
            },
            #endregion
        };
    }
}
