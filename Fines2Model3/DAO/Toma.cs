namespace SqlOrganize.Sql.Fines2Model3
{
    public static class Toma
    {
        public static EntitySql TomaAprobadaDeCursoQuery(this Db db, object idCurso)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso = @0 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor = 'Pasar'
                ")
                .Parameters(idCurso);
        }
    }
}
