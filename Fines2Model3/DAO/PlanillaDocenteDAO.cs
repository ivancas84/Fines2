using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class PlanillaDocenteDAO
    {
        public static IEnumerable<PlanillaDocente> Planillas()
        {
            string sql = "SELECT DISTINCT id FROM planilla_docente";
            return Context.db.CacheSql().QueryIds<PlanillaDocente>(sql);
        }

   
    }
}
