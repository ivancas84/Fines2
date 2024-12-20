﻿using Dapper;
using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class AsignacionDAO
    {
    
        public static IEnumerable<AlumnoComision> Asignaciones__By_IdAlumno__NotIn_IdComision(object idAlumno, object idComision)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                WHERE alumno.comision.alumno = @IdAlumno
                AND alumno_comision.comision != @IdComision
            ";

            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdComision = idComision, IdAlumno = idAlumno });
        }

        public static AlumnoComision? Asignacion__By_IdCurso_NumeroDocumento(object id_curso, object numero_documento)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                INNER JOIN alumno ON (alumno.id = alumno_comision.alumno)
                INNER JOIN persona ON (alumno.persona = persona.id)
                INNER JOIN (
                    SELECT comision FROM curso WHERE id = @IdCurso
                ) AS sub ON (sub.comision = alumno_comision.comision)
                WHERE persona.numero_documento = @NumeroDocumento
            ";

            var response = Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdCurso = id_curso, NumeroDocumento = numero_documento });

            return (response.Any()) ? response.ElementAt(0) : null;
        }

        public static AlumnoComision? Asignacion__By_IdComision_NumeroDocumento(object id_comision, object numero_documento)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                INNER JOIN alumno ON (alumno.id = alumno_comision.alumno)
                INNER JOIN persona ON (alumno.persona = persona.id)
                WHERE persona.numero_documento = @NumeroDocumento
                AND alumno_comision.comision = @IdComision
            ";

            var response = Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdComision = id_comision, NumeroDocumento = numero_documento });

            return (response.Any()) ? response.ElementAt(0) : null;
        }

        public static IEnumerable<AlumnoComision> Asignaciones__By_IdComisiones(params object[] idComisiones)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                WHERE alumno_comision.comision IN ( @IdComisiones )
            ";

            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdComisiones = idComisiones});
        }

        public static IEnumerable<AlumnoComision> AsignacionesDeComisionesAutorizadas__By_Periodo(object anio, object semestre)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                INNER JOIN comision ON (alumno_comision.comision = comision.id)
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario.anio = @Anio 
                AND calendario.semestre = @Semestre
                AND comision.autorizada = true
            ";

            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { Anio = anio, Semestre = semestre });

        }


        public static IEnumerable<AlumnoComision> AsignacionesActivas__By_IdComision__NotIn_IdAlumnos(object idComision, params object[] idAlumnos)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                WHERE alumno_comision.comision = @IdComision 
                AND estado = 'Activo'
            ";

            if (idAlumnos.Any())
                sql += @"
                    AND alumno_comision.alumno NOT IN ( @IdAlumnos )
                ";


            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdComision = idComision, IdAlumnos = idAlumnos });
        }

        public static IEnumerable<AlumnoComision> Asignaciones__By_IdCalendario(object idCalendario)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                INNER JOIN comision ON (alumno_comision.comision = comision.id)
                WHERE comision.calendario = @IdCalendario
            ";

            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdCalendario = idCalendario });


        }

        public static IEnumerable<AlumnoComision> Asignaciones__With_ComisionSiguiente__By_IdCalendario(object idCalendario)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                INNER JOIN comision ON (alumno_comision.comision = comision.id)
                WHERE comision.calendario = @IdCalendario
                AND comision.comision_siguiente IS NOT NULL
            ";

            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdCalendario = idCalendario });
        }

        public static IEnumerable<AlumnoComision> AsignacionesActivas__With_ComisionSiguiente__By_IdCalendario(object idCalendario)
        {
            string sql = @"
                SELECT id FROM alumno_comision 
                INNER JOIN comision ON (alumno_comision.comision = comision.id)
                WHERE comision.calendario = @IdCalendario
                AND comision.estado = 'Activo'
                AND comision.comision_siguiente IS NOT NULL
            ";


            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdCalendario = idCalendario });
        }


        public static IEnumerable<dynamic> Count_AsignacionesActivasDuplicadas__By_IdCalendario__Group_IdAumno(object idCalendario)
        {
            string sql = @"
                SELECT alumno, COUNT(id) AS cantidad
                FROM alumno_comision 
                INNER JOIN comision ON (alumno_comision.comision = comision.id)
                WHERE comision.calendario = @IdCalendario
                AND comision.estado = 'Activo'
                AND comision.autorizada = true
                GROUP BY alumno
                HAVING cantidad > 1
            ";

            using(var connection = Context.db.Connection().Open())
            {
                return connection.Query(sql, new { IdCalendario = idCalendario });
            }
        }

        public static IEnumerable<AlumnoComision> AsignacionesActivas__BY_IdCalendario_IdsAlumnos(object idCalendario, IEnumerable<object> idsAlumnos)
        {
            string sql = @"
                SELECT id
                FROM alumno_comision 
                INNER JOIN comision ON (alumno_comision.comision = comision.id)
                WHERE comision.calendario = @IdCalendario
                AND alumno_comision.alumno IN ( @IdsAlumnos )
                AND alumno_comision.estado = 'Activo'
                AND comision.autorizada = true
                GROUP BY alumno
                HAVING cantidad > 1
            ";


            return Context.db.CacheSql().QueryIds<AlumnoComision>(sql, new { IdCalendario = idCalendario, IdsAlumnos = idsAlumnos });
        }


        public static IEnumerable<string> EstadosDeAsignaciones()
        {
            string sql = @"
                SELECT DISTINCT estado
                FROM alumno_comision 
            ";


            using (var connection = Context.db.Connection().Open())
            {
                return connection.Query<string>(sql);
            }
        }

    }
    
}
