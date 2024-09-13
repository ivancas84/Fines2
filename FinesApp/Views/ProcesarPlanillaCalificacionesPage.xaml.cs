using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System.Collections.ObjectModel;
using System.Windows.Controls;
using System.Windows.Threading;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System.Windows;
using WpfUtils;
using WpfUtils.Fines.Curso;
using WpfUtils.Controls;
using System.Windows.Input;

namespace FinesApp.Views;

public partial class ProcesarPlanillaCalificacionesPage : Page
{

    #region Autocomplete v3 - organismo
    private ObservableCollection<Curso_> cursoOC = new(); //datos consultados de la base de datos
    private DispatcherTimer cursoTypingTimer; //timer para buscar
    #endregion

    private ObservableCollection<Calificacion_> calificacionProcesadaOC = new();
    private ObservableCollection<Calificacion_> calificacionAprobadaOC = new(); //calificaciones aprobadas del curso
    private ObservableCollection<AlumnoComision_> asignacionDesaprobadaOC = new(); //asignaciones activas que no figuran aprobadas del curso

    List<EntityPersist> persists = new();

    private ObservableCollection<string> dnisProcesados = new();
    public ProcesarPlanillaCalificacionesPage()
    {
        InitializeComponent();
        calificacionDataGrid.ItemsSource = calificacionProcesadaOC;
        cursoComboBox.InitComboBoxConstructor(cursoOC);
        cursoTypingTimer = new DispatcherTimer
        {
            Interval = TimeSpan.FromMilliseconds(300)
        };
        cursoTypingTimer.Tick += CursoTimer_Tick;

        calificacionAprobadaDataGrid.ItemsSource = calificacionAprobadaOC;
        asignacionDesaprobadaDataGrid.ItemsSource = asignacionDesaprobadaOC;

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
        try
        {
            if (!persists.Any())
            {
                ToastExtensions.Show("No existen registros para guardar");
                return;
            }

            persists.Transaction().RemoveCache();

            ContainerApp.db.ConsultarCalificacionesAprobadasAsignacionesDesaprobadas(cursoComboBox.SelectedValue, calificacionAprobadaOC, asignacionDesaprobadaOC);

            ToastExtensions.Show("Registro realizado exitosamente");
        } catch (Exception ex)
        {
            ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex);
        }
    }

    private void EliminarButton_Click(object sender, RoutedEventArgs e)
    {
        var calificaciones = ContainerApp.db.CalificacionesCursoSql(cursoComboBox.SelectedValue).Cache().Dicts().ColOfVal<object>("id");

        if (!calificaciones.Any())
        {
            ToastExtensions.Show("No existen calificaciones para eliminar");

        }
        else
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

        var cursoSeleccionado = (Curso_)cursoComboBox.SelectedItem;
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
                CalificacionValues calificacionVal;

                if (sourceComboBox.SelectedItem.ToString().Contains("Programa"))
                    calificacionVal = ((CalificacionValues)ContainerApp.db.Values("calificacion")).
                        SetFromProgramaFines(_data[j]);
                else
                    calificacionVal = ((CalificacionValues)ContainerApp.db.Values("calificacion")).
                       SetFromPlanilla(_data[j]);

                if (dnisProcesados.Contains((string)calificacionVal.Get("persona-numero_documento")))
                    throw new Exception("El DNI ya se encuentra procesado");

                calificacionVal.PersistProcesarCurso(cursoSeleccionado.id).AddToIfSql(persists);

                Calificacion_ calificacionObj = calificacionVal.GetData<Calificacion_>();

                dnisProcesados.Add(calificacionObj.persona__numero_documento);

                calificacionProcesadaOC.Add(calificacionObj);
            }
            catch (Exception ex)
            {
                var calificacionError = ((CalificacionValues)ContainerApp.db.Values("calificacion")).GetCalificacionConError(j, ex.Message, _data[j]);
                calificacionProcesadaOC.Add(calificacionError);
            }
        }
    }

}
