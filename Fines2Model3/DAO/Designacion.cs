namespace SqlOrganize.Sql.Fines2Model3
{
    public static class DesignacionDAO
    {
        public static EntitySql ReferentesDeSedeQuery(this Db db, params object[] idSedes)
        {
            return db.Sql("designacion")
                .Fields()
                .Size(0)
                .Where(@"
                    $sede IN ( @0 ) 
                    AND $cargo = '1'
                    AND $hasta IS NULL
                ")
                .Param("@0", idSedes);
        }
    }
}
