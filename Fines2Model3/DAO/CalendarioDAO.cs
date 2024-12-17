using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalendarioDAO
    {
    
        public static IEnumerable<Calendario> Calendarios()
        {
            string sql = "SELECT id FROM calendario ORDER BY anio DESC, semestre DESC";

            return Context.db.CacheSql().QueryIds<Calendario>("calendario", sql);
        }
    }

}
