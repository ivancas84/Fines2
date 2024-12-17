

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class PlanificacionDAO
    {
        public static Planificacion? PlanificacionSiguiente__By_IdPlan_Anio_Semestre(object idPlan, object anio, object semestre)
        {
            (string anio_, string semestre_) = Planificacion.AnioSemestreSiguiente(anio, semestre);

            string sql = @"SELECT id FROM planificacion 
                WHERE anio = @Anio
                AND semestre = @Semestre
                AND plan = @IdPlan;
            ";

            return Context.db.CacheSql().QueryIds<Planificacion>(sql, new { Anio = anio, Semestre = semestre, IdPlan = idPlan}).FirstOrDefault();

        }
    }
}
