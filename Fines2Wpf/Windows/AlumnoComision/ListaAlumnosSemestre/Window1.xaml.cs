using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using Utils;
using Fines2Wpf.Model;
using System.Windows.Controls;
using SqlOrganize;
using WpfUtils;
using System.ComponentModel;
using System.Windows.Data;

namespace Fines2Wpf.Windows.AlumnoComision.ListaAlumnosSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private DAO.Calificacion calificacionDAO = new();
        private SqlOrganize.DAO dao = new(ContainerApp.db);
        private ObservableCollection<Asignacion> asignacionData = new();
        private Data_alumno_comision_r search = new(DataInitMode.Null);

        private ICollectionView asignacionDataCV;


        public Window1()
        {
            InitializeComponent();

            var asignacionDataCVS = new CollectionViewSource() { Source = asignacionData };
            asignacionDataCV = asignacionDataCVS.View;
            asignacionDataCV.Filter = AsignacionDataCV_Filter;
            asignacionGrid.ItemsSource = asignacionDataCV;



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

            Loaded += Window1_Loaded;
        }

        private bool AsignacionDataCV_Filter(object obj)
        {
            var o = obj as Asignacion;
            return filterTextBox.Text.IsNullOrEmpty()
                || (!o.persona__nombres.IsNullOrEmptyOrDbNull() && o.persona__nombres.ToString().ToLower().Contains(filterTextBox.Text.ToLower()))
                || (!o.persona__apellidos.IsNullOrEmptyOrDbNull() && o.persona__apellidos.ToString().ToLower().Contains(filterTextBox.Text.ToLower()))
                || (!o.comision__Label.IsNullOrEmptyOrDbNull() && o.comision__Label.ToString().ToLower().Contains(filterTextBox.Text.ToLower()))
                || (!o.persona__telefono.IsNullOrEmptyOrDbNull() && o.persona__telefono.ToString().ToLower().Contains(filterTextBox.Text.ToLower()))
                || (!o.persona__numero_documento.IsNullOrEmptyOrDbNull() && o.persona__numero_documento.ToString().ToLower().Contains(filterTextBox.Text.ToLower()))
                || (!o.sede__nombre.IsNullOrEmptyOrDbNull() && o.sede__nombre.ToString().ToLower().Contains(filterTextBox.Text.ToLower()));

        }

        private void FilterTextBox_TextChanged(object sender, System.Windows.Controls.TextChangedEventArgs e)
        {
            if (asignacionDataCV != null)
                asignacionDataCV.Refresh();
        }


        public void LoadAsignaciones()
        {
            var data = dao.Search("alumno_comision", search);
            asignacionData.Clear();
            List<object> alumnosYplanes = new();
            ObservableCollection<Asignacion> asignacionDataAux = new();

            foreach (var d in data)
            {
                var v = (Values.AlumnoComision)ContainerApp.db.Values("alumno_comision").Values(d);
                var va = (Values.Alumno)ContainerApp.db.Values("alumno","alumno").Set(d);
                var o = d.Obj<Asignacion>();
                o.tramo_ingreso = va.TramoIngreso();
                o.comision__Label = v.ValuesTree("comision")?.ToString() ?? "";
                o.color_estado_inscripcion = va.ColorEstadoInscripcion(o.alumno__estado_inscripcion);
                alumnosYplanes.Add(o.alumno!.ToString() +o.planificacion__plan!.ToString());
                asignacionDataAux.Add(o);
            }
            var dataCalificacionesDict = calificacionDAO.CantidadCalificacionesAprobadasAgrupadasPorPlanificacionSinArchivarPorAlumnosYPlanesQuery(alumnosYplanes).ColOfDict().DictOfDictByKeysValue("cantidad", "alumno", "planificacion_dis-anio", "planificacion_dis-semestre");
                
            foreach (var d in asignacionDataAux)
            {
                var key = d.alumno!.ToString()!;
                if(dataCalificacionesDict.ContainsKey(key + "~" + "1" + "~" + "1"))
                    d.cantidad_aprobadas11 = (long)dataCalificacionesDict[key + "~" + "1" + "~" + "1"];
                if (dataCalificacionesDict.ContainsKey(key + "~" + "1" + "~" + "2"))
                    d.cantidad_aprobadas12 = (long)dataCalificacionesDict[key + "~" + "1" + "~" + "2"];
                if (dataCalificacionesDict.ContainsKey(key + "~" + "2" + "~" + "1"))
                    d.cantidad_aprobadas21 = (long)dataCalificacionesDict[key + "~" + "2" + "~" + "1"];
                if (dataCalificacionesDict.ContainsKey(key + "~" + "2" + "~" + "2"))
                    d.cantidad_aprobadas22 = (long)dataCalificacionesDict[key + "~" + "2" + "~" + "2"];
                if (dataCalificacionesDict.ContainsKey(key + "~" + "3" + "~" + "1"))
                    d.cantidad_aprobadas31 = (long)dataCalificacionesDict[key + "~" + "3" + "~" + "1"];
                if (dataCalificacionesDict.ContainsKey(key + "~" + "3" + "~" + "2"))
                    d.cantidad_aprobadas32 = (long)dataCalificacionesDict[key + "~" + "3" + "~" + "2"];

                var va = (Values.Alumno)ContainerApp.db.Values("alumno", "alumno");

                short ai = 1;
                if (!d.alumno__anio_ingreso.IsNullOrEmptyOrDbNull())
                    ai = short.Parse(d.alumno__anio_ingreso!);
                short pa = short.Parse(d.planificacion__anio!);
                short pe = short.Parse(d.planificacion__semestre!);

                d.color_aprobadas11 = va.ColorCantidadAprobadasSemestreActual(d.cantidad_aprobadas11, 1, 1, pa, pe, ai, d.alumno__semestre_ingreso);
                d.color_aprobadas12 = va.ColorCantidadAprobadasSemestreActual(d.cantidad_aprobadas12, 1, 2, pa, pe, ai, d.alumno__semestre_ingreso);
                d.color_aprobadas21 = va.ColorCantidadAprobadasSemestreActual(d.cantidad_aprobadas21, 2, 1, pa, pe, ai, d.alumno__semestre_ingreso);
                d.color_aprobadas22 = va.ColorCantidadAprobadasSemestreActual(d.cantidad_aprobadas22, 2, 2, pa, pe, ai, d.alumno__semestre_ingreso);
                d.color_aprobadas31 = va.ColorCantidadAprobadasSemestreActual(d.cantidad_aprobadas31, 3, 1, pa, pe, ai, d.alumno__semestre_ingreso);
                d.color_aprobadas32 = va.ColorCantidadAprobadasSemestreActual(d.cantidad_aprobadas32, 3, 2, pa, pe, ai, d.alumno__semestre_ingreso);

                asignacionData.Add(d);
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

    public class EstadoInscripcionData
    {
        public ObservableCollection<string> Estados()
        {
            return new()
            {
                "Correcto",
                "Indeterminado",
                "Caso particular",
                "Titulado",
            };
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
