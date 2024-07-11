using SqlOrganize.Sql.Fines2Model3;
using System;

namespace Fines2Wpf.Windows.Alumno.ConstanciaPdf
{
    internal class ConstanciaData : Data_alumno_r
    {
        public Byte[] qr_code { get; set; }

        public string url { get; set; } = "No válido";

        public string titulo_constancia { get; set; } = "CONSTANCIA GENERAL";

    }
}
