using System.Collections.Generic;
using SqlOrganize;

namespace Fines2Wpf.Windows.EnviarEmailToma
{
    internal class DAO
    {
        public IEnumerable<Dictionary<string, object>> TomaAll()
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $confirmada = false
                    AND $docente-email_abc IS NOT NULL
                ")
                .Order("$comision-pfid ASC")
                .Parameters("2023", "2").ColOfDictCache();

        }
    }
}
