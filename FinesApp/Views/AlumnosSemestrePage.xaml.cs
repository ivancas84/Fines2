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

            IEnumerable<string> concats_alumno_planCurso = source.ColOfValConcat("alumno", "planificacion__plan");

            var calificacionesAprobadasAgrupadas = CalificacionDAO.COUNT_calificacionesAprobadas__BY_Concat_alumno_planDeCurso__GROUP_alumno_planDeCurso_anio_semestre(concats_alumno_planCurso).Cache().Dicts().DictOfDictByKeysValue("cantidad", "alumno", "planificacion__plan", "planificacion_dis1__anio", "planificacion_dis1__semestre");

            ocAsignacion.Clear();

            for (var i = 0; i < source.Count(); i++)
            {
                AlumnoComision obj = Entity.CreateFromDict<AlumnoComision>(source.ElementAt(i));
                obj.Index = i;

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "1"))
                    obj.CantidadAprobadas11 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "1"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "2"))
                    obj.CantidadAprobadas12 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "2"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "1"))
                    obj.CantidadAprobadas21 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "1"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "2"))
                    obj.CantidadAprobadas22 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "2"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "1"))
                    obj.CantidadAprobadas31 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "1"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "2"))
                    obj.CantidadAprobadas32 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "2"];

                ocAsignacion.Add(obj);
            }


            var idsAlumnosAsignacionesDuplicadas = AsignacionDAO.COUNT_AsignacionesActivasDuplicadasDeComisionesAutorizadas__BY_idCalendario__GROUP_alumno(cbxCalendario.SelectedValue).Cache().Column("alumno");
            AsignacionDAO.AsignacionesActivasDeComisionesAutorizadas__BY_idCalendario_idsAlumnos(cbxCalendario.SelectedValue, idsAlumnosAsignacionesDuplicadas).Cache().AddEntityToClearOC(ocAsignacionDuplicada);

        }
        catch (Exception ex)
        {
            ex.ToastException();
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
}
