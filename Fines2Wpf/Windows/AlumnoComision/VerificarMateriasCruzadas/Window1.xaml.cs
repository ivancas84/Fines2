using System.Collections.Generic;
using System.Windows;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Fines2Model3;

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
            var idsAlumnosMateriasCruzadas = calificacionDAO.IdsAlumnosConCalificacionesAprobadasCruzadasNoArchivadasQuery(idsAlumnos).Cache().Dicts().ColOfVal<object>("cantidad_planes");
            var calificaciones = calificacionDAO.CalificacionesAprobadasNoArchivadasDeAlumnosQuery(idsAlumnosMateriasCruzadas).Cache().Dicts();

            calificacionesGrid.ItemsSource = calificaciones.Objs<Data_calificacion_r>();

        }
    }

}
