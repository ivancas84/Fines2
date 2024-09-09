using SqlOrganize;
using SqlOrganize.Sql;
using System.Collections.Generic;
using System.Linq;

namespace Fines2Wpf.DAO
{
    public static class Alumno
    {

        public static IEnumerable<Dictionary<string, object>> AlumnosPorIds(IEnumerable<object> ids)
        {
            if (ids.Count() == 0) return Enumerable.Empty<Dictionary<string, object>>();
            return ContainerApp.db.Sql("alumno").Cache().Ids(ids.ToArray());
        }

        public static EntitySql SearchLikeQuery(string search)
        {
            return ContainerApp.db.Sql("alumno").
                Where("$persona__nombres LIKE @0 ").
                Where("OR $persona__apellidos LIKE @0 ").
                Where("OR $persona__numero_documento LIKE @0 ").
                Where("OR $persona__email LIKE @0 ").
                Where("OR $persona__telefono LIKE @0 ").
                Order("$persona__nombres ASC, $persona__apellidos ASC").
                Param ("@0", "%" + search + "%");
        }
    }
}
