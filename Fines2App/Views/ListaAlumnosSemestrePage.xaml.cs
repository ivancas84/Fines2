using Fines2App.Data;
using Fines2App.Views.ListaAlumnosSemestre;
using SqlOrganize;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Media;
using System.Windows.Threading;
using Utils;
using WpfUtils;

namespace Fines2App.Views;

public partial class ListaAlumnosSemestrePage : Page, INotifyPropertyChanged
{
    private DAO.Calificacion calificacionDAO = new();
    private SqlOrganize.DAO dao = new(ContainerApp.db);
    private ObservableCollection<Asignacion> asignacionOC = new();
    private Data_alumno_comision_r search = new(DataInitMode.Null);
    private ICollectionView asignacionCV;
    private DataGridUtils dgu = new(ContainerApp.db);

    DispatcherTimer typingTimer;

    public ListaAlumnosSemestrePage()
    {
        InitializeComponent();
        DataContext = this;

        #region asignacionDataGrid
        var asignacionCVS = new CollectionViewSource() { Source = asignacionOC };
        asignacionCV = asignacionCVS.View;
        asignacionCV.Filter = AsignacionCV_Filter;
        asignacionDataGrid.ItemsSource = asignacionCV;
        asignacionDataGrid.CellEditEnding += AsignacionDataGrid_CellEditEnding;

        #endregion

        #region estadoComboBox
        estadoComboBox.SelectedValuePath = "Key";
        estadoComboBox.DisplayMemberPath = "Value";
        estadoComboBox.Items.Add(new KeyValuePair<string?, string>(null, "(Todos)"));
        estadoComboBox.Items.Add(new KeyValuePair<string, string>("Activo", "Activo"));
        estadoComboBox.Items.Add(new KeyValuePair<string, string>("No activo", "No activo"));
        estadoComboBox.Items.Add(new KeyValuePair<string, string>("Mesa", "Mesa"));
        #endregion

        #region search
        search.calendario__anio = 2023;
        search.calendario__semestre = 2;
        search.estado = "Activo";
        DataContext = search;
        #endregion
    }


    private bool AsignacionCV_Filter(object obj)
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
        if (typingTimer == null)
        {
            typingTimer = new DispatcherTimer();
            typingTimer.Interval = TimeSpan.FromMilliseconds(300);
            typingTimer.Tick += new EventHandler(FilterTextBox_HandleTypingTimerTimeout);

        }

        typingTimer.Stop(); // Resets the timer
        typingTimer.Tag = (sender as TextBox).Text; // This should be done with EventArgs
        typingTimer.Start();
    }

    private void FilterTextBox_HandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer; // WPF
        if (timer == null)
        {
            return;
        }

        if (asignacionCV != null)
            asignacionCV.Refresh();

        // The timer must be stopped! We want to act only once per keystroke.
        timer.Stop();
    }

    public void LoadAsignaciones()
    {
        var data = dao.SearchObj("alumno_comision", search);
        asignacionOC.Clear();
        List<object> alumnosYplanes = new();
        ObservableCollection<Asignacion> asignacionOCAux = new();

        foreach (var d in data)
        {
            var va = (Values.Alumno)ContainerApp.db.Values("alumno", "alumno").Set(d);
            var o = d.Obj<Asignacion>();
            o.tramo_ingreso = va.TramoIngreso();
            o.comision__Label = ContainerApp.db.Values("comision", "comision").Set(d).ToString();
            o.color_estado_inscripcion = va.ColorEstadoInscripcion(o.alumno__estado_inscripcion);
            alumnosYplanes.Add(o.alumno!.ToString() + o.planificacion__plan!.ToString());
            asignacionOCAux.Add(o);
        }
        var dataCalificacionesDict = calificacionDAO.CantidadCalificacionesAprobadasAgrupadasPorPlanificacionSinArchivarPorAlumnosYPlanesQuery(alumnosYplanes).ColOfDict().DictOfDictByKeysValue("cantidad", "alumno", "planificacion_dis-anio", "planificacion_dis-semestre");

        foreach (var d in asignacionOCAux)
        {
            var key = d.alumno!.ToString()!;
            if (dataCalificacionesDict.ContainsKey(key + "~" + "1" + "~" + "1"))
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

            asignacionOC.Add(d);
        }


    }


    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        LoadAsignaciones();
    }

    private void AsignacionDataGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
    {
        string key = "";
        object? value = null;

        var columnT = e.Column as DataGridTemplateColumn;
        if (columnT != null)
        {
            var datePicker = VisualTreeHelper.GetChild(e.EditingElement, 0) as DatePicker;

        }

        var columnCo = e.Column as DataGridComboBoxColumn;
        if (columnCo != null)
        {
            key = ((Binding)columnCo.SelectedValueBinding).Path.Path; //column's binding
            value = (e.EditingElement as ComboBox)!.SelectedValue;
        }

        var column = e.Column as DataGridBoundColumn;
        if (column != null)
        {
            key = ((Binding)column.Binding).Path.Path; //column's binding
            value = (e.EditingElement as TextBox)!.Text;
        }


        var reload = dgu.DataGridCellEditEndingEventArgs_CellEditEnding<Data_alumno_comision_r>(e, "alumno_comision", key, value);
        if (reload)
            LoadAsignaciones(); //debe recargarse para visualizar los cambios realizados en otras iteraciones
                                //Dada una relacion a : b, si se modifica b correspondiente a a.b, se deberan actualizar todas las filas
    }

    private void EntityGrid_CellCheckBoxClick(object sender, RoutedEventArgs e)
    {
        var cell = sender as DataGridCell;
        bool reload = dgu.DataGridCell_CheckBoxClick<Data_alumno_comision_r>(cell!, "alumno_comision");
        if (reload)
            LoadAsignaciones();//debe recargarse para visualizar los cambios realizados en otras iteraciones
                               //Dada una relacion a : b, si se modifica b correspondiente a a.b, se deberan actualizar todas las filas
    }


public event PropertyChangedEventHandler PropertyChanged;

    private void Set<T>(ref T storage, T value, [CallerMemberName] string propertyName = null)
    {
        if (Equals(storage, value))
        {
            return;
        }

        storage = value;
        OnPropertyChanged(propertyName);
    }

    private void OnPropertyChanged(string propertyName) => PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
}
