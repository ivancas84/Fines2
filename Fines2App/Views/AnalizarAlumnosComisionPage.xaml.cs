#nullable enable
using CommunityToolkit.WinUI.Notifications;
using Fines2App.Data;
using Fines2App.Views.AnalizarAlumnosComision;
using Newtonsoft.Json;
using SqlOrganize;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;
using Utils;
using Windows.Networking.Vpn;

namespace Fines2App.Views;

public partial class AnalizarAlumnosComisionPage : Page, INotifyPropertyChanged
{


    private DAO.AlumnoComision asignacionDAO = new();
    private DAO.Comision comisionDAO = new();
    private ObservableCollection<Data_comision_r> comisionOC = new(); //datos consultados de la base de datos
    private DispatcherTimer comisionTypingTimer; //timer para buscar
    private ObservableCollection<Data_persona> resultadoOC = new();
    private ObservableCollection<AlumnoParaCargar> alumnosACargarOC = new();



    public AnalizarAlumnosComisionPage()
    {
        InitializeComponent();
        DataContext = this;


        comisionComboBox.ItemsSource = comisionOC;
        comisionComboBox.DisplayMemberPath = "Label";
        comisionComboBox.SelectedValuePath = "id";


        resultadoDataGrid.ItemsSource = resultadoOC;
    }


    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - GotFocus - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void ComisionComboBox_GotFocus(object sender, RoutedEventArgs e)
    {
        (sender as ComboBox).IsDropDownOpen = true;
    }


    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - TextChanged - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void ComisionComboBox_TextChanged(object sender, TextChangedEventArgs e)
    {
        ComboBox cb = (sender as ComboBox);
        DispatcherTimer timer = comisionTypingTimer;

        if (cb.Text.IsNullOrEmpty())
            cb.IsDropDownOpen = true;
        if (cb.SelectedIndex > -1)
        {
            if (cb.Text.Equals(((Data_comision_r)cb.SelectedItem).Label))
                return;
            cb.Text = "";
        }

        if (timer == null)
        {
            timer = new DispatcherTimer();
            timer.Interval = TimeSpan.FromMilliseconds(300);
            timer.Tick += new EventHandler(ComisionHandleTypingTimerTimeout);
        }

        timer.Stop(); // Resets the timer
        timer.Tag = cb.Text; // This should be done with EventArgs
        timer.Start();
    }

    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - HandleTypingTimerTimeout - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790603741</remarks>
    private void ComisionHandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer; // WPF
        if (timer == null)
            return;

        _ComisionComboBox_TextChanged();

        // The timer must be stopped! We want to act only once per keystroke.
        timer.Stop();
    }


    /// <summary>
    /// Autocomplete 2 - _TextChanged
    /// </summary>
    /// <remarks>Autocomplete 2 - _TextChanged - v 2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790603853</remarks>
    private void _ComisionComboBox_TextChanged()
    {

        comisionOC.Clear();
        string text = comisionComboBox.Text;

        if (string.IsNullOrEmpty(text) || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
        {
            return;
        }

        IEnumerable<Dictionary<string, object>> list = comisionDAO.BusquedaAproximadaQuery(text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

        foreach (var item in list)
        {
            var o = new Data_comision_r();
            o.SetData(item);
            o.Label = o.sede__numero + o.division + "/" + o.planificacion__anio + o.planificacion__semestre + " " + o.calendario__anio + "-" + o.calendario__semestre;
            comisionOC.Add(o);
        }
    }

    /// <summary>
    /// </summary>
    /// <remarks>Autocomplete 2 - SelectionChanged - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790604099</remarks>
    private void ComisionComboBox_SelectionChanged(object sender, RoutedEventArgs e)
    {
        var cb = (ComboBox)sender;

        if (cb.SelectedIndex < 0)
            cb.IsDropDownOpen = true;
    }




    private void GuardarButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {

            IEnumerable<Data_persona> alumnos = JsonConvert.DeserializeObject<IEnumerable<Data_persona>>(alumnosTextBox.Text)!;
            object idComision = comisionComboBox.SelectedValue;
            IDictionary<string, Dictionary<string, object?>> asignaciones = asignacionDAO.
                AsignacionesPorComisiones(new List<object>() { idComision }).
                DictOfDictByKey<string>("persona-numero_documento");

            foreach (var alumno in alumnos)
            {
                if (asignaciones.ContainsKey(alumno.numero_documento!))
                {
                    EntityValues p1 = ContainerApp.db.Values("persona").SetObj(alumno);
                    //EntityValues p2 = ContainerApp.db.Values("persona", "persona").Set(ac);
                    IDictionary<string, object?> compare = p1.CompareFields(asignaciones[alumno.numero_documento!], new List<string>() { "nombres", "apellidos", "numero_documento" }, false, false);
                    if (!compare.IsNullOrEmpty())
                        alumno.Label = "Comparacion erronea revisar alumno " + alumno.numero_documento + ".";
                    else
                        alumno.Label = "OK.";
                } else
                {
                    alumno.Label = "No se encuentra registrado en la base de datos de planfines2.com.ar.";
                }

                resultadoOC.Add(alumno);
            }

            IDictionary<string, Data_persona> alumnosDict = alumnos.DictOfObjByProp<string, Data_persona>("numero_documento");

            foreach (var (dni, asignacion) in asignaciones)
            {
                var asign = asignacion.Obj<Data_alumno_comision_r>();
                if (!alumnosDict.ContainsKey(asign.persona__numero_documento!))
                {
                    Data_persona alumno = new();
                    alumno.nombres = asign.persona__nombres;
                    alumno.apellidos = asign.persona__apellidos;
                    alumno.numero_documento = asign.persona__numero_documento;
                    alumno.Label = "No se encuentra registrado en la base de datos programafines.ar.";
                    resultadoOC.Add(alumno);

                    if(asign.estado.Equals("No activo"))
                        alumno.Label += " No se encuentra activo.";

                    AlumnoParaCargar apc = new();
                    apc.nombre = asign.persona__nombres;
                    apc.apellido = asign.persona__apellidos;
                    apc.dni_cargar = asign.persona__numero_documento;

                    if (asign.persona__genero != null && asign.persona__genero.Equals("Femenino"))
                        apc.sexo = "2";
                    else if (asign.persona__genero != null && asign.persona__genero.Equals("Otro"))
                        apc.sexo = "3";
                    else
                        apc.sexo = "1";

                    if (!asign.persona__fecha_nacimiento.IsNullOrEmptyOrDbNull())
                    {
                        apc.dia_nac = ((DateTime)asign.persona__fecha_nacimiento!).Day.ToString();
                        apc.mes_nac = ((DateTime)asign.persona__fecha_nacimiento!).Month.ToString();
                        apc.ano_nac = ((DateTime)asign.persona__fecha_nacimiento!).Year.ToString();
                    }

                    alumnosACargarOC.Add(apc);
                }
            }

            cargarTextBox.Text = JsonConvert.SerializeObject(alumnosACargarOC)!; ;
        }
        catch (Exception ex)
        {
            new ToastContentBuilder()
                .AddText("Analisis de alumnos")
                .AddText(ex.Message)
                .Show();
        }
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
