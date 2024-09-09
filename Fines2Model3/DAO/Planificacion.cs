

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class PlanificacionDAO
    {
        public static EntitySql PlanificacionSiguienteSql(this Db db, object anio, object semestre, object planId)
        {
            (string anio_, string semestre_) = PlanificacionValues.AnioSemestreSiguiente(anio, semestre);

            return db.Sql("planificacion").
                Where(@"
                    $anio = @0 AND $semestre = @1 AND $plan = @2
                ").Param("@0", anio_).Param("@1", semestre_).Param("@2", planId);

        }
    }
}
