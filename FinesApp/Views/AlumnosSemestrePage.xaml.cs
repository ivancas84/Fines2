using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using FinesApp.Contracts.Services;
using SqlOrganize;
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
    private ObservableCollection<AlumnoComision> ocAsignacionDuplicada = new();



    public AlumnosSemestrePage(INavigationService navigationService)
    {
        _navigationService = navigationService;
        InitializeComponent();
        DataContext = this;
        Loaded += Page_Loaded;
    }

    private void Page_Loaded(object sender, RoutedEventArgs e)
    {
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        dgdAsignacion.ItemsSource = ocAsignacion;
        dgdAsignacionDuplicada.ItemsSource = ocAsignacionDuplicada;
        CalendarioDAO.CalendariosSql().Cache().AddEntitiesToClearOC(ocCalendario);
    }

    private void BtnBuscarAlumnos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxCalendario.SelectedIndex < 0)
                throw new Exception("Seleccione calendario");


            var source = AsignacionDAO.Asignaciones__BY_idCalendario(cbxCalendario.SelectedValue).Cache().Dicts();
            ocAsignacion.Clear();
            AlumnoComision.AddDataToOC__WITH_Cantidades(source, ocAsignacion);

            var idsAlumnosAsignacionesDuplicadas = AsignacionDAO.COUNT_AsignacionesActivasDuplicadas__BY_idCalendario__GROUP_alumno(cbxCalendario.SelectedValue).Cache().Column("alumno");
            ocAsignacionDuplicada.Clear();
            if (idsAlumnosAsignacionesDuplicadas.Any())
            {
                source = AsignacionDAO.AsignacionesActivas__BY_idCalendario_idsAlumnos(cbxCalendario.SelectedValue, idsAlumnosAsignacionesDuplicadas).Cache().Dicts();
                AlumnoComision.AddDataToOC__WITH_CantidadAprobadasPlan(source, ocAsignacionDuplicada);
            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void BtnCambiarEstado_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (!ocAsignacion.Any())
                throw new Exception("No se encontraron asignaciones");

            using (Context.db.CreateQueue())
            {
                PersistContext persist = Context.db.Persist();
                foreach (var asignacion in ocAsignacion)
                {
                    if (asignacion.CantidadAprobadasComision.IsNoE() || asignacion.CantidadAprobadasComision < 3)
                        asignacion.estado = "No activo";

                    else
                        asignacion.estado = "Activo";

                    persist.UpdateField(asignacion, "estado");
                }
                Context.db.ProcessQueue();
            }

        } catch(Exception exception)
        {
            exception.ToastException();
        }

    }

    private void BtnTransferirActivos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (!ocAsignacion.Any())
                throw new Exception("No se encontraron asignaciones");

            using (Context.db.CreateQueue())
            {
                PersistContext persist = Context.db.Persist();
                foreach (var asignacion in ocAsignacion)
                {
                    if (asignacion.estado.Equals("Activo") && !asignacion.comision_.comision_siguiente.IsNoE())
                    {
                        var asignacion_ = new AlumnoComision();
                        asignacion_.alumno = asignacion.alumno;
                        asignacion_.comision = asignacion.comision_.comision_siguiente;
                        asignacion_.estado = "Activo";
                        persist.InsertIfNotExists(asignacion_);
                    }

                }
                Context.db.ProcessQueue();
            }

        }
        catch (Exception exception)
        {
            exception.ToastException();
        }
    }

    #region INotifyPropertyChanged
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
    #endregion

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

    private void BtnEliminarAsignacion_Click(object sender, RoutedEventArgs e)
    {
        ocAsignacion.DgdDeleteRow(sender);
       
    }

    private void BtnEliminarAsignacionDuplicada_Click(object sender, RoutedEventArgs e)
    {
        ocAsignacionDuplicada.DgdDeleteRow(sender);

    }

    private void CbxEstado_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        Context.db.ComboBoxUpdateSelectedValue(sender, "alumno_comision", "estado");
    }

    private void Cbx_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        if (sender is ComboBox comboBox && comboBox.SelectedItem != null)
        {
            var selectedEstado = comboBox.SelectedItem.ToString();
            var alumnoComision = (AlumnoComision)((FrameworkElement)comboBox).DataContext;
            alumnoComision.UpdateFieldValue("estado", selectedEstado);
        }
    }

    
}

public class AsignacionEstadosData
{
    public ObservableCollection<string> Estados()
    {
        var data = AsignacionDAO.EstadosDeAsignacionesSql().Cache().Column<string>();
        return new ObservableCollection<string>(data);
    }
}
