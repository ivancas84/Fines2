using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class  Schema : Sql.ISchema
    {
         Dictionary<string, EntityMetadata> Sql.ISchema.entities => new() {
            #region alumno
            {
                "alumno", new () {
                    name = "alumno",
                    alias = "alum",
                    pk = [ "id" ],
                    fk = [ "persona", "plan", "resolucion_inscripcion" ],
                    unique = [ "libro_folio", "persona" ],
                    notNull = [ "id", "persona", "tiene_dni", "tiene_constancia", "tiene_certificado", "previas_completas", "tiene_partida", "creado", "confirmado_direccion" ],
                    tree = {
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                #region domicilio
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                        } },
                        #endregion
                        #region resolucion_inscripcion
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                        } },
                        #endregion
                    },
                    relations = {
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "",
                        } },
                        #endregion
                        #region resolucion_inscripcion
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "",
                        } },
                        #endregion
                    },
                    om = {
                        #region AlumnoComision_
                        { "AlumnoComision_", new () {
                            fieldName = "alumno",
                            entityName = "alumno_comision",
                        } },
                        #endregion
                        #region Calificacion_
                        { "Calificacion_", new () {
                            fieldName = "alumno",
                            entityName = "calificacion",
                        } },
                        #endregion
                        #region DisposicionPendiente_
                        { "DisposicionPendiente_", new () {
                            fieldName = "alumno",
                            entityName = "disposicion_pendiente",
                        } },
                        #endregion
                    },
                    fields = {
                        #region alumno.id
                        {
                            "id", new () {
                                entityName = "alumno",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.anio_ingreso
                        {
                            "anio_ingreso", new () {
                                #region configuracion manual
                                defaultValue = "1",
                                #endregion

                                entityName = "alumno",
                                name = "anio_ingreso",
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
                            }
                        },
                        #endregion
                        #region alumno.observaciones
                        {
                            "observaciones", new () {
                                entityName = "alumno",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                        #region alumno.persona
                        {
                            "persona", new () {
                                entityName = "alumno",
                                name = "persona",
                                dataType = "varchar",
                                type = "string",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.estado_inscripcion
                        {
                            "estado_inscripcion", new () {
                                entityName = "alumno",
                                name = "estado_inscripcion",
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
                            }
                        },
                        #endregion
                        #region alumno.fecha_titulacion
                        {
                            "fecha_titulacion", new () {
                                entityName = "alumno",
                                name = "fecha_titulacion",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.plan
                        {
                            "plan", new () {
                                entityName = "alumno",
                                name = "plan",
                                dataType = "varchar",
                                type = "string",
                                alias = "pla",
                                refEntityName = "plan",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.resolucion_inscripcion
                        {
                            "resolucion_inscripcion", new () {
                                entityName = "alumno",
                                name = "resolucion_inscripcion",
                                dataType = "varchar",
                                type = "string",
                                alias = "res",
                                refEntityName = "resolucion",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.anio_inscripcion
                        {
                            "anio_inscripcion", new () {
                                entityName = "alumno",
                                name = "anio_inscripcion",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.semestre_inscripcion
                        {
                            "semestre_inscripcion", new () {
                                entityName = "alumno",
                                name = "semestre_inscripcion",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.semestre_ingreso
                        {
                            "semestre_ingreso", new () {
                                #region configuracion manual
                                defaultValue = 1,
                                #endregion

                                entityName = "alumno",
                                name = "semestre_ingreso",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.adeuda_legajo
                        {
                            "adeuda_legajo", new () {
                                entityName = "alumno",
                                name = "adeuda_legajo",
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
                            }
                        },
                        #endregion
                        #region alumno.adeuda_deudores
                        {
                            "adeuda_deudores", new () {
                                entityName = "alumno",
                                name = "adeuda_deudores",
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
                            }
                        },
                        #endregion
                        #region alumno.documentacion_inscripcion
                        {
                            "documentacion_inscripcion", new () {
                                entityName = "alumno",
                                name = "documentacion_inscripcion",
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
                            }
                        },
                        #endregion
                        #region alumno.anio_inscripcion_completo
                        {
                            "anio_inscripcion_completo", new () {
                                entityName = "alumno",
                                name = "anio_inscripcion_completo",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.establecimiento_inscripcion
                        {
                            "establecimiento_inscripcion", new () {
                                entityName = "alumno",
                                name = "establecimiento_inscripcion",
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
                            }
                        },
                        #endregion
                        #region alumno.libro_folio
                        {
                            "libro_folio", new () {
                                entityName = "alumno",
                                name = "libro_folio",
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
                            }
                        },
                        #endregion
                        #region alumno.libro
                        {
                            "libro", new () {
                                entityName = "alumno",
                                name = "libro",
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
                            }
                        },
                        #endregion
                        #region alumno.folio
                        {
                            "folio", new () {
                                entityName = "alumno",
                                name = "folio",
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
                            }
                        },
                        #endregion
                        #region alumno.comentarios
                        {
                            "comentarios", new () {
                                entityName = "alumno",
                                name = "comentarios",
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
                            }
                        },
                        #endregion
                        #region alumno.tiene_dni
                        {
                            "tiene_dni", new () {
                                entityName = "alumno",
                                name = "tiene_dni",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.tiene_constancia
                        {
                            "tiene_constancia", new () {
                                entityName = "alumno",
                                name = "tiene_constancia",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.tiene_certificado
                        {
                            "tiene_certificado", new () {
                                entityName = "alumno",
                                name = "tiene_certificado",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.previas_completas
                        {
                            "previas_completas", new () {
                                entityName = "alumno",
                                name = "previas_completas",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.tiene_partida
                        {
                            "tiene_partida", new () {
                                entityName = "alumno",
                                name = "tiene_partida",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.creado
                        {
                            "creado", new () {
                                entityName = "alumno",
                                name = "creado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno.confirmado_direccion
                        {
                            "confirmado_direccion", new () {
                                entityName = "alumno",
                                name = "confirmado_direccion",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region alumno_comision
            {
                "alumno_comision", new () {
                    #region Configuracion manual
                    uniqueMultiple = [
                      [ "alumno", "comision" ]
                    ],
                    #endregion

                    name = "alumno_comision",
                    alias = "alu1",
                    pk = [ "id" ],
                    fk = [ "comision", "alumno" ],
                    notNull = [ "id", "creado", "alumno" ],
                    tree = {
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                #region sede
                                { "sede", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        #region domicilio
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                        #region tipo_sede
                                        { "tipo_sede", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        #endregion
                                        #region centro_educativo
                                        { "centro_educativo", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                #region domicilio_cen
                                                { "domicilio_cen", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region modalidad
                                { "modalidad", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                #endregion
                                #region planificacion
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region calendario
                                { "calendario", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region alumno
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            children = new() {
                                #region persona
                                { "persona", new () {
                                    fieldName = "persona",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        #region domicilio_per
                                        { "domicilio_per", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region plan_alu
                                { "plan_alu", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                #endregion
                                #region resolucion_inscripcion
                                { "resolucion_inscripcion", new () {
                                    fieldName = "resolucion_inscripcion",
                                    refFieldName = "id",
                                    refEntityName = "resolucion",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        #endregion
                        #region alumno
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            parentId = "",
                        } },
                        #endregion
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "alumno",
                        } },
                        #endregion
                        #region domicilio_per
                        { "domicilio_per", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                        #region plan_alu
                        { "plan_alu", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "alumno",
                        } },
                        #endregion
                        #region resolucion_inscripcion
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "alumno",
                        } },
                        #endregion
                    },
                    fields = {
                        #region alumno_comision.id
                        {
                            "id", new () {
                                entityName = "alumno_comision",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno_comision.creado
                        {
                            "creado", new () {
                                entityName = "alumno_comision",
                                name = "creado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno_comision.activo
                        {
                            "activo", new () {
                                entityName = "alumno_comision",
                                name = "activo",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                },
                            }
                        },
                        #endregion
                        #region alumno_comision.observaciones
                        {
                            "observaciones", new () {
                                entityName = "alumno_comision",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                        #region alumno_comision.comision
                        {
                            "comision", new () {
                                entityName = "alumno_comision",
                                name = "comision",
                                dataType = "varchar",
                                type = "string",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno_comision.alumno
                        {
                            "alumno", new () {
                                entityName = "alumno_comision",
                                name = "alumno",
                                dataType = "varchar",
                                type = "string",
                                alias = "alu",
                                refEntityName = "alumno",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno_comision.estado
                        {
                            "estado", new () {
                                entityName = "alumno_comision",
                                name = "estado",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "Activo",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region alumno_comision.pfid
                        {
                            "pfid", new () {
                                entityName = "alumno_comision",
                                name = "pfid",
                                dataType = "int",
                                type = "uint",
                                checks = new () {
                                        { "type", "uint" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region asignacion_planilla_docente
            {
                "asignacion_planilla_docente", new () {
                    name = "asignacion_planilla_docente",
                    alias = "asig",
                    pk = [ "id" ],
                    fk = [ "planilla_docente", "toma" ],
                    notNull = [ "id", "planilla_docente", "toma", "insertado", "reclamo" ],
                    tree = {
                        #region planilla_docente
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                        } },
                        #endregion
                        #region toma
                        { "toma", new () {
                            fieldName = "toma",
                            refFieldName = "id",
                            refEntityName = "toma",
                            children = new() {
                                #region curso
                                { "curso", new () {
                                    fieldName = "curso",
                                    refFieldName = "id",
                                    refEntityName = "curso",
                                    children = new() {
                                        #region comision
                                        { "comision", new () {
                                            fieldName = "comision",
                                            refFieldName = "id",
                                            refEntityName = "comision",
                                            children = new() {
                                                #region sede
                                                { "sede", new () {
                                                    fieldName = "sede",
                                                    refFieldName = "id",
                                                    refEntityName = "sede",
                                                    children = new() {
                                                        #region domicilio
                                                        { "domicilio", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                        #endregion
                                                        #region tipo_sede
                                                        { "tipo_sede", new () {
                                                            fieldName = "tipo_sede",
                                                            refFieldName = "id",
                                                            refEntityName = "tipo_sede",
                                                        } },
                                                        #endregion
                                                        #region centro_educativo
                                                        { "centro_educativo", new () {
                                                            fieldName = "centro_educativo",
                                                            refFieldName = "id",
                                                            refEntityName = "centro_educativo",
                                                            children = new() {
                                                                #region domicilio_cen
                                                                { "domicilio_cen", new () {
                                                                    fieldName = "domicilio",
                                                                    refFieldName = "id",
                                                                    refEntityName = "domicilio",
                                                                } },
                                                                #endregion
                                                            },
                                                        } },
                                                        #endregion
                                                    },
                                                } },
                                                #endregion
                                                #region modalidad
                                                { "modalidad", new () {
                                                    fieldName = "modalidad",
                                                    refFieldName = "id",
                                                    refEntityName = "modalidad",
                                                } },
                                                #endregion
                                                #region planificacion
                                                { "planificacion", new () {
                                                    fieldName = "planificacion",
                                                    refFieldName = "id",
                                                    refEntityName = "planificacion",
                                                    children = new() {
                                                        #region plan
                                                        { "plan", new () {
                                                            fieldName = "plan",
                                                            refFieldName = "id",
                                                            refEntityName = "plan",
                                                        } },
                                                        #endregion
                                                    },
                                                } },
                                                #endregion
                                                #region calendario
                                                { "calendario", new () {
                                                    fieldName = "calendario",
                                                    refFieldName = "id",
                                                    refEntityName = "calendario",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                        #region disposicion
                                        { "disposicion", new () {
                                            fieldName = "disposicion",
                                            refFieldName = "id",
                                            refEntityName = "disposicion",
                                            children = new() {
                                                #region asignatura
                                                { "asignatura", new () {
                                                    fieldName = "asignatura",
                                                    refFieldName = "id",
                                                    refEntityName = "asignatura",
                                                } },
                                                #endregion
                                                #region planificacion_dis
                                                { "planificacion_dis", new () {
                                                    fieldName = "planificacion",
                                                    refFieldName = "id",
                                                    refEntityName = "planificacion",
                                                    children = new() {
                                                        #region plan_pla
                                                        { "plan_pla", new () {
                                                            fieldName = "plan",
                                                            refFieldName = "id",
                                                            refEntityName = "plan",
                                                        } },
                                                        #endregion
                                                    },
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region docente
                                { "docente", new () {
                                    fieldName = "docente",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        #region domicilio_doc
                                        { "domicilio_doc", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region reemplazo
                                { "reemplazo", new () {
                                    fieldName = "reemplazo",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        #region domicilio_ree
                                        { "domicilio_ree", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region planilla_docente_tom
                                { "planilla_docente_tom", new () {
                                    fieldName = "planilla_docente",
                                    refFieldName = "id",
                                    refEntityName = "planilla_docente",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region planilla_docente
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "",
                        } },
                        #endregion
                        #region toma
                        { "toma", new () {
                            fieldName = "toma",
                            refFieldName = "id",
                            refEntityName = "toma",
                            parentId = "",
                        } },
                        #endregion
                        #region curso
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "toma",
                        } },
                        #endregion
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        #endregion
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        #endregion
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region planificacion_dis
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region plan_pla
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        #endregion
                        #region docente
                        { "docente", new () {
                            fieldName = "docente",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "toma",
                        } },
                        #endregion
                        #region domicilio_doc
                        { "domicilio_doc", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "docente",
                        } },
                        #endregion
                        #region reemplazo
                        { "reemplazo", new () {
                            fieldName = "reemplazo",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "toma",
                        } },
                        #endregion
                        #region domicilio_ree
                        { "domicilio_ree", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "reemplazo",
                        } },
                        #endregion
                        #region planilla_docente_tom
                        { "planilla_docente_tom", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "toma",
                        } },
                        #endregion
                    },
                    fields = {
                        #region asignacion_planilla_docente.id
                        {
                            "id", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region asignacion_planilla_docente.planilla_docente
                        {
                            "planilla_docente", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "planilla_docente",
                                dataType = "varchar",
                                type = "string",
                                alias = "pla",
                                refEntityName = "planilla_docente",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region asignacion_planilla_docente.toma
                        {
                            "toma", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "toma",
                                dataType = "varchar",
                                type = "string",
                                alias = "tom",
                                refEntityName = "toma",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region asignacion_planilla_docente.insertado
                        {
                            "insertado", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "insertado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region asignacion_planilla_docente.comentario
                        {
                            "comentario", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "comentario",
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
                            }
                        },
                        #endregion
                        #region asignacion_planilla_docente.reclamo
                        {
                            "reclamo", new () {
                                entityName = "asignacion_planilla_docente",
                                name = "reclamo",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region asignatura
            {
                "asignatura", new () {
                    name = "asignatura",
                    alias = "asi1",
                    pk = [ "id" ],
                    unique = [ "nombre" ],
                    notNull = [ "id", "nombre" ],
                    om = {
                        #region Disposicion_
                        { "Disposicion_", new () {
                            fieldName = "asignatura",
                            entityName = "disposicion",
                        } },
                        #endregion
                    },
                    fields = {
                        #region asignatura.id
                        {
                            "id", new () {
                                entityName = "asignatura",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region asignatura.nombre
                        {
                            "nombre", new () {
                                entityName = "asignatura",
                                name = "nombre",
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
                            }
                        },
                        #endregion
                        #region asignatura.formacion
                        {
                            "formacion", new () {
                                entityName = "asignatura",
                                name = "formacion",
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
                            }
                        },
                        #endregion
                        #region asignatura.clasificacion
                        {
                            "clasificacion", new () {
                                entityName = "asignatura",
                                name = "clasificacion",
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
                            }
                        },
                        #endregion
                        #region asignatura.codigo
                        {
                            "codigo", new () {
                                entityName = "asignatura",
                                name = "codigo",
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
                            }
                        },
                        #endregion
                        #region asignatura.perfil
                        {
                            "perfil", new () {
                                entityName = "asignatura",
                                name = "perfil",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region calendario
            {
                "calendario", new () {
                    name = "calendario",
                    alias = "cale",
                    pk = [ "id" ],
                    notNull = [ "id", "anio", "semestre", "insertado" ],
                    om = {
                        #region Comision_
                        { "Comision_", new () {
                            fieldName = "calendario",
                            entityName = "comision",
                        } },
                        #endregion
                    },
                    fields = {
                        #region calendario.id
                        {
                            "id", new () {
                                entityName = "calendario",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calendario.inicio
                        {
                            "inicio", new () {
                                entityName = "calendario",
                                name = "inicio",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region calendario.fin
                        {
                            "fin", new () {
                                entityName = "calendario",
                                name = "fin",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region calendario.anio
                        {
                            "anio", new () {
                                #region configuracion manual
                                defaultValue = "current_year",
                                #endregion

                                entityName = "calendario",
                                name = "anio",
                                dataType = "year",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calendario.semestre
                        {
                            "semestre", new () {
                                #region configuracion manual
                                defaultValue = "current_semester",
                                #endregion

                                entityName = "calendario",
                                name = "semestre",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calendario.insertado
                        {
                            "insertado", new () {
                                entityName = "calendario",
                                name = "insertado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calendario.descripcion
                        {
                            "descripcion", new () {
                                entityName = "calendario",
                                name = "descripcion",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region calificacion
            {
                "calificacion", new () {
                    #region Configuracion manual
                    uniqueMultiple = [
                      [ "disposicion", "alumno" ]
                    ],
                    #endregion

                    name = "calificacion",
                    alias = "cali",
                    pk = [ "id" ],
                    fk = [ "curso", "alumno", "disposicion" ],
                    notNull = [ "id", "alumno", "disposicion", "archivado" ],
                    tree = {
                        #region curso
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            children = new() {
                                #region comision
                                { "comision", new () {
                                    fieldName = "comision",
                                    refFieldName = "id",
                                    refEntityName = "comision",
                                    children = new() {
                                        #region sede
                                        { "sede", new () {
                                            fieldName = "sede",
                                            refFieldName = "id",
                                            refEntityName = "sede",
                                            children = new() {
                                                #region domicilio
                                                { "domicilio", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                #endregion
                                                #region tipo_sede
                                                { "tipo_sede", new () {
                                                    fieldName = "tipo_sede",
                                                    refFieldName = "id",
                                                    refEntityName = "tipo_sede",
                                                } },
                                                #endregion
                                                #region centro_educativo
                                                { "centro_educativo", new () {
                                                    fieldName = "centro_educativo",
                                                    refFieldName = "id",
                                                    refEntityName = "centro_educativo",
                                                    children = new() {
                                                        #region domicilio_cen
                                                        { "domicilio_cen", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                        #endregion
                                                    },
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                        #region modalidad
                                        { "modalidad", new () {
                                            fieldName = "modalidad",
                                            refFieldName = "id",
                                            refEntityName = "modalidad",
                                        } },
                                        #endregion
                                        #region planificacion
                                        { "planificacion", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                #region plan
                                                { "plan", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                        #region calendario
                                        { "calendario", new () {
                                            fieldName = "calendario",
                                            refFieldName = "id",
                                            refEntityName = "calendario",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region disposicion_cur
                                { "disposicion_cur", new () {
                                    fieldName = "disposicion",
                                    refFieldName = "id",
                                    refEntityName = "disposicion",
                                    children = new() {
                                        #region asignatura
                                        { "asignatura", new () {
                                            fieldName = "asignatura",
                                            refFieldName = "id",
                                            refEntityName = "asignatura",
                                        } },
                                        #endregion
                                        #region planificacion_dis
                                        { "planificacion_dis", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                #region plan_pla
                                                { "plan_pla", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region alumno
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            children = new() {
                                #region persona
                                { "persona", new () {
                                    fieldName = "persona",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        #region domicilio_per
                                        { "domicilio_per", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region plan_alu
                                { "plan_alu", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                #endregion
                                #region resolucion_inscripcion
                                { "resolucion_inscripcion", new () {
                                    fieldName = "resolucion_inscripcion",
                                    refFieldName = "id",
                                    refEntityName = "resolucion",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                #region asignatura_dis
                                { "asignatura_dis", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                #endregion
                                #region planificacion_dis1
                                { "planificacion_dis1", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan_pla1
                                        { "plan_pla1", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region curso
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "",
                        } },
                        #endregion
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        #endregion
                        #region disposicion_cur
                        { "disposicion_cur", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        #endregion
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion_cur",
                        } },
                        #endregion
                        #region planificacion_dis
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion_cur",
                        } },
                        #endregion
                        #region plan_pla
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        #endregion
                        #region alumno
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            parentId = "",
                        } },
                        #endregion
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "alumno",
                        } },
                        #endregion
                        #region domicilio_per
                        { "domicilio_per", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                        #region plan_alu
                        { "plan_alu", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "alumno",
                        } },
                        #endregion
                        #region resolucion_inscripcion
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "alumno",
                        } },
                        #endregion
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        #endregion
                        #region asignatura_dis
                        { "asignatura_dis", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region planificacion_dis1
                        { "planificacion_dis1", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region plan_pla1
                        { "plan_pla1", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis1",
                        } },
                        #endregion
                    },
                    fields = {
                        #region calificacion.id
                        {
                            "id", new () {
                                entityName = "calificacion",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.nota1
                        {
                            "nota1", new () {
                                entityName = "calificacion",
                                name = "nota1",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.nota2
                        {
                            "nota2", new () {
                                entityName = "calificacion",
                                name = "nota2",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.nota3
                        {
                            "nota3", new () {
                                entityName = "calificacion",
                                name = "nota3",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.nota_final
                        {
                            "nota_final", new () {
                                entityName = "calificacion",
                                name = "nota_final",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.crec
                        {
                            "crec", new () {
                                entityName = "calificacion",
                                name = "crec",
                                dataType = "decimal",
                                type = "decimal",
                                checks = new () {
                                        { "type", "decimal" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.curso
                        {
                            "curso", new () {
                                entityName = "calificacion",
                                name = "curso",
                                dataType = "varchar",
                                type = "string",
                                alias = "cur",
                                refEntityName = "curso",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.porcentaje_asistencia
                        {
                            "porcentaje_asistencia", new () {
                                entityName = "calificacion",
                                name = "porcentaje_asistencia",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.observaciones
                        {
                            "observaciones", new () {
                                entityName = "calificacion",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                        #region calificacion.division
                        {
                            "division", new () {
                                entityName = "calificacion",
                                name = "division",
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
                            }
                        },
                        #endregion
                        #region calificacion.alumno
                        {
                            "alumno", new () {
                                entityName = "calificacion",
                                name = "alumno",
                                dataType = "varchar",
                                type = "string",
                                alias = "alu",
                                refEntityName = "alumno",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.disposicion
                        {
                            "disposicion", new () {
                                entityName = "calificacion",
                                name = "disposicion",
                                dataType = "varchar",
                                type = "string",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.fecha
                        {
                            "fecha", new () {
                                entityName = "calificacion",
                                name = "fecha",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region calificacion.archivado
                        {
                            "archivado", new () {
                                entityName = "calificacion",
                                name = "archivado",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region cargo
            {
                "cargo", new () {
                    name = "cargo",
                    alias = "carg",
                    pk = [ "id" ],
                    unique = [ "descripcion" ],
                    notNull = [ "id", "descripcion" ],
                    om = {
                        #region Designacion_
                        { "Designacion_", new () {
                            fieldName = "cargo",
                            entityName = "designacion",
                        } },
                        #endregion
                    },
                    fields = {
                        #region cargo.id
                        {
                            "id", new () {
                                entityName = "cargo",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region cargo.descripcion
                        {
                            "descripcion", new () {
                                entityName = "cargo",
                                name = "descripcion",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region centro_educativo
            {
                "centro_educativo", new () {
                    name = "centro_educativo",
                    alias = "cent",
                    pk = [ "id" ],
                    fk = [ "domicilio" ],
                    unique = [ "cue" ],
                    notNull = [ "id", "nombre" ],
                    tree = {
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                        } },
                        #endregion
                    },
                    relations = {
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "",
                        } },
                        #endregion
                    },
                    om = {
                        #region Sede_
                        { "Sede_", new () {
                            fieldName = "centro_educativo",
                            entityName = "sede",
                        } },
                        #endregion
                    },
                    fields = {
                        #region centro_educativo.id
                        {
                            "id", new () {
                                entityName = "centro_educativo",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region centro_educativo.nombre
                        {
                            "nombre", new () {
                                entityName = "centro_educativo",
                                name = "nombre",
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
                            }
                        },
                        #endregion
                        #region centro_educativo.cue
                        {
                            "cue", new () {
                                entityName = "centro_educativo",
                                name = "cue",
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
                            }
                        },
                        #endregion
                        #region centro_educativo.domicilio
                        {
                            "domicilio", new () {
                                entityName = "centro_educativo",
                                name = "domicilio",
                                dataType = "varchar",
                                type = "string",
                                alias = "dom",
                                refEntityName = "domicilio",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region centro_educativo.observaciones
                        {
                            "observaciones", new () {
                                entityName = "centro_educativo",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region comision
            {
                "comision", new () {
                    name = "comision",
                    alias = "comi",
                    pk = [ "id" ],
                    fk = [ "sede", "modalidad", "planificacion", "comision_siguiente", "calendario" ],
                    notNull = [ "id", "division", "autorizada", "apertura", "publicada", "alta", "sede", "modalidad", "calendario" ],
                    tree = {
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            children = new() {
                                #region domicilio
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                                #region tipo_sede
                                { "tipo_sede", new () {
                                    fieldName = "tipo_sede",
                                    refFieldName = "id",
                                    refEntityName = "tipo_sede",
                                } },
                                #endregion
                                #region centro_educativo
                                { "centro_educativo", new () {
                                    fieldName = "centro_educativo",
                                    refFieldName = "id",
                                    refEntityName = "centro_educativo",
                                    children = new() {
                                        #region domicilio_cen
                                        { "domicilio_cen", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            children = new() {
                                #region plan
                                { "plan", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                        } },
                        #endregion
                    },
                    relations = {
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "",
                        } },
                        #endregion
                    },
                    om = {
                        #region AlumnoComision_
                        { "AlumnoComision_", new () {
                            fieldName = "comision",
                            entityName = "alumno_comision",
                        } },
                        #endregion
                        #region ComisionRelacionada_
                        { "ComisionRelacionada_", new () {
                            fieldName = "comision",
                            entityName = "comision_relacionada",
                        } },
                        #endregion
                        #region ComisionRelacionada_relacion_
                        { "ComisionRelacionada_relacion_", new () {
                            fieldName = "relacion",
                            entityName = "comision_relacionada",
                        } },
                        #endregion
                        #region Curso_
                        { "Curso_", new () {
                            fieldName = "comision",
                            entityName = "curso",
                        } },
                        #endregion
                    },
                    fields = {
                        #region comision.id
                        {
                            "id", new () {
                                entityName = "comision",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.turno
                        {
                            "turno", new () {
                                entityName = "comision",
                                name = "turno",
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
                            }
                        },
                        #endregion
                        #region comision.division
                        {
                            "division", new () {
                                entityName = "comision",
                                name = "division",
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
                            }
                        },
                        #endregion
                        #region comision.comentario
                        {
                            "comentario", new () {
                                entityName = "comision",
                                name = "comentario",
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
                            }
                        },
                        #endregion
                        #region comision.autorizada
                        {
                            "autorizada", new () {
                                entityName = "comision",
                                name = "autorizada",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.apertura
                        {
                            "apertura", new () {
                                entityName = "comision",
                                name = "apertura",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.publicada
                        {
                            "publicada", new () {
                                entityName = "comision",
                                name = "publicada",
                                dataType = "tinyint",
                                type = "bool",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.observaciones
                        {
                            "observaciones", new () {
                                entityName = "comision",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                        #region comision.alta
                        {
                            "alta", new () {
                                entityName = "comision",
                                name = "alta",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.sede
                        {
                            "sede", new () {
                                entityName = "comision",
                                name = "sede",
                                dataType = "varchar",
                                type = "string",
                                alias = "sed",
                                refEntityName = "sede",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.modalidad
                        {
                            "modalidad", new () {
                                entityName = "comision",
                                name = "modalidad",
                                dataType = "varchar",
                                type = "string",
                                alias = "mod",
                                refEntityName = "modalidad",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.planificacion
                        {
                            "planificacion", new () {
                                entityName = "comision",
                                name = "planificacion",
                                dataType = "varchar",
                                type = "string",
                                alias = "pla",
                                refEntityName = "planificacion",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.comision_siguiente
                        {
                            "comision_siguiente", new () {
                                entityName = "comision",
                                name = "comision_siguiente",
                                dataType = "varchar",
                                type = "string",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.calendario
                        {
                            "calendario", new () {
                                entityName = "comision",
                                name = "calendario",
                                dataType = "varchar",
                                type = "string",
                                alias = "cal",
                                refEntityName = "calendario",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.identificacion
                        {
                            "identificacion", new () {
                                entityName = "comision",
                                name = "identificacion",
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
                            }
                        },
                        #endregion
                        #region comision.estado
                        {
                            "estado", new () {
                                entityName = "comision",
                                name = "estado",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "Confirma",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.configuracion
                        {
                            "configuracion", new () {
                                entityName = "comision",
                                name = "configuracion",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "Histórica",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision.pfid
                        {
                            "pfid", new () {
                                entityName = "comision",
                                name = "pfid",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region comision_relacionada
            {
                "comision_relacionada", new () {
                    name = "comision_relacionada",
                    alias = "com1",
                    pk = [ "id" ],
                    fk = [ "comision", "relacion" ],
                    notNull = [ "id", "comision", "relacion" ],
                    tree = {
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                #region sede
                                { "sede", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        #region domicilio
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                        #region tipo_sede
                                        { "tipo_sede", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        #endregion
                                        #region centro_educativo
                                        { "centro_educativo", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                #region domicilio_cen
                                                { "domicilio_cen", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region modalidad
                                { "modalidad", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                #endregion
                                #region planificacion
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region calendario
                                { "calendario", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region relacion
                        { "relacion", new () {
                            fieldName = "relacion",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                #region sede_rel
                                { "sede_rel", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        #region domicilio_sed
                                        { "domicilio_sed", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                        #region tipo_sede_sed
                                        { "tipo_sede_sed", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        #endregion
                                        #region centro_educativo_sed
                                        { "centro_educativo_sed", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                #region domicilio_cen1
                                                { "domicilio_cen1", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region modalidad_rel
                                { "modalidad_rel", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                #endregion
                                #region planificacion_rel
                                { "planificacion_rel", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan_pla
                                        { "plan_pla", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region calendario_rel
                                { "calendario_rel", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        #endregion
                        #region relacion
                        { "relacion", new () {
                            fieldName = "relacion",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        #endregion
                        #region sede_rel
                        { "sede_rel", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "relacion",
                        } },
                        #endregion
                        #region domicilio_sed
                        { "domicilio_sed", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede_rel",
                        } },
                        #endregion
                        #region tipo_sede_sed
                        { "tipo_sede_sed", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede_rel",
                        } },
                        #endregion
                        #region centro_educativo_sed
                        { "centro_educativo_sed", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede_rel",
                        } },
                        #endregion
                        #region domicilio_cen1
                        { "domicilio_cen1", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo_sed",
                        } },
                        #endregion
                        #region modalidad_rel
                        { "modalidad_rel", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "relacion",
                        } },
                        #endregion
                        #region planificacion_rel
                        { "planificacion_rel", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "relacion",
                        } },
                        #endregion
                        #region plan_pla
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_rel",
                        } },
                        #endregion
                        #region calendario_rel
                        { "calendario_rel", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "relacion",
                        } },
                        #endregion
                    },
                    fields = {
                        #region comision_relacionada.id
                        {
                            "id", new () {
                                entityName = "comision_relacionada",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision_relacionada.comision
                        {
                            "comision", new () {
                                entityName = "comision_relacionada",
                                name = "comision",
                                dataType = "varchar",
                                type = "string",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region comision_relacionada.relacion
                        {
                            "relacion", new () {
                                entityName = "comision_relacionada",
                                name = "relacion",
                                dataType = "varchar",
                                type = "string",
                                alias = "rel",
                                refEntityName = "comision",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region contralor
            {
                "contralor", new () {
                    name = "contralor",
                    alias = "cont",
                    pk = [ "id" ],
                    fk = [ "planilla_docente" ],
                    notNull = [ "id", "insertado", "planilla_docente" ],
                    tree = {
                        #region planilla_docente
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                        } },
                        #endregion
                    },
                    relations = {
                        #region planilla_docente
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "",
                        } },
                        #endregion
                    },
                    fields = {
                        #region contralor.id
                        {
                            "id", new () {
                                entityName = "contralor",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region contralor.fecha_contralor
                        {
                            "fecha_contralor", new () {
                                entityName = "contralor",
                                name = "fecha_contralor",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region contralor.fecha_consejo
                        {
                            "fecha_consejo", new () {
                                entityName = "contralor",
                                name = "fecha_consejo",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region contralor.insertado
                        {
                            "insertado", new () {
                                entityName = "contralor",
                                name = "insertado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region contralor.planilla_docente
                        {
                            "planilla_docente", new () {
                                entityName = "contralor",
                                name = "planilla_docente",
                                dataType = "varchar",
                                type = "string",
                                alias = "pla",
                                refEntityName = "planilla_docente",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region curso
            {
                "curso", new () {
                    name = "curso",
                    alias = "curs",
                    pk = [ "id" ],
                    fk = [ "comision", "disposicion" ],
                    notNull = [ "id", "horas_catedra", "comision", "alta" ],
                    tree = {
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            children = new() {
                                #region sede
                                { "sede", new () {
                                    fieldName = "sede",
                                    refFieldName = "id",
                                    refEntityName = "sede",
                                    children = new() {
                                        #region domicilio
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                        #region tipo_sede
                                        { "tipo_sede", new () {
                                            fieldName = "tipo_sede",
                                            refFieldName = "id",
                                            refEntityName = "tipo_sede",
                                        } },
                                        #endregion
                                        #region centro_educativo
                                        { "centro_educativo", new () {
                                            fieldName = "centro_educativo",
                                            refFieldName = "id",
                                            refEntityName = "centro_educativo",
                                            children = new() {
                                                #region domicilio_cen
                                                { "domicilio_cen", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region modalidad
                                { "modalidad", new () {
                                    fieldName = "modalidad",
                                    refFieldName = "id",
                                    refEntityName = "modalidad",
                                } },
                                #endregion
                                #region planificacion
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region calendario
                                { "calendario", new () {
                                    fieldName = "calendario",
                                    refFieldName = "id",
                                    refEntityName = "calendario",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                #region asignatura
                                { "asignatura", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                #endregion
                                #region planificacion_dis
                                { "planificacion_dis", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan_pla
                                        { "plan_pla", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        #endregion
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        #endregion
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region planificacion_dis
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region plan_pla
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        #endregion
                    },
                    om = {
                        #region Calificacion_
                        { "Calificacion_", new () {
                            fieldName = "curso",
                            entityName = "calificacion",
                        } },
                        #endregion
                        #region Horario_
                        { "Horario_", new () {
                            fieldName = "curso",
                            entityName = "horario",
                        } },
                        #endregion
                        #region Toma_
                        { "Toma_", new () {
                            fieldName = "curso",
                            entityName = "toma",
                        } },
                        #endregion
                    },
                    fields = {
                        #region curso.id
                        {
                            "id", new () {
                                entityName = "curso",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region curso.horas_catedra
                        {
                            "horas_catedra", new () {
                                entityName = "curso",
                                name = "horas_catedra",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region curso.ige
                        {
                            "ige", new () {
                                entityName = "curso",
                                name = "ige",
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
                            }
                        },
                        #endregion
                        #region curso.comision
                        {
                            "comision", new () {
                                entityName = "curso",
                                name = "comision",
                                dataType = "varchar",
                                type = "string",
                                alias = "com",
                                refEntityName = "comision",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region curso.alta
                        {
                            "alta", new () {
                                entityName = "curso",
                                name = "alta",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region curso.descripcion_horario
                        {
                            "descripcion_horario", new () {
                                entityName = "curso",
                                name = "descripcion_horario",
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
                            }
                        },
                        #endregion
                        #region curso.codigo
                        {
                            "codigo", new () {
                                entityName = "curso",
                                name = "codigo",
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
                            }
                        },
                        #endregion
                        #region curso.disposicion
                        {
                            "disposicion", new () {
                                entityName = "curso",
                                name = "disposicion",
                                dataType = "varchar",
                                type = "string",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region curso.observaciones
                        {
                            "observaciones", new () {
                                entityName = "curso",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region designacion
            {
                "designacion", new () {
                    name = "designacion",
                    alias = "desi",
                    pk = [ "id" ],
                    fk = [ "cargo", "sede", "persona" ],
                    notNull = [ "id", "cargo", "sede", "persona", "alta" ],
                    tree = {
                        #region cargo
                        { "cargo", new () {
                            fieldName = "cargo",
                            refFieldName = "id",
                            refEntityName = "cargo",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            children = new() {
                                #region domicilio
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                                #region tipo_sede
                                { "tipo_sede", new () {
                                    fieldName = "tipo_sede",
                                    refFieldName = "id",
                                    refEntityName = "tipo_sede",
                                } },
                                #endregion
                                #region centro_educativo
                                { "centro_educativo", new () {
                                    fieldName = "centro_educativo",
                                    refFieldName = "id",
                                    refEntityName = "centro_educativo",
                                    children = new() {
                                        #region domicilio_cen
                                        { "domicilio_cen", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                #region domicilio_per
                                { "domicilio_per", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region cargo
                        { "cargo", new () {
                            fieldName = "cargo",
                            refFieldName = "id",
                            refEntityName = "cargo",
                            parentId = "",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio_per
                        { "domicilio_per", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                    },
                    fields = {
                        #region designacion.id
                        {
                            "id", new () {
                                entityName = "designacion",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region designacion.desde
                        {
                            "desde", new () {
                                entityName = "designacion",
                                name = "desde",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region designacion.hasta
                        {
                            "hasta", new () {
                                entityName = "designacion",
                                name = "hasta",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region designacion.cargo
                        {
                            "cargo", new () {
                                entityName = "designacion",
                                name = "cargo",
                                dataType = "varchar",
                                type = "string",
                                alias = "car",
                                refEntityName = "cargo",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region designacion.sede
                        {
                            "sede", new () {
                                entityName = "designacion",
                                name = "sede",
                                dataType = "varchar",
                                type = "string",
                                alias = "sed",
                                refEntityName = "sede",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region designacion.persona
                        {
                            "persona", new () {
                                entityName = "designacion",
                                name = "persona",
                                dataType = "varchar",
                                type = "string",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region designacion.alta
                        {
                            "alta", new () {
                                entityName = "designacion",
                                name = "alta",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region designacion.pfid
                        {
                            "pfid", new () {
                                entityName = "designacion",
                                name = "pfid",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region detalle_persona
            {
                "detalle_persona", new () {
                    name = "detalle_persona",
                    alias = "deta",
                    pk = [ "id" ],
                    fk = [ "archivo", "persona" ],
                    notNull = [ "id", "descripcion", "creado", "persona" ],
                    tree = {
                        #region archivo
                        { "archivo", new () {
                            fieldName = "archivo",
                            refFieldName = "id",
                            refEntityName = "file",
                        } },
                        #endregion
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                #region domicilio
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region archivo
                        { "archivo", new () {
                            fieldName = "archivo",
                            refFieldName = "id",
                            refEntityName = "file",
                            parentId = "",
                        } },
                        #endregion
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                    },
                    fields = {
                        #region detalle_persona.id
                        {
                            "id", new () {
                                entityName = "detalle_persona",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region detalle_persona.descripcion
                        {
                            "descripcion", new () {
                                entityName = "detalle_persona",
                                name = "descripcion",
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
                            }
                        },
                        #endregion
                        #region detalle_persona.archivo
                        {
                            "archivo", new () {
                                entityName = "detalle_persona",
                                name = "archivo",
                                dataType = "varchar",
                                type = "string",
                                alias = "arc",
                                refEntityName = "file",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region detalle_persona.creado
                        {
                            "creado", new () {
                                entityName = "detalle_persona",
                                name = "creado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region detalle_persona.persona
                        {
                            "persona", new () {
                                entityName = "detalle_persona",
                                name = "persona",
                                dataType = "varchar",
                                type = "string",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region detalle_persona.fecha
                        {
                            "fecha", new () {
                                entityName = "detalle_persona",
                                name = "fecha",
                                dataType = "date",
                                type = "DateTime",
                                defaultValue = "curdate()",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region detalle_persona.tipo
                        {
                            "tipo", new () {
                                entityName = "detalle_persona",
                                name = "tipo",
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
                            }
                        },
                        #endregion
                        #region detalle_persona.asunto
                        {
                            "asunto", new () {
                                entityName = "detalle_persona",
                                name = "asunto",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region dia
            {
                "dia", new () {
                    name = "dia",
                    alias = "dia",
                    pk = [ "id" ],
                    unique = [ "dia", "numero" ],
                    notNull = [ "id", "numero", "dia" ],
                    om = {
                        #region Horario_
                        { "Horario_", new () {
                            fieldName = "dia",
                            entityName = "horario",
                        } },
                        #endregion
                    },
                    fields = {
                        #region dia.id
                        {
                            "id", new () {
                                entityName = "dia",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region dia.numero
                        {
                            "numero", new () {
                                entityName = "dia",
                                name = "numero",
                                dataType = "smallint",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region dia.dia
                        {
                            "dia", new () {
                                entityName = "dia",
                                name = "dia",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region disposicion
            {
                "disposicion", new () {
                    name = "disposicion",
                    alias = "disp",
                    pk = [ "id" ],
                    fk = [ "asignatura", "planificacion" ],
                    notNull = [ "id", "asignatura", "planificacion" ],
                    tree = {
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            children = new() {
                                #region plan
                                { "plan", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                    },
                    om = {
                        #region Calificacion_
                        { "Calificacion_", new () {
                            fieldName = "disposicion",
                            entityName = "calificacion",
                        } },
                        #endregion
                        #region Curso_
                        { "Curso_", new () {
                            fieldName = "disposicion",
                            entityName = "curso",
                        } },
                        #endregion
                        #region DisposicionPendiente_
                        { "DisposicionPendiente_", new () {
                            fieldName = "disposicion",
                            entityName = "disposicion_pendiente",
                        } },
                        #endregion
                        #region DistribucionHoraria_
                        { "DistribucionHoraria_", new () {
                            fieldName = "disposicion",
                            entityName = "distribucion_horaria",
                        } },
                        #endregion
                    },
                    fields = {
                        #region disposicion.id
                        {
                            "id", new () {
                                entityName = "disposicion",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region disposicion.asignatura
                        {
                            "asignatura", new () {
                                entityName = "disposicion",
                                name = "asignatura",
                                dataType = "varchar",
                                type = "string",
                                alias = "asi",
                                refEntityName = "asignatura",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region disposicion.planificacion
                        {
                            "planificacion", new () {
                                entityName = "disposicion",
                                name = "planificacion",
                                dataType = "varchar",
                                type = "string",
                                alias = "pla",
                                refEntityName = "planificacion",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region disposicion.orden_informe_coordinacion_distrital
                        {
                            "orden_informe_coordinacion_distrital", new () {
                                entityName = "disposicion",
                                name = "orden_informe_coordinacion_distrital",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region disposicion_pendiente
            {
                "disposicion_pendiente", new () {
                    name = "disposicion_pendiente",
                    alias = "dis1",
                    pk = [ "id" ],
                    fk = [ "disposicion", "alumno" ],
                    notNull = [ "id", "disposicion", "alumno" ],
                    tree = {
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                #region asignatura
                                { "asignatura", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                #endregion
                                #region planificacion
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region alumno
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            children = new() {
                                #region persona
                                { "persona", new () {
                                    fieldName = "persona",
                                    refFieldName = "id",
                                    refEntityName = "persona",
                                    children = new() {
                                        #region domicilio
                                        { "domicilio", new () {
                                            fieldName = "domicilio",
                                            refFieldName = "id",
                                            refEntityName = "domicilio",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region plan_alu
                                { "plan_alu", new () {
                                    fieldName = "plan",
                                    refFieldName = "id",
                                    refEntityName = "plan",
                                } },
                                #endregion
                                #region resolucion_inscripcion
                                { "resolucion_inscripcion", new () {
                                    fieldName = "resolucion_inscripcion",
                                    refFieldName = "id",
                                    refEntityName = "resolucion",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        #endregion
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region alumno
                        { "alumno", new () {
                            fieldName = "alumno",
                            refFieldName = "id",
                            refEntityName = "alumno",
                            parentId = "",
                        } },
                        #endregion
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "alumno",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                        #region plan_alu
                        { "plan_alu", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "alumno",
                        } },
                        #endregion
                        #region resolucion_inscripcion
                        { "resolucion_inscripcion", new () {
                            fieldName = "resolucion_inscripcion",
                            refFieldName = "id",
                            refEntityName = "resolucion",
                            parentId = "alumno",
                        } },
                        #endregion
                    },
                    fields = {
                        #region disposicion_pendiente.id
                        {
                            "id", new () {
                                entityName = "disposicion_pendiente",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region disposicion_pendiente.disposicion
                        {
                            "disposicion", new () {
                                entityName = "disposicion_pendiente",
                                name = "disposicion",
                                dataType = "varchar",
                                type = "string",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region disposicion_pendiente.alumno
                        {
                            "alumno", new () {
                                entityName = "disposicion_pendiente",
                                name = "alumno",
                                dataType = "varchar",
                                type = "string",
                                alias = "alu",
                                refEntityName = "alumno",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region disposicion_pendiente.modo
                        {
                            "modo", new () {
                                entityName = "disposicion_pendiente",
                                name = "modo",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region distribucion_horaria
            {
                "distribucion_horaria", new () {
                    name = "distribucion_horaria",
                    alias = "dist",
                    pk = [ "id" ],
                    fk = [ "disposicion" ],
                    notNull = [ "id", "horas_catedra", "dia" ],
                    tree = {
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            children = new() {
                                #region asignatura
                                { "asignatura", new () {
                                    fieldName = "asignatura",
                                    refFieldName = "id",
                                    refEntityName = "asignatura",
                                } },
                                #endregion
                                #region planificacion
                                { "planificacion", new () {
                                    fieldName = "planificacion",
                                    refFieldName = "id",
                                    refEntityName = "planificacion",
                                    children = new() {
                                        #region plan
                                        { "plan", new () {
                                            fieldName = "plan",
                                            refFieldName = "id",
                                            refEntityName = "plan",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "",
                        } },
                        #endregion
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                    },
                    fields = {
                        #region distribucion_horaria.id
                        {
                            "id", new () {
                                entityName = "distribucion_horaria",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region distribucion_horaria.horas_catedra
                        {
                            "horas_catedra", new () {
                                entityName = "distribucion_horaria",
                                name = "horas_catedra",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region distribucion_horaria.dia
                        {
                            "dia", new () {
                                entityName = "distribucion_horaria",
                                name = "dia",
                                dataType = "int",
                                type = "int",
                                checks = new () {
                                        { "type", "int" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region distribucion_horaria.disposicion
                        {
                            "disposicion", new () {
                                entityName = "distribucion_horaria",
                                name = "disposicion",
                                dataType = "varchar",
                                type = "string",
                                alias = "dis",
                                refEntityName = "disposicion",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region domicilio
            {
                "domicilio", new () {
                    name = "domicilio",
                    alias = "domi",
                    pk = [ "id" ],
                    notNull = [ "id", "calle", "numero", "localidad" ],
                    om = {
                        #region CentroEducativo_
                        { "CentroEducativo_", new () {
                            fieldName = "domicilio",
                            entityName = "centro_educativo",
                        } },
                        #endregion
                        #region Persona_
                        { "Persona_", new () {
                            fieldName = "domicilio",
                            entityName = "persona",
                        } },
                        #endregion
                        #region Sede_
                        { "Sede_", new () {
                            fieldName = "domicilio",
                            entityName = "sede",
                        } },
                        #endregion
                    },
                    fields = {
                        #region domicilio.id
                        {
                            "id", new () {
                                entityName = "domicilio",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region domicilio.calle
                        {
                            "calle", new () {
                                entityName = "domicilio",
                                name = "calle",
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
                            }
                        },
                        #endregion
                        #region domicilio.entre
                        {
                            "entre", new () {
                                entityName = "domicilio",
                                name = "entre",
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
                            }
                        },
                        #endregion
                        #region domicilio.numero
                        {
                            "numero", new () {
                                entityName = "domicilio",
                                name = "numero",
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
                            }
                        },
                        #endregion
                        #region domicilio.piso
                        {
                            "piso", new () {
                                entityName = "domicilio",
                                name = "piso",
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
                            }
                        },
                        #endregion
                        #region domicilio.departamento
                        {
                            "departamento", new () {
                                entityName = "domicilio",
                                name = "departamento",
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
                            }
                        },
                        #endregion
                        #region domicilio.barrio
                        {
                            "barrio", new () {
                                entityName = "domicilio",
                                name = "barrio",
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
                            }
                        },
                        #endregion
                        #region domicilio.localidad
                        {
                            "localidad", new () {
                                entityName = "domicilio",
                                name = "localidad",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region email
            {
                "email", new () {
                    name = "email",
                    alias = "emai",
                    pk = [ "id" ],
                    fk = [ "persona" ],
                    notNull = [ "id", "email", "verificado", "insertado", "persona" ],
                    tree = {
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                #region domicilio
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                    },
                    fields = {
                        #region email.id
                        {
                            "id", new () {
                                entityName = "email",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region email.email
                        {
                            "email", new () {
                                entityName = "email",
                                name = "email",
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
                            }
                        },
                        #endregion
                        #region email.verificado
                        {
                            "verificado", new () {
                                entityName = "email",
                                name = "verificado",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region email.insertado
                        {
                            "insertado", new () {
                                entityName = "email",
                                name = "insertado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region email.eliminado
                        {
                            "eliminado", new () {
                                entityName = "email",
                                name = "eliminado",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region email.persona
                        {
                            "persona", new () {
                                entityName = "email",
                                name = "persona",
                                dataType = "varchar",
                                type = "string",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region file
            {
                "file", new () {
                    name = "file",
                    alias = "file",
                    pk = [ "id" ],
                    notNull = [ "id", "name", "type", "content", "size", "created" ],
                    om = {
                        #region DetallePersona_archivo_
                        { "DetallePersona_archivo_", new () {
                            fieldName = "archivo",
                            entityName = "detalle_persona",
                        } },
                        #endregion
                    },
                    fields = {
                        #region file.id
                        {
                            "id", new () {
                                entityName = "file",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region file.name
                        {
                            "name", new () {
                                entityName = "file",
                                name = "name",
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
                            }
                        },
                        #endregion
                        #region file.type
                        {
                            "type", new () {
                                entityName = "file",
                                name = "type",
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
                            }
                        },
                        #endregion
                        #region file.content
                        {
                            "content", new () {
                                entityName = "file",
                                name = "content",
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
                            }
                        },
                        #endregion
                        #region file.size
                        {
                            "size", new () {
                                entityName = "file",
                                name = "size",
                                dataType = "int",
                                type = "uint",
                                checks = new () {
                                        { "type", "uint" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region file.created
                        {
                            "created", new () {
                                entityName = "file",
                                name = "created",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region horario
            {
                "horario", new () {
                    name = "horario",
                    alias = "hora",
                    pk = [ "id" ],
                    fk = [ "curso", "dia" ],
                    notNull = [ "id", "hora_inicio", "hora_fin", "curso", "dia" ],
                    tree = {
                        #region curso
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            children = new() {
                                #region comision
                                { "comision", new () {
                                    fieldName = "comision",
                                    refFieldName = "id",
                                    refEntityName = "comision",
                                    children = new() {
                                        #region sede
                                        { "sede", new () {
                                            fieldName = "sede",
                                            refFieldName = "id",
                                            refEntityName = "sede",
                                            children = new() {
                                                #region domicilio
                                                { "domicilio", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                #endregion
                                                #region tipo_sede
                                                { "tipo_sede", new () {
                                                    fieldName = "tipo_sede",
                                                    refFieldName = "id",
                                                    refEntityName = "tipo_sede",
                                                } },
                                                #endregion
                                                #region centro_educativo
                                                { "centro_educativo", new () {
                                                    fieldName = "centro_educativo",
                                                    refFieldName = "id",
                                                    refEntityName = "centro_educativo",
                                                    children = new() {
                                                        #region domicilio_cen
                                                        { "domicilio_cen", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                        #endregion
                                                    },
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                        #region modalidad
                                        { "modalidad", new () {
                                            fieldName = "modalidad",
                                            refFieldName = "id",
                                            refEntityName = "modalidad",
                                        } },
                                        #endregion
                                        #region planificacion
                                        { "planificacion", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                #region plan
                                                { "plan", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                        #region calendario
                                        { "calendario", new () {
                                            fieldName = "calendario",
                                            refFieldName = "id",
                                            refEntityName = "calendario",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region disposicion
                                { "disposicion", new () {
                                    fieldName = "disposicion",
                                    refFieldName = "id",
                                    refEntityName = "disposicion",
                                    children = new() {
                                        #region asignatura
                                        { "asignatura", new () {
                                            fieldName = "asignatura",
                                            refFieldName = "id",
                                            refEntityName = "asignatura",
                                        } },
                                        #endregion
                                        #region planificacion_dis
                                        { "planificacion_dis", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                #region plan_pla
                                                { "plan_pla", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region dia
                        { "dia", new () {
                            fieldName = "dia",
                            refFieldName = "id",
                            refEntityName = "dia",
                        } },
                        #endregion
                    },
                    relations = {
                        #region curso
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "",
                        } },
                        #endregion
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        #endregion
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        #endregion
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region planificacion_dis
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region plan_pla
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        #endregion
                        #region dia
                        { "dia", new () {
                            fieldName = "dia",
                            refFieldName = "id",
                            refEntityName = "dia",
                            parentId = "",
                        } },
                        #endregion
                    },
                    fields = {
                        #region horario.id
                        {
                            "id", new () {
                                entityName = "horario",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region horario.hora_inicio
                        {
                            "hora_inicio", new () {
                                entityName = "horario",
                                name = "hora_inicio",
                                dataType = "time",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region horario.hora_fin
                        {
                            "hora_fin", new () {
                                entityName = "horario",
                                name = "hora_fin",
                                dataType = "time",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region horario.curso
                        {
                            "curso", new () {
                                entityName = "horario",
                                name = "curso",
                                dataType = "varchar",
                                type = "string",
                                alias = "cur",
                                refEntityName = "curso",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region horario.dia
                        {
                            "dia", new () {
                                entityName = "horario",
                                name = "dia",
                                dataType = "varchar",
                                type = "string",
                                alias = "dia",
                                refEntityName = "dia",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region modalidad
            {
                "modalidad", new () {
                    name = "modalidad",
                    alias = "moda",
                    pk = [ "id" ],
                    unique = [ "nombre" ],
                    notNull = [ "id", "nombre" ],
                    om = {
                        #region Comision_
                        { "Comision_", new () {
                            fieldName = "modalidad",
                            entityName = "comision",
                        } },
                        #endregion
                    },
                    fields = {
                        #region modalidad.id
                        {
                            "id", new () {
                                entityName = "modalidad",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region modalidad.nombre
                        {
                            "nombre", new () {
                                entityName = "modalidad",
                                name = "nombre",
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
                            }
                        },
                        #endregion
                        #region modalidad.pfid
                        {
                            "pfid", new () {
                                entityName = "modalidad",
                                name = "pfid",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region persona
            {
                "persona", new () {
                    name = "persona",
                    alias = "pers",
                    pk = [ "id" ],
                    fk = [ "domicilio" ],
                    unique = [ "cuil", "email_abc", "numero_documento" ],
                    notNull = [ "id", "nombres", "numero_documento", "alta", "telefono_verificado", "email_verificado", "info_verificada" ],
                    tree = {
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                        } },
                        #endregion
                    },
                    relations = {
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "",
                        } },
                        #endregion
                    },
                    oo = {
                        #region Alumno_
                        { "Alumno_", new () {
                            fieldName = "persona",
                            entityName = "alumno",
                        } },
                        #endregion
                    },
                    om = {
                        #region Designacion_
                        { "Designacion_", new () {
                            fieldName = "persona",
                            entityName = "designacion",
                        } },
                        #endregion
                        #region DetallePersona_
                        { "DetallePersona_", new () {
                            fieldName = "persona",
                            entityName = "detalle_persona",
                        } },
                        #endregion
                        #region Email_
                        { "Email_", new () {
                            fieldName = "persona",
                            entityName = "email",
                        } },
                        #endregion
                        #region Telefono_
                        { "Telefono_", new () {
                            fieldName = "persona",
                            entityName = "telefono",
                        } },
                        #endregion
                        #region Toma_docente_
                        { "Toma_docente_", new () {
                            fieldName = "docente",
                            entityName = "toma",
                        } },
                        #endregion
                        #region Toma_reemplazo_
                        { "Toma_reemplazo_", new () {
                            fieldName = "reemplazo",
                            entityName = "toma",
                        } },
                        #endregion
                    },
                    fields = {
                        #region persona.id
                        {
                            "id", new () {
                                entityName = "persona",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region persona.nombres
                        {
                            "nombres", new () {
                                entityName = "persona",
                                name = "nombres",
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
                            }
                        },
                        #endregion
                        #region persona.apellidos
                        {
                            "apellidos", new () {
                                entityName = "persona",
                                name = "apellidos",
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
                            }
                        },
                        #endregion
                        #region persona.fecha_nacimiento
                        {
                            "fecha_nacimiento", new () {
                                entityName = "persona",
                                name = "fecha_nacimiento",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region persona.numero_documento
                        {
                            "numero_documento", new () {
                                entityName = "persona",
                                name = "numero_documento",
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
                            }
                        },
                        #endregion
                        #region persona.cuil
                        {
                            "cuil", new () {
                                entityName = "persona",
                                name = "cuil",
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
                            }
                        },
                        #endregion
                        #region persona.genero
                        {
                            "genero", new () {
                                entityName = "persona",
                                name = "genero",
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
                            }
                        },
                        #endregion
                        #region persona.apodo
                        {
                            "apodo", new () {
                                entityName = "persona",
                                name = "apodo",
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
                            }
                        },
                        #endregion
                        #region persona.telefono
                        {
                            "telefono", new () {
                                entityName = "persona",
                                name = "telefono",
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
                            }
                        },
                        #endregion
                        #region persona.email
                        {
                            "email", new () {
                                entityName = "persona",
                                name = "email",
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
                            }
                        },
                        #endregion
                        #region persona.email_abc
                        {
                            "email_abc", new () {
                                entityName = "persona",
                                name = "email_abc",
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
                            }
                        },
                        #endregion
                        #region persona.alta
                        {
                            "alta", new () {
                                entityName = "persona",
                                name = "alta",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region persona.domicilio
                        {
                            "domicilio", new () {
                                entityName = "persona",
                                name = "domicilio",
                                dataType = "varchar",
                                type = "string",
                                alias = "dom",
                                refEntityName = "domicilio",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region persona.lugar_nacimiento
                        {
                            "lugar_nacimiento", new () {
                                entityName = "persona",
                                name = "lugar_nacimiento",
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
                            }
                        },
                        #endregion
                        #region persona.telefono_verificado
                        {
                            "telefono_verificado", new () {
                                entityName = "persona",
                                name = "telefono_verificado",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region persona.email_verificado
                        {
                            "email_verificado", new () {
                                entityName = "persona",
                                name = "email_verificado",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region persona.info_verificada
                        {
                            "info_verificada", new () {
                                entityName = "persona",
                                name = "info_verificada",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region persona.descripcion_domicilio
                        {
                            "descripcion_domicilio", new () {
                                entityName = "persona",
                                name = "descripcion_domicilio",
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
                            }
                        },
                        #endregion
                        #region persona.cuil1
                        {
                            "cuil1", new () {
                                entityName = "persona",
                                name = "cuil1",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            }
                        },
                        #endregion
                        #region persona.cuil2
                        {
                            "cuil2", new () {
                                entityName = "persona",
                                name = "cuil2",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            }
                        },
                        #endregion
                        #region persona.departamento
                        {
                            "departamento", new () {
                                entityName = "persona",
                                name = "departamento",
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
                            }
                        },
                        #endregion
                        #region persona.localidad
                        {
                            "localidad", new () {
                                entityName = "persona",
                                name = "localidad",
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
                            }
                        },
                        #endregion
                        #region persona.partido
                        {
                            "partido", new () {
                                entityName = "persona",
                                name = "partido",
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
                            }
                        },
                        #endregion
                        #region persona.codigo_area
                        {
                            "codigo_area", new () {
                                entityName = "persona",
                                name = "codigo_area",
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
                            }
                        },
                        #endregion
                        #region persona.nacionalidad
                        {
                            "nacionalidad", new () {
                                entityName = "persona",
                                name = "nacionalidad",
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
                            }
                        },
                        #endregion
                        #region persona.sexo
                        {
                            "sexo", new () {
                                entityName = "persona",
                                name = "sexo",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            }
                        },
                        #endregion
                        #region persona.dia_nacimiento
                        {
                            "dia_nacimiento", new () {
                                entityName = "persona",
                                name = "dia_nacimiento",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            }
                        },
                        #endregion
                        #region persona.mes_nacimiento
                        {
                            "mes_nacimiento", new () {
                                entityName = "persona",
                                name = "mes_nacimiento",
                                dataType = "tinyint",
                                type = "byte",
                                checks = new () {
                                        { "type", "byte" },
                                },
                            }
                        },
                        #endregion
                        #region persona.anio_nacimiento
                        {
                            "anio_nacimiento", new () {
                                entityName = "persona",
                                name = "anio_nacimiento",
                                dataType = "smallint",
                                type = "ushort",
                                checks = new () {
                                        { "type", "ushort" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region plan
            {
                "plan", new () {
                    name = "plan",
                    alias = "plan",
                    pk = [ "id" ],
                    notNull = [ "id", "orientacion" ],
                    om = {
                        #region Alumno_
                        { "Alumno_", new () {
                            fieldName = "plan",
                            entityName = "alumno",
                        } },
                        #endregion
                        #region Planificacion_
                        { "Planificacion_", new () {
                            fieldName = "plan",
                            entityName = "planificacion",
                        } },
                        #endregion
                    },
                    fields = {
                        #region plan.id
                        {
                            "id", new () {
                                entityName = "plan",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region plan.orientacion
                        {
                            "orientacion", new () {
                                entityName = "plan",
                                name = "orientacion",
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
                            }
                        },
                        #endregion
                        #region plan.resolucion
                        {
                            "resolucion", new () {
                                entityName = "plan",
                                name = "resolucion",
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
                            }
                        },
                        #endregion
                        #region plan.distribucion_horaria
                        {
                            "distribucion_horaria", new () {
                                entityName = "plan",
                                name = "distribucion_horaria",
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
                            }
                        },
                        #endregion
                        #region plan.pfid
                        {
                            "pfid", new () {
                                entityName = "plan",
                                name = "pfid",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region planificacion
            {
                "planificacion", new () {
                    name = "planificacion",
                    alias = "pla1",
                    pk = [ "id" ],
                    fk = [ "plan" ],
                    notNull = [ "id", "anio", "semestre", "plan" ],
                    tree = {
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                        } },
                        #endregion
                    },
                    relations = {
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "",
                        } },
                        #endregion
                    },
                    om = {
                        #region Comision_
                        { "Comision_", new () {
                            fieldName = "planificacion",
                            entityName = "comision",
                        } },
                        #endregion
                        #region Disposicion_
                        { "Disposicion_", new () {
                            fieldName = "planificacion",
                            entityName = "disposicion",
                        } },
                        #endregion
                    },
                    fields = {
                        #region planificacion.id
                        {
                            "id", new () {
                                entityName = "planificacion",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region planificacion.anio
                        {
                            "anio", new () {
                                entityName = "planificacion",
                                name = "anio",
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
                            }
                        },
                        #endregion
                        #region planificacion.semestre
                        {
                            "semestre", new () {
                                entityName = "planificacion",
                                name = "semestre",
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
                            }
                        },
                        #endregion
                        #region planificacion.plan
                        {
                            "plan", new () {
                                entityName = "planificacion",
                                name = "plan",
                                dataType = "varchar",
                                type = "string",
                                alias = "pla",
                                refEntityName = "plan",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region planificacion.pfid
                        {
                            "pfid", new () {
                                entityName = "planificacion",
                                name = "pfid",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region planilla_docente
            {
                "planilla_docente", new () {
                    name = "planilla_docente",
                    alias = "pla2",
                    pk = [ "id" ],
                    notNull = [ "id", "numero", "insertado" ],
                    om = {
                        #region AsignacionPlanillaDocente_
                        { "AsignacionPlanillaDocente_", new () {
                            fieldName = "planilla_docente",
                            entityName = "asignacion_planilla_docente",
                        } },
                        #endregion
                        #region Contralor_
                        { "Contralor_", new () {
                            fieldName = "planilla_docente",
                            entityName = "contralor",
                        } },
                        #endregion
                        #region Toma_
                        { "Toma_", new () {
                            fieldName = "planilla_docente",
                            entityName = "toma",
                        } },
                        #endregion
                    },
                    fields = {
                        #region planilla_docente.id
                        {
                            "id", new () {
                                entityName = "planilla_docente",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region planilla_docente.numero
                        {
                            "numero", new () {
                                entityName = "planilla_docente",
                                name = "numero",
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
                            }
                        },
                        #endregion
                        #region planilla_docente.insertado
                        {
                            "insertado", new () {
                                entityName = "planilla_docente",
                                name = "insertado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region planilla_docente.fecha_contralor
                        {
                            "fecha_contralor", new () {
                                entityName = "planilla_docente",
                                name = "fecha_contralor",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region planilla_docente.fecha_consejo
                        {
                            "fecha_consejo", new () {
                                entityName = "planilla_docente",
                                name = "fecha_consejo",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region planilla_docente.observaciones
                        {
                            "observaciones", new () {
                                entityName = "planilla_docente",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region resolucion
            {
                "resolucion", new () {
                    name = "resolucion",
                    alias = "reso",
                    pk = [ "id" ],
                    notNull = [ "id", "numero" ],
                    om = {
                        #region Alumno_
                        { "Alumno_", new () {
                            fieldName = "resolucion_inscripcion",
                            entityName = "alumno",
                        } },
                        #endregion
                    },
                    fields = {
                        #region resolucion.id
                        {
                            "id", new () {
                                entityName = "resolucion",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region resolucion.numero
                        {
                            "numero", new () {
                                entityName = "resolucion",
                                name = "numero",
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
                            }
                        },
                        #endregion
                        #region resolucion.anio
                        {
                            "anio", new () {
                                entityName = "resolucion",
                                name = "anio",
                                dataType = "year",
                                type = "short",
                                checks = new () {
                                        { "type", "short" },
                                },
                            }
                        },
                        #endregion
                        #region resolucion.tipo
                        {
                            "tipo", new () {
                                entityName = "resolucion",
                                name = "tipo",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region sede
            {
                "sede", new () {
                    name = "sede",
                    alias = "sede",
                    pk = [ "id" ],
                    fk = [ "domicilio", "tipo_sede", "centro_educativo", "organizacion" ],
                    notNull = [ "id", "numero", "nombre", "alta" ],
                    tree = {
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            children = new() {
                                #region domicilio_cen
                                { "domicilio_cen", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                    },
                    om = {
                        #region Comision_
                        { "Comision_", new () {
                            fieldName = "sede",
                            entityName = "comision",
                        } },
                        #endregion
                        #region Designacion_
                        { "Designacion_", new () {
                            fieldName = "sede",
                            entityName = "designacion",
                        } },
                        #endregion
                    },
                    fields = {
                        #region sede.id
                        {
                            "id", new () {
                                entityName = "sede",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region sede.numero
                        {
                            "numero", new () {
                                entityName = "sede",
                                name = "numero",
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
                            }
                        },
                        #endregion
                        #region sede.nombre
                        {
                            "nombre", new () {
                                entityName = "sede",
                                name = "nombre",
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
                            }
                        },
                        #endregion
                        #region sede.observaciones
                        {
                            "observaciones", new () {
                                entityName = "sede",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                        #region sede.alta
                        {
                            "alta", new () {
                                entityName = "sede",
                                name = "alta",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region sede.baja
                        {
                            "baja", new () {
                                entityName = "sede",
                                name = "baja",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region sede.domicilio
                        {
                            "domicilio", new () {
                                entityName = "sede",
                                name = "domicilio",
                                dataType = "varchar",
                                type = "string",
                                alias = "dom",
                                refEntityName = "domicilio",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region sede.tipo_sede
                        {
                            "tipo_sede", new () {
                                entityName = "sede",
                                name = "tipo_sede",
                                dataType = "varchar",
                                type = "string",
                                alias = "tip",
                                refEntityName = "tipo_sede",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region sede.centro_educativo
                        {
                            "centro_educativo", new () {
                                entityName = "sede",
                                name = "centro_educativo",
                                dataType = "varchar",
                                type = "string",
                                alias = "cen",
                                refEntityName = "centro_educativo",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region sede.fecha_traspaso
                        {
                            "fecha_traspaso", new () {
                                entityName = "sede",
                                name = "fecha_traspaso",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region sede.organizacion
                        {
                            "organizacion", new () {
                                entityName = "sede",
                                name = "organizacion",
                                dataType = "varchar",
                                type = "string",
                                alias = "org",
                                refEntityName = "sede",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region sede.pfid
                        {
                            "pfid", new () {
                                entityName = "sede",
                                name = "pfid",
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
                            }
                        },
                        #endregion
                        #region sede.pfid_organizacion
                        {
                            "pfid_organizacion", new () {
                                entityName = "sede",
                                name = "pfid_organizacion",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region telefono
            {
                "telefono", new () {
                    name = "telefono",
                    alias = "tele",
                    pk = [ "id" ],
                    fk = [ "persona" ],
                    notNull = [ "id", "numero", "insertado", "persona" ],
                    tree = {
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                #region domicilio
                                { "domicilio", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                    },
                    relations = {
                        #region persona
                        { "persona", new () {
                            fieldName = "persona",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "persona",
                        } },
                        #endregion
                    },
                    fields = {
                        #region telefono.id
                        {
                            "id", new () {
                                entityName = "telefono",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region telefono.tipo
                        {
                            "tipo", new () {
                                entityName = "telefono",
                                name = "tipo",
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
                            }
                        },
                        #endregion
                        #region telefono.prefijo
                        {
                            "prefijo", new () {
                                entityName = "telefono",
                                name = "prefijo",
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
                            }
                        },
                        #endregion
                        #region telefono.numero
                        {
                            "numero", new () {
                                entityName = "telefono",
                                name = "numero",
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
                            }
                        },
                        #endregion
                        #region telefono.insertado
                        {
                            "insertado", new () {
                                entityName = "telefono",
                                name = "insertado",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region telefono.eliminado
                        {
                            "eliminado", new () {
                                entityName = "telefono",
                                name = "eliminado",
                                dataType = "timestamp",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region telefono.persona
                        {
                            "persona", new () {
                                entityName = "telefono",
                                name = "persona",
                                dataType = "varchar",
                                type = "string",
                                alias = "per",
                                refEntityName = "persona",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region tipo_sede
            {
                "tipo_sede", new () {
                    name = "tipo_sede",
                    alias = "tipo",
                    pk = [ "id" ],
                    unique = [ "descripcion" ],
                    notNull = [ "id", "descripcion" ],
                    om = {
                        #region Sede_
                        { "Sede_", new () {
                            fieldName = "tipo_sede",
                            entityName = "sede",
                        } },
                        #endregion
                    },
                    fields = {
                        #region tipo_sede.id
                        {
                            "id", new () {
                                entityName = "tipo_sede",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region tipo_sede.descripcion
                        {
                            "descripcion", new () {
                                entityName = "tipo_sede",
                                name = "descripcion",
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
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
            #region toma
            {
                "toma", new () {
                    name = "toma",
                    alias = "toma",
                    pk = [ "id" ],
                    fk = [ "curso", "docente", "reemplazo", "planilla_docente" ],
                    notNull = [ "id", "tipo_movimiento", "alta", "curso", "calificacion", "temas_tratados", "asistencia", "sin_planillas", "confirmada" ],
                    tree = {
                        #region curso
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            children = new() {
                                #region comision
                                { "comision", new () {
                                    fieldName = "comision",
                                    refFieldName = "id",
                                    refEntityName = "comision",
                                    children = new() {
                                        #region sede
                                        { "sede", new () {
                                            fieldName = "sede",
                                            refFieldName = "id",
                                            refEntityName = "sede",
                                            children = new() {
                                                #region domicilio
                                                { "domicilio", new () {
                                                    fieldName = "domicilio",
                                                    refFieldName = "id",
                                                    refEntityName = "domicilio",
                                                } },
                                                #endregion
                                                #region tipo_sede
                                                { "tipo_sede", new () {
                                                    fieldName = "tipo_sede",
                                                    refFieldName = "id",
                                                    refEntityName = "tipo_sede",
                                                } },
                                                #endregion
                                                #region centro_educativo
                                                { "centro_educativo", new () {
                                                    fieldName = "centro_educativo",
                                                    refFieldName = "id",
                                                    refEntityName = "centro_educativo",
                                                    children = new() {
                                                        #region domicilio_cen
                                                        { "domicilio_cen", new () {
                                                            fieldName = "domicilio",
                                                            refFieldName = "id",
                                                            refEntityName = "domicilio",
                                                        } },
                                                        #endregion
                                                    },
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                        #region modalidad
                                        { "modalidad", new () {
                                            fieldName = "modalidad",
                                            refFieldName = "id",
                                            refEntityName = "modalidad",
                                        } },
                                        #endregion
                                        #region planificacion
                                        { "planificacion", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                #region plan
                                                { "plan", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                        #region calendario
                                        { "calendario", new () {
                                            fieldName = "calendario",
                                            refFieldName = "id",
                                            refEntityName = "calendario",
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                                #region disposicion
                                { "disposicion", new () {
                                    fieldName = "disposicion",
                                    refFieldName = "id",
                                    refEntityName = "disposicion",
                                    children = new() {
                                        #region asignatura
                                        { "asignatura", new () {
                                            fieldName = "asignatura",
                                            refFieldName = "id",
                                            refEntityName = "asignatura",
                                        } },
                                        #endregion
                                        #region planificacion_dis
                                        { "planificacion_dis", new () {
                                            fieldName = "planificacion",
                                            refFieldName = "id",
                                            refEntityName = "planificacion",
                                            children = new() {
                                                #region plan_pla
                                                { "plan_pla", new () {
                                                    fieldName = "plan",
                                                    refFieldName = "id",
                                                    refEntityName = "plan",
                                                } },
                                                #endregion
                                            },
                                        } },
                                        #endregion
                                    },
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region docente
                        { "docente", new () {
                            fieldName = "docente",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                #region domicilio_doc
                                { "domicilio_doc", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region reemplazo
                        { "reemplazo", new () {
                            fieldName = "reemplazo",
                            refFieldName = "id",
                            refEntityName = "persona",
                            children = new() {
                                #region domicilio_ree
                                { "domicilio_ree", new () {
                                    fieldName = "domicilio",
                                    refFieldName = "id",
                                    refEntityName = "domicilio",
                                } },
                                #endregion
                            },
                        } },
                        #endregion
                        #region planilla_docente
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                        } },
                        #endregion
                    },
                    relations = {
                        #region curso
                        { "curso", new () {
                            fieldName = "curso",
                            refFieldName = "id",
                            refEntityName = "curso",
                            parentId = "",
                        } },
                        #endregion
                        #region comision
                        { "comision", new () {
                            fieldName = "comision",
                            refFieldName = "id",
                            refEntityName = "comision",
                            parentId = "curso",
                        } },
                        #endregion
                        #region sede
                        { "sede", new () {
                            fieldName = "sede",
                            refFieldName = "id",
                            refEntityName = "sede",
                            parentId = "comision",
                        } },
                        #endregion
                        #region domicilio
                        { "domicilio", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "sede",
                        } },
                        #endregion
                        #region tipo_sede
                        { "tipo_sede", new () {
                            fieldName = "tipo_sede",
                            refFieldName = "id",
                            refEntityName = "tipo_sede",
                            parentId = "sede",
                        } },
                        #endregion
                        #region centro_educativo
                        { "centro_educativo", new () {
                            fieldName = "centro_educativo",
                            refFieldName = "id",
                            refEntityName = "centro_educativo",
                            parentId = "sede",
                        } },
                        #endregion
                        #region domicilio_cen
                        { "domicilio_cen", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "centro_educativo",
                        } },
                        #endregion
                        #region modalidad
                        { "modalidad", new () {
                            fieldName = "modalidad",
                            refFieldName = "id",
                            refEntityName = "modalidad",
                            parentId = "comision",
                        } },
                        #endregion
                        #region planificacion
                        { "planificacion", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "comision",
                        } },
                        #endregion
                        #region plan
                        { "plan", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion",
                        } },
                        #endregion
                        #region calendario
                        { "calendario", new () {
                            fieldName = "calendario",
                            refFieldName = "id",
                            refEntityName = "calendario",
                            parentId = "comision",
                        } },
                        #endregion
                        #region disposicion
                        { "disposicion", new () {
                            fieldName = "disposicion",
                            refFieldName = "id",
                            refEntityName = "disposicion",
                            parentId = "curso",
                        } },
                        #endregion
                        #region asignatura
                        { "asignatura", new () {
                            fieldName = "asignatura",
                            refFieldName = "id",
                            refEntityName = "asignatura",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region planificacion_dis
                        { "planificacion_dis", new () {
                            fieldName = "planificacion",
                            refFieldName = "id",
                            refEntityName = "planificacion",
                            parentId = "disposicion",
                        } },
                        #endregion
                        #region plan_pla
                        { "plan_pla", new () {
                            fieldName = "plan",
                            refFieldName = "id",
                            refEntityName = "plan",
                            parentId = "planificacion_dis",
                        } },
                        #endregion
                        #region docente
                        { "docente", new () {
                            fieldName = "docente",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio_doc
                        { "domicilio_doc", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "docente",
                        } },
                        #endregion
                        #region reemplazo
                        { "reemplazo", new () {
                            fieldName = "reemplazo",
                            refFieldName = "id",
                            refEntityName = "persona",
                            parentId = "",
                        } },
                        #endregion
                        #region domicilio_ree
                        { "domicilio_ree", new () {
                            fieldName = "domicilio",
                            refFieldName = "id",
                            refEntityName = "domicilio",
                            parentId = "reemplazo",
                        } },
                        #endregion
                        #region planilla_docente
                        { "planilla_docente", new () {
                            fieldName = "planilla_docente",
                            refFieldName = "id",
                            refEntityName = "planilla_docente",
                            parentId = "",
                        } },
                        #endregion
                    },
                    om = {
                        #region AsignacionPlanillaDocente_
                        { "AsignacionPlanillaDocente_", new () {
                            fieldName = "toma",
                            entityName = "asignacion_planilla_docente",
                        } },
                        #endregion
                    },
                    fields = {
                        #region toma.id
                        {
                            "id", new () {
                                entityName = "toma",
                                name = "id",
                                dataType = "varchar",
                                type = "string",
                                defaultValue = "guid",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.fecha_toma
                        {
                            "fecha_toma", new () {
                                entityName = "toma",
                                name = "fecha_toma",
                                dataType = "date",
                                type = "DateTime",
                                checks = new () {
                                        { "type", "DateTime" },
                                },
                            }
                        },
                        #endregion
                        #region toma.estado
                        {
                            "estado", new () {
                                entityName = "toma",
                                name = "estado",
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
                            }
                        },
                        #endregion
                        #region toma.observaciones
                        {
                            "observaciones", new () {
                                entityName = "toma",
                                name = "observaciones",
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
                            }
                        },
                        #endregion
                        #region toma.comentario
                        {
                            "comentario", new () {
                                entityName = "toma",
                                name = "comentario",
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
                            }
                        },
                        #endregion
                        #region toma.tipo_movimiento
                        {
                            "tipo_movimiento", new () {
                                entityName = "toma",
                                name = "tipo_movimiento",
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
                            }
                        },
                        #endregion
                        #region toma.estado_contralor
                        {
                            "estado_contralor", new () {
                                entityName = "toma",
                                name = "estado_contralor",
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
                            }
                        },
                        #endregion
                        #region toma.alta
                        {
                            "alta", new () {
                                entityName = "toma",
                                name = "alta",
                                dataType = "timestamp",
                                type = "DateTime",
                                defaultValue = "current_timestamp()",
                                checks = new () {
                                        { "type", "DateTime" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.curso
                        {
                            "curso", new () {
                                entityName = "toma",
                                name = "curso",
                                dataType = "varchar",
                                type = "string",
                                alias = "cur",
                                refEntityName = "curso",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                        { "required", "True" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.docente
                        {
                            "docente", new () {
                                entityName = "toma",
                                name = "docente",
                                dataType = "varchar",
                                type = "string",
                                alias = "doc",
                                refEntityName = "persona",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.reemplazo
                        {
                            "reemplazo", new () {
                                entityName = "toma",
                                name = "reemplazo",
                                dataType = "varchar",
                                type = "string",
                                alias = "ree",
                                refEntityName = "persona",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.planilla_docente
                        {
                            "planilla_docente", new () {
                                entityName = "toma",
                                name = "planilla_docente",
                                dataType = "varchar",
                                type = "string",
                                alias = "pla",
                                refEntityName = "planilla_docente",
                                refFieldName = "id",
                                checks = new () {
                                        { "type", "string" },
                                },
                                resets = new () {
                                        { "trim", " " },
                                        { "removeMultipleSpaces", "True" },
                                        { "nullIfEmpty", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.calificacion
                        {
                            "calificacion", new () {
                                entityName = "toma",
                                name = "calificacion",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.temas_tratados
                        {
                            "temas_tratados", new () {
                                entityName = "toma",
                                name = "temas_tratados",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.asistencia
                        {
                            "asistencia", new () {
                                entityName = "toma",
                                name = "asistencia",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.sin_planillas
                        {
                            "sin_planillas", new () {
                                entityName = "toma",
                                name = "sin_planillas",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                        #region toma.confirmada
                        {
                            "confirmada", new () {
                                entityName = "toma",
                                name = "confirmada",
                                dataType = "tinyint",
                                type = "bool",
                                defaultValue = "False",
                                checks = new () {
                                        { "type", "bool" },
                                        { "required", "True" },
                                },
                            }
                        },
                        #endregion
                    },
                }
            },
            #endregion
        };
    }
}
