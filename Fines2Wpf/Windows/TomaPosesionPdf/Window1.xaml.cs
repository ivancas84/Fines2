using QRCoder;
using QuestPDF.Fluent;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using Fines2Wpf.Windows.TomaPosesionPdf;
using Utils;
using Fines2Wpf.Windows.ListaTomas;
using Fines2Model3.Data;

namespace Fines2Wpf.Windows.TomaPosesionPdf
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        Search search = new();
        DAO.Toma tomaDao = new();
        QRCodeGenerator qrGenerator = new QRCodeGenerator();

        public Window1()
        {
            string calendarioAnio = "2024"; //DateTime.Now.Year.ToString();
            int calendarioSemestre = 1; //DateTime.Now.ToSemester();

            InitializeComponent();
            IEnumerable<Dictionary<string, object>> list = tomaDao.TomasSemestre(calendarioAnio, calendarioSemestre) ;
            foreach(Dictionary<string, object> item in list)
            {
                ConstanciaData toma = item.Obj<ConstanciaData>();
                QRCodeData qrCodeData = qrGenerator.CreateQrCode("https://planfines2.com.ar/validar-toma/" + toma.id, QRCodeGenerator.ECCLevel.Q);
                QRCode qrCode = new QRCode(qrCodeData);
                Bitmap qrCodeImage = qrCode.GetGraphic(20);
                ImageConverter converter = new ImageConverter();
                toma.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
                ConstanciaDocument document = new(toma);
                document.GeneratePdf("C:\\Users\\ivan\\Downloads\\" + toma.comision__pfid + "_" + toma.asignatura__codigo + "_" + toma.docente__numero_documento + ".pdf");
            
            }
        }


    }



    internal class Search
    {
        public string calendario__anio { get; set; } = DateTime.Now.Year.ToString();
        public int calendario__semestre { get; set; } = DateTime.Now.ToSemester();
    }


}
