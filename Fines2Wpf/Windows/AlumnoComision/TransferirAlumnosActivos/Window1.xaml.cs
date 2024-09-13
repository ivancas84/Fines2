using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;

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
            List<EntityPersist> persists = new();

            IDictionary < string, List<Dictionary<string, object?>>> alumnosComisiones = ContainerApp.db.Sql("alumno_comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true
                    AND $comision__comision_siguiente IS NOT NULL
                    AND $estado = 'Activo'
                ")
                .Param("@0", calendarioAnioTextBox.Text).Param("@1", calendarioSemestreTextBox.Text)
                .Cache().Dicts()
                .DictOfListByKeys("comision__comision_siguiente");
           
            asignacionOC.Clear();
            foreach (var (idComisionSiguiente, acs) in alumnosComisiones)
            {
                
                foreach (Dictionary<string, object?> ac in acs)
                {
                    Data_alumno_comision_r acObj = ac.Obj<Data_alumno_comision_r>();
                    acObj.comision__Label = acObj.sede__numero + acObj.comision__division + "/" + acObj.planificacion__anio + acObj.planificacion__semestre;
                    asignacionOC.Add(acObj);

                    EntityVal acVal = ContainerApp.db.Values("alumno_comision").
                        Set("comision", idComisionSiguiente).
                        Set("alumno", acObj.alumno).
                        Set("estado", "Activo").Default().Reset();

                    ContainerApp.db.Persist().Insert(acVal).AddTo(persists);
                }
            }

            persists.Exec().RemoveCache();

        }
    }
}
