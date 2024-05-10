using Fines2Wpf.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.Alumno.ConstanciaAlumnoRegularPdf
{
    internal class ConstanciaData : Data_alumno_r
    {
        public Byte[] qr_code { get; set; }

        public string url { get; set; } = "No válido";

        public string anio_constancia { get; set; }

        public string resolucion_constancia { get; set; }

        public string orientacion_constancia { get; set; }
        public string? observaciones_constancia { get; set; }


    }
}
