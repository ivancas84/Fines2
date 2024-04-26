using Fines2Wpf.Data;
using System.Collections.Generic;
using System.Windows;
using Utils;
using SqlOrganize;

namespace Fines2Wpf.Windows.AlumnoComision.VerificarMateriasCruzadas
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        DAO.AlumnoComision asignacionDAO = new();
        DAO.Calificacion calificacionDAO = new();

        List<string> logs = new();


        public Window1()
        {
            InitializeComponent();

            var idsAlumnos = asignacionDAO.IdsAlumnosDeComisionesAutorizadasPorSemestre("2023", "2");
            var idsAlumnosMateriasCruzadas = calificacionDAO.IdsAlumnosConCalificacionesAprobadasCruzadasNoArchivadasQuery(idsAlumnos).ColOfDictCache().ColOfVal<object>("cantidad_planes");
            var calificaciones = calificacionDAO.CalificacionesAprobadasNoArchivadasDeAlumnosQuery(idsAlumnosMateriasCruzadas).ColOfDictCache();

            calificacionesGrid.ItemsSource = calificaciones.ColOfObj<Data_calificacion_r>();

        }
    }

}
