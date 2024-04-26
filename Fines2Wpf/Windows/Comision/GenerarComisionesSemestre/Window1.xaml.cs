using Fines2Wpf.Data;
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

namespace Fines2Wpf.Windows.Comision.GenerarComisionesSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        DAO.Planificacion planificacionDAO = new ();
        public Window1()
        {
            InitializeComponent();

            Data_comision_r comisionObj = new (SqlOrganize.DataInitMode.Null);
            comisionObj.calendario__anio = Convert.ToInt16(DateTime.Now.Year);
            comisionObj.calendario__semestre = Convert.ToInt16(DateTime.Now.ToSemester());
            comisionObj.autorizada = true;
            formGroupBox.DataContext = comisionObj;
        }

        private void GenerarButton_Click(object sender, RoutedEventArgs e)
        {

            Data_comision_r comisionObj = ((Data_comision_r)formGroupBox.DataContext).Clone()!;


            Values.Calendario calendarioVal = (Values.Calendario)ContainerApp.db.Values("calendario").
                Set("anio", comisionObj.calendario__anio).Set("semestre", comisionObj.calendario__semestre);

            (short anio, short semestre) anioSemestre = calendarioVal.AnioSemestreAnterior();
            comisionObj.calendario__anio = anioSemestre.anio;
            comisionObj.calendario__semestre = anioSemestre.semestre;


            #region Consultar comisiones del semestre anterior
            IEnumerable<Dictionary<string, object?>> comisionesAutorizadasSemestreAnterior = ContainerApp.db.Sql("comision").
                SearchObj(comisionObj).
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

                string idPlanificacionSiguiente = planificacionDAO.PlanificacionSiguiente(comObj.planificacion__anio!, comObj.planificacion__semestre!, comObj.plan__id!);

                EntityValues comisionVal = ContainerApp.db.Values("comision").
                    SetObj(comObj).
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
