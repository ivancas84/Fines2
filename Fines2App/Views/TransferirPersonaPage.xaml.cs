using CommunityToolkit.WinUI.Notifications;
using Fines2App.Data;
using Org.BouncyCastle.Asn1.Ocsp;
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
            EntityPersist persist = ContainerApp.db.Persist();
            var idOrigen = origenComboBox.SelectedValue;
            var idDestino = destinoComboBox.SelectedValue;

            if(idOrigen.IsNullOrEmptyOrDbNull() || idDestino.IsNullOrEmptyOrDbNull()) 
                throw new Exception("Persona no seleccionada");

            var alumnoOrigenData = ContainerApp.db.Query("alumno").Where("$persona = @0").Parameters(idOrigen).DictCache();
            var alumnoDestinoData = ContainerApp.db.Query("alumno").Where("$persona = @0").Parameters(idDestino).DictCache();

            EntityValues alumnoDestinoValues = ContainerApp.db.Values("alumno");
            if (alumnoDestinoData != null)
                alumnoDestinoValues.Set(alumnoDestinoData);

            if (alumnoOrigenData != null)
            {
                persist.DeleteIds(new List<object> { alumnoOrigenData["id"] }, "alumno");
                alumnoDestinoValues.values.Copy(alumnoOrigenData, sourceNotNull: true, compareNotNull: true, ignoreKeys: new List<string>() { "id" });
            }
            alumnoDestinoValues.Default();



            var alumnoOrigen = new Data_alumno();
            if (!alumnoOrigenData.IsNullOrEmptyOrDbNull())
                alumnoOrigen.SetData(alumnoOrigenData);

            if (!alumnoOrigenData.IsNullOrEmptyOrDbNull())
                alumnoOrigen.SetData(alumnoOrigenData);

            new ToastContentBuilder()
                    .AddText("Búsqueda de Causas del WS")
                    .AddText("La consulta no arrojó resultados")
                    .Show();
        }
        catch (Exception ex)
        {
            new ToastContentBuilder()
                .AddText("Búsqueda de Causas del WS")
                .AddText(ex.Message)
                .Show();
        }
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
