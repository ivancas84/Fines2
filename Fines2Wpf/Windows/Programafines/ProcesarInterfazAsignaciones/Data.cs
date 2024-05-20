using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones
{
    internal class Data
    {
        public string nombre { get; set; }

        public string nacimiento { get; set; }

        public string pfid { get; set; }

        public string? telefono { get; set; }

        public string dni { get; set; }

        public string comision { get; set; }

        public bool existe { get; set; } = false;

        public string? comparacion { get; set; } = null;
    }
}
