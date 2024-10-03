using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class SedeDAO
    {
    
        public static EntitySql SedesDeCalendarioSql(object idCalendario)
        {
            var subSql = "SELECT sede FROM comision WHERE calendario = @0 AND autorizada = true";

            return Context.db.Sql("sede")
                .Join("INNER JOIN (" + subSql + ") AS sub ON (sub.sede = $id)")
                .Order("$nombre ASC")
                .Param("@0", idCalendario);
        }
    }

}
