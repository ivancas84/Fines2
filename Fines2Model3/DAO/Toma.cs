namespace SqlOrganize.Sql.Fines2Model3
{
    public static class Toma
    {
        public static EntitySql TomaAprobadaDeCursoQuery(this Db db, params object[] idCurso)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso IN ( @0 ) 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor = 'Pasar'
                ")
                .Parameters(idCurso);
        }

        public static EntitySql TomaAprobadaDeComisionQuery(this Db db, params object[] idComisiones)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso-comision IN ( @0 ) 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor = 'Pasar'
                ")
                .Parameters(idComisiones);
        }
    }
}
