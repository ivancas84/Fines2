using SqlOrganize.DateTimeUtils;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class AlumnoComisionValues : EntityVal
    {
        public AlumnoComisionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public override string ToString()
        {
            var s = "";
            s += GetStr("estado", "?");
            s += " ";
            s += GetValuesCache("comision")?.ToString() ?? "?";
            s += " ";
            s += GetValuesCache("persona")?.ToString() ?? "?";
            return s;
        }

       


        public string EstadoIngreso()
        {
            string estado = GetOrNull("estado")?.ToString().ToLower() ?? "?";
            DateTime? alta = (DateTime?)GetOrNull("alta");

            if (estado == "no activo")
                return "TRAYECTORIA INTERRUMPIDA";
            if (estado == "activo" && alta?.ToYearSemester() == DateTime.Now.ToYearSemester())
                return "INGRESANTE";
            if (estado.ToLower() == "activo" && alta?.ToYearSemester() != DateTime.Now.ToYearSemester())
                return "CONTINÚA TRAYECTORIA";
            return estado;
        }


        /// <summary>
        /// 
        /// </summary>
        /// <param name="comision"></param>
        /// <param name="personaVal"></param>
        /// <returns></returns>
        /// <exception cref="Exception"></exception>
        public EntityPersist PersistProcesarComisionPersona(object comision, PersonaValues personaVal)
        {
            Comision_ comisionObj = db.Sql("comision").Cache().Id(comision)!.Obj<Comision_>()!;

            var asignacionData = db.AsignacionComisionDniSql(comision, personaVal.Get("numero_documento")).Cache().Dict();

            EntityPersist persist = db.Persist();

            if (!asignacionData.IsNoE()) //existe asignacion > comparar datos principales de persona
            {
                SetValues(asignacionData!);

                CompareParams compare = new()
                {
                    FieldsToCompare = new List<string> { "nombres", "apellidos" },
                    Data = db.Values("persona", "persona").Set(asignacionData!).Values(),
                };

                var response = personaVal.Compare(compare);

                if (!response.IsNoE())
                    throw new Exception(" Comparacion de persona diferente: " + compare.Data.ToStringKeyValuePair());

            }
            else //asignacion inexistente > agregar
            {
                var personaData = db.PersonaDniSql(personaVal.Get("numero_documento")).Cache().Dict();

                if (personaData.IsNoE())
                {
                    logging.AddLog("persona", "Persona insertada", "insert", Logging.Level.Warning);
                    personaVal.Default().Reset().Insert(persist);
                } else
                {
                    personaVal.Set("id", personaData!["id"]);
                } 

                var alumnoData = db.AlumnoPersonaSql(personaVal.Get("id")).Cache().Dict();
                var alumnoVal = db.Values("alumno");
                if (alumnoData.IsNoE())
                {
                    logging.AddLog("alumno", "Alumno insertado", "insert", Logging.Level.Warning);
                    alumnoVal.Set("persona", personaVal.Get("id")).
                        Set("plan", comisionObj.planificacion__plan).
                        Default().Insert(persist);
                }
                else
                {
                    logging.AddLog("alumno", "Alumno existente", null, Logging.Level.Warning);
                    alumnoVal.Set(alumnoData!);
                }

                if (!alumnoVal.Get("plan").Equals(comisionObj.planificacion__plan))
                    logging.AddLog("alumno", "Plan alumno distinto de comision", null, Logging.Level.Warning);

                logging.AddLog("alumno", "Asignacion insertada", "insert", Logging.Level.Warning);

                Set("alumno", alumnoVal.Get("id")).
                Set("comision", comision).
                Default().Reset().Insert(persist);
            }

            return persist;
        }

        
    }
}
