using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.RightsManagement;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.DAO
{
    internal static class Comision2
    {
      
        public static EntityQuery ComisionesAutorizadasDeAnioSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Query("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre);
        }


    }
}
