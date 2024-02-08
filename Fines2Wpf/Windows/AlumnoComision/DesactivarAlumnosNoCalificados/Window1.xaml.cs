using Org.BouncyCastle.Crypto;
using SqlOrganize;
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

namespace Fines2Wpf.Windows.AlumnoComision.DesactivarAlumnosNoCalificados
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        DAO.AlumnoComision asignacionDAO = new();
        DAO.Calificacion calificacionDAO = new();


        public Window1()
        {
            InitializeComponent();

            var alumnosComisiones = asignacionDAO.AsignacionesActivasDeComisionesAutorizadasPorSemestre("2023", "2");
            List<AlumnoComision> data = new();
            List<object> ids = new();
            foreach (var alumnoComision in alumnosComisiones)
            {

                var qu = calificacionDAO.CantidadCalificacionesAprobadasDeAlumnoPorTramoQuery(alumnoComision["alumno"], alumnoComision["planificacion-anio"], alumnoComision["planificacion-semestre"]).DictCache();
                var q = (qu.IsNullOrEmpty()) ? (Int64)qu["cantidad"]! :  0;
                
                if (q < 3)
                {
                    ids.Add(alumnoComision["id"]);
                    var a = alumnoComision.Obj<AlumnoComision>();
                    data.Add(a);
                }
            }
            if(ids.Count > 0) { 
                alumnoComisionGrid.ItemsSource = data;
                ContainerApp.db.Persist().UpdateValueIds("alumno_comision", "estado", "No activo", ids).Exec().RemoveCache();
            }


        }
    }


    internal class AlumnoComision { 
        public string id { get; set; }
        public string persona__nombres { get; set; }
        public string persona__apellidos { get; set; }
        public string persona__numero_documento { get; set; }
        public string comision__division { get; set; }
        public string sede__numero { get; set; }
        public string planificacion__anio { get; set; }
        public string planificacion__semestre { get; set; }
    }
}
