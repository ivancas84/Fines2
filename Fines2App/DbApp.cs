using Microsoft.Extensions.Caching.Memory;
using SqlOrganize;
using SqlOrganizeMy;

namespace Fines2App
{
    internal class DbApp : DbMy
    {
        public DbApp(Config config, Schema sch, MemoryCache cache) : base(config, sch, cache)
        {
        }

        public override EntityValues Values(string entityName, string? fieldId = null)
        {
            switch (entityName)
            {
                case "alumno":
                    return new Values.Alumno(this, entityName, fieldId);
                case "alumno_comision":
                    return new Values.AlumnoComision(this, entityName, fieldId);
                case "calendario":
                    return new Values.Calendario(this, entityName, fieldId);
                case "comision":
                    return new Values.Comision(this, entityName, fieldId);
                case "curso":
                    return new Values.Curso(this, entityName, fieldId);
                case "disposicion":
                    return new Values.Disposicion(this, entityName, fieldId);
                case "domicilio":
                    return new Values.Domicilio(this, entityName, fieldId);
                case "persona":
                    return new Values.Persona(this, entityName, fieldId);
                case "plan":
                    return new Values.Plan(this, entityName, fieldId);

            }

            return new EntityValues(this, entityName, fieldId);

        }
    }
}
