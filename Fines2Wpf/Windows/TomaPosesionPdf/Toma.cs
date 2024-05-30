using Fines2Model3.Data;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.TomaPosesionPdf
{

    internal class ConstanciaData : Data_toma_r
    {

        public ConstanciaData() : base()
        {
        }

        public ConstanciaData(Db db) : base(db)
        {
        }

        public Byte[] qr_code { get; set; }

    }
}
