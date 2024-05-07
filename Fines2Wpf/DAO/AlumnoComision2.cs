﻿using SqlOrganize;
using System.Collections.Generic;
using Utils;

namespace Fines2Wpf.DAO
{
    public static class AlumnoComision2
    {
        public static EntitySql TodasLasAsignacionesAsignacionesDelSemestrePorDNIQuery(object anio, object semestre, IEnumerable<object> dni)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND $persona-numero_documento IN (@2)")
                .Parameters(anio, semestre, dni);

        }


        public static EntitySql AsignacionesActivasDeComisionesAutorizadasPorSemestreYProgramafinesQuery(object anio, object semestre, bool programafines)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $programafines = @2
                    AND $comision-autorizada = true 
                    AND $estado = 'Activo'")
                .Parameters(anio, semestre, programafines);

        }

        public static EntitySql AsignacionesActivasDeComisionesAutorizadasConProgramafinesPorSemestreQuery(object anio, object semestre)
        {
            return AsignacionesActivasDeComisionesAutorizadasPorSemestreYProgramafinesQuery(anio, semestre, true);

        }

        public static EntitySql AsignacionesActivasDeComisionesAutorizadasSinProgramafinesPorSemestreQuery(object anio, object semestre)
        {
            return AsignacionesActivasDeComisionesAutorizadasPorSemestreYProgramafinesQuery(anio, semestre, false);
        }

        public static EntitySql AsignacionesNoActivasDeComisionesAutorizadasConProgramafinesPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $programafines = true
                    AND $comision-autorizada = true 
                    AND $estado != 'Activo'")
                .Parameters(anio, semestre);

        }

        public static EntitySql AsignacionActivaDeAlumnoAnioSemestreQuery(object alumno, object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$alumno = @0
                    AND $calendario-anio = @1 
                    AND $calendario-semestre = @2 
                    AND $comision-autorizada = true 
                    AND $estado = 'Activo'")
                .Parameters(alumno, anio, semestre);
        }

        public static EntitySql AsignacionesDeComisionSinPlanQuery(object comision, object plan)
        {
            return ContainerApp.db.Sql("alumno_comision")
               .Fields("alumno")
               .Size(0)
               .Where(@"
                    $alumno-plan != @0
                    AND $comision = @1
                    AND $activo = true
                ")
               .Parameters(plan, comision);

        }
    }

}
