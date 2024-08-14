using SqlOrganize.ValueTypesUtils;
using System;
using System.Threading.Tasks;
using static System.Net.Mime.MediaTypeNames;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class CalendarioValues : EntityValues
    {
        public CalendarioValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }
        public override string ToString()
        {
            var s = "";

            s += GetOrNull("anio")?.ToString() ?? "?";
            s += "-";
            s += GetOrNull("semestre")?.ToString() ?? "?";
            s += "";
            s += GetOrNull("descripcion")?.ToString() ?? "?";
            return s.Trim();
        }

        public static  (short anio, short semestre)  AnioSemestreAnterior(short anio, short semestre)
        { 
            if(semestre == 1)
            {
                semestre = 2;
                anio--; 
            } else
            {
                semestre = 1;
            }

            return (anio, semestre);
        }


        public static  (short anio, short semestre) AnioSemestreSiguiente(short anio, short semestre)
        {
            if (semestre == 2)
            {
                semestre = 1;
                anio++;
            }
            else
            {
                semestre = 2;
            }

            return (anio, semestre);
        }

        public override T GetData<T>()
        {

            string label = ToString();

            var obj = db.Data<T>(Values());
            if (obj is Data_calendario p)
                p.Label = label;
            if (Logging.HasLogs())
                obj.Msg += Logging.ToString();

            return obj;
        }


        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde un xlsx de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public List<EntityPersist> PersistTomasPf(string data)
        {
            logging.Clear();
            List<EntityPersist> persists = new();

            string[] headers = { "persona-apellidos", "persona-nombres", "persona-numero_documento", "persona-descripcion_domicilio", "persona-localidad", "persona-fecha_nacimiento", "persona-telefono", "persona-email_abc", "comision-pfid", "descripcion_asignatura", "CENS"};

            var _data = data.Split("\r\n");
            if (_data.IsNoE())
                throw new Exception("Datos vacios");

            for (var j = 0; j < _data.Length; j++)
            {
                try
                {
                    var values = _data[j].Split("\t");

                    if (values.Count() < headers.Length)
                        throw new Exception("La cantidad de datos no es suficiente");

                    Dictionary<string, object?> dict = new();
                    for (var k = 0; k < headers.Length; k++)
                        dict[headers[k]] = values[k];

                    if (!dict["CENS"]!.ToString()!.Equals("462"))
                        throw new Exception("No corresponde al 462");

                    (string? dni, string? cuil) = PersonaValues.CuilDni(dict["persona-numero_documento"]);
                    var personaValues = ((PersonaValues)db.Values("persona", "persona").SetNotNull(dict).Set("numero_documento", dni));
                    personaValues.PersistCompare().AddTo(persists);
                    if (personaValues.Logging.HasLogs())
                        logging.AddLog(j.ToString(), personaValues.Logging.ToString(), "persist_tomas_pf", Logging.Level.Info);

                    object pfid = dict["comision-pfid"]!;
                    string codigo = dict["descripcion_asignatura"]!.ToString()!.SubstringBetween("(", ")");

                    object? idCurso = db.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(pfid, codigo, Get("id")!).Value<object>("id");
                    if (idCurso.IsNoE())
                        throw new Exception("No existe curso");

                    var tomaExistente = db.TomaAprobadaDeCursoQuery(idCurso).Dict();
                    if (tomaExistente.IsNoE())
                    {
                        var tomaVal = db.Values("toma").
                        Set("curso", idCurso).
                        Set("docente", personaValues.Get("id")!).
                        Set("fecha_toma", Get("inicio")!).
                        Set("estado", "Aprobada").
                        Set("estado_contralor", "Pasar").
                        Set("tipo_movimiento", "AI").Default().Reset();
                        if (!tomaVal.Check())
                            throw new Exception(tomaVal.Logging.ToString());

                        tomaVal.Insert().AddTo(persists);
                    } else
                    {
                        if (!tomaExistente["docente"]!.Equals(personaValues.Get("id")))
                            throw new Exception("Existe una toma asignada a un docente diferente");
                        else
                            throw new Exception("Ya existe la toma");
                    }

                    logging.AddLog(j.ToString(), "proceso finalizado", "persist_tomas_pf", Logging.Level.Info);

                }
                catch (Exception ex)
                {
                    logging.AddLog(j.ToString(), ex.Message, "persist_tomas_pf", Logging.Level.Error);
                    continue;
                }
            }

            return persists;






        }

    }
}
