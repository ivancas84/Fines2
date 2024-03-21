using Fines2Wpf.Data;
using System;
using System.Collections.Generic;
using System.Windows;
using Utils;

namespace Fines2Wpf.Windows.EnviarEmailToma
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        DAO dao = new();
        public Window1()
        {
            InitializeComponent();
            IEnumerable<Dictionary<string, object>> list = dao.TomaAll();
            foreach (Dictionary<string, object> item in list)
            {
                List<string> comisiones = new() { "10086" };
                Data_toma_r toma = item.Obj<Data_toma_r>();
                if (comisiones.Contains(toma.comision__pfid) && toma.docente__numero_documento.Equals("24869647")) {
                    if (toma.docente__email_abc.IsNullOrEmpty())
                    {
                        info.Text += $@"El email de la docente no esta definido en: {toma.comision__pfid} {toma.asignatura__nombre}
";
                        continue;
                    }
                    Email email = new Email(toma);
                    email.Send();
                    info.Text += email.Subject + @"
";
                }
                /*info.Text += email.To + @"
";
                info.Text += email.Attachment + @"
";
                
                info.Text += email.Body + @"



"; */
            }

            

        }
    }

}
