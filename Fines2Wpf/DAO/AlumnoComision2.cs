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
                .Where(@"$calendario__anio = @0 
                    AND $calendario__semestre = @1
                    AND $persona__numero_documento IN (@2)")
                .Param("@0", anio).Param("@1", semestre).Param("@2", dni);

        }



        


        public static EntitySql AsignacionesNoActivasDeComisionesAutorizadasConProgramafinesPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true 
                    AND $estado != 'Activo'")
                .Param("@0", anio).Param("@1", semestre);

        }

        public static EntitySql AsignacionActivaDeAlumnoAnioSemestreQuery(object alumno, object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$alumno = @0
                    AND $calendario__anio = @1 
                    AND $calendario__semestre = @2 
                    AND $comision__autorizada = true 
                    AND $estado = 'Activo'")
                .Param("@0", alumno).Param("@1", anio).Param("@2", semestre);
        }

        public static EntitySql AsignacionesDeComisionSinPlanQuery(object comision, object plan)
        {
            return ContainerApp.db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $alumno__plan != @0
                    AND $comision = @1
                    AND $activo = true
                ")
               .Param("@0", plan).Param("@1", comision);

        }


       

       
    }

}
