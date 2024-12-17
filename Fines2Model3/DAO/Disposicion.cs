
namespace SqlOrganize.Sql.Fines2Model3
{
    public class DisposicionDAO
    {

        /// <summary> Todas las calificaciones del alumno para un determinado plan </summary>
        public static IEnumerable<Disposicion> Disposiciones__By_IdPlan_Anio_Semestre(object idPlan, object anio, object semestre)
        {
            string sql = @"
                SELECT id 
                FROM disposicion 
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                WHERE planificacion.plan = @IdPlan
                AND planificacion.anio = @Anio
                AND planificacion.semestre = @Semestre";

            return Context.db.CacheSql().QueryIds<Disposicion>(sql, new { IdPlan = idPlan, Anio = anio, Semestre = semestre });
        }

        

    }
}
