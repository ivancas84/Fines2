using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalendarioDAO
    {

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
