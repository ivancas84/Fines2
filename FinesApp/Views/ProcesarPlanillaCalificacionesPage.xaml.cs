using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System.Collections.ObjectModel;
using System.Windows.Controls;
using System.Windows.Threading;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System.Windows;
using WpfUtils;
using WpfUtils.Controls;
using System.Windows.Input;
using System.Linq;
using WpfUtils.Fines;
using FinesApp.Contracts.Services;
using FinesApp.Contracts.Views;


namespace FinesApp.Views;

public partial class ProcesarPlanillaCalificacionesPage : Page, INavigationAware
{
    private readonly INavigationService _navigationService;

    private Curso curso;

    private ObservableCollection<Calificacion> calificacionProcesadaOC = new();

    List<PersistContext> persists = new();

    private ObservableCollection<string> dnisProcesados = new();
    public ProcesarPlanillaCalificacionesPage(INavigationService navigationService)
    {
        _navigationService = navigationService;

        InitializeComponent();

        calificacionDataGrid.ItemsSource = calificacionProcesadaOC;


    }

    #region INavigationAware
    public void OnNavigatedTo(object parameter)
    {
        try
        {
            curso = Context.db.Sql("curso").Equal("$id", parameter).Cache().ToEntity<Curso>();
            tbxCurso.Text = curso.Label;

            calificacionAprobadaDataGrid.ItemsSource = curso.Calificacion_;
            asignacionDesaprobadaDataGrid.ItemsSource = curso.comision_.AlumnoComision_;

        } catch(Exception ex) {
            ex.ToastException();
        }
    }

    public void OnNavigatedFrom()
    {
    }
    #endregion
    private void GuardarButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            Context.db.ProcessQueue();
            curso.ConsultarCalificacionesAprobadasAsignacionesDesaprobadas();

            ToastExtensions.Show("Registro realizado exitosamente");
        } catch (Exception ex)
        {
            ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex);
        }
    }

    private void EliminarButton_Click(object sender, RoutedEventArgs e)
    {
        var calificaciones = CalificacionDAO.CalificacionesCursoSql(curso.id).Cache().Dicts().ColOfVal<object>("id");

        if (!calificaciones.Any())
        {
            ToastExtensions.Show("No existen calificaciones para eliminar");

        }
        else
        {
            using (Context.db.CreateQueue())
            {
                Context.db.Persist().DeleteIds("calificacion", calificaciones.ToArray());
                Context.db.ProcessQueue();
                ToastExtensions.Show("Calificaciones eliminadas");
            }

        }
    }


    private void ProcesarButton_Click(object sender, RoutedEventArgs e)
    {
        Context.db.CreateQueue();

        var _data = dataTextBox.Text.Split("\r\n");
        if (_data.IsNoE())
        {
            ToastExtensions.Show("Datos vacios");
            return;
        }

        if (curso == null)
        {
            ToastExtensions.Show("Falta seleccionar el curso");
            return;
        }


        curso.ConsultarCalificacionesAprobadasAsignacionesDesaprobadas();

        dnisProcesados.Clear();
        calificacionProcesadaOC.Clear();
        for (var j = 0; j < _data.Length; j++)
        {
            if (_data[j].IsNoE())
                continue;

            try
            {
                Calificacion calificacion = new ();
                calificacion.curso_ = curso;

                if (sourceComboBox.SelectedItem.ToString().Contains("Programa"))
                    calificacion.SetFromProgramaFines(_data[j]);
                else
                    calificacion.SetFromPlanilla(_data[j]);

                if (dnisProcesados.Contains(calificacion.alumno_.persona_.numero_documento))
                    throw new Exception("El DNI ya se encuentra procesado");

                calificacion.Persist1();
                //calificacion.PersistProcesarCurso(cursoSeleccionado.id).AddToIfSql(persists);

                dnisProcesados.Add(calificacion.alumno_.persona_.numero_documento);

                calificacion.Msg = calificacion.Logging.ToString();
                calificacion.Status = "Ok";

                calificacionProcesadaOC.Add(calificacion);
            }
            catch (Exception ex)
            {
                Calificacion calificacionError = Calificacion.CreateCalificacionError(j, ex.Message, _data[j]);
                calificacionProcesadaOC.Add(calificacionError);
            }
        }
    }

}
