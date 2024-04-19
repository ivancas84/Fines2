using SqlOrganize;

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
    }

}
