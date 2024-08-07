using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using System.Windows.Threading;
using WpfUtils;
using WpfUtils.Controls;
using WpfUtils.Fines.Curso;

namespace FinesApp.Views;

public partial class PF_ProcesarCalificacionesPage : Page, INotifyPropertyChanged
{

    #region Autocomplete v3 - organismo
    private ObservableCollection<Data_curso_r> cursoOC = new(); //datos consultados de la base de datos
    private DispatcherTimer cursoTypingTimer; //timer para buscar
    #endregion

    private ObservableCollection<Data_calificacion_r> calificacionProcesadaOC = new();
    private ObservableCollection<Data_calificacion_r> calificacionAprobadaOC = new(); //calificaciones aprobadas del curso
    private ObservableCollection<Data_alumno_comision_r> asignacionDesaprobadaOC = new(); //asignaciones activas que no figuran aprobadas del curso

    List<EntityPersist> persists = new();

    private ObservableCollection<string> dnisProcesados = new();
    public PF_ProcesarCalificacionesPage()
    {
        InitializeComponent();
        calificacionDataGrid.ItemsSource = calificacionProcesadaOC;
        cursoComboBox.InitializeAutoCompleteItemConstructor(cursoOC);
        cursoTypingTimer = new DispatcherTimer
        {
            Interval = TimeSpan.FromMilliseconds(300)
        };
        cursoTypingTimer.Tick += CursoTimer_Tick;

        calificacionAprobadaDataGrid.ItemsSource = calificacionAprobadaOC;
        asignacionDesaprobadaDataGrid.ItemsSource = asignacionDesaprobadaOC;

        DataContext = this;
    }


    #region Autocomplete v3 - organismo
    private void CursoTimer_Tick(object sender, EventArgs e)
    {
        ContainerApp.db.SetCursoTimerTick(cursoComboBox, cursoTypingTimer, cursoOC);
    }

    private void CursoAutocompleteComboBox_KeyUp(object sender, KeyEventArgs e)
    {
        cursoTypingTimer.SetKeyUp(e);
    }
    #endregion

    private void GuardarButton_Click(object sender, RoutedEventArgs e)
    {
        if (!persists.Any())
        { 
            ToastExtensions.Show("No existen registros para guardar");
            return;
        }

        persists.Transaction().RemoveCache();

        ContainerApp.db.ConsultarCalificacionesAprobadasAsignacionesDesaprobadas(cursoComboBox.SelectedValue, calificacionAprobadaOC, asignacionDesaprobadaOC);

        ToastExtensions.Show("Registro realizado exitosamente");
    }

    private void EliminarButton_Click(object sender, RoutedEventArgs e)
    {
        var calificaciones = ContainerApp.db.CalificacionesCursoSql(cursoComboBox.SelectedValue).Cache().ColOfDict().ColOfVal<object>("id");

        if (!calificaciones.Any())
        {
            ToastExtensions.Show("No existen calificaciones para eliminar");

        } else
        {
            ContainerApp.db.Persist().DeleteIds("calificacion", calificaciones.ToArray()).Exec().RemoveCache();
            ToastExtensions.Show("Calificaciones eliminadas");

        }
    }


    private void ProcesarButton_Click(object sender, RoutedEventArgs e)
    {
        var _data = dataTextBox.Text.Split("\r\n");
        if (_data.IsNoE())
        {
            ToastExtensions.Show("Datos vacios");
            return;
        }

        var cursoSeleccionado = (Data_curso_r)cursoComboBox.SelectedItem;
        if (cursoSeleccionado.IsNoE())
        {
            ToastExtensions.Show("Falta seleccionar el curso");
            return;
        }

        ContainerApp.db.ConsultarCalificacionesAprobadasAsignacionesDesaprobadas(cursoSeleccionado.id, calificacionAprobadaOC, asignacionDesaprobadaOC);

        dnisProcesados.Clear();
        calificacionProcesadaOC.Clear();
        persists.Clear();
        for (var j = 0; j < _data.Length; j++)
        {
            if (_data[j].IsNoE())
                continue;

            try
            {
                CalificacionValues calificacionVal = ((CalificacionValues)ContainerApp.db.Values("calificacion")).
                    SetFromProgramaFines(_data[j]);

                if (dnisProcesados.Contains((string)calificacionVal.Get("persona-numero_documento")))
                    throw new Exception("El DNI ya se encuentra procesado");

                calificacionVal.PersistProcesarCurso(cursoSeleccionado.id).AddTo(persists);
                
                Data_calificacion_r calificacionObj = calificacionVal.GetData<Data_calificacion_r>();
                
                dnisProcesados.Add(calificacionObj.persona__numero_documento);
                
                calificacionProcesadaOC.Add(calificacionObj);
            } catch (Exception ex)
            {
                var calificacionError = ((CalificacionValues)ContainerApp.db.Values("calificacion")).GetCalificacionConError(j, ex.Message, _data[j]);
                calificacionProcesadaOC.Add(calificacionError);
            }
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
    #endregion;
}
