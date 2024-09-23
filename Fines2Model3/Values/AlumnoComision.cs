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
       
        
    }
}
