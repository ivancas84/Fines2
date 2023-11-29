using Fines2Wpf.Data;
using Fines2Wpf.Values;
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

namespace Fines2Wpf.Pedidos.Windows.GenerarTareasCalificacionesSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        DAO.Toma tomaDAO = new ();
        private ObservableCollection<Data_toma_r> tomaOC = new();

        public Window1()
        {
            InitializeComponent();
            searchGroupBox.DataContext = new Data_toma_r(DataInitMode.Null);
            tomaDataGrid.ItemsSource = tomaOC;
        }

        private void GenerarPedidos_Click(object sender, RoutedEventArgs e)
        {
            Data_toma_r search = (Data_toma_r)searchGroupBox.DataContext;
            tomaOC.Clear();
            var data = tomaDAO.TomasAprobadasSemestreQuery(search.calendario__anio!, search.calendario__semestre!).ColOfDictCache();
            foreach (var item in data)
            {
                //item.Obj<Data_disposicion_r>();
                Data_toma_r obj = new(DataInitMode.Null);
                obj.SetData(item);
                obj.curso__Label = obj.sede__numero + obj.comision__division + "/" + obj.planificacion__anio + obj.planificacion__semestre + " " + obj.sede__nombre;
                tomaOC.Add(obj);
            }

        }

        private void GuardarPedidos_Click(object sender, RoutedEventArgs e)
        {
            Data_toma_r search = (Data_toma_r)searchGroupBox.DataContext;

            foreach (var t in tomaOC)
            {
                EntityValues ticketsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_tickets").Default().
                   Set("subject", "Calificaciones " + t.docente__nombres + " " + t.docente__apellidos).
                   Set("status", 1).
                   Set("cust_24", t.docente__numero_documento).
                   Set("cust_27", t.docente__telefono).
                   Set("cust_28", "Carga de calificación en período " + search.calendario__anio + "-" + search.calendario__semestre).
                   Set("assigned_agent", "").Reset();

                EntityValues threadsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_threads").Default().
                    Set("ticket", ticketsValues.Get("id")).
                    Set("body", @"
                        <p>Alumno activo en período 2023-1</p>
                    ").Reset();

                if(!ticketsValues.Check() && !threadsValues.Check())
                {
                    throw new Exception("El chequeo de valores es incorrecto");
                }

                EntityPersist persist = ContainerApp.dbPedidos.Persist();
                ticketsValues.Insert(persist);
                threadsValues.Insert(persist);
                persist.Transaction().RemoveCache();
            }
        }
    }
}
