﻿using Fines2Model3.Data;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
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
            List<EntityPersist> persists = new();

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
                .Cache().ColOfDict()
                .DictOfListByKeys("comision-comision_siguiente");
           
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

                    ContainerApp.db.Persist().Insert(acVal).AddTo(persists);
                }
            }

            persists.Exec().RemoveCache();

        }
    }
}
