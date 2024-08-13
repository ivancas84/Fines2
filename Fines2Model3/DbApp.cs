using Microsoft.Extensions.Caching.Memory;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class DbApp : DbMy
    {
        public DbApp(Config config, Schema sch, MemoryCache cache) : base(config, sch, cache)
        {
        }

        /// <summary>Referencia y cast rapido para una determinada subclase de EntityValues</summary>
        public T Values<T>(string? fieldId = null) where T : EntityValues
        {
            if (typeof(T) == typeof(AlumnoValues))
                return (T)Values("alumno", fieldId);

            if (typeof(T) == typeof(AlumnoComisionValues))
                return (T)Values("alumno_comision", fieldId);

            if (typeof(T) == typeof(AlumnoComisionValues))
                return (T)Values("calendario", fieldId);

            if (typeof(T) == typeof(CalificacionValues))
                return (T)Values("calificacion", fieldId);

            if (typeof(T) == typeof(DesignacionValues))
                return (T)Values("designacion", fieldId);

            if (typeof(T) == typeof(DomicilioValues))
                return (T)Values("domicilio", fieldId);

            if (typeof(T) == typeof(ComisionValues))
                return (T)Values("comision", fieldId);

            if (typeof(T) == typeof(PersonaValues))
                return (T)Values("persona", fieldId);

            if (typeof(T) == typeof(PlanValues))
                return (T)Values("plan", fieldId);

            if (typeof(T) == typeof(PlanificacionValues))
                return (T)Values("planificacion", fieldId);

            if (typeof(T) == typeof(DisposicionValues))
                return (T)Values("disposicion", fieldId);

            if (typeof(T) == typeof(CursoValues))
                return (T)Values("curso", fieldId);
            
            throw new InvalidOperationException($"Unsupported type: {typeof(T).Name}");
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
