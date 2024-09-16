using Newtonsoft.Json;
using SqlOrganize.CollectionUtils;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalendarioDAO
    {

        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde el html procesado con pagemanipulator de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public static IEnumerable<EntityPersist> PersistTomasPfHtml(this Db db, Calendario calendarioObj, string data)
        {
            List<EntityPersist> persists = new();

            var pfidComisiones = db.ComisionesAutorizadasDeCalendarioSql(calendarioObj.id).Cache().Dicts().EnumOfVal<string>("pfid");
            var docentes = JsonConvert.DeserializeObject<List<DocentePfItem>>(data)!;
            
            foreach (DocentePfItem docenteItem in docentes)
            {
                EntityPersist persist = db.Persist();

                try
                {
                    #region insertar o actualizar docente (se insertan o actualizan todos)
                    var d = docenteItem.Dict();
                    if (!d["anio_nacimiento"].IsNoE() && !d["mes_nacimiento"].IsNoE() && !d["dia_nacimiento"].IsNoE())
                        d["fecha_nacimiento"] = new DateTime((int)d["anio_nacimiento"], (int)d["mes_nacimiento"], (int)d["dia_nacimiento"]);

                    (string? dni, string? cuil) = PersonaValues.CuilDni(d["numero_documento"]);

                    CompareParams compare = new CompareParams
                    {
                        FieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };
                    var personaVal = db.Values("persona").SetNotNull(d).Set("numero_documento", dni).Reset().
                       PersistCompare(persist, compare);
                    #endregion


                    #region insertar o actualizar cargo
                    bool existenCargos = false;

                    foreach (var cargo in docenteItem.cargos)
                    {

                        if (!pfidComisiones.Contains(cargo["comision"]))
                            continue;

                        existenCargos = true;

                        try
                        {


                            object idCurso = db.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(cargo["comision"], cargo["codigo"], calendarioObj.id).Cache().Dict()?["id"]!;
                            if (idCurso.IsNoE())
                                throw new Exception("No existe curso " + cargo["comision"] + " " + cargo["codigo"]);


                            IDictionary<string, object?> rowTomaActiva = db.TomaAprobadaDeCursoQuery(idCurso).Cache().Dict();
                            if (rowTomaActiva != null)
                            {
                                if (!rowTomaActiva["docente"]!.Equals(personaVal.Get("id")))
                                    throw new Exception("Existe una toma activa con otro docente en " + cargo["comision"] + " " + cargo["codigo"]);
                                else
                                    persist.logging.AddLog("calendario", "La toma ya se encuentra cargada", "PersistTomasPfHtml");

                            }
                            else
                            {
                                db.Values("toma").
                                    Set("curso", idCurso).
                                    Set("docente", personaVal.Get("id")).
                                    Set("estado", "Aprobada").
                                    Set("estado_contralor", "Pendiente").
                                    Set("tipo_movimiento", "AI").
                                    Set("fecha_toma", calendarioObj.inicio).
                                    Default().Reset().Insert(persist);
                            }

                            
                        }
                        catch (Exception ex)
                        {
                            persist.logging.AddLog("calendario", "ERROR " + ex.Message + " (" + docenteItem.Dict().ToStringKeyValuePair() + ")", "PersistTomasPfHtml");
                        }

                    }

                    if (existenCargos)
                        persist.AddTo(persists);
                    #endregion

                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("calendario", "ERROR " + ex.Message + " (" + docenteItem.Dict().ToStringKeyValuePair() + ")", "PersistTomasPfHtml");
                    persist.AddTo(persists);
                }

            }
            return persists;
        }

        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde un xlsx de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public static IEnumerable<EntityPersist> PersistTomasPf(this Db db, Calendario calendarioObj, string data)
        {
            List<EntityPersist> persists = new();

            string[] headers = { "persona__apellidos", "persona__nombres", "persona__numero_documento", "persona__descripcion_domicilio", "persona__localidad", "persona__fecha_nacimiento", "persona__telefono", "persona__email_abc", "comision__pfid", "descripcion_asignatura", "CENS" };

            var _data = data.Split("\r\n");
            if (_data.IsNoE())
                throw new Exception("Datos vacios");

            for (var j = 0; j < _data.Length; j++)
            {
                EntityPersist persist = db.Persist();

                try
                {

                    var values = _data[j].Split("\t");

                    if (values.Count() < headers.Length)
                        throw new Exception("La cantidad de datos no es suficiente");

                    Dictionary<string, object?> dict = new();
                    for (var k = 0; k < headers.Length; k++)
                        dict[headers[k]] = values[k];

                    if (!dict["CENS"]!.ToString()!.Equals("462"))
                        throw new Exception("CENS " + dict["CENS"] + ": no corresponde al 462");

                    (string? dni, string? cuil) = PersonaValues.CuilDni(dict["persona__numero_documento"]);
                    CompareParams compare = new CompareParams
                    {
                        FieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };
                    var personaValues = db.Values("persona", "persona").SetNotNull(dict).Set("numero_documento", dni).
                        PersistCompare(persist, compare);

                    object pfid = dict["comision__pfid"]!;
                    string codigo = dict["descripcion_asignatura"]!.ToString()!.SubstringBetween("(", ")");

                    object? idCurso = db.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(pfid, codigo, calendarioObj.id!).Value<object>("id");
                    if (idCurso.IsNoE())
                        throw new Exception("No existe curso");

                    var tomaExistente = db.TomaAprobadaDeCursoQuery(idCurso!).Dict();
                    if (tomaExistente.IsNoE())
                    {
                        var tomaVal = db.Values("toma").
                        Set("curso", idCurso).
                        Set("docente", personaValues.Get("id")!).
                        Set("fecha_toma", calendarioObj.inicio!).
                        Set("estado", "Aprobada").
                        Set("estado_contralor", "Pasar").
                        Set("tipo_movimiento", "AI").Default().Reset();
                        if (!tomaVal.Check())
                            throw new Exception(tomaVal.Logging.ToString());

                        tomaVal.Insert(persist);
                    }
                    else
                    {
                        if (!tomaExistente["docente"]!.Equals(personaValues.Get("id")))
                            throw new Exception("Existe una toma asignada a un docente diferente");
                        else
                            throw new Exception("Ya existe la toma");
                    }


                    persist.logging.AddLog("calendario", "proceso finalizado", "persist_tomas_pf", Logging.Level.Info);

                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("calendario", ex.Message, "persist_tomas_pf", Logging.Level.Error);
                    persist.AddTo(persists);
                    continue;
                }

                persist.AddTo(persists);
            }

            return persists;






        }
    }
}
