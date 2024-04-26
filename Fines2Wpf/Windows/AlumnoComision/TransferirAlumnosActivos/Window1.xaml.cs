using Fines2Wpf.Data;
using Fines2Wpf.Windows.Curso.ListaCursoSemestreSinTomasAprobadas;
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

namespace Fines2Wpf.Windows.AlumnoComision.TransferirAlumnosActivos
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        ObservableCollection<Data_alumno_comision_r> asignacionOC = new();
        public Window1()
        {
            InitializeComponent();
            calendarioAnioTextBox.Text = DateTime.Now.Year.ToString();
            calendarioSemestreTextBox.Text = DateTime.Now.ToSemester().ToString();
            asignacionDataGrid.ItemsSource = asignacionOC;
        }

        private void TransferirButton_Click(object sender, RoutedEventArgs e)
        {

            IDictionary < string, List<Dictionary<string, object?>>> alumnosComisiones = ContainerApp.db.Sql("alumno_comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                    AND $comision-comision_siguiente IS NOT NULL
                    AND $estado = 'Activo'
                ")
                .Parameters(calendarioAnioTextBox.Text, calendarioSemestreTextBox.Text)
                .ColOfDictCache()
                .DictOfListByKeys("comision-comision_siguiente");

            EntityPersist persist = ContainerApp.db.Persist();
            asignacionOC.Clear();
            foreach (var (idComisionSiguiente, acs) in alumnosComisiones)
            {
                
                foreach (Dictionary<string, object?> ac in acs)
                {
                    Data_alumno_comision_r acObj = ac.Obj<Data_alumno_comision_r>();
                    acObj.comision__Label = acObj.sede__numero + acObj.comision__division + "/" + acObj.planificacion__anio + acObj.planificacion__semestre;
                    asignacionOC.Add(acObj);

                    EntityValues acVal = ContainerApp.db.Values("alumno_comision").
                        Set("comision", idComisionSiguiente).
                        Set("alumno", acObj.alumno).
                        Set("estado", "Activo").Default().Reset();

                    persist.Insert(acVal);
                }
            }

            persist.TransactionSplit().RemoveCache();

        }
    }
}
