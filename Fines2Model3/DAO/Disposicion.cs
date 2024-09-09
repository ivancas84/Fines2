using System.Numerics;

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
                        $planificacion__plan = @0 
                        AND $planificacion__anio >= @1 
                        AND $planificacion__semestre >= @2").
                Param("@0", plan!).Param("@1", anio!).Param("@2", semestre!);
        }

        public static EntitySql DisposicionPlanificacionAsignaturaSql(this Db db, object planificacion, object asignatura)
        {
            return db.Sql("disposicion").
                Size(0).
                Where(@"$planificacion = @0 AND $asignatura = @1"
            ).
                Param("@0", planificacion!).
                Param("@1", asignatura!);
        }

    }
}
