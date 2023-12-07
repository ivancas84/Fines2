using Org.BouncyCastle.Crypto;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2App.DAO
{
    public class Calificacion
    {

        public EntityQuery CalificacionesAprobadasNoArchivadasDeAlumnosQuery(IEnumerable<object> idsAlumnos)
        {
            return ContainerApp.db.Query("calificacion")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND ($nota_final >= 7
                    OR $crec >= 4)
                    AND $archivado = false
                ")
                .Order("$persona-apellidos ASC, $persona-nombres ASC, $planificacion_dis-anio ASC, $planificacion_dis-semestre ASC, $planificacion_dis-plan")
                .Parameters(idsAlumnos);
        }


        public EntityQuery IdsAlumnosConCalificacionesAprobadasCruzadasNoArchivadasQuery(IEnumerable<object> ids)
        {
            return CalificacionesAprobadasNoArchivadasDeAlumnosQuery(ids).
                Select("COUNT(DISTINCT $plan_pla-id) as cantidad_planes").
                Group("$alumno").
                Having("cantidad_planes > 1");
        }



        public EntityQuery CantidadCalificacionesAprobadasAgrupadasPorPlanificacionSinArchivarPorAlumnosYPlanesQuery(List<object> alumnosYplanes)
        {
            return ContainerApp.db.Query("calificacion")
                .Select("COUNT($id) as cantidad")
                .Group("$alumno, $planificacion_dis-anio, $planificacion_dis-semestre")
                .Size(0)
                .Where(@"
                    CONCAT($alumno, $planificacion_dis-plan) IN (@0)
                    AND ($nota_final >= 7 OR $crec >= 4) 
                    AND $archivado = false
                ")
                .Order("$alumno ASC, $planificacion_dis-anio ASC, $planificacion_dis-semestre ASC")
                .Parameters(alumnosYplanes);
        }


        /// <summary>
        /// Se devuelven las calificaciones por tramo, sin tener en cuenta el plan
        /// </summary>
        /// <param name="alumno"></param>
        /// <param name="anio"></param>
        /// <param name="semestre"></param>
        /// <returns></returns>
        public EntityQuery CantidadCalificacionesAprobadasDeAlumnoPorTramoQuery(object alumno, object anio, object semestre)
        {
            return ContainerApp.db.Query("calificacion")
                .Select("COUNT($id) as cantidad")
                .Size(0)
                .Where(@"
                    $alumno = @0
                    AND $planificacion_dis-anio = @1
                    AND $planificacion_dis-semestre = @2
                ")
                .Parameters(alumno, anio, semestre);
        }

        public EntityQuery CalificacionesDeAlumnoPlanArchivoQuery(object idAlumno, object idPlan, bool archivado = false)
        {
            return ContainerApp.db.Query("calificacion")
               .Size(0)
               .Where(@"
                    $alumno = @0
                    AND $planificacion_dis-plan = @1
                    AND $archivado = @2
                ")
                .Order("$planificacion_dis-anio ASC, $planificacion_dis-semestre ASC, $asignatura-nombre")
               .Parameters(idAlumno, idPlan, archivado);
        }



    }
}
