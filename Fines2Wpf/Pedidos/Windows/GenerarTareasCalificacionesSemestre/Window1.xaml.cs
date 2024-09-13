using SqlOrganize;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.ObjectModel;
using System.Windows;

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
            searchGroupBox.DataContext = new Data_toma_r();
            tomaDataGrid.ItemsSource = tomaOC;
        }

        private void GenerarPedidos_Click(object sender, RoutedEventArgs e)
        {
            Data_toma_r search = (Data_toma_r)searchGroupBox.DataContext;
            tomaOC.Clear();
            var data = tomaDAO.TomasAprobadasSemestreQuery(search.calendario__anio!, search.calendario__semestre!).Cache().Dicts();
            foreach (var item in data)
            {
                //item.Obj<Data_disposicion_r>();
                Data_toma_r obj = item.Obj<Data_toma_r>();
                obj.curso__Label = obj.comision__pfid + " "+ obj.sede__numero + obj.comision__division + "/" + obj.planificacion__anio + obj.planificacion__semestre + " " + obj.sede__nombre;
                tomaOC.Add(obj);
            }

        }

        private void GuardarPedidos_Click(object sender, RoutedEventArgs e)
        {
            Data_toma_r search = (Data_toma_r)searchGroupBox.DataContext;

            foreach (var t in tomaOC)
            {
                string cursoLabel = t.comision__pfid + " " + t.sede__numero + t.comision__division + "/" + t.planificacion__anio + t.planificacion__semestre + " " + t.calendario__anio + "-" + t.calendario__semestre + " " + t.asignatura__nombre + " " + t.asignatura__codigo;
                EntityVal ticketsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_tickets").Default().
                   Set("subject", " " + cursoLabel + ": " + t.docente__apellidos!.ToUpper() + ", " + t.docente__nombres).
                   Set("status", 1).
                   Set("cust_24", t.docente__numero_documento).
                   Set("cust_27", t.docente__telefono).
                   Set("cust_28", "Carga de calificación período " + search.calendario__anio + "-" + search.calendario__semestre).
                   Set("assigned_agent", "").Reset();

                EntityVal threadsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_threads").Default().
                    Set("ticket", ticketsValues.Get("id")).
                    Set("body", @"
                        <p>SEDE " + t.sede__nombre + @"</p>
                        <p>ID CURSO " + t.curso__id + @"</p>
                    ").Reset();

                if(!ticketsValues.Check() && !threadsValues.Check())
                {
                    throw new Exception("El chequeo de valores es incorrecto");
                }

                EntityPersist persist = ContainerApp.dbPedidos.Persist();
                persist.Insert(ticketsValues)
                    .Insert(threadsValues)
                    .Exec()
                    .RemoveCache();
            }
        }
    }
}
