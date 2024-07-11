namespace SqlOrganize.Sql.Fines2Model3
{
    public static class AsignacionDAO
    {

        public static EntitySql AsignacionesDeComisionesSql(this Db db, params object[] id_comisiones)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $comision IN ( @0 )
                "
            )
               .Parameters(id_comisiones);
        }

        public static EntitySql AsignacionesDeComisionesAutorizadasDelPeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                "
                )
               .Parameters(anio, semestre);
        }


        public static EntitySql AsignacionesActivasDeComisionesAutorizadasDelPeriodoSinGeneroSql(this Db db, object anio, object semestre)
        {
            var subSql = db.Sql("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                    AND $estado = 'Activo'
                    AND $persona-genero IS NULL
                ");

            return db.Sql("alumno").
                Join("INNER JOIN (" + subSql + ") AS sub ON (sub.alumno = alumno.id)").
                Parameters(anio, semestre);
        }

    }
}
