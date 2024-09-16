using FinesModel4.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace FinesModel4.DAO
{
    public class CalendarioDAO
    {
        public static List<Calendario> GetCalendarios()
        {
            using (var context = new Planfi1020204Context())
            {
                return context.Calendarios
                    .Where(c => c.Inicio.HasValue) 
                    .OrderByDescending(c => c.Inicio)
                    .ToList();

            }
        }
    }
}
