using Newtonsoft.Json;
using SqlOrganize.CollectionUtils;
using SqlOrganize.ValueTypesUtils;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using static System.Runtime.InteropServices.JavaScript.JSType;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalendarioDAO
    {

        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde el html procesado con pagemanipulator de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public static IEnumerable<EntityPersist> PersistTomasPfHtml(this Db db, Data_calendario calendarioObj, string data)
        {
            List<EntityPersist> persists = new();

            var pfidComisiones = db.ComisionesAutorizadasDeCalendarioSql(calendarioObj.id).Cache().ColOfDict().ColOfVal<string>("pfid");
            var docentes = JsonConvert.DeserializeObject<List<DocentePfItem>>(data)!;
            
            foreach (DocentePfItem docente in docentes)
            {
                EntityPersist persist = db.Persist();

                #region insertar o actualizar docente (se insertan o actualizan todos)
                var d = docente.Dict();
                if (!d["anio_nacimiento"].IsNoE() && !d["mes_nacimiento"].IsNoE() && !d["dia_nacimiento"].IsNoE())
                    d["fecha_nacimiento"] = new DateTime((int)d["anio_nacimiento"], (int)d["mes_nacimiento"], (int)d["dia_nacimiento"]);

                (string? dni, string? cuil) = PersonaValues.CuilDni(d["persona-numero_documento"]);

                EntityValues vPersona = db.Values("persona").SetNotNull(d).Set("numero_documento", dni).Reset();

                CompareParams compare = new CompareParams
                {
                    fieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                };
                var personaValues = db.Values("persona").Set(d).Reset().
                   PersistCompare(persist, compare);
                #endregion

                #region insertar o actualizar cargo
                foreach (var cargo in docente.cargos)
                {
                    if (pfidComisiones.Contains(cargo["comision"]))
                    {
                        object idCurso = db.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(cargo["comision"], cargo["codigo"], calendarioObj.id);
                        if (idCurso.IsNoE())
                        {
                            persist.logging.AddLog("calendario", "No existe curso " + cargo["comision"] + " " + cargo["codigo"], "PersistTomasPfHtml");
                            continue;

                        }

                        IDictionary<string, object> rowTomaActiva = dao.TomaActiva(idCurso);
                        if (rowTomaActiva != null)
                        {
                            if (!rowTomaActiva["docente"].Equals(vPersona.Get("id")))
                                logs.Add("Existe una toma activa con otro docente en " + cargo["comision"] + " " + cargo["codigo"]);
                            else
                                logs.Add("La toma ya se encuentra cargada " + cargo["comision"] + " " + cargo["codigo"]);
                        }
                        else
                        {
                            EntityValues vToma = ContainerApp.db.Values("toma").
                                Set("curso", idCurso).
                                Set("docente", vPersona.Get("id")).
                                Set("estado", "Aprobada").
                                Set("estado_contralor", "Pendiente").
                                Set("tipo_movimiento", "AI").
                                Set("fecha_toma", new DateTime(2024, 03, 11));
                            vToma.Default().Reset();
                            var p = ContainerApp.db.Persist().Insert(vToma).Exec().RemoveCache();
                        }

                    }


                }
                #endregion
            }
            return persists;
        }

        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde un xlsx de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public static IEnumerable<EntityPersist> PersistTomasPf(this Db db, Data_calendario calendarioObj, string data)
        {
            List<EntityPersist> persists = new();

            string[] headers = { "persona-apellidos", "persona-nombres", "persona-numero_documento", "persona-descripcion_domicilio", "persona-localidad", "persona-fecha_nacimiento", "persona-telefono", "persona-email_abc", "comision-pfid", "descripcion_asignatura", "CENS" };

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
                        throw new Exception("CENS " + dict["CENS"] + " - no corresponde al 462");

                    (string? dni, string? cuil) = PersonaValues.CuilDni(dict["persona-numero_documento"]);
                    CompareParams compare = new CompareParams
                    {
                        fieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };
                    var personaValues = db.Values("persona", "persona").SetNotNull(dict).Set("numero_documento", dni).
                        PersistCompare(persist, compare);

                    object pfid = dict["comision-pfid"]!;
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
