using Fines2Model3.Data;
using Org.BouncyCastle.Crypto;
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

namespace Fines2Wpf.Windows.AlumnoComision.DesactivarAlumnosNoCalificados
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        DAO.AlumnoComision asignacionDAO = new();
        DAO.Calificacion calificacionDAO = new();

        ObservableCollection<Data_alumno_comision_r> alumnosParaActivarOC = new();
        ObservableCollection<Data_alumno_comision_r> alumnosParaDesactivarOC = new();

        public Window1()
        {
            InitializeComponent();

            alumnosParaActivarDataGrid.ItemsSource = alumnosParaActivarOC;
            alumnosParaDesactivarDataGrid.ItemsSource = alumnosParaDesactivarOC;

            alumnosParaActivarOC.Clear();
            alumnosParaDesactivarOC.Clear();

            var alumnosComisiones = asignacionDAO.AsignacionesDeComisionesAutorizadasPorSemestre("2023", "2");
            List<object> idsParaActivar = new();
            List<object> idsParaDesactivar = new();

            foreach (var alumnoComision in alumnosComisiones)
            {
                Data_alumno_comision_r alumnoComisionObj = alumnoComision.Obj<Data_alumno_comision_r>();
                var qu = calificacionDAO.CantidadCalificacionesAprobadaNoArchivadasDeAlumnoPorTramoQuery(alumnoComisionObj.alumno, alumnoComisionObj.planificacion__anio, alumnoComisionObj.planificacion__semestre).DictCache();
                var q = (!qu.IsNullOrEmptyOrDbNull()) ? (Int64)qu["cantidad"]! : 0;

                if (alumnoComisionObj.estado.Equals("Activo") && q < 3)
                {
                    idsParaDesactivar.Add(alumnoComision["id"]);
                    var a = alumnoComision.Obj<Data_alumno_comision_r>();
                    alumnosParaDesactivarOC.Add(a);
                }

                if (alumnoComisionObj.estado.Equals("No activo") && q >= 3)
                {
                    idsParaActivar.Add(alumnoComision["id"]);
                    var a = alumnoComision.Obj<Data_alumno_comision_r>();
                    alumnosParaActivarOC.Add(a);
                }
            }

            if (idsParaDesactivar.Count > 0)
                ContainerApp.db.Persist().UpdateValueIds("alumno_comision", "estado", "No activo", idsParaDesactivar.ToArray()).Exec().RemoveCache();


            if (idsParaActivar.Count > 0)
                ContainerApp.db.Persist().UpdateValueIds("alumno_comision", "estado", "Activo", idsParaActivar.ToArray()).Exec().RemoveCache();

        }
    }

}
