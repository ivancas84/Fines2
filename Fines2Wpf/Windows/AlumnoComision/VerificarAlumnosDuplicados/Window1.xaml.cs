using Fines2Wpf.Data;
using System;
using System.Collections.Generic;
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

namespace Fines2Wpf.Windows.AlumnoComision.VerificarAlumnosDuplicados
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    /// 

    public partial class Window1 : Window
    {
        Fines2Wpf.DAO.AlumnoComision asignacionDAO = new();
        List<string> logs = new();

        public Window1()
        {
            InitializeComponent();
            IEnumerable<object> idsAlumnos = asignacionDAO.IdsAlumnosActivosDuplicadosPorSemestreDeComisionesAutorizadasQuery("2023", "2").Column<object>("alumno");
            var alumnos = DAO.Alumno.AlumnosPorIds(idsAlumnos);

            alumnosGrid.ItemsSource = alumnos.ColOfObj<Data_alumno_r>();
        }

    }
}
