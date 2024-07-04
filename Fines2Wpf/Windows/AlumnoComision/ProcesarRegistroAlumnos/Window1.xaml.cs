using CommunityToolkit.WinUI.Notifications;
using Fines2Model3.Data;
using Fines2Wpf.DAO;
using Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
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
using Utils;

namespace Fines2Wpf.Windows.AlumnoComision.ProcesarRegistroAlumnos
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {


        private ObservableCollection<Data_alumno_comision_r> asignacionRegistroOC = new();

        public Window1()
        {
            InitializeComponent();
        }

        private async void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            asignacionRegistroOC.Clear();

            var datosIniciales = ConsultarDatosIniciales();

            
        }

        private (IDictionary<string, object?> pfidsComisiones, IDictionary<string, Data_alumno_comision_r> asignacionesDb) ConsultarDatosIniciales()
        {
            IDictionary<string, object?>  pfidsComisiones = ContainerApp.db.
                ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, 1).
                Cache().ColOfDict().
                DictOfDictByKeysValue("id", "pfid");

            IDictionary<string, Data_alumno_comision_r> asignacionesDb = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, 1).
                Cache().ColOfDict().
                ColOfObj<Data_alumno_comision_r>().
                DictOfObjByPropertyNames("persona__numero_documento");

            return (pfidsComisiones, asignacionesDb);

        }
    }
}
