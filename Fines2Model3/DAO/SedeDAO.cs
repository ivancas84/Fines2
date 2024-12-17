using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class SedeDAO
    {
    
        public static IEnumerable<Sede> Sedes__By_IdCalendario(object idCalendario)
        {
            string sql = @"SELECT DISTINCT sede.id FROM sede 
                INNER JOIN comision ON comision.sede = sede.id 
                WHERE calendario = @IdCalendario
            ";

            return Context.db.CacheSql().QueryIds<Sede>(sql, new { IdCalendario = idCalendario});
        }
    }

}
