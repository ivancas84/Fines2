using SqlOrganize.Sql;
using System.Collections.Generic;

namespace Fines2Wpf.DAO
{
    public class Calificacion
    {

        public EntitySql CalificacionesAprobadasNoArchivadasDeAlumnosQuery(IEnumerable<object> idsAlumnos)
        {
            return ContainerApp.db.Sql("calificacion")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND ($nota_final >= 7
                    OR $crec >= 4)
                    AND $archivado = false
                ")
                .Order("$persona__apellidos ASC, $persona__nombres ASC, $planificacion_dis__anio ASC, $planificacion_dis__semestre ASC, $planificacion_dis__plan")
                .Param("@0", idsAlumnos);
        }


        public EntitySql IdsAlumnosConCalificacionesAprobadasCruzadasNoArchivadasQuery(IEnumerable<object> ids)
        {
            return CalificacionesAprobadasNoArchivadasDeAlumnosQuery(ids).
                Select("COUNT(DISTINCT $plan_pla__id) as cantidad_planes").
                Group("$alumno").
                Having("cantidad_planes > 1");
        }



        public EntitySql CantidadCalificacionesAprobadasAgrupadasPorPlanificacionSinArchivarPorAlumnosYPlanesQuery(List<object> alumnosYplanes)
        {
            return ContainerApp.db.Sql("calificacion")
                .Select("COUNT($id) as cantidad")
                .Group("$alumno, $planificacion_dis__anio, $planificacion_dis__semestre")
                .Size(0)
                .Where(@"
                    CONCAT($alumno, $planificacion_dis__plan) IN (@0)
                    AND ($nota_final >= 7 OR $crec >= 4) 
                    AND $archivado = false
                ")
                .Order("$alumno ASC, $planificacion_dis__anio ASC, $planificacion_dis__semestre ASC")
                .Param("@0", alumnosYplanes);
        }


        /// <summary>
        /// Se devuelven las calificaciones por tramo, sin tener en cuenta el plan
        /// </summary>
        /// <param name="alumno"></param>
        /// <param name="anio"></param>
        /// <param name="semestre"></param>
        /// <returns></returns>
        public EntitySql CantidadCalificacionesAprobadaNoArchivadasDeAlumnoPorTramoQuery(object alumno, object anio, object semestre)
        {
            return ContainerApp.db.Sql("calificacion")
                .Select("COUNT($id) as cantidad")
                .Size(0)
                .Where(@"
                    $alumno = @0
                    AND $planificacion_dis__anio = @1
                    AND $planificacion_dis__semestre = @2
                    AND $archivado = false
                    AND ($nota_final >= 7 OR $crec >= 4)
                ")
                .Param("@0", alumno).Param("@1", anio).Param("@2", semestre);
        }

        public EntitySql CalificacionesDeAlumnoPlanArchivoQuery(object idAlumno, object idPlan, bool archivado = false)
        {
            return ContainerApp.db.Sql("calificacion")
               .Size(0)
               .Where(@"
                    $alumno = @0
                    AND $planificacion_dis__plan = @1
                    AND $archivado = @2
                ")
                .Order("$planificacion_dis__anio ASC, $planificacion_dis__semestre ASC, $asignatura__nombre")
               .Param("@0", idAlumno).Param("@1", idPlan).Param("@2", archivado);
        }

        public EntitySql CalificacionesArchivadasDeAlumnoQuery(object idAlumno)
        {
            return ContainerApp.db.Sql("calificacion")
               .Size(0)
               .Where(@"
                    $alumno = @0
                    AND $archivado = true
                ")
                .Order("$planificacion_dis__anio ASC, $planificacion_dis__semestre ASC, $asignatura__nombre")
               .Param("@0", idAlumno);
        }



    }
}
