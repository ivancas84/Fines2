using CommunityToolkit.WinUI.Notifications;
using Fines2App.Data;
using MySql.Data.MySqlClient;
using SqlOrganize;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;
using Utils;

namespace Fines2App.Views;

public partial class TransferirPersonaPage : Page, INotifyPropertyChanged
{
    private DAO.Persona personaDAO = new();
    private ObservableCollection<Data_persona> origenOC = new(); //datos consultados de la base de datos
    private DispatcherTimer origenTypingTimer; //timer para buscar

    private ObservableCollection<Data_persona> destinoOC = new(); //datos consultados de la base de datos
    private DispatcherTimer destinoTypingTimer; //timer para buscar
    private MySqlConnection connection; //conexion para manejar transaccion
    private MySqlTransaction transaction; //transaccion para consultas independientes

    public TransferirPersonaPage()
    {
        InitializeComponent();
        DataContext = this;

        origenComboBox.ItemsSource = origenOC;
        origenComboBox.DisplayMemberPath = "Label";
        origenComboBox.SelectedValuePath = "id";

        destinoComboBox.ItemsSource = destinoOC;
        destinoComboBox.DisplayMemberPath = "Label";
        destinoComboBox.SelectedValuePath = "id";

    }

    #region origen
    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - GotFocus - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void OrigenComboBox_GotFocus(object sender, RoutedEventArgs e)
    {
        (sender as ComboBox).IsDropDownOpen = true;
    }

    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - TextChanged - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void OrigenComboBox_TextChanged(object sender, TextChangedEventArgs e)
    {
        ComboBox cb = (sender as ComboBox);
        DispatcherTimer timer = origenTypingTimer;

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
            timer.Tick += new EventHandler(OrigenHandleTypingTimerTimeout);
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
    private void OrigenHandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer; // WPF
        if (timer == null)
            return;

        _OrigenComboBox_TextChanged();

        // The timer must be stopped! We want to act only once per keystroke.
        timer.Stop();
    }

    /// <summary>
    /// Autocomplete 2 - _TextChanged
    /// </summary>
    /// <remarks>Autocomplete 2 - _TextChanged - v 2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790603853</remarks>
    private void _OrigenComboBox_TextChanged()
    {

        origenOC.Clear();

        if (string.IsNullOrEmpty(origenComboBox.Text) || origenComboBox.Text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
        {
            return;
        }

        IEnumerable<Dictionary<string, object>> list = personaDAO.SearchLikeQuery(origenComboBox.Text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

        foreach (var item in list)
        {
            var o = new Data_persona();
            o.SetData(item);
            o.Label = o.nombres + " " + o.apellidos + " " + o.numero_documento;
            origenOC.Add(o);
        }
    }

    /// <summary>
    /// </summary>
    /// <remarks>Autocomplete 2 - SelectionChanged - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790604099</remarks>
    private void OrigenComboBox_SelectionChanged(object sender, RoutedEventArgs e)
    {
        var cb = (ComboBox)sender;

        if (cb.SelectedIndex < 0)
            cb.IsDropDownOpen = true;
    }
    #endregion

    #region destino
    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - GotFocus - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void DestinoComboBox_GotFocus(object sender, RoutedEventArgs e)
    {
        (sender as ComboBox).IsDropDownOpen = true;
    }

    /// <summary>
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    /// <remarks>Autocomplete 2 - TextChanged - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
    private void DestinoComboBox_TextChanged(object sender, TextChangedEventArgs e)
    {
        ComboBox cb = (sender as ComboBox);
        DispatcherTimer timer = destinoTypingTimer;

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
            timer.Tick += new EventHandler(DestinoHandleTypingTimerTimeout);
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
    private void DestinoHandleTypingTimerTimeout(object sender, EventArgs e)
    {
        var timer = sender as DispatcherTimer; // WPF
        if (timer == null)
            return;

        _DestinoComboBox_TextChanged();

        // The timer must be stopped! We want to act only once per keystroke.
        timer.Stop();
    }

    /// <summary>
    /// Autocomplete 2 - _TextChanged
    /// </summary>
    /// <remarks>Autocomplete 2 - _TextChanged - v 2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790603853</remarks>
    private void _DestinoComboBox_TextChanged()
    {

        destinoOC.Clear();

        if (string.IsNullOrEmpty(destinoComboBox.Text) || destinoComboBox.Text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
        {
            return;
        }

        IEnumerable<Dictionary<string, object>> list = personaDAO.SearchLikeQuery(destinoComboBox.Text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

        foreach (var item in list)
        {
            var o = new Data_persona();
            o.SetData(item);
            o.Label = o.nombres + " " + o.apellidos + " " + o.numero_documento;
            destinoOC.Add(o);
        }
    }

    /// <summary>
    /// </summary>
    /// <remarks>Autocomplete 2 - SelectionChanged - v2023.11<br/>
    /// https://github.com/Pericial/GAP/issues/54#issuecomment-1790604099</remarks>
    private void DestinoComboBox_SelectionChanged(object sender, RoutedEventArgs e)
    {
        var cb = (ComboBox)sender;

        if (cb.SelectedIndex < 0)
            cb.IsDropDownOpen = true;
    }
    #endregion

    private void TransferirButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            connection = new(ContainerApp.config.connectionString);
            connection.Open();
            try
            {
                transaction = connection.BeginTransaction();
                try
                {
                    var idOrigen = origenComboBox.SelectedValue;
                    var idDestino = destinoComboBox.SelectedValue;

                    if (idOrigen.IsNullOrEmptyOrDbNull() || idDestino.IsNullOrEmptyOrDbNull())
                        throw new Exception("Persona no seleccionada");

                    var alumnoOrigenData = ContainerApp.db.Query("alumno").SetConn(connection).Where("$persona = @0").Parameters(idOrigen).DictCache();
                    var alumnoDestinoData = ContainerApp.db.Query("alumno").SetConn(connection).Where("$persona = @0").Parameters(idDestino).DictCache();

                    if (alumnoOrigenData != null || alumnoDestinoData != null)
                    {
                        EntityValues alumnoDestinoValues = ContainerApp.db.Values("alumno");
                        if (alumnoDestinoData != null)
                            alumnoDestinoValues.Set(alumnoDestinoData);

                        if (alumnoOrigenData != null)
                        {
                            alumnoDestinoValues.Values().Copy(alumnoOrigenData, targetNull: true, sourceNotNull: true, createKey: false, compareNotNull: false, ignoreKeys: new List<string>() { "id" });
                            ContainerApp.db.Persist().SetConn(connection).Persist(alumnoDestinoValues).Exec();
                            TransferirRelacionesAlumno(alumnoOrigenData["id"], alumnoDestinoValues.Get("id"));
                            ContainerApp.db.Persist().SetConn(connection).DeleteIds("alumno", alumnoOrigenData["id"]).Exec();

                        }
                    }

                    TransferirRelacionesPersona(alumnoOrigenData["persona"], alumnoDestinoData["persona"]);

                    ContainerApp.db.Persist().SetConn(connection).DeleteIds("persona", idOrigen).Exec();

                    transaction.Commit();

                    ContainerApp.cache.Clear();

                    new ToastContentBuilder()
                            .AddText("Transferir Persona")
                            .AddText("Transferencia realizada exitosamente")
                            .Show();

                }
                catch (Exception)
                {
                    transaction.Rollback();
                    throw;
                }
                finally
                {
                    transaction.Dispose();
                }
            }
            finally
            {
                connection.Close();
                connection.Dispose();
            }
        }
        catch (Exception ex)
        {
            new ToastContentBuilder()
                .AddText("Búsqueda de Causas del WS")
                .AddText(ex.Message)
                .Show();
        }
    }


    private void TransferirRelacionesAlumno(object idAlumnoOrigen, object idAlumnoDestino)
    {

        #region alumno_comision
        IEnumerable<Dictionary<string, object?>> data = ContainerApp.db.Query("alumno_comision").
            SetConn(connection).
            Where("alumno = @0").
            Parameters(idAlumnoOrigen).
            ColOfDictCache();

        foreach (var ac in data)
        {
            EntityValues values = ContainerApp.db.Values("alumno_comision").
                Set(ac).
                SetDefault("id"). //se reasigna id
                Set("alumno", idAlumnoDestino);

            ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                DeleteIds("alumno_comision", ac["id"]).
                Exec();
        }
        #endregion

        #region calificacion (solo aprobadas)
        data = ContainerApp.db.Query("calificacion").
            SetConn(connection).
            Where("alumno = @0 AND ($nota_final >= 7 OR $crec >= 4)").
            Parameters(idAlumnoOrigen).
            ColOfDictCache();

        foreach (var cal in data)
        {
            EntityValues values = ContainerApp.db.Values("calificacion").
                Set(cal).
                SetDefault("id"). //se reasigna id
                Set("alumno", idAlumnoDestino);

            ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                DeleteIds("calificacion", cal["id"]).
                Exec();
        }
        #endregion

        #region calificacion (eliminar no aprobadas)
        var idsCalificacionesAprobadas = data.ColOfVal<object>("id");

        data = ContainerApp.db.Query("calificacion").
            SetConn(connection).
            Where("$alumno = @0 AND $id NOT IN (@1)").
            Parameters(idAlumnoOrigen, idsCalificacionesAprobadas).
            ColOfDictCache();

        foreach (var cal in data)
        {
            ContainerApp.db.Persist().
                SetConn(connection).
                DeleteIds("calificacion", cal["id"]).
                Exec();
        }
        #endregion

    }

    private void TransferirRelacionesPersona(object idPersonaOrigen, object idPersonaDestino)
    {
        
        #region detalle_persona
        IEnumerable<Dictionary<string, object?>> data = ContainerApp.db.Query("detalle_persona").
            SetConn(connection).
            Where("$persona = @0").
            Parameters(idPersonaOrigen).
            ColOfDictCache();

        foreach (var dd in data)
        {
            EntityValues values = ContainerApp.db.Values("detalle_persona").
                Set(dd).
                SetDefault("id"). //se reasigna id
                Set("persona", idPersonaDestino);

            ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                DeleteIds("detalle_persona", dd["id"]).
                Exec();
        }
        #endregion

        #region designacion
        data = ContainerApp.db.Query("designacion").
            SetConn(connection).
            Where("$persona = @0").
            Parameters(idPersonaOrigen).
            ColOfDictCache();

        foreach (var dd in data)
        {
            EntityValues values = ContainerApp.db.Values("designacion").
                Set(dd).
                SetDefault("id"). //se reasigna id
                Set("persona", idPersonaDestino);

            EntityPersist persist = ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                DeleteIds("designacion", dd["id"]).
                Exec();
        }
        #endregion

        #region toma (docente)
        data = ContainerApp.db.Query("toma").
            SetConn(connection).
            Where("$docente = @0").
            Parameters(idPersonaOrigen).
            ColOfDictCache();

        foreach (var dd in data)
        {
            EntityValues values = ContainerApp.db.Values("toma").
                Set(dd).
                SetDefault("id"). //se reasigna id
                Set("docente", idPersonaDestino);

            EntityPersist persist = ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                Exec();

            TransferirRelacionesToma(dd["id"], values.Get("id"));

            persist = ContainerApp.db.Persist().
                SetConn(connection).
                DeleteIds("toma", dd["id"]).
                Exec();

        }
        #endregion

        #region toma (reemplazo)
        data = ContainerApp.db.Query("toma").
            SetConn(connection).
            Where("$reemplazo = @0").
            Parameters(idPersonaOrigen).
            ColOfDictCache();

        foreach (var dd in data)
        {
            EntityValues values = ContainerApp.db.Values("toma").
                Set(dd).
                SetDefault("id"). //se reasigna id
                Set("reemplazo", idPersonaDestino);

            EntityPersist persist = ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).Exec();

            TransferirRelacionesToma(dd["id"], values.Get("id"));

            persist = ContainerApp.db.Persist().
                SetConn(connection).
                DeleteIds("toma", dd["id"]).
                Exec();
        }
        #endregion

        #region telefono
        data = ContainerApp.db.Query("telefono").
            SetConn(connection).
            Where("$persona = @0").
            Parameters(idPersonaOrigen).
            ColOfDictCache();

        foreach (var dd in data)
        {
            EntityValues values = ContainerApp.db.Values("telefono").
                Set(dd).
                SetDefault("id"). //se reasigna id
                Set("persona", idPersonaDestino);

            EntityPersist persist = ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                DeleteIds("telefono", dd["id"]).
                Exec();
        }
        #endregion

        #region email
        data = ContainerApp.db.Query("email").
            SetConn(connection).
            Where("$persona = @0").
            Parameters(idPersonaOrigen).
            ColOfDictCache();

        foreach (var dd in data)
        {
            EntityValues values = ContainerApp.db.Values("email").
                Set(dd).
                SetDefault("id"). //se reasigna id
                Set("persona", idPersonaDestino);

            EntityPersist persist = ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                DeleteIds("email", dd["id"]).
                Exec();
        }
        #endregion
    }


    private void TransferirRelacionesToma(object idTomaOrigen, object idTomaDestino)
    {
        #region asignacion_planilla_docente
        IEnumerable<Dictionary<string, object?>> data = ContainerApp.db.Query("asignacion_planilla_docente").
            SetConn(connection).
            Where("$toma = @0").
            Parameters(idTomaOrigen).
            ColOfDictCache();

        foreach (var dd in data)
        {
            EntityValues values = ContainerApp.db.Values("asignacion_planilla_docente").
                Set(dd).
                SetDefault("id"). //se reasigna id
                Set("toma", idTomaDestino);

            EntityPersist persist = ContainerApp.db.Persist().
                SetConn(connection).
                Persist(values).
                DeleteIds("asignacion_planilla_docente", dd["id"]).
                Exec();
        }
        #endregion
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
