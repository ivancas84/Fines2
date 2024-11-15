using FinesApp.Contracts.Views;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using SqlOrganize;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;
using System.Collections.ObjectModel;
using System.Windows.Threading;
using System.Windows.Input;
using WpfUtils.Controls;
using WpfUtils;

namespace FinesApp.Views;

public partial class AdministrarAlumnoPage : Page, INotifyPropertyChanged, INavigationAware
{

    #region Autocomplete v3 - Buscar
    private ObservableCollection<Persona> ocPersona = new();
    private DispatcherTimer dtPersona; //timer para buscar
    #endregion

    public AdministrarAlumnoPage()
    {
        InitializeComponent();
        DataContext = this;

        #region Autocomplete v3 - persona
        cbxPersona.InitComboBoxConstructor(ocPersona);
        dtPersona = new DispatcherTimer { Interval = TimeSpan.FromMilliseconds(300) };
        dtPersona.Tick += SetPersonaTimerTick;
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

    #region INavigationAware
    public void OnNavigatedTo(object parameter)
    {
        if (!parameter.IsNoE())
        {
            gbxPrincipal.DataContext = Entity.CreateFromId<Alumno>(parameter);
        }

        else
            gbxPrincipal.DataContext = new Alumno();
    }

    public void OnNavigatedFrom()
    {
        //throw new NotImplementedException();
    }
    #endregion

    private void BtnGuardarAlumno_Click(object sender, System.Windows.RoutedEventArgs e)
    {

    }



    #region Autocomplete v3 - organismo
    private void CbxPersona_KeyUp(object sender, System.Windows.Input.KeyEventArgs e)
    {
        dtPersona.SetKeyUp(e);
    }

    private void SetPersonaTimerTick(object sender, EventArgs e)
    {
        try
        {
            (string? text, TextBox? textBox, int? textBoxPos) = cbxPersona.SetTimerTickInitializeItem<Persona>(dtPersona);
            if (text == null)
                return;

            IEnumerable<Dictionary<string, object?>> list;

            PersonaDAO.SqlPersonas__SearchLike(text).Size(50).Cache().AddEntitiesToClearOC(ocPersona);

            cbxPersona.SetTimerTickFinalize(textBox!, text, (int)textBoxPos!);
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }


    #endregion

    private void CbxPersona_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        var cb = (ComboBox)sender;

        if (cb.SelectedIndex > -1)
        {
            gbxPrincipal.DataContext = (Persona)cb.SelectedItem;

        }
    }

    private void BtnGuardarPersona_Click(object sender, System.Windows.RoutedEventArgs e)
    {

    }
}
