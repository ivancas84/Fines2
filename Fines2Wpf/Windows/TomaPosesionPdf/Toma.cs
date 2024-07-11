using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;

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
