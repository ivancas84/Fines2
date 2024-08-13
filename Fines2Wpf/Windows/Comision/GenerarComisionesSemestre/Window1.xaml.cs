using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Windows;

namespace Fines2Wpf.Windows.Comision.GenerarComisionesSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        public Window1()
        {
            InitializeComponent();

            Data_comision_r comisionObj = new ();
            comisionObj.calendario__anio = Convert.ToInt16(DateTime.Now.Year);
            comisionObj.calendario__semestre = Convert.ToInt16(DateTime.Now.ToSemester());
            comisionObj.autorizada = true;
            formGroupBox.DataContext = comisionObj;
        }

        private void GenerarButton_Click(object sender, RoutedEventArgs e)
        {

            Data_comision_r comisionObj = ((Data_comision_r)formGroupBox.DataContext).Clone()!;
            (short anio, short semestre) anioSemestre = CalendarioValues.AnioSemestreAnterior((short)comisionObj.calendario__anio, (short)comisionObj.calendario__semestre);
            comisionObj.calendario__anio = anioSemestre.anio;
            comisionObj.calendario__semestre = anioSemestre.semestre;

            #region Consultar comisiones del semestre anterior
            IEnumerable<Dictionary<string, object?>> comisionesAutorizadasSemestreAnterior = ContainerApp.db.Sql("comision").
                Search(comisionObj).
                Where(@" 
                    AND (
                        ($planificacion-anio = '3' AND $planificacion-semestre = '1')
                        OR ($planificacion-anio = '2' AND $planificacion-semestre = '2')
                        OR ($planificacion-anio = '2' AND $planificacion-semestre = '1')
                        OR ($planificacion-anio = '1' AND $planificacion-semestre = '2')
                        OR ($planificacion-anio = '1' AND $planificacion-semestre = '1')
                    )
                ").
                Size(0).
                ColOfDict();
            #endregion


            EntityPersist persist = ContainerApp.db.Persist();
            foreach (Dictionary<string, object?> com in comisionesAutorizadasSemestreAnterior)
            {
                Data_comision_r comObj = com.Obj<Data_comision_r>();

                string idPlanificacionSiguiente = ContainerApp.db.PlanificacionSiguienteSql(comObj.planificacion__anio!, comObj.planificacion__semestre!, comObj.plan__id!).Value<string>();

                EntityValues comisionVal = ContainerApp.db.Values("comision").
                    Set(comObj).
                    SetDefault("id").
                    Set("planificacion", idPlanificacionSiguiente).
                    Set("apertura", false).
                    Set("configuracion", "Histórica").
                    Set("calendario", idNuevoCalendarioTextBox.Text).
                    SetDefault("alta").
                    Reset();

                persist.Insert(comisionVal);
                persist.UpdateValueIds("comision", "comision_siguiente", comisionVal.Get("id"), comObj.id);
            }

            persist.Transaction().RemoveCache();

        }
    }
}
