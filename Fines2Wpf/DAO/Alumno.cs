using SqlOrganize;
using System.Collections.Generic;
using System.Linq;

namespace Fines2Wpf.DAO
{
    public static class Alumno
    {

        public static IEnumerable<Dictionary<string, object>> AlumnosPorIds(IEnumerable<object> ids)
        {
            if (ids.Count() == 0) return Enumerable.Empty<Dictionary<string, object>>();
            return ContainerApp.db.Sql("alumno").CacheByIds(ids.ToArray());
        }

        public static EntitySql SearchLikeQuery(string search)
        {
            return ContainerApp.db.Sql("alumno").
                Where("$persona-nombres LIKE @0 ").
                Where("OR $persona-apellidos LIKE @0 ").
                Where("OR $persona-numero_documento LIKE @0 ").
                Where("OR $persona-email LIKE @0 ").
                Where("OR $persona-telefono LIKE @0 ").
                Order("$persona-nombres ASC, $persona-apellidos ASC").
                Parameters("%" + search + "%");
        }
    }
}
