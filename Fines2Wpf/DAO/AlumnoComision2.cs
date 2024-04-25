using SqlOrganize;
using System.Collections.Generic;
using Utils;

namespace Fines2Wpf.DAO
{
    public static class AlumnoComision2
    {

        public static EntityQuery AsignacionesActivasDeComisionesAutorizadasSinProgramafinesPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where(@"$calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $programafines = false
                    AND $comision-autorizada = true 
                    AND $estado = 'Activo'")
                .Parameters(anio, semestre);

        }

        public static EntityQuery AsignacionesNoActivasDeComisionesAutorizadasConProgramafinesPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where(@"$calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $programafines = true
                    AND $comision-autorizada = true 
                    AND $estado != 'Activo'")
                .Parameters(anio, semestre);

        }

        public static EntityQuery AsignacionActivaDeAlumnoAnioSemestreQuery(object alumno, object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where(@"$alumno = @0
                    AND $calendario-anio = @1 
                    AND $calendario-semestre = @2 
                    AND $comision-autorizada = true 
                    AND $estado = 'Activo'")
                .Parameters(alumno, anio, semestre);
        }

        public static EntityQuery AsignacionesDeComisionSinPlanQuery(object comision, object plan)
        {
            return ContainerApp.db.Query("alumno_comision")
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
