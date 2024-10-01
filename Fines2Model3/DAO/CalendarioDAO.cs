using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalendarioDAO
    {
    
        public static EntitySql CalendariosSql()
        {
            return Context.db.Sql("calendario").Order("$inicio DESC");
        }
    }

}
