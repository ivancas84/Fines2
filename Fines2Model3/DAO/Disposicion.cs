namespace SqlOrganize.Sql.Fines2Model3
{
    public static class DisposicionDAO
    {

        /// <summary> Todas las calificaciones del alumno para un determinado plan </summary>
        public static EntitySql DisposicionesPlanAnioSemestre(this Db db, object plan, object anio, object semestre)
        {
            return db.Sql("disposicion").
                Size(0).
                Where(@"
                        $planificacion-plan = @0 
                        AND $planificacion-anio >= @1 
                        AND $planificacion-semestre >= @2").
                Parameters(plan!, anio!, semestre!);
        }
    }
}
