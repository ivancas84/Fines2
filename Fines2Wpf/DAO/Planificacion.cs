using SqlOrganize;
using SqlOrganize.Sql.Fines2Model3;
using System;

namespace Fines2Wpf.DAO
{
    public class Planificacion
    {
        public string PlanificacionSiguiente(string anio, string semestre, string plan__id)
        {
            PlanificacionValues planificacionValues = (PlanificacionValues)ContainerApp.db.Values("planificacion").
                Set("anio", anio).
                Set("semestre", semestre).
                Set("plan", plan__id);

            (string  anio_, string semestre_) = planificacionValues.AnioSemestreSiguiente();

            var data = ContainerApp.db.Sql("planificacion").
                Where(@"
                    $anio = @0 AND $semestre = @1 AND $plan = @2
                ").Parameters(anio_, semestre_, plan__id).Cache().Dict();

            if (data.IsNoE())
                throw new Exception("No existe planificacion siguiente");
            
            return (string)data!["id"]!;

        }

    }
}
