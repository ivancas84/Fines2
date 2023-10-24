using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using Utils;
using Fines2Wpf.Model;
using System.Windows.Controls;
using SqlOrganize;
using WpfUtils;
using System.Windows.Data;
using Google.Protobuf.WellKnownTypes;

namespace Fines2Wpf.Windows.AlumnoComision.ListaAlumnosSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private DAO.Sede sedeDAO = new();
        private SqlOrganize.DAO dao = new(ContainerApp.db);
        public ObservableCollection<Data_alumno_comision_r> asignacionData = new();
        public Data_alumno_comision_r search = new(DataInitMode.Null);
        
        public Window1()
        {
            InitializeComponent();

            estadoCombo.SelectedValuePath = "Key";
            estadoCombo.DisplayMemberPath = "Value";
            estadoCombo.Items.Add(new KeyValuePair<string?, string>(null, "(Todos)"));
            estadoCombo.Items.Add(new KeyValuePair<string, string>("Activo", "Activo"));
            estadoCombo.Items.Add(new KeyValuePair<string, string>("No activo", "No activo"));
            estadoCombo.Items.Add(new KeyValuePair<string, string>("Mesa", "Mesa"));

            search.calendario__anio = 2023;
            search.calendario__semestre = 2;
            search.estado = "Activo";
            DataContext = search;

            asignacionGrid.CellEditEnding += AsignacionGrid_CellEditEnding;

            asignacionGrid.ItemsSource = asignacionData;
            Loaded += Window1_Loaded;
        }



        public void LoadAsignaciones()
        {
            var data = dao.Search("alumno_comision", search);
            asignacionData.Clear();
            foreach (var d in data)
            {
                var v = (Values.AlumnoComision)ContainerApp.db.Values("alumno_comision").Values(d);
                var o = d.Obj<Data_alumno_comision_r>();
                o.comision__Label = v.ValuesTree("comision")?.ToString() ?? "";
                asignacionData.Add(o);
            }
        }

        private void Window1_Loaded(object sender, RoutedEventArgs e)
        {
            LoadAsignaciones();
        }

        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            LoadAsignaciones();
        }

        private void AsignacionGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            var reload = e.DataGridCellEditEndingEventArgs_CellEditEnding<Data_alumno_comision_r>("alumno_comision");
            if (reload)
                LoadAsignaciones(); //debe recargarse para visualizar los cambios realizados en otras iteraciones
                                    //Dada una relacion a : b, si se modifica b correspondiente a a.b, se deberan actualizar todas las filas
        }

        private void EntityGrid_CellCheckBoxClick(object sender, RoutedEventArgs e)
        {
            var cell = sender as DataGridCell;
            bool reload = cell!.DataGridCell_CheckBoxClick<Data_alumno_comision_r>("alumno_comision");
            if (reload)
              LoadAsignaciones();//debe recargarse para visualizar los cambios realizados en otras iteraciones
                                 //Dada una relacion a : b, si se modifica b correspondiente a a.b, se deberan actualizar todas las filas
        }
    }

    public class SedeViewModel
    {
        public ObservableCollection<Data_sede> Sedes()
        {
            ObservableCollection<Data_sede> r = new ObservableCollection<Data_sede>();
            var data = ContainerApp.db.Query("sede").Size(0).Parameters().ColOfDictCache();
            r.Clear();
            r.AddRange(data);
            return r;
        }
    }
}
