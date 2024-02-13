using Microsoft.Extensions.Caching.Memory;
using SqlOrganize;
using SqlOrganizeMy;
using Fines2Wpf.Values;

namespace Fines2Wpf
{
    internal class DbApp : DbMy
    {
        public DbApp(Config config, Schema sch, MemoryCache cache) : base(config, sch, cache)
        {
        }

        public object Dao(string entityName)
        {
            switch(entityName)
            {
                case "comision":
                    return new DAO.Comision();
            }

            return new SqlOrganize.DAO(this);
        }

        public override EntityValues Values(string entityName, string? fieldId = null)
        {
            switch (entityName)
            {
                case "alumno":
                    return new Alumno(this, entityName, fieldId);

                case "alumno_comision":
                    return new AlumnoComision(this, entityName, fieldId);

                case "calendario":
                    return new Calendario(this, entityName, fieldId);

                case "designacion":
                    return new Designacion(this, entityName, fieldId);

                case "domicilio":
                    return new Domicilio(this, entityName, fieldId);

                case "comision":
                    return new Comision(this, entityName, fieldId);

                case "persona":
                    return new Persona(this, entityName, fieldId);

                case "plan":
                    return new Plan(this, entityName, fieldId);

                case "planificacion":
                    return new Planificacion(this, entityName, fieldId);

                case "disposicion":
                    return new Disposicion(this, entityName, fieldId);

                case "curso":
                    return new Curso(this, entityName, fieldId);
            }

            return new EntityValues(this, entityName, fieldId);

        }
    }
}
