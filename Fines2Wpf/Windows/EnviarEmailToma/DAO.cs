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
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $confirmada = false
                    AND $docente__email_abc IS NOT NULL
                ")
                .Order("$comision__pfid ASC")
                .Param("@0", "2023").Param("@1", "2").Cache().Dicts();

        }
    }
}
