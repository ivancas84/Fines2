﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.TomaPosesionPdf
{
    internal class DAO
    {
        public IEnumerable<Dictionary<string, object>> TomaAll(Search search)
        {
            return ContainerApp.db.Query("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(search.calendario__anio, search.calendario__semestre).ColOfDictCache();
        }
    }
}
