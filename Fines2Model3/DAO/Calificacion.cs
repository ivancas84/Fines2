using SqlOrganize.CollectionUtils;
using System.Numerics;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalificacionDAO
    {

        /// <summary> Todas las calificaciones del alumno para un determinado plan </summary>
        public static EntitySql CalificacionesDeAlumnoPlanQuery( object idAlumno, object idPlan, bool archivado = false)
        {
            return Context.db.Sql("calificacion")
               .Size(0)
               .Where(@"
                    $alumno = @0
                    AND $planificacion_dis__plan = @1
                ")
                .Order("$planificacion_dis__anio ASC, $planificacion_dis__semestre ASC, $asignatura__nombre")
               .Param("@0", idAlumno).Param("@1", idPlan);
        }

        public static EntitySql CalificacionesDesaprobadasDeAlumnoSql(object alumno)
        {
            return Context.db.Sql("calificacion").
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

        public static EntitySql CalificacionesAprobadasAnterioresDeAlumnoPlanConAnioSemestreIngresoSql(object plan, object alumno, object anio_actual, object semestre_actual)
        {
            return Context.db.Sql("calificacion").
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

        public static EntitySql CalificacionesAprobadasDeAlumnoPlanConAnioSemestreIngresoSql(object plan, object alumno, object anio, object semestre)
        {
            return Context.db.Sql("calificacion").
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

        public static EntitySql CalificacionesAprobadasDeAlumnoPlanDistintoSql(object alumno, object plan)
        {
            return Context.db.Sql("calificacion").
                    Size(0).
                    Where(@"
                        $planificacion_dis__plan != @0
                        AND $alumno = @1
                        AND ($nota_final >= 7 OR $crec >= 4)").
                        Param("@0", plan).
                        Param("@1", alumno);
        }

        public static EntitySql CalificacionDisposicionAlumnosSql(object disposicion, params object[] alumnos)
        {
            return Context.db.Sql("calificacion").
                    Size(0).
                    Where("$disposicion = @0 AND $alumno IN (@1)").
                    Param("@0", disposicion).
                    Param("@1", alumnos);
        }


        public static EntitySql CalificacionAprobadaCursoSql(object idCurso)
        {
            var data = Context.db.Sql("curso").Cache().Id(idCurso) ?? throw new Exception("curso inexistente");
            var curso = Context.db.ToData<Curso>(data);

            string subSql = "SELECT DISTINCT alumno FROM alumno_comision WHERE comision = @0";

            return Context.db.Sql("calificacion").
                    Size(0).
                    Where("$alumno IN (" + subSql + ") AND $disposicion = @1 AND (nota_final >= 7 OR crec >= 4)").
                    Param("@0", curso.comision!).
                    Param("@1", curso.disposicion!);
        }

        public static EntitySql CalificacionDesaprobadaCursoAlumnosActivosSql(object idCurso)
        {
            var data = Context.db.Sql("curso").Cache().Id(idCurso) ?? throw new Exception("curso inexistente");
            Curso curso = Context.db.ToData<Curso>(data);

            string subSql = "SELECT DISTINCT alumno FROM alumno_comision WHERE estado = 'Activo' AND comision = @0";

            return Context.db.Sql("calificacion").
                    Size(0).
                    Where(@"$alumno IN (" + subSql + ") AND $disposicion = @1 AND nota_final < 7 AND crec < 4").
                    Param("@0", curso.comision!).
                    Param("@1", curso.disposicion!);
        }

        public static EntitySql CalificacionesCursoSql(object idCurso)
        {
            var cursoData = Context.db.Sql("curso").Cache().Id(idCurso);

            var curso = Context.db.ToData<Curso>(cursoData);

            var idAlumnos = curso.comision_.SqlRef("alumno_comision", "comision").Cache().Values("alumno");

            return CalificacionDisposicionAlumnosSql(curso.disposicion!, idAlumnos.ToArray());
        }


        public static EntitySql CantidadCalificacionesAprobadasPorAlumnoDePlaniticacion(List<object> alumnosYplanes)
        {
            return Context.db.Sql("calificacion")
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
        public static EntitySql CalificacionesAprobadasPorAlumnoDePlanificacionSql(object idPlanificacion, params object[] idAlumnos)
        {
            return Context.db.Sql("calificacion")
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
        public static EntitySql CantidadCalificacionesAprobadasPorAlumnoDePlanificacionSql(object idPlanificacion, params object[] idAlumnos)
        {
            return Context.db.Sql("calificacion")
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
