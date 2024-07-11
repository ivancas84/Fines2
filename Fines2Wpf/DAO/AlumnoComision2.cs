using SqlOrganize.Sql;
using System.Collections.Generic;

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



        


        public static EntitySql AsignacionesNoActivasDeComisionesAutorizadasConProgramafinesPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$calendario-anio = @0 
                    AND $calendario-semestre = @1 
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
