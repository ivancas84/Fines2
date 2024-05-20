using System;
using System.Collections.Generic;
using System.Diagnostics;
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
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace Fines2Wpf.Windows.Index
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        private Dictionary<string, Window> windows = new Dictionary<string, Window>();

        public Window1()
        {
            InitializeComponent();
        }
        private void Hyperlink_RequestNavigate(object sender, RequestNavigateEventArgs e)
        {
            Window window;
            switch (e.Uri.AbsoluteUri.Substring(12).Replace("/",""))
            {
                case "lista_asignaciones_programa_fines":
                    window = new AlumnoComision.ListaAlumnosProgramaFines.Window1();
                    break;

                case "lista_alumnos_semestre_sin_genero":
                    window = new Windows.AlumnoComision.AlumnosSemestreSinGenero();
                    break;

                case "verificar_alumnos_progresar":
                    window = new Windows.AlumnoComision.VerificarAlumnosProgresar.Window1();
                    break;
                    

                default:
                    window = new Window();
                    break;
            }

            window.Show();
        }
    }
}
