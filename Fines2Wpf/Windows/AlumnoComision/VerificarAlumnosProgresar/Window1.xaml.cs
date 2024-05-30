using Fines2Model3.Data;
using SqlOrganize;
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

namespace Fines2Wpf.Windows.AlumnoComision.VerificarAlumnosProgresar
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private ObservableCollection<Data_alumno_comision_r> asignacionesOC = new();

        public Window1()
        {
            InitializeComponent();
            asignacionesDataGrid.ItemsSource = asignacionesOC;
            headerTextBox.Text = "persona-numero_documento";
        }

        private void ProcesarAlumnosButton_Click(object sender, RoutedEventArgs e)
        {
            IEnumerable<string> _headers = headerTextBox.Text.Split(", ").Select(s => s.Trim());

            string[] _data = alumnoTextBox.Text.Split("\r\n");
            List<object> dnis = new();

            for (var j = 0; j < _data.Length; j++)
            {
                if (_data[j].IsNullOrEmpty() || _data[j].Length < 8)
                    continue;

                dnis.Add(_data[j].Substring(2,8));
            }

            IEnumerable<Dictionary<string, object?>> asignacionesData = DAO.AlumnoComision2.TodasLasAsignacionesAsignacionesDelSemestrePorDNIQuery("2024", "1", dnis).
                ColOfDictCache();

            asignacionesOC.Clear();
            asignacionesOC.AddRange(asignacionesData);
        }


    }
}