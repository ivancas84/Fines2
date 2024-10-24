﻿using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class AsignacionDAO
    {
    
        public static EntitySql OtrasAsignacionesDeAlumnoSql(object idAlumno, object idComision)
        {
            return Context.db.Sql("alumno_comision").Where("$alumno = @0 AND $comision != @1").
                       Param("@0", idAlumno).Param("@1", idComision);
        }
        public static EntitySql AsignacionCursoDniSql(object curso, object dni)
        {
            string subSql = "SELECT comision FROM curso WHERE id = @0";

            return Context.db.Sql("alumno_comision")
               .Join("INNER JOIN (" + subSql + ") AS sub ON (sub.comision = $comision)")
               .Where("$persona__numero_documento = @1")
               .Param("@0", curso).Param("@1", dni);
        }

        public static EntitySql AsignacionComisionDniSql(object comision, object dni)
        {

            return Context.db.Sql("alumno_comision")
               .Where("$comision = @0 AND $persona__numero_documento = @1")
               .Param("@0",comision).Param("@1", dni);
        }

        public static EntitySql AsignacionesDeComisionesSql(params object[] id_comisiones)
        {
            return Context.db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $comision IN ( @0 )
                "
            )
               .Param("@0", id_comisiones);
        }

        public static EntitySql AsignacionesDeComisionesAutorizadasDelPeriodoSql(object anio, object semestre)
        {
            return Context.db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true
                "
                )
               .Param("@0", anio).Param("@1", semestre);
        }


        public static EntitySql AsignacionesActivasDeComisionesAutorizadasDelPeriodoSinGeneroSql(object anio, object semestre)
        {
            var subSql = Context.db.Sql("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true
                    AND $estado = 'Activo'
                    AND $persona__genero IS NULL
                ");

            return Context.db.Sql("alumno").
                Join("INNER JOIN (" + subSql + ") AS sub ON (sub.alumno = alumno.id)").
                Param("@0", anio).Param("@1", semestre);
        }

        public static EntitySql AsignacionesActivasRestantesComisionSql(object comision, IEnumerable<object> alumnos)
        {
            var esql = Context.db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $estado = 'Activo'
                    AND $comision = @0                    
                ")
               .Param("@0", comision);

            if (alumnos.Any())
                esql.And("$alumno NOT IN(@1)").Param("@1", alumnos);
 
            return esql;

        }

        public static EntitySql AsignacionesDeCalendario(object idCalendario)
        {
            var esql = Context.db.Sql("alumno_comision")
               .Size(0)
               .Where("$calendario__id = @0")
               .Param("@0", idCalendario);

            return esql;
        }


        public static EntitySql COUNT_AsignacionesActivasDuplicadasDeComisionesAutorizadas__BY_idCalendario__GROUP_alumno(object idCalendario)
        {
            return Context.db.Sql("alumno_comision")
                .Select("COUNT($id) AS cantidad")
                .Group("$alumno")
                .Size(0)
                .Where(@"
                    $calendario__id = @0
                    AND $estado = 'Activo'
                    AND $comision__autorizada = true")
                .Having("cantidad > 1")
                .Param("@0", idCalendario);
        }

        public static EntitySql AsignacionesActivasDeComisionesAutorizadas__BY_idCalendario_idsAlumnos(object idCalendario, IEnumerable<object> idsAlumnos)
        {
            return Context.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"
                    $calendario__id = @0
                    AND $alumno IN (@1)
                    AND $estado = 'Activo'
                    AND $comision__autorizada = true")
                .Param("@0", idCalendario)
                .Param("@1", idsAlumnos);
        }

    }
    
}
