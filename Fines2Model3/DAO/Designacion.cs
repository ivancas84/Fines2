namespace SqlOrganize.Sql.Fines2Model3
{
    public static class DesignacionDAO
    {
        public static IEnumerable<Designacion> Referentes__By_IdsSedes(params object[] idsSedes)
        {

            string sql = @"
                SELECT id FROM designacion
                INNER JOIN sede ON (designacion.sede = sede.id)
                WHERE designacion.cargo = '1'
                AND designacion.hasta IS NULL
                AND designacion.sede IN ( @IdsSede )
            ";

            return Context.db.CacheSql().QueryIds<Designacion>(sql, new { IdsSedes = idsSedes });
        }
    }
}
