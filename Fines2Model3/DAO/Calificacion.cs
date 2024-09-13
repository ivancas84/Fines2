using SqlOrganize.CollectionUtils;
using System.Numerics;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalificacionDAO
    {

        /// <summary> Todas las calificaciones del alumno para un determinado plan </summary>
        public static EntitySql CalificacionesDeAlumnoPlanQuery(this Db db, object idAlumno, object idPlan, bool archivado = false)
        {
            return db.Sql("calificacion")
               .Size(0)
               .Where(@"
                    $alumno = @0
                    AND $planificacion_dis__plan = @1
                ")
                .Order("$planificacion_dis__anio ASC, $planificacion_dis__semestre ASC, $asignatura__nombre")
               .Param("@0", idAlumno).Param("@1", idPlan);
        }

        public static EntitySql CalificacionesDesaprobadasDeAlumnoSql(this Db db, object alumno)
        {
            return db.Sql("calificacion").
                Size(0).
                Where(@"
                        $alumno = @0
                        AND (
                            ($nota_final < 7 AND $crec < 4)
                            OR ($nota_final < 7 AND $crec IS NULL)
                            OR ($nota_final IS NULL AND $crec < 4)
                            OR ($nota_final IS NULL AND $crec IS NULL)
                        )
                    ").
                Param("@0", alumno);
        }

        public static EntitySql CalificacionesAprobadasAnterioresDeAlumnoPlanConAnioSemestreIngresoSql(this Db db, object plan, object alumno, object anio_actual, object semestre_actual)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where(@"
                        $planificacion_dis__plan = @0
                        AND $planificacion_dis__anio < @1 AND $planificacion_dis__semestre < @2 
                        AND $alumno = @3
                        AND ($nota_final >= 7 OR $crec >= 4)").
                    Param("@0", plan).
                    Param("@1", anio_actual).
                    Param("@2", semestre_actual).
                    Param("@3", alumno);
        }

        public static EntitySql CalificacionesAprobadasDeAlumnoPlanConAnioSemestreIngresoSql(this Db db, object plan, object alumno, object anio, object semestre)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where(@"
                        $planificacion_dis__plan = @0
                        AND $planificacion_dis__anio >= @1 AND $planificacion_dis__semestre >= @2 
                        AND $alumno = @3
                        AND ($nota_final >= 7 OR $crec >= 4)").
                        Param("@0", plan).
                        Param("@1", anio).
                        Param("@2", semestre).
                        Param("@3", alumno);
        }

        public static EntitySql CalificacionesAprobadasDeAlumnoPlanDistintoSql(this Db db, object alumno, object plan)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where(@"
                        $planificacion_dis__plan != @0
                        AND $alumno = @1
                        AND ($nota_final >= 7 OR $crec >= 4)").
                        Param("@0", plan).
                        Param("@1", alumno);
        }

        public static EntitySql CalificacionDisposicionAlumnosSql(this Db db, object disposicion, params object[] alumnos)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where("$disposicion = @0 AND $alumno IN (@1)").
                    Param("@0", disposicion).
                    Param("@1", alumnos);
        }


        public static EntitySql CalificacionAprobadaCursoSql(this Db db, object curso)
        {
            var data = db.Sql("curso").Cache().Id(curso) ?? throw new Exception("curso inexistente");
            CursoValues cursoVal = (CursoValues)db.Values("curso").SetValues(data);

            string subSql = "SELECT DISTINCT alumno FROM alumno_comision WHERE comision = @0";

            return db.Sql("calificacion").
                    Size(0).
                    Where("$alumno IN (" + subSql + ") AND $disposicion = @1 AND (nota_final >= 7 OR crec >= 4)").
                    Param("@0", cursoVal.Get("comision")!).
                    Param("@1", cursoVal.Get("disposicion")!);
        }

        public static EntitySql CalificacionDesaprobadaCursoAlumnosActivosSql(this Db db, object curso)
        {
            var data = db.Sql("curso").Cache().Id(curso) ?? throw new Exception("curso inexistente");
            CursoValues cursoVal = (CursoValues)db.Values("curso").SetValues(data);

            string subSql = "SELECT DISTINCT alumno FROM alumno_comision WHERE estado = 'Activo' AND comision = @0";

            return db.Sql("calificacion").
                    Size(0).
                    Where(@"$alumno IN (" + subSql + ") AND $disposicion = @1 AND nota_final < 7 AND crec < 4").
                    Param("@0", cursoVal.Get("comision")!).
                    Param("@1", cursoVal.Get("disposicion")!);
        }

        public static EntitySql CalificacionesCursoSql(this Db db, object curso)
        {
            var cursoData = db.Sql("curso").Cache().Id(curso);

            var alumnos = db.AsignacionesDeComisionesSql(cursoData["comision"]).Cache().Dicts().ColOfVal<object>("alumno");

            var disposicion = ((CursoValues)db.Values("curso").SetValues(cursoData)).Get("disposicion");

            return db.CalificacionDisposicionAlumnosSql(disposicion, alumnos.ToArray());
        }


        public static EntitySql CantidadCalificacionesAprobadasPorAlumnoDePlaniticacion(this Db db, List<object> alumnosYplanes)
        {
            return db.Sql("calificacion")
                .Select("COUNT($id) as cantidad")
                .Group("$alumno, $planificacion_dis__anio, $planificacion_dis__semestre")
                .Size(0)
                .Where(@"
                    CONCAT($alumno, $planificacion_dis__plan) IN (@0)
                    AND ($nota_final >= 7 OR $crec >= 4) 
                ")
                .Order("$alumno ASC, $planificacion_dis__anio ASC, $planificacion_dis__semestre ASC").
                Param("@0", alumnosYplanes);
        }


        /// <summary> Calificaciones aprobadas de alumno para una determinada planificacion </summary>
        /// <remarks> Recordar que una planificacion es la combinacion entre anio, semestre y plan</remarks>
        public static EntitySql CalificacionesAprobadasPorAlumnoDePlanificacionSql(this Db db, object idPlanificacion, params object[] idAlumnos)
        {
            return db.Sql("calificacion")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND $planificacion_dis__id = @1
                    AND ($nota_final >= 7 OR $crec >= 4) 
                ")
                .Order("$alumno ASC, $planificacion_dis__anio ASC, $planificacion_dis__semestre ASC").
                Param("@0", idAlumnos.ToList()).
                Param("@0", idPlanificacion);
        }


        /// <summary> Cantidad de calificaciones de alumno para una determinada planificacion </summary>
        /// <remarks> Recordar que una planificacion es la combinacion entre anio, semestre y plan</remarks>
        public static EntitySql CantidadCalificacionesAprobadasPorAlumnoDePlanificacionSql(this Db db, object idPlanificacion, params object[] idAlumnos)
        {
            return db.Sql("calificacion")
                .Select("COUNT($id) as cantidad")
                .Group("$alumno")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND $planificacion_dis__id = @1
                    AND ($nota_final >= 7 OR $crec >= 4) 
                ")
                .Order("$alumno ASC, $planificacion_dis__anio ASC, $planificacion_dis__semestre ASC").
                Param("@0", idAlumnos.ToList()).
                Param("@1", idPlanificacion);
        }
    }
}
