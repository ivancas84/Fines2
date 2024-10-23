using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using FinesApp.Contracts.Services;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
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
        dgdAsignacion.ItemsSource = ocAsignacion;

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

            var cantidad = AsignacionDAO.AsignacionesDeCalendario(cbxCalendario.SelectedValue).Select("COUNT(*) as cantidad").Cache().Value("cantidad");
    
            var source = AsignacionDAO.AsignacionesDeCalendario(cbxCalendario.SelectedValue).Cache().Dicts();

            var idAlumnos = source.ColOfVal<object>("id");

            var calificacionesAprobadasAgrupadas = CalificacionDAO.CantidadCalificacionesAprobadasPorAlumnoAgrupadasPorAnioSemestre(idAlumnos).Dicts();

            ocAsignacion.Clear();

            for (var i = 0; i < source.Count(); i++)
            {
                AlumnoComision obj = Entity.CreateFromDict<AlumnoComision>(source.ElementAt(i));
                obj.Index = i;
                ocAsignacion.Add(obj);
            }

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

    private void btn_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            var button = (e.OriginalSource as Button);
            var entity = (AlumnoComision)button.DataContext;
            _navigationService.NavigateTo(typeof(AdministrarAlumnoPage), entity.alumno);


        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
}
