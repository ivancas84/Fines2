using Fines2Wpf.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.Alumno.ConstanciaPdf
{
    internal class ConstanciaData : Data_alumno_r
    {
        public Byte[] qr_code { get; set; }

        public string url { get; set; } = "No válido";

        public string titulo_constancia { get; set; } = "CONSTANCIA GENERAL";

    }
}
