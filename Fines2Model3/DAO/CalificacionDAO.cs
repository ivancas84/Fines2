using Dapper;
using SqlOrganize.CollectionUtils;
using System.Numerics;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class CalificacionDAO
    {

        /// <summary> Todas las calificaciones del alumno para un determinado plan </summary>
        public static IEnumerable<Calificacion> Calificaciones__By_IdAlumno_IdPlan( object idAlumno, object idPlan)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE planificacion.plan = @IdPlan
                AND calificacion.alumno = @IdAlumno
                ORDER BY planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdAlumno = idAlumno, IdPlan = idPlan });
        }

        public static IEnumerable<Calificacion> CalificacionesDesaprobadas__By_IdAlumno(object idAlumno)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                WHERE calificacion.alumno = @IdAlumno
                AND (
                    (nota_final < 7 AND crec < 4)
                    OR (nota_final < 7 AND crec IS NULL)
                    OR (nota_final IS NULL AND crec < 4)
                    OR (nota_final IS NULL AND crec IS NULL)
                )
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdAlumno = idAlumno });

        }

        public static IEnumerable<Calificacion> CalificacionesAprobadasAnteriores__By_IdAlumno_IdPlan_Anio_Semestre(object idAlumno, object idPlan, object anio, object semestre)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE planificacion.plan = @IdPlan
                AND planificacion.anio < @Anio AND planificacion.semestre < @Semestre 
                AND calificacion.alumno = @IdAlumno
                AND (nota_final >= 7 OR crec >= 4)
                ORDER BY planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdAlumno = idAlumno, IdPlan = idPlan, Anio = anio, Semestre = semestre });

        }

        public static IEnumerable<Calificacion> CalificacionesAprobadas__By_IdAlumno_IdPlan_Anio_Semestre(object idAlumno, object idPlan, object anio, object semestre)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE planificacion.plan = @IdPlan
                AND planificacion.anio >= @Anio AND planificacion.semestre >= @Semestre 
                AND calificacion.alumno = @IdAlumno
                AND (nota_final >= 7 OR crec >= 4)
                ORDER BY planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdAlumno = idAlumno, IdPlan = idPlan, Anio = anio, Semestre = semestre });
        }

        public static IEnumerable<Calificacion> CalificacionesAprobadas__By_IdAlumno_DiffIdPlan(object idAlumno, object idPlan)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE planificacion.plan != @IdPlan
                AND calificacion.alumno = @IdAlumno
                AND (nota_final >= 7 OR crec >= 4)
                ORDER BY planificacion.plan ASC, planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdAlumno = idAlumno, IdPlan = idPlan, });
        }

        public static IEnumerable<Calificacion> Calificaciones__By_IdDisposicion_IdsAlumnos(object idDisposicion, params object[] idsAlumnos)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                WHERE calificacion.disposicion = @IdDisposicion
                AND calificacion.alumno IN ( @IdsAlumnos )
                ORDER BY planificacion.plan ASC, planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdsAlumnos = idsAlumnos, IdDisposicion = idDisposicion });
        }

        /// <summary> Calificaciones aprobadas de curso </summary>
        /// <remarks> No se realiza join directamente a curso, porque puede darse que el curso sea null </remarks>
        public static IEnumerable<Calificacion> CalificacionesAprobadas__By_IdCurso(object idCurso)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                INNER JOIN alumno ON calificacion.alumno = alumno.id
                INNER JOIN (
                    SELECT DISTINCT alumno_comision.alumno, curso.disposicion
                    FROM alumno_comision
                    INNER JOIN comision ON alumno_comision.comision = comision.id
                    INNER JOIN curso ON curso.comision = comision.id
                    WHERE curso.id = @IdCurso
                ) AS sub ON (sub.alumno = alumno.id AND sub.disposicion = calificacion.disposicion)
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE (nota_final >= 7 OR crec >= 4)
                ORDER BY planificacion.plan ASC, planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdCurso = idCurso });
        }

        /// <summary> Calificaciones desaprobadas de curso </summary>
        /// <remarks> No se realiza join directamente a curso, porque puede darse que el curso sea null </remarks>
        public static IEnumerable<Calificacion> CalificacionesDesaprobadas__By_IdCurso(object idCurso)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                INNER JOIN alumno ON calificacion.alumno = alumno.id
                INNER JOIN (
                    SELECT DISTINCT alumno_comision.alumno, curso.disposicion
                    FROM alumno_comision
                    INNER JOIN comision ON alumno_comision.comision = comision.id
                    INNER JOIN curso ON curso.comision = comision.id
                    WHERE curso.id = @IdCurso
                ) AS sub ON (sub.alumno = alumno.id AND sub.disposicion = calificacion.disposicion)
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE 
                    (nota_final < 7 AND crec < 4)
                    OR (nota_final < 7 AND crec IS NULL)
                    OR (nota_final IS NULL AND crec < 4)
                    OR (nota_final IS NULL AND crec IS NULL)
                ORDER BY planificacion.plan ASC, planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdCurso = idCurso });

        }

        public static IEnumerable<dynamic> CountCalificacionesAprobadas__By_ConcatAlumnoPlan_Group_IdAlumno_IdPlan(List<object> concatAlumnoPlan)
        {
            string sql = @"
                SELECT DISTINCT calificacion.alumno, planificacion.plan AS alumno_plan, COUNT(id) AS cantidad 
                FROM calificacion
                INNER JOIN alumno ON calificacion.alumno = alumno.id
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                WHERE  (nota_final > 7 OR crec > 4)
                AND CONCAT(alumno, disposicion.plan) in ( @ConcatAlumnoPlan )
                GROUP BY alumno, planificacion.plan
            ";

            using (var connection = Context.db.Connection().Open())
            {
                return connection.Query(sql, new { ConcatAlumnoPlan = concatAlumnoPlan });
            }
        }


        /// <summary> Calificaciones aprobadas de alumno para una determinada planificacion </summary>
        /// <remarks> Recordar que una planificacion es la combinacion entre anio, semestre y plan</remarks>
        public static IEnumerable<Calificacion> CalificacionesAprobadas_By_IdPlanificacion_IdsAlumnos(object idPlanificacion, params object[] idsAlumnos)
        {
            string sql = @"
                SELECT DISTINCT id FROM calificacion
                INNER JOIN alumno ON calificacion.alumno = alumno.id
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE (nota_final > 7 OR crec > 4)
                AND disposicion.planificacion = @IdPlanificacion AND calificacion.alumno IN ( @IdsAlumnos )
                ORDER BY planificacion.plan ASC, planificacion.anio ASC, planificacion.semestre ASC, asignatura.nombre ASC
            ";

            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdPlanificacion = idPlanificacion, IdsAlumnos = idsAlumnos });
        }


        /// <summary> Cantidad de calificaciones de alumno para una determinada planificacion </summary>
        /// <remarks> Recordar que una planificacion es la combinacion entre anio, semestre y plan</remarks>
        public static IEnumerable<Calificacion> Count_CalificacionesAprobadas__By_IdPlanificacion_IdsAlumnos__Group_idAlumno(object idPlanificacion, params object[] idsAlumnos)
        {
            string sql = @"
                SELECT alumno, COUNT(id) FROM calificacion
                INNER JOIN alumno ON calificacion.alumno = alumno.id
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                WHERE (nota_final > 7 OR crec > 4)
                AND disposicion.planificacion = @IdPlanificacion AND calificacion.alumno IN ( @IdsAlumnos )
                GROUP BY alumno
            ";
                
            return Context.db.CacheSql().QueryIds<Calificacion>(sql, new { IdPlanificacion = idPlanificacion, IdsAlumnos = idsAlumnos });

        }

        public static IEnumerable<dynamic> Count_CalificacionesAprobadas__BY_Concat_IdAlumno_IdPlan__GROUP_IdAlumno_IdPlan_Anio_Semestre(IEnumerable<object> concatsAlumnoPlan)
        {
            string sql = @"
                SELECT DISTINCT calificacion.alumno, planificacion.plan, anio, semestre AS alumno_plan, COUNT(id) AS cantidad 
                FROM calificacion
                INNER JOIN alumno ON calificacion.alumno = alumno.id
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                WHERE  (nota_final > 7 OR crec > 4)
                AND CONCAT(alumno, disposicion.plan) in ( @ConcatsAlumnoPlan )
                GROUP BY alumno, planificacion.plan, planificacion.anio, planificacion.semestre
            ";

            using (var connection = Context.db.Connection().Open())
            {
                return connection.Query(sql, new { ConcatsAlumnoPlan = concatsAlumnoPlan });
            }
        }

        public static  IEnumerable<dynamic> Count_CalificacionesAprobadas__By_Concat_IdAlumno_IdPlanificacion__Group_IdAlumno_IdPlanificacion(IEnumerable<object> concatsAlumnoPlanificacion)
        {
            string sql = @"
                SELECT DISTINCT calificacion.alumno, disposicion.planificacion, COUNT(id) AS cantidad 
                FROM calificacion
                INNER JOIN alumno ON calificacion.alumno = alumno.id
                INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                WHERE  (nota_final > 7 OR crec > 4)
                AND CONCAT(alumno, disposicion.planificacion) in ( @ConcatsAlumnoPlanificacion )
                GROUP BY calificacion.alumno, disposicion.planificacion
            ";

            using (var connection = Context.db.Connection().Open())
            {
                return connection.Query(sql, new { ConcatsAlumnoPlanificacion = concatsAlumnoPlanificacion });
            }
        }


    }
}
