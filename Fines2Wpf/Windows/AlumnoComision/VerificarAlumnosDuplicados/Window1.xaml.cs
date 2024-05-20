using Fines2Wpf.Data;
using System.Collections.Generic;
using System.Windows;
using Utils;
using SqlOrganize;

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
            IEnumerable<object> idsAlumnos = asignacionDAO.IdsAlumnosActivosDuplicadosPorSemestreDeComisionesAutorizadasQuery("2024", "1").Column<object>("alumno");
            var alumnos = DAO.Alumno.AlumnosPorIds(idsAlumnos);

            alumnosGrid.ItemsSource = alumnos.ColOfObj<Data_alumno_r>();
        }

    }
}
