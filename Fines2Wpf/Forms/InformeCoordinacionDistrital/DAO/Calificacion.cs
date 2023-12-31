﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Forms.InformeCoordinacionDistrital.DAO
{
    class Calificacion
    {
        public IEnumerable<Dictionary<string, object>> AprobadasPorAlumnoPlan(string idAlumno, string idPlan)
        {
            return ContainerApp.db.Query("calificacion")
                    .Size(0)
                    .Where("$alumno = @0 AND ($nota_final >= 7 OR $crec >= 4) AND $planificacion_dis-plan = @1")
                    .Parameters(idAlumno, idPlan)
                    .Order("$planificacion_dis-anio ASC, $planificacion_dis-semestre ASC, $disposicion-orden_informe_coordinacion_distrital ASC").
                    ColOfDictCache();
        }
    }
}
