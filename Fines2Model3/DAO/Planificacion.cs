

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class PlanificacionDAO
    {
        public static EntitySql PlanificacionSiguienteSql(this Db db, string anio, string semestre, string plan__id)
        {
            (string anio_, string semestre_) = PlanificacionValues.AnioSemestreSiguiente(anio, semestre);

            return db.Sql("planificacion").
                Where(@"
                    $anio = @0 AND $semestre = @1 AND $plan = @2
                ").Parameters(anio_, semestre_, plan__id);

        }
    }
}
