﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.DAO
{
    public class Sede
    {
        public IEnumerable<Dictionary<string, object>> BusquedaAproximada(string search)
        {
            return ContainerApp.db.Query("sede").
                Fields("id, nombre").
                Size(10).
                Where(@"
                    $nombre LIKE @0 
                    OR $numero LIKE @1
                ")
                .Parameters("%" + search + "%", "%" + search + "%")
                .Order("$nombre ASC").ColOfDictCache();
        }

    }
}
