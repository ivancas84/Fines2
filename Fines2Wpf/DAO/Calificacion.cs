﻿using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.DAO
{
    public class Calificacion
    {
        public IEnumerable<object> IdsAlumnosConCalificacionesAprobadasCruzadasNoArchivadas(IEnumerable<object> ids)
        {
            return ContainerApp.db.Query("calificacion")
                .Select("COUNT(DISTINCT $plan_pla-id) as cantidad_planes")
                .Group("$alumno")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND ($nota_final >= 7
                    OR $crec >= 4)
                    AND $archivado = false
                ")
                .Having("cantidad_planes > 1")
                .Parameters(ids).ColOfDictCache().ColOfVal<object>("cantidad_planes");

        }

        public IEnumerable<Dictionary<string, object>> CalificacionesAprobadasDeAlumnosNoArchivadas(IEnumerable<object> idsAlumnos)
        {
            return ContainerApp.db.Query("calificacion")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND ($nota_final >= 7
                    OR $crec >= 4) 
                    AND $archivado = false
                ")
                .Parameters(idsAlumnos)
                .Order("$persona-apellidos ASC, $persona-nombres ASC, $planificacion_dis-anio ASC, $planificacion_dis-semestre ASC, $planificacion_dis-plan")
                .ColOfDictCache();

        }


        /// <summary>
        /// Se devuelven las calificaciones por tramo, sin tener en cuenta el plan
        /// </summary>
        /// <param name="alumno"></param>
        /// <param name="anio"></param>
        /// <param name="semestre"></param>
        /// <returns></returns>
        public Int64 CantidadCalificacionesAprobadasDeAlumnoPorTramo(object alumno, object anio, object semestre)
        {
            var d = ContainerApp.db.Query("calificacion")
                .Select("COUNT($id) as cantidad")
                .Size(0)
                .Where(@"
                    $alumno = @0
                    AND $planificacion_dis-anio = @1
                    AND $planificacion_dis-semestre = @2
                ")
                .Parameters(alumno, anio, semestre).DictCache();

            if (d.IsNullOrEmpty()) { return 0; }
            return (Int64)d["cantidad"];
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

    }
}
