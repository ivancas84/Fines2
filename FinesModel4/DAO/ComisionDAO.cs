using FinesModel4.Models;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace FinesModel4.DAO
{
    public class ComisionDAO
    {
        public static void AddFilteredComisionesToCollection(short anio, short semestre, string cargo)
        {
            using (var context = new Planfi1020204Context())
            {
                var query = context.Comisiones
                    .Where(c => c.CalendarioFk.Anio == anio
                             && c.CalendarioFk.Semestre == semestre
                             && c.SedeFk.Designacions.Any(d => d.Cargo == cargo))
                    .ToList();

            }
        }

    }
}
