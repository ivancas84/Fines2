using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.DAO
{
    internal class Curso
    {
        public IEnumerable<Dictionary<string, object>> CursosAutorizadosSemestre(object calendarioAnio, object calendarioSemestre, object? sede = null, bool? autorizada = null)
        {
            return ContainerApp.db.Query("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true 
                ")
                .Parameters(calendarioAnio, calendarioSemestre).ColOfDictCache();

        }

        public IEnumerable<Dictionary<string, object>> CursosSemestre(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Query("curso")
                .Fields()
                .Select("CONCAT($sede-numero, $comision-division, '/', $planificacion-anio, $planificacion-semestre) AS numero")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(calendarioAnio, calendarioSemestre).ColOfDictCache();
        }
    }
}
