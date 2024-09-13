using System.Collections.Generic;
using SqlOrganize;

namespace Fines2Wpf.Forms.InformeCoordinacionDistrital.DAO
{
    class Calificacion
    {
        public IEnumerable<Dictionary<string, object>> AprobadasPorAlumnoPlan(string idAlumno, string idPlan)
        {
            return ContainerApp.db.Sql("calificacion")
                    .Size(0)
                    .Where("$alumno = @0 AND ($nota_final >= 7 OR $crec >= 4) AND $planificacion_dis__plan = @1")
                    .Param("@0", idAlumno).Param("@1", idPlan)
                    .Order("$planificacion_dis__anio ASC, $planificacion_dis__semestre ASC, $disposicion__orden_informe_coordinacion_distrital ASC").
                    Cache().Dicts();
        }
    }
}
