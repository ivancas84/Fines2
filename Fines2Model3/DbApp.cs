using Microsoft.Extensions.Caching.Memory;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class DbApp : DbMy
    {
        public DbApp(Config config, Schema sch, MemoryCache cache) : base(config, sch, cache)
        {
        }


        public override EntityValues Values(string entityName, string? fieldId = null)
        {
            switch (entityName)
            {
                case "alumno":
                    return new AlumnoValues(this, entityName, fieldId);

                case "alumno_comision":
                    return new AlumnoComisionValues(this, entityName, fieldId);

                case "calendario":
                    return new CalendarioValues(this, entityName, fieldId);

                case "calificacion":
                    return new CalificacionValues(this, entityName, fieldId);

                case "designacion":
                    return new DesignacionValues(this, entityName, fieldId);

                case "domicilio":
                    return new DomicilioValues(this, entityName, fieldId);

                case "comision":
                    return new ComisionValues(this, entityName, fieldId);

                case "persona":
                    return new PersonaValues(this, entityName, fieldId);

                case "plan":
                    return new PlanValues(this, entityName, fieldId);

                case "planificacion":
                    return new PlanificacionValues(this, entityName, fieldId);

                case "disposicion":
                    return new DisposicionValues(this, entityName, fieldId);

                case "curso":
                    return new CursoValues(this, entityName, fieldId);
            }

            return new EntityValues(this, entityName, fieldId);

        }
    }
}
