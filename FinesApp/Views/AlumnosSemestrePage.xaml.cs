using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using FinesApp.Contracts.Services;
using SqlOrganize.Sql.Fines2Model3;
using WpfUtils;
using WpfUtils.Controls;

namespace FinesApp.Views;

public partial class AlumnosSemestrePage : Page, INotifyPropertyChanged
{

    private readonly INavigationService _navigationService;

    private ObservableCollection<Calendario> ocCalendario = new();
    private ObservableCollection<AlumnoComision> ocAsignacion = new();


    public AlumnosSemestrePage(INavigationService navigationService)
    {
        _navigationService = navigationService;

        InitializeComponent();
        DataContext = this;

        cbxCalendario.InitComboBoxConstructor(ocCalendario);

        Loaded += Page_Loaded;
    }

    private void Page_Loaded(object sender, RoutedEventArgs e)
    {
        CalendarioDAO.CalendariosSql().Cache().AddEntityToClearOC(ocCalendario);
    }

    private void BtnBuscarAlumnos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxCalendario.SelectedIndex < 0)
                throw new Exception("Seleccione calendario");

            AsignacionDAO.AsignacionesDeCalendario(cbxCalendario.SelectedValue).Cache().AddEntityToClearOC(ocAsignacion);
        }
        catch (Exception ex)
        {
            ex.ToastException();
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
