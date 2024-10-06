using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class PlanillaDocenteDAO
    {
        public static EntitySql PlanillasSql()
        {
            return Context.db.Sql("planilla_docente").Order("fecha_contralor DESC");
        }

   
    }
}
