using QuestPDF.Infrastructure;
using System;
using System.Windows;
using System.Windows.Navigation;

namespace Fines2Wpf
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {


        public MainWindow()
        {
            QuestPDF.Settings.License = LicenseType.Community;
            InitializeComponent();

        }


        private void listaComisiones_Click(object sender, RoutedEventArgs e)
        {
            Windows.Comision.ListaComisionesSemestre.Window1 win = new();
            win.Show();
        }

        private void informeCoordinacionDistrital_Click(object sender, RoutedEventArgs e)
        {
            Forms.InformeCoordinacionDistrital.Window1 win = new();
            win.Show();

        }

        private void listaSedesSemestre_Click(object sender, RoutedEventArgs e)
        {
            Forms.ListaSedesSemestre.Window1 win = new();
            win.Show();

        }

        private void listaPlanificaciones_Click(object sender, RoutedEventArgs e)
        {
            Forms.ListaPlanificacion.Window1 win = new();
            win.Show();

        }

        private void listaModalidades_Click(object sender, RoutedEventArgs e)
        {
            Forms.ListaModalidad.Window1 win = new();
            win.Show();

        }

        private void ListaReferentes_Click(object sender, RoutedEventArgs e)
        {
            Forms.ListaReferentesSemestre.Window1 win = new();
            win.Show();

        }

        private void ListaCursos_Click(object sender, RoutedEventArgs e)
        {
            Windows.ListaCursos.Window1 win = new();
            win.Show();

        }
        private void ListaTomas_Click(object sender, RoutedEventArgs e)
        {
            Windows.ListaTomas.Window1 win = new();
            win.Show();

        }

        private void ProcesarDocentesProgramaFines_Click(object sender, RoutedEventArgs e)
        {
            Windows.ProcesarDocentesProgramaFines.Window1 win = new();
            win.Show();
        }
        private void ProcesarComisionesProgramaFines_Click(object sender, RoutedEventArgs e)
        {
            Windows.ProcesarComisionesProgramaFines.Window1 win = new();
            win.Show();
        }

        private void PruebaPdf_Click(object sender, RoutedEventArgs e)
        {
            Windows.TomaPosesionPdf.Window1 win = new();
            win.Show();
        }

        private void EnviarEmailToma_Click(object sender, RoutedEventArgs e)
        {
            Windows.EnviarEmailToma.Window1 win = new();
            win.Show();
        }

        private void ActualizarPlanAlumnos_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.ActualizarPlanAlumnos.Window1 win = new();
            win.Show();
        }
        private void VerificarMateriasCruzadas_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.VerificarMateriasCruzadas.Window1 win = new();
            win.Show();
        }

        private void VerificarAlumnosDuplicados_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.VerificarAlumnosDuplicados.Window1 win = new();
            win.Show();
        }

        private void DesactivarAlumnosNoCalificados_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.DesactivarAlumnosNoCalificados.Window1 win = new();
            win.Show();
        }

        private void ListaCursosSinTomasAprobadas_Click(object sender, RoutedEventArgs e)
        {
            Windows.Curso.ListaCursoSemestreSinTomasAprobadas.Window1 win = new();
            win.Show();
        }

        private void TransferirAlumnosActivos_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.TransferirAlumnosActivos.Window1 win = new();
            win.Show();
        }

        private void listaAsignacionesSemestre_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.ListaAlumnosSemestre.Window1 win = new();
            win.Show();
        }

        private void listaAsignacionesProgramaFines_Click(object sender, RoutedEventArgs e)
        {
            PageManipulator.FormEstudiantes win = new();
            win.Show();
        }


        private void AdministrarAlumno_Click(object sender, RoutedEventArgs e)
        {
            Windows.Alumno.AdministrarAlumno.Window1 window1 = new();
            window1.Show();
        }

        private void GenerarPedidosCalificaciones_Click(object sender, RoutedEventArgs e)
        {
            Pedidos.Windows.GenerarTareasCalificacionesSemestre.Window1 window1 = new();
            window1.Show();
        }

        private void GenerarComisionesSemestre_Click(object sender, RoutedEventArgs e)
        {
            Windows.Comision.GenerarComisionesSemestre.Window1 window1 = new();
            window1.Show(); 
        }

        private void GenerarCursosSemestre_Click(object sender, RoutedEventArgs e)
        {
            Windows.Curso.GenerarCursosSemestre.Window1 window1 = new();
            window1.Show();
        }

        private void AdministrarComision_Click(object sender, RoutedEventArgs e)
        {
            Windows.Comision.AdministrarComision.Window1 window1 = new();
            window1.Show();
        }

        private void GenerarPDFTomaPosesion_Click(object sender, RoutedEventArgs e)
        {
            Windows.TomaPosesionPdf.Window1 window1 = new();
            window1.Show();
        }

        private void ListaCursosToma_Click(object sender, RoutedEventArgs e)
        {
            Windows.ListaCursosToma.Window1 window1 = new();
            window1.Show();
        }

        private void Contralor_Click(object sender, RoutedEventArgs e)
        {
            Windows.Toma.Contralor.Window1 window1 = new();
            window1.Show();
        }

        private void ListaAlumnosProgramaFines_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.ListaAlumnosProgramaFines.Window1 window1 = new();
            window1.Show();
        }

        private void ProcesarAlumnosListadoGeneralProgramaFines_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.ProcesarAlumnosListadoGeneral.Window1 window1 = new();
            window1.Show();
        }

        private void GenerarConstanciasAlumnos_Click(object sender, RoutedEventArgs e)
        {
            Windows.Alumno.ConstanciaPdf.Window1 window1 = new();
            window1.Show();
        }

        private void GenerarConstanciaAlumnoRegular_Click(object sender, RoutedEventArgs e)
        {
            Windows.Alumno.ConstanciaAlumnoRegularPdf.Window1 window1 = new();
            window1.Show();
        }

        private void ProcesarAsignacionesProgramaFines_Click(object sender, RoutedEventArgs e)
        {
            //Windows.AlumnoComision.ProcesarAsignacionesProgramaFines.Window1 window1 = new();
            //window1.Show();
        }

        private void ProcesarAsignacionesProgresar_Click(object sender, RoutedEventArgs e)
        {
            Windows.AlumnoComision.VerificarAlumnosProgresar.Window1 window1 = new();
            window1.Show(); 
        }

        private void IndiceHerramientasMenuItem_Click(object sender, RoutedEventArgs e)
        {
            Windows.Index.Window1 window1 = new();
            window1.Show();
        }

        private void Hyperlink_RequestNavigate(object sender, RequestNavigateEventArgs e)
        {
            Window window;
            switch (e.Uri.AbsoluteUri.Substring(12).Replace("/", ""))
            {
                case "pf_procesar_interfaz_asignaciones":
                    window = new Windows.Programafines.ProcesarInterfazAsignaciones.Window1();
                    break;

                case "procesar_registro_alumnos":
                    window = new Windows.AlumnoComision.ProcesarRegistroAlumnos.Window1();
                    break;

                default:
                    window = new Window();
                    break;
            }

            window.Show();
        }

        private void TransferirAlumno_Click(object sender, RoutedEventArgs e)
        {
            Windows.Alumno.TransferirAlumno.Window1 window1 = new();
            window1.Show();
        }

        private void CantidadesPorComision_Click(object sender, RoutedEventArgs e)
        {
            Windows.Informe.CantidadesPorComision.Window1 window1 = new();
            window1.Show();
        }
    }
}


    