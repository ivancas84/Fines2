using SqlOrganize.DateTimeUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class AlumnoComisionValues : EntityValues
    {
        public AlumnoComisionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
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
    }
}
