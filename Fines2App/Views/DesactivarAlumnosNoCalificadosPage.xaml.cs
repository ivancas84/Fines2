using CommunityToolkit.WinUI.Notifications;
using Fines2App.Data;
using SqlOrganize;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using Utils;

namespace Fines2App.Views;

public partial class DesactivarAlumnosNoCalificadosPage : Page, INotifyPropertyChanged
{

    DAO.AlumnoComision asignacionDAO = new();
    DAO.Calificacion calificacionDAO = new();

    ObservableCollection<Data_alumno_comision_r> resultado = new();
    public DesactivarAlumnosNoCalificadosPage()
    {
        InitializeComponent();
        DataContext = this;

        searchGroupBox.DataContext = new Data_calendario(SqlOrganize.DataInitMode.Null) ;

        resultadoDataGrid.ItemsSource = resultado;
    }


    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            Data_calendario search = (Data_calendario)searchGroupBox.DataContext;
            var alumnosComisiones = asignacionDAO.AsignacionesActivasDeComisionesAutorizadasPorSemestre(search.anio, search.semestre);
            resultado.Clear();
            List<object> ids = new();
            foreach (var alumnoComision in alumnosComisiones)
            {

                var qu = calificacionDAO.CantidadCalificacionesAprobadasDeAlumnoPorTramoQuery(alumnoComision["alumno"], alumnoComision["planificacion-anio"], alumnoComision["planificacion-semestre"]).DictCache();
                var q = (!qu.IsNullOrEmpty()) ? (Int64)qu["cantidad"]! : 0;

                if (q < 3)
                {
                    Data_alumno_comision_r alumnoParaDesactivar = new(DataInitMode.Null);
                    alumnoParaDesactivar.SetData(alumnoComision);
                    alumnoParaDesactivar.comision__Label = alumnoParaDesactivar.sede__numero + alumnoParaDesactivar.comision__division + "/" + alumnoParaDesactivar.planificacion__anio + alumnoParaDesactivar.planificacion__semestre + " " + alumnoParaDesactivar.calendario__anio + "-" + alumnoParaDesactivar.calendario__semestre + " (" + alumnoParaDesactivar.comision__pfid + ")";
                    ids.Add(alumnoParaDesactivar.id);
                    resultado.Add(alumnoParaDesactivar);
                }
            }
            if (ids.Count > 0)
            {
                ContainerApp.db.Persist().UpdateValueIds("alumno_comision", "estado", "No activo", ids).Exec().RemoveCache();
                new ToastContentBuilder()
                .AddText("Desactivar alumnos no calificados")
                .AddText("Se han desactivado " + ids.Count + " alumnos.")
                .Show();
            } else
            {
                new ToastContentBuilder()
                .AddText("Desactivar alumnos no calificados")
                .AddText("No existen alumnos para desactivar.")
                .Show();
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
