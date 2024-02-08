using Fines2App.Data;
using Fines2App.Views.Main;
using Microsoft.Win32;
using MimeTypes;
using SqlOrganize;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.IO;
using System.Net;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Media;
using System.Windows.Threading;
using Utils;
using WpfUtils;

namespace Fines2App.Views;

public partial class MainPage : Page, INotifyPropertyChanged
{
    #region oc
    private ObservableCollection<Data_persona> personaOC = new(); //datos consultados de la base de datos
    private ObservableCollection<Data_resolucion> resolucionOC = new(); //datos consultados de la base de datos
    private ObservableCollection<Data_plan> planOC = new(); //datos consultados de la base de datos
    private DispatcherTimer personaTypingTimer; //timer para buscar
    #endregion

    #region dao
    private DAO.Persona personaDAO = new(); //objeto de acceso a datos de persona
    private DAO.Comision comisionDAO = new(); //objeto de acceso a datos de comision
    private DAO.Curso cursoDAO = new(); //objeto de acceso a datos de curso
    private DAO.Calificacion calificacionDAO = new(); //objeto de acceso a datos de calificacion
    #endregion

    #region asignacionGroupBox
    private ObservableCollection<Asignacion> asignacionOC = new(); //datos a visualizar
    private DispatcherTimer typingTimerComision; //timer para busqueda de comision en asignacion
    private Asignacion asignacion; //asignacion que esta siendo editada
    #endregion

    #region calificacionGroupBox
    private ObservableCollection<Calificacion> calificacionOC = new(); //datos a visualizar
    private ICollectionView calificacionCV; //CV para filtro
    DispatcherTimer calificacionTypingTimer; //timer para filtro
    Calificacion calificacion; //calificacion que esta siendo administrada
    private ObservableCollection<Data_disposicion_r> disposicionOC = new(); //datos a visualizar del comboBox
    private DispatcherTimer cursoTypingTimer; //timer para busqueda de comision en asignacion
    #endregion

    #region detallePersonaGroupBox
    private ObservableCollection<DetallePersona> detallePersonaOC = new();
    #endregion

    public MainPage(object? personaId = null)
    {
        InitializeComponent();

        DataContext = this;

        #region personaComboBox
        personaComboBox.ItemsSource = personaOC;
        personaComboBox.DisplayMemberPath = "Label";
        personaComboBox.SelectedValuePath = "id";
        personaGroupBox.DataContext = new Data_persona(SqlOrganize.DataInitMode.Default);
        #endregion

        #region documentacionInscripcionComboBox
        documentacionInscripcionComboBox.SelectedValuePath = "Key";
        documentacionInscripcionComboBox.DisplayMemberPath = "Value";
        documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>(null, "--Seleccione--"));
        documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Constancia", "Constancia"));
        documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Certificado", "Certificado"));
        documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Analítico Parcial", "Analítico Parcial"));
        documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Analítico Completo", "Analítico Completo"));
        #endregion

        #region resolucionInscripcionComboBox
        resolucionInscripcionComboBox.SelectedValuePath = "id";
        resolucionInscripcionComboBox.DisplayMemberPath = "numero";
        resolucionInscripcionComboBox.ItemsSource = resolucionOC;
        var data = ContainerApp.db.Query("resolucion").Order("$numero ASC").ColOfDictCache();
        resolucionOC.Clear();
        resolucionOC.AddRange(data);
        #endregion

        #region planComboBox
        planComboBox.SelectedValuePath = "id";
        planComboBox.DisplayMemberPath = "Label";
        planComboBox.ItemsSource = planOC;
        var dataPlan = ContainerApp.db.Query("plan").Order("$orientacion ASC").ColOfDictCache();

        planOC.Clear();
        foreach (var item in dataPlan)
        {
            var o = item.Obj<Data_plan>();
            o.Label = ContainerApp.db.Values("plan").Set(item).ToString();
            planOC.Add(o);
        }
        #endregion

        #region anioInscripcionCompletoComboBox
        anioInscripcionCompletoComboBox.SelectedValuePath = "Key";
        anioInscripcionCompletoComboBox.DisplayMemberPath = "Value";
        anioInscripcionCompletoComboBox.Items.Add(new KeyValuePair<bool?, string>(null, "--Seleccione--"));
        anioInscripcionCompletoComboBox.Items.Add(new KeyValuePair<bool?, string>(true, "Completo"));
        anioInscripcionCompletoComboBox.Items.Add(new KeyValuePair<bool?, string>(false, "Incompleto"));
        #endregion

        #region asignacionDataGrid
        asignacionDataGrid.ItemsSource = asignacionOC;
        #endregion

        #region calificacionGroupBox
        var calificacionCVS = new CollectionViewSource() { Source = calificacionOC };
        calificacionCV = calificacionCVS.View;
        calificacionCV.Filter = CalificacionCV_Filter;
        calificacionDataGrid.ItemsSource = calificacionCV;
        calificacionDataGrid.CellEditEnding += CalificacionDataGrid_CellEditEnding;
        #endregion

        #region detallePersonaGroupBox
        detallePersonaDataGrid.ItemsSource = detallePersonaOC;
        #endregion


        SetPersonaGroupBox();
        SetAlumnoGroupBox();

        if (!personaId.IsNullOrEmptyOrDbNull())
        {
            personaOC.Clear();
            //var o = personaDAO.ConsultarPorValorInicial(valorInicial).DictCache().Obj<Data_persona>();
            //o.Label = o.Nombres + " " + o.Apellidos;
            //personaOC.Add(o);
        }
    }


    private void SetAlumnoGroupBox(Alumno? alumno = null)
    {
        if (alumno.IsNullOrEmpty())
        {
            alumno = new Alumno(SqlOrganize.DataInitMode.Default);
            var per = (Data_persona)personaGroupBox.DataContext;
            alumno.persona = per.id;
        }
        var value = (Values.Alumno)ContainerApp.db.Values("alumno").SetObj(alumno!);
        alumno!.color_anio_ingreso = alumno.anio_ingreso.IsNullOrEmptyOrDbNull() ? ContainerApp.config.colorRed : ContainerApp.config.colorGreen;
        alumno!.color_semestre_ingreso = alumno.semestre_ingreso.IsNullOrEmptyOrDbNull() ? ContainerApp.config.colorRed : ContainerApp.config.colorGreen;
        alumno!.color_plan = alumno.plan.IsNullOrEmptyOrDbNull() ? ContainerApp.config.colorRed : ContainerApp.config.colorGreen;
        alumno!.color_estado_inscripcion = value.ColorEstadoInscripcion(alumno.estado_inscripcion);
        alumno!.color_confirmado_direccion = alumno.confirmado_direccion ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
        alumno!.color_tiene_certificado = alumno.tiene_certificado ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
        alumno!.color_tiene_constancia = alumno.tiene_constancia ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
        alumno!.color_tiene_partida = alumno.tiene_partida ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
        alumno!.color_tiene_dni = alumno.tiene_dni ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
        alumno!.color_previas_completas = alumno.previas_completas ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
        alumno!.Validate = true;


        alumnoGroupBox.DataContext = alumno;

    }

    private void LoadAsignaciones(Alumno a)
    {
        asignacionOC.Clear();
        if (a.IsNullOrEmptyOrDbNull() || a.id.IsNullOrEmptyOrDbNull())
            return;
        var data = ContainerApp.db.Query("alumno_comision").
            Where("$alumno = @0").
            Parameters(a.id!).ColOfDictCache();

        foreach (var item in data)
        {
            var asignacion = item.Obj<Asignacion>();


            var val = ContainerApp.db.Values("comision", "comision").Set(item);
            var comision = val.values.Obj<Data_comision_r>();
            comision.Label = val.ToString();

            asignacion.comision__Label = comision.Label;
            asignacion.Comisiones.Add(comision);

            asignacionOC.Add(asignacion);
        }
    }

    private void LoadCalificaciones(Data_alumno a)
    {
        calificacionOC.Clear();

        if (a.IsNullOrEmptyOrDbNull() || a.id.IsNullOrEmptyOrDbNull())
            return;
        if (a.plan.IsNullOrEmpty())
            return;

        var data = calificacionDAO.CalificacionesDeAlumnoPlanArchivoQuery(a.id!, a.plan!, false).ColOfDictCache();

        foreach (var item in data)
        {
            var calificacion = item.Obj<Calificacion>();

            var val = ContainerApp.db.Values("disposicion", "disposicion").Set(item);
            var disposicion = val.values.Obj<Data_disposicion_r>();
            disposicion.Label = val.ToString();
            calificacion.Disposiciones.Add(disposicion);

            var valc = (Values.Curso)ContainerApp.db.Values("curso", "curso").Set(item);
            var curso = val.values.Obj<Data_curso_r>();
            curso.Label = valc.ToStringDocente();
            calificacion.curso__Label = curso.Label;

            calificacion.Validate = true;
            calificacionOC.Add(calificacion);
        }
    }

    private void LoadDisposiciones(Data_alumno a)
    {
        disposicionOC.Clear();

        if (a.IsNullOrEmptyOrDbNull() || a.id.IsNullOrEmptyOrDbNull() || a.plan.IsNullOrEmptyOrDbNull())
            return;

        var data = ContainerApp.db.Query("disposicion").
            Where("$planificacion-plan = @0").
            Parameters(a.plan!).ColOfDictCache();

        foreach (var item in data)
        {
            var disposicion = item.Obj<Data_disposicion_r>();
            disposicion.Label = ContainerApp.db.Values("disposicion").Set(item).ToString();
            disposicionOC.Add(disposicion);
        }
    }

    private void LoadDetalles(Data_persona p)
    {
        detallePersonaOC.Clear();

        var data = ContainerApp.db.Query("detalle_persona").
            Where("$persona = @0").
            Parameters(p.id!).ColOfDictCache();

        foreach (var item in data)
        {
            var detalle = item.Obj<DetallePersona>();
            detalle.arch = detalle.archivo;
            detallePersonaOC.Add(detalle);
        }
    }


    private void GuardarPersonaButton_Click(object sender, RoutedEventArgs e)
    {
        var persona = (Data_persona)personaGroupBox.DataContext;
        if (persona.Error.IsNullOrEmpty())
        {
            var per = (Data_persona)personaGroupBox.DataContext;
            EntityPersist p = ContainerApp.db.Persist();
            try
            {
                p.PersistObj("persona", per).Exec().RemoveCache();
                var alu = (Data_alumno)alumnoGroupBox.DataContext;
                MessageBox.Show("Registro de persona realizado");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }
        else
        {
            MessageBox.Show("Verificar formulario: " + persona.Error);
        }
        return;

    }

    private void GuardarAlumnoButton_Click(object sender, RoutedEventArgs e)
    {
        var alu = (Alumno)alumnoGroupBox.DataContext;

        EntityPersist p = ContainerApp.db.Persist();
        try
        {
            p.PersistObj("alumno", alu).Exec().RemoveCache();
            SetAlumnoGroupBox(alu);
            MessageBox.Show("Registro de alumno realizado");
        }
        catch (Exception ex)
        {
            MessageBox.Show(ex.Message);
        }
    }


    #region personaComboBox
    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - GotFocus - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void PersonaComboBox_GotFocus(object sender, RoutedEventArgs e)
    {
        (sender as ComboBox).IsDropDownOpen = true;
    }

    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - TextChanged - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void PersonaComboBox_TextChanged(object sender, TextChangedEventArgs e)
    {
        ComboBox cb = (sender as ComboBox);
        DispatcherTimer timer = personaTypingTimer;

        if (cb.Text.IsNullOrEmpty())
            cb.IsDropDownOpen = true;
        if (cb.SelectedIndex > -1)
        {
            if (cb.Text.Equals(((Data_persona)cb.SelectedItem).Label))
                return;
            cb.Text = "";
        }

        if (timer == null)
        {
            timer = new DispatcherTimer();
            timer.Interval = TimeSpan.FromMilliseconds(300);
            timer.Tick += new EventHandler(PersonaHandleTypingTimerTimeout);
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
    private void PersonaHandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer; // WPF
        if (timer == null)
            return;

        _PersonaComboBox_TextChanged();

        // The timer must be stopped! We want to act only once per keystroke.
        timer.Stop();
    }

    /// <summary>
    /// Autocomplete 2 - _TextChanged
    /// </summary>
    /// <remarks>Autocomplete 2 - _TextChanged - v 2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790603853</remarks>
    private void _PersonaComboBox_TextChanged()
    {

        personaOC.Clear();

        if (string.IsNullOrEmpty(personaComboBox.Text) || personaComboBox.Text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
        {
            return;
        }

        IEnumerable<Dictionary<string, object>> list = personaDAO.SearchLikeQuery(personaComboBox.Text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

        foreach (var item in list)
        {
            var o = new Data_persona();
            o.SetData(item);
            o.Label = o.nombres + " " + o.apellidos + " " + o.numero_documento;
            personaOC.Add(o);
        }
    }

    private void PersonaComboBox_SelectionChanged(object sender, RoutedEventArgs e)
    {
        if (this.personaComboBox.SelectedIndex > -1)
        {
            var pgb = (Data_persona)personaGroupBox.DataContext;
            var pcb = (Data_persona)personaComboBox.SelectedItem;
            if (pgb != null && pgb.id!.ToString().Equals(pcb.id))
                return;

            var a = ContainerApp.db.Query("alumno").Where("$persona = @0").Parameters(pcb.id!).Obj<Alumno>();

            SetPersonaGroupBox(pcb);
            SetAlumnoGroupBox(a);
            LoadAsignaciones(a);
            LoadCalificaciones(a);
            LoadDisposiciones(a);
            LoadDetalles(pcb);
        }
        else
        {
            SetPersonaGroupBox();
            SetAlumnoGroupBox();
            asignacionOC.Clear();
            calificacionOC.Clear();
            disposicionOC.Clear();
            detallePersonaOC.Clear();

            this.personaComboBox.IsDropDownOpen = true;
        }
    }
    #endregion




    private void SetPersonaGroupBox(Data_persona? persona = null)
    {
        if (persona.IsNullOrEmpty())
        {
            persona = new Data_persona(SqlOrganize.DataInitMode.Default);
        }

        personaGroupBox.DataContext = persona;
        persona.Validate = true;

    }


    #region asignacionGroupBox
    private void ComisionComboBox_TextChanged(object sender, TextChangedEventArgs e)
    {
        var cb = (ComboBox)sender;

        if (cb!.Text.IsNullOrEmpty())
            cb.IsDropDownOpen = true;

        if (cb.SelectedIndex > -1)
        {
            if (cb.Text.Equals(((Data_comision_r)cb.SelectedItem).Label))
                return;

            cb.Text = ""; //si hay seleccionado y cambio al texto, se blanquea el texto. Si no se incluye esta opción el texto se blanquea igual por defecto, pero tarda mas tiempo y es mas engorroso
        }

        asignacion = (Asignacion)cb.DataContext; //se carga la asignacion que esta siendo editada
        asignacion.SearchComision = cb.Text;

        if (typingTimerComision == null)
        {
            typingTimerComision = new DispatcherTimer();
            typingTimerComision.Interval = TimeSpan.FromMilliseconds(300);
            typingTimerComision.Tick += new EventHandler(ComisionComboBox_HandleTypingTimerTimeout);
        }

        typingTimerComision.Stop(); // Resets the timer
        typingTimerComision.Tag = asignacion.SearchComision; // This should be done with EventArgs
        typingTimerComision.Start();
    }

    private void ComisionComboBox_HandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer;
        if (timer == null)
            return;

        _ComisionComboBox_TextChanged();

        timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
    }

    private void _ComisionComboBox_TextChanged()
    {

        asignacion.Comisiones.Clear();

        if (string.IsNullOrEmpty(asignacion.SearchComision) || asignacion.SearchComision.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
            return;

        IEnumerable<Dictionary<string, object?>> list = comisionDAO.BusquedaAproximadaQuery(asignacion.SearchComision).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto
        foreach (var item in list)
        {
            var val = ContainerApp.db.Values("comision").Set(item);
            var obj = val.values.Obj<Data_comision_r>();
            obj.Label = val.ToString();
            asignacion.Comisiones.Add(obj);
        }
    }

    private void ComisionComboBox_GotFocus(object sender, RoutedEventArgs e)
    {
        (sender as ComboBox)!.IsDropDownOpen = true;
    }

    private void ComisionComboBox_SelectionChanged(object sender, RoutedEventArgs e)
    {
        var cb = (ComboBox)sender;
        var asignacion = (Asignacion)cb.DataContext; //se carga la asignacion que esta siendo editada
        if (cb.SelectedIndex > -1)
        {
            if (!cb.SelectedValue.ToString()!.Equals(asignacion.comision))
                asignacion.comision = cb.SelectedValue.ToString();
            asignacion.comision__Label = (cb.SelectedItem as Data_comision_r)!.Label;
        }
        else
        {
            asignacion.comision__Label = "";
            cb.IsDropDownOpen = true;
        }

    }

    private void AgregarAsignacion_Click(object sender, RoutedEventArgs e)
    {
        var a = new Asignacion();
        var alumno = (Data_alumno)alumnoGroupBox.DataContext;
        a.alumno = alumno.id;
        asignacionOC.Add(a);
    }

    private void GuardarAsignacion_Click(object sender, RoutedEventArgs e)
    {
        var button = (e.OriginalSource as Button);
        var asignacion = (Data_alumno_comision)button!.DataContext;
        var p = ContainerApp.db.Persist();
        try
        {
            p.PersistObj("alumno_comision", asignacion).Exec().RemoveCache();
            MessageBox.Show("Registro realizado");
        }
        catch (Exception ex)
        {
            MessageBox.Show(ex.Message);
        }
    }

    private void EliminarAsignacion_Click(object sender, RoutedEventArgs e)
    {
        var button = (e.OriginalSource as Button);
        var a = (Asignacion)button!.DataContext;
        try
        {
            if (!a.id.IsNullOrEmpty())
                ContainerApp.db.Persist().DeleteIds("alumno_comision", a.id!).Exec().RemoveCache();
            asignacionOC.Remove(a);

        }
        catch (Exception ex)
        {
            MessageBox.Show(ex.Message);
        }
    }

    #endregion asignacionGroupBox


    #region calificacionGroupBox
    private bool CalificacionCV_Filter(object obj)
    {
        var o = obj as Data_calificacion_r;
        return calificacionFilterTextBox.Text.IsNullOrEmpty()
            || (!o.asignatura_dis__nombre.IsNullOrEmptyOrDbNull() && o.asignatura_dis__nombre.ToString().ToLower().Contains(calificacionFilterTextBox.Text.ToLower()));

    }

    private void CalificacionFilterTextBox_TextChanged(object sender, TextChangedEventArgs e)
    {
        if (calificacionTypingTimer == null)
        {
            calificacionTypingTimer = new DispatcherTimer();
            calificacionTypingTimer.Interval = TimeSpan.FromMilliseconds(300);
            calificacionTypingTimer.Tick += new EventHandler(CalificacionFilterTextBox_HandleTypingTimerTimeout);

        }

        calificacionTypingTimer.Stop(); // Resets the timer
        calificacionTypingTimer.Tag = (sender as TextBox).Text; // This should be done with EventArgs
        calificacionTypingTimer.Start();
    }

    private void CalificacionFilterTextBox_HandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer;
        if (timer == null)
            return;

        if (calificacionCV != null)
            calificacionCV.Refresh();

        timer.Stop();// The timer must be stopped! We want to act only once per keystroke.
    }
    #endregion



    #region CalificacionGroupBox
    private void DisposicionComboBox_GotFocus(object sender, RoutedEventArgs e)
    {
        var cb = (ComboBox)sender;
        var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada

        calificacion.Disposiciones.Clear();
        calificacion.Disposiciones.AddRange(disposicionOC);
    }

    private void DisposicionComboBox_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        var cb = (ComboBox)sender;
        var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
        if (cb.SelectedIndex > -1)
        {
            if (!cb.SelectedValue.ToString()!.Equals(calificacion.disposicion))
            {
                ContainerApp.db.Persist().UpdateValueIds("calificacion", "disposicion", cb.SelectedValue, calificacion.id!).Exec().RemoveCache();
                calificacion.disposicion = (string)cb.SelectedValue;
            }

        }
    }

    private void DisposicionComboBox_LostFocus(object sender, RoutedEventArgs e)
    {
        var cb = (ComboBox)sender;
        var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
        cb.SelectedValue = calificacion.disposicion;
    }

    private void CursoComboBox_TextChanged(object sender, TextChangedEventArgs e)
    {
        var cb = (ComboBox)sender;

        if (cb!.Text.IsNullOrEmpty())
            cb.IsDropDownOpen = true;

        if (cb.SelectedIndex > -1)
        {
            if (cb.Text.Equals(((Data_curso_r)cb.SelectedItem).Label))
                return;

            cb.Text = ""; //si hay seleccionado y cambio al texto, se blanquea el texto. Si no se incluye esta opción el texto se blanquea igual por defecto, pero tarda mas tiempo y es mas engorroso
        }

        calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
        calificacion.SearchCurso = cb.Text;

        if (cursoTypingTimer == null)
        {
            cursoTypingTimer = new DispatcherTimer();
            cursoTypingTimer.Interval = TimeSpan.FromMilliseconds(300);
            cursoTypingTimer.Tick += new EventHandler(CursoComboBox_HandleTypingTimerTimeout!);
        }

        cursoTypingTimer.Stop(); // Resets the timer
        cursoTypingTimer.Tag = calificacion.SearchCurso; // This should be done with EventArgs
        cursoTypingTimer.Start();
    }

    private void CursoComboBox_HandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer;
        if (timer == null)
            return;

        _CursoComboBox_TextChanged();

        timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
    }

    private void _CursoComboBox_TextChanged()
    {
        calificacion.Cursos.Clear();

        if (string.IsNullOrEmpty(calificacion.SearchCurso) || calificacion.SearchCurso.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
            return;

        IEnumerable<Dictionary<string, object?>> list = cursoDAO.BusquedaAproximadaQuery(calificacion.SearchCurso).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto
        foreach (var item in list)
        {
            var val = (Values.Curso)ContainerApp.db.Values("curso").Set(item);
            var obj = val.values.Obj<Data_curso_r>();
            obj.Label = val.ToStringDocente();
            calificacion.Cursos.Add(obj);
        }
    }
    private void CursoComboBox_GotFocus(object sender, RoutedEventArgs e)
    {
        (sender as ComboBox)!.IsDropDownOpen = true;
    }

    private void CursoComboBox_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        var cb = (ComboBox)sender;
        var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
        if (cb.SelectedIndex > -1)
        {
            ContainerApp.db.Persist().UpdateValueIds("calificacion", "curso", cb.SelectedValue, calificacion.id!).Exec().RemoveCache();
            calificacion.curso__Label = (cb.SelectedItem as Data_curso_r)!.Label;
        }

        else
        {
            calificacion.curso__Label = "";
            cb.IsDropDownOpen = true;
        }
    }


    private void CalificacionDataGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
    {
        string key = "";
        object? value = null;

        var column = e.Column as DataGridBoundColumn;
        if (column != null)
        {
            key = ((Binding)column.Binding).Path.Path; //column's binding
            value = (e.EditingElement as TextBox)!.Text;
        }

        var columnT = e.Column as DataGridTemplateColumn;
        if (columnT != null)
        {
            var datePicker = VisualTreeHelper.GetChild(e.EditingElement, 0) as DatePicker;
            if (datePicker != null)
            {
                key = datePicker.Name;
                value = datePicker.SelectedDate;
            }
        }

        if (key.IsNullOrEmpty())
            return;

        e.DataGridCellEditEndingEventArgs_CellEditEnding<Data_alumno_comision_r>("calificacion", key, value);

    }
    #endregion

    private void DescargarArchivo_Click(object sender, RoutedEventArgs e)
    {


        var dp = ((Hyperlink)e.OriginalSource).DataContext as DetallePersona;
        WebClient client = new WebClient();
        client.Credentials = new NetworkCredential(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword);

        SaveFileDialog saveFileDialog = new SaveFileDialog();
        saveFileDialog.InitialDirectory = ContainerApp.config.download;
        saveFileDialog.RestoreDirectory = false;
        saveFileDialog.Title = "Descargar archivo de legajo";
        saveFileDialog.DefaultExt = Path.GetExtension(dp.archivo__name);
        saveFileDialog.FileName = dp.archivo__name;
        if (saveFileDialog.ShowDialog() == true)
            client.DownloadFile(ContainerApp.config.upload + dp.archivo__content, saveFileDialog.FileName);
        
    }

    private void BrowseButton_Click(object sender, RoutedEventArgs e)
    {
        Microsoft.Win32.OpenFileDialog openFileDlg = new Microsoft.Win32.OpenFileDialog();

        var button = (e.OriginalSource as Button);
        var dp = (DetallePersona)button!.DataContext;

        bool? result = openFileDlg.ShowDialog();  // Launch OpenFileDialog by calling ShowDialog method

        if (result == true)
        {
            string year = DateTime.Now.Year.ToString();
            string month = DateTime.Now.Month.ToString();

            WebRequestUtils.CreateDirectoryIfNotExists(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword, ContainerApp.config.upload, year);
            WebRequestUtils.CreateDirectoryIfNotExists(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword, ContainerApp.config.upload + year + "/", month);

            string dir = year + "/" + month;
            FileInfo fileInfo = new(openFileDlg.FileName);

            Data_file archivo = new(DataInitMode.Default);
            var fileName = archivo.id + fileInfo.Extension;

            try
            {
                WebRequestUtils.UploadFile(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword, ContainerApp.config.upload + dir + "/" + fileName, openFileDlg.FileName);
                archivo.name = openFileDlg.SafeFileName;
                archivo.content = dir + "/" + fileName;
                archivo.type = MimeTypeMap.GetMimeType(fileInfo.Extension);
                archivo.size = Convert.ToUInt32(fileInfo.Length);
                dp.archivo = archivo.id;
                dp.archivo__name = archivo.name;
                EntityPersist p = ContainerApp.db.Persist().InsertObj("file", archivo);
                p.UpdateValueIds("detalle_persona", "archivo", archivo.id, dp.id!);
                p.Transaction().RemoveCache();
            }
            catch (WebException ex)
            {
                MessageBox.Show(ex.Message);
            }
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

    private void BuscarButton_Click(object sender, System.Windows.RoutedEventArgs e)
    {

    }




}

