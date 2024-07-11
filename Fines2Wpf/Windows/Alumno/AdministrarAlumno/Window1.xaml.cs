
using CommunityToolkit.WinUI.Notifications;
using Fines2Model3.DAO;
using Microsoft.Win32;
using MimeTypes;
using SqlOrganize;
using SqlOrganize.Sql;
using SqlOrganize.ValueTypesUtils;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Media;
using System.Windows.Threading;
using WpfUtils;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{


    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        #region pf connection
        HttpClientHandler handler;
        HttpClient client;
        #endregion

        private DAO.Comision comisionDAO = new(); //objeto de acceso a datos de comision
        private DAO.Curso cursoDAO = new(); //objeto de acceso a datos de curso
        private DAO.Calificacion calificacionDAO = new(); //objeto de acceso a datos de calificacion


        private ObservableCollection<Data_persona> personaOC = new(); //datos consultados de la base de datos
        private ObservableCollection<Data_resolucion> resolucionOC = new(); //datos consultados de la base de datos
        private ObservableCollection<Data_plan> planOC = new(); //datos consultados de la base de datos
        private DispatcherTimer typingTimer;


        #region asignacionGroupBox
        private ObservableCollection<Asignacion> asignacionOC = new(); //datos a visualizar
        private DispatcherTimer typingTimerComision; //timer para busqueda de comision en asignacion
        private Asignacion asignacion; //asignacion que esta siendo editada
        #endregion

        #region calificacionGroupBox
        private ObservableCollection<Calificacion> calificacionOC = new(); //datos a visualizar
        private ICollectionView calificacionCV; //CV para filtro
        DispatcherTimer calificacionTypingTimer; //timer para filtro
        Calificacion calificacion; //calificacion que esta siendo administrada
        private ObservableCollection<Data_disposicion_r> disposicionOC = new(); //datos a visualizar del comboBox
        private DispatcherTimer cursoTypingTimer; //timer para busqueda de comision en asignacion
        #endregion

        #region calificacionArchivadaGroupBox
        private ObservableCollection<Data_calificacion_r> calificacionArchivadaOC = new(); //datos a visualizar
        private ICollectionView calificacionArchivadaCV; //CV para filtro
        DispatcherTimer calificacionArchivadaTypingTimer; //timer para filtro
        Calificacion calificacionArchivada; //calificacion que esta siendo administrada
        #endregion

        #region detallePersonaGroupBox
        private ObservableCollection<DetallePersona> detallePersonaOC = new();
        #endregion

        public Window1()
        {
            InitializeComponent();
            ContentRendered += Window1_ContentRendered;

            #region personaComboBox
            personaComboBox.ItemsSource = personaOC;
            personaComboBox.DisplayMemberPath = "Label";
            personaComboBox.SelectedValuePath = "id";
            personaGroupBox.DataContext = new Data_persona(ContainerApp.db);
            #endregion

            #region sexoComboBox
            sexoComboBox.SelectedValuePath = "Key";
            sexoComboBox.DisplayMemberPath = "Value";
            sexoComboBox.Items.Add(new KeyValuePair<byte?, string>(null, "(No seleccionado)")); //quitar esta linea si no permite valor null
            sexoComboBox.Items.Add(new KeyValuePair<byte, string>(1, "Masculino"));
            sexoComboBox.Items.Add(new KeyValuePair<byte, string>(2, "Femenino"));
            sexoComboBox.Items.Add(new KeyValuePair<byte, string>(3, "Otro"));
            #endregion

            #region documentacion_inscripcion
            documentacionInscripcionComboBox.SelectedValuePath = "Key";
            documentacionInscripcionComboBox.DisplayMemberPath = "Value";
            documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>(null, "--Seleccione--"));
            documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Constancia", "Constancia"));
            documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Certificado", "Certificado"));
            documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Analítico Parcial", "Analítico Parcial"));
            documentacionInscripcionComboBox.Items.Add(new KeyValuePair<string?, string>("Analítico Completo", "Analítico Completo"));
            #endregion

            #region resolucion_inscripcion
            resolucionInscripcionComboBox.SelectedValuePath = "id";
            resolucionInscripcionComboBox.DisplayMemberPath = "numero";
            resolucionInscripcionComboBox.ItemsSource = resolucionOC;
            var data = ContainerApp.db.Sql("resolucion").Order("$numero ASC").Cache().ColOfDict();
            resolucionOC.Clear();
            resolucionOC.AddRange(data);
            #endregion

            #region plan
            planComboBox.SelectedValuePath = "id";
            planComboBox.DisplayMemberPath = "Label";
            planComboBox.ItemsSource = planOC;
            var dataPlan = ContainerApp.db.Sql("plan").Order("$orientacion ASC").Cache().ColOfDict();

            planOC.Clear();
            foreach (var item in dataPlan)
            {
                var o = item.Obj<Data_plan>();
                o.Label = ContainerApp.db.Values("plan").Set(item).ToString();
                planOC.Add(o);
            }
            #endregion

            #region anio_inscripcion_completo
            anioInscripcionCompletoComboBox.SelectedValuePath = "Key";
            anioInscripcionCompletoComboBox.DisplayMemberPath = "Value";
            anioInscripcionCompletoComboBox.Items.Add(new KeyValuePair<bool?, string>(null, "--Seleccione--"));
            anioInscripcionCompletoComboBox.Items.Add(new KeyValuePair<bool?, string>(true, "Completo"));
            anioInscripcionCompletoComboBox.Items.Add(new KeyValuePair<bool?, string>(false, "Incompleto"));
            #endregion

            #region asignacionDataGrid
            asignacionDataGrid.ItemsSource = asignacionOC;
            #endregion

            #region calificacionGroupBox
            var calificacionCVS = new CollectionViewSource() { Source = calificacionOC };
            calificacionCV = calificacionCVS.View;
            calificacionCV.Filter = CalificacionCV_Filter;
            calificacionDataGrid.ItemsSource = calificacionCV;
            calificacionDataGrid.CellEditEnding += CalificacionDataGrid_CellEditEnding;
            #endregion

            #region calificacionArchivadaGroupBox
            var calificacionArchivadaCVS = new CollectionViewSource() { Source = calificacionArchivadaOC };
            calificacionArchivadaCV = calificacionArchivadaCVS.View;
            calificacionArchivadaCV.Filter = CalificacionArchivadaCV_Filter;
            calificacionArchivadaDataGrid.ItemsSource = calificacionArchivadaCV;
            #endregion

            #region detallePersonaGroupBox
            detallePersonaDataGrid.ItemsSource = detallePersonaOC;
            #endregion

            Closing += Window_Closing;
        }

        private void Window_Closing(object sender, System.ComponentModel.CancelEventArgs e)
        {
            // Dispose HttpClient and HttpClientHandler
            if (!client.IsNoE())
                client.Dispose();

            if (!handler.IsNoE())
                handler.Dispose();
        }


        private void SetPersonaGroupBox(Data_persona? persona = null)
        {
            if (persona.IsNoE())
            {
                persona = new Data_persona(ContainerApp.db);
            }

            personaGroupBox.DataContext = persona;
            persona._Validate = true;

        }
        private void SetAlumnoGroupBox(Alumno? alumno = null)
        {
            if (alumno.IsNoE())
            {
                alumno = new Alumno(ContainerApp.db);
                var per = (Data_persona)personaGroupBox.DataContext;
                alumno.persona = per.id;
            }
            var value = (AlumnoValues)ContainerApp.db.Values("alumno").Set(alumno!);
            alumno!.color_anio_ingreso = alumno.anio_ingreso.IsNoE() ? ContainerApp.config.colorRed : ContainerApp.config.colorGreen;
            alumno!.color_semestre_ingreso = alumno.semestre_ingreso.IsNoE() ? ContainerApp.config.colorRed : ContainerApp.config.colorGreen;
            alumno!.color_plan = alumno.plan.IsNoE() ? ContainerApp.config.colorRed : ContainerApp.config.colorGreen;
            alumno!.color_estado_inscripcion = value.ColorEstadoInscripcion(alumno.estado_inscripcion);
            alumno!.color_confirmado_direccion = alumno.confirmado_direccion ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
            alumno!.color_tiene_certificado = alumno.tiene_certificado ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
            alumno!.color_tiene_constancia = alumno.tiene_constancia ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
            alumno!.color_tiene_partida = alumno.tiene_partida ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
            alumno!.color_tiene_dni = alumno.tiene_dni ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
            alumno!.color_previas_completas = alumno.previas_completas ?? false ? ContainerApp.config.colorGreen : ContainerApp.config.colorRed;
            alumno!._Validate = true;
            

            alumnoGroupBox.DataContext = alumno;

        }

        private void LoadAsignaciones(Alumno a)
        {
            asignacionOC.Clear();
            if (a.IsNoE() || a.id.IsNoE())
                return;
            var data = ContainerApp.db.Sql("alumno_comision").
                Where("$alumno = @0").
                Order("$calendario-anio DESC, $calendario-semestre DESC").
                Parameters(a.id!).Cache().ColOfDict();

            foreach (var item in data)
            {
                var asignacion = item.Obj<Asignacion>();


                var val = ContainerApp.db.Values("comision", "comision").Set(item);
                var comision = val.Values().Obj<Data_comision_r>();
                comision.Label = val.ToString();

                asignacion.comision__Label = comision.Label;
                asignacion.Comisiones.Add(comision);

                asignacionOC.Add(asignacion);
            }
        }

        private void LoadCalificaciones(Data_alumno a)
        {
            calificacionOC.Clear();

            if (a.IsNoE() || a.id.IsNoE())
                return;
            if (a.plan.IsNoE())
                return;
            
            var data = calificacionDAO.CalificacionesDeAlumnoPlanArchivoQuery(a.id!, a.plan!, false).Cache().ColOfDict();

            foreach (var item in data)
            {
                var calificacion = item.Obj<Calificacion>();

                var val = ContainerApp.db.Values("disposicion", "disposicion").Set(item);
                var disposicion = val.Values().Obj<Data_disposicion_r>();
                disposicion.Label = val.ToString();
                calificacion.Disposiciones.Add(disposicion);

                var valc = (CursoValues)ContainerApp.db.Values("curso", "curso").Set(item);
                var curso = val.Values().Obj<Data_curso_r>();
                curso.Label = valc.ToStringDocente();
                calificacion.curso__Label = curso.Label;

                calificacion._Validate = true;

                calificacion.color_nota_final = ContainerApp.config.colorRed;
                calificacion.color_crec = ContainerApp.config.colorRed;

                if ((!calificacion.nota_final.IsNoE() && calificacion.nota_final >= 7)
                   || (!calificacion.crec.IsNoE() && calificacion.crec >= 4))
                {
                    calificacion.color_nota_final = ContainerApp.config.colorGreen;
                    calificacion.color_crec = ContainerApp.config.colorGreen;
                }
                
                calificacionOC.Add(calificacion);
            }
        }

        private void LoadCalificacionesArchivadas(Data_alumno a)
        {
            calificacionArchivadaOC.Clear();

            if (a.IsNoE() || a.id.IsNoE())
                return;

            var data = calificacionDAO.CalificacionesArchivadasDeAlumnoQuery(a.id!).Cache().ColOfDict();

            foreach (var item in data)
            {
                var calificacion = item.Obj<Data_calificacion_r>();

                calificacion.disposicion__Label = ContainerApp.db.Values("disposicion", "disposicion").Set(item).ToString();

                var valc = (CursoValues)ContainerApp.db.Values("curso", "curso").Set(item);
                calificacion.curso__Label = valc.ToStringDocente();

                calificacionArchivadaOC.Add(calificacion);
            }
        }

        private void LoadDisposiciones(Data_alumno a)
        {
            disposicionOC.Clear();

            if(a.IsNoE() || a.id.IsNoE() || a.plan.IsNoE())
                return;

            var data = ContainerApp.db.Sql("disposicion").
                Where("$planificacion-plan = @0").
                Parameters(a.plan!).Cache().ColOfDict();

            foreach (var item in data)
            {
                var disposicion = item.Obj<Data_disposicion_r>();
                disposicion.Label = ContainerApp.db.Values("disposicion").Set(item).ToString();                
                disposicionOC.Add(disposicion);
            }
        }

        private void LoadDetalles(Data_persona p)
        {
            detallePersonaOC.Clear();

            var data = ContainerApp.db.Sql("detalle_persona").
                Where("$persona = @0").
                Parameters(p.id!).Cache().ColOfDict();

            foreach (var item in data)
            {
                var detalle = item.Obj<DetallePersona>();
                detalle.arch = detalle.archivo;
                detallePersonaOC.Add(detalle);
            }
        }

        private void Window1_ContentRendered(object? sender, EventArgs e)
        {
            SetPersonaGroupBox();
            SetAlumnoGroupBox();
        }

 

        private void GuardarAlumnoButton_Click(object sender, RoutedEventArgs e)
        {
            var alu = (Alumno)alumnoGroupBox.DataContext;

            try
            {
                ContainerApp.db.Persist().
                    Persist("alumno", alu).Exec().RemoveCache();
                SetAlumnoGroupBox(alu);
                MessageBox.Show("Registro de alumno realizado");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private void PersonaComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            personaComboBox.IsDropDownOpen = true;
        }

        private void PersonaComboBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            if (this.personaComboBox.Text.IsNoE())
                personaComboBox.IsDropDownOpen = true;
            if (this.personaComboBox.SelectedIndex > -1)
            {
                if (this.personaComboBox.Text.Equals(((Data_persona)this.personaComboBox.SelectedItem).Label))
                    return;
                else
                    this.personaComboBox.Text = "";
            }

            if (typingTimer == null)
            {
                typingTimer = new DispatcherTimer();
                typingTimer.Interval = TimeSpan.FromMilliseconds(300);
                typingTimer.Tick += new EventHandler(PersonaComboBox_HandleTypingTimerTimeout);
            }

            typingTimer.Stop(); // Resets the timer
            typingTimer.Tag = (sender as ComboBox).Text; // This should be done with EventArgs
            typingTimer.Start();
        }

        private void PersonaComboBox_HandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer; // WPF
            if (timer == null)
            {
                return;
            }

            _PersonaComboBox_TextChanged();

            // The timer must be stopped! We want to act only once per keystroke.
            timer.Stop();
        }

        private void _PersonaComboBox_TextChanged()
        {

            personaOC.Clear();

            if (this.personaComboBox.Text.IsNoE() || this.personaComboBox.Text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            IEnumerable<Dictionary<string, object>> list = DAO.Persona.SearchLikeQuery(this.personaComboBox.Text).Cache().ColOfDict(); //busqueda de valores a mostrar en funcion del texto

            foreach (var item in list)
            {
                var v = (PersonaValues)ContainerApp.db.Values("persona").Set(item!);
                var o = item.Obj<Data_persona>();
                o.Label = v.ToString();
                personaOC.Add(o);
            }
        }

        private void PersonaComboBox_SelectionChanged(object sender, RoutedEventArgs e)
        {
            if (this.personaComboBox.SelectedIndex > -1)
            {
                var pgb = (Data_persona)personaGroupBox.DataContext;
                var pcb = (Data_persona)personaComboBox.SelectedItem;
                if (pgb.IsNoE() && !pgb.IsNoE() && pgb.id!.ToString().Equals(pcb.id))
                    return;

                var a = ContainerApp.db.Sql("alumno").Where("$persona = @0").Parameters(pcb.id!).Obj<Alumno>();

                SetPersonaGroupBox(pcb);
                SetAlumnoGroupBox(a);
                LoadAsignaciones(a);
                LoadCalificaciones(a);
                LoadCalificacionesArchivadas(a);
                LoadDisposiciones(a);
                LoadDetalles(pcb);
            }
            else
            {
                SetPersonaGroupBox();
                SetAlumnoGroupBox();
                asignacionOC.Clear();
                calificacionOC.Clear();
                disposicionOC.Clear();
                detallePersonaOC.Clear();
                calificacionArchivadaOC.Clear();
                this.personaComboBox.IsDropDownOpen = true;
            }
        }

        #region asignacionGroupBox
        private void ComisionComboBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            var cb = (ComboBox)sender;

            if (cb!.Text.IsNoE())
                cb.IsDropDownOpen = true;

            if (cb.SelectedIndex > -1)
            {
                if (cb.Text.Equals(((Data_comision_r)cb.SelectedItem).Label))
                    return;

                cb.Text = ""; //si hay seleccionado y cambio al texto, se blanquea el texto. Si no se incluye esta opción el texto se blanquea igual por defecto, pero tarda mas tiempo y es mas engorroso
            }

            asignacion = (Asignacion)cb.DataContext; //se carga la asignacion que esta siendo editada
            asignacion.SearchComision = cb.Text;

            if (typingTimerComision == null)
            {
                typingTimerComision = new DispatcherTimer();
                typingTimerComision.Interval = TimeSpan.FromMilliseconds(300);
                typingTimerComision.Tick += new EventHandler(ComisionComboBox_HandleTypingTimerTimeout);
            }

            typingTimerComision.Stop(); // Resets the timer
            typingTimerComision.Tag = asignacion.SearchComision; // This should be done with EventArgs
            typingTimerComision.Start();
        }

        private void ComisionComboBox_HandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer;
            if (timer == null)
                return;

            _ComisionComboBox_TextChanged();

            timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
        }

        private void _ComisionComboBox_TextChanged()
        {

            asignacion.Comisiones.Clear();

            if (asignacion.SearchComision.IsNoE() || asignacion.SearchComision.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            IEnumerable<Dictionary<string, object?>> list = comisionDAO.BusquedaAproximadaQuery(asignacion.SearchComision).Cache().ColOfDict(); //busqueda de valores a mostrar en funcion del texto
            foreach(var item in list)
            {
                var val = ContainerApp.db.Values("comision").Set(item);
                var obj = val.Values().Obj<Data_comision_r>();
                obj.Label = val.ToString();
                asignacion.Comisiones.Add(obj);
            }
        }

        private void ComisionComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            (sender as ComboBox)!.IsDropDownOpen = true;
        }

        private void ComisionComboBox_SelectionChanged(object sender, RoutedEventArgs e)
        {
            var cb = (ComboBox)sender;
            var asignacion = (Asignacion)cb.DataContext; //se carga la asignacion que esta siendo editada
            if (cb.SelectedIndex > -1) 
            {
                if(!cb.SelectedValue.ToString()!.Equals(asignacion.comision))
                    asignacion.comision = cb.SelectedValue.ToString();
                    asignacion.comision__Label = (cb.SelectedItem as Data_comision_r)!.Label;
            }
            else
            {
                asignacion.comision__Label = "";
                cb.IsDropDownOpen = true;
            }

        }

        private void AgregarAsignacion_Click(object sender, RoutedEventArgs e)
        {
            var a = new Asignacion(ContainerApp.db);
            var alumno = (Data_alumno)alumnoGroupBox.DataContext;
            a.alumno = alumno.id;
            asignacionOC.Add(a);
        }

        private void GuardarAsignacion_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var asignacion = (Data_alumno_comision)button!.DataContext;
            var p = ContainerApp.db.Persist();
            try
            {
                p.Persist("alumno_comision", asignacion).Exec().RemoveCache();
                MessageBox.Show("Registro realizado");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private void EliminarAsignacion_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var a = (Asignacion)button!.DataContext;
            try
            {
                if (!a.id.IsNoE())
                    ContainerApp.db.Persist().DeleteIds("alumno_comision", a.id!).Exec().RemoveCache();
                asignacionOC.Remove(a);

            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private async void AgregarAsignacionPF_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var a = (Asignacion)button!.DataContext;
            try
            {
                var persona = (Data_persona)personaGroupBox.DataContext;
                var values = ContainerApp.db.Values("persona").Set(persona);

                using (handler = ProgramaFines.NewHandler())
                {
                    using (client = new HttpClient(handler))
                    {
                        await ProgramaFines.PF_Login(client);

                        Dictionary<string, string> dataForm = new();

                        await ProgramaFines.PF_InscribirEstudianteValues(client, asignacion.comision__pfid!, values);

                        new ToastContentBuilder()
                                        .AddText("Inscripción PF")
                                        .AddText("Inscripción PF realizado correctamente")
                                    .Show();
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }
        #endregion asignacionGroupBox


        #region calificacionArchivadaGroupBox
        private bool CalificacionArchivadaCV_Filter(object obj)
        {
            var o = obj as Data_calificacion_r;
            return calificacionArchivadaFilterTextBox.Text.IsNoE()
                || (!o.asignatura_dis__nombre.IsNoE() && o.asignatura_dis__nombre.ToString().ToLower().Contains(calificacionArchivadaFilterTextBox.Text.ToLower()));

        }

        private void CalificacionArchivadaFilterTextBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            if (calificacionArchivadaTypingTimer == null)
            {
                calificacionArchivadaTypingTimer = new DispatcherTimer();
                calificacionArchivadaTypingTimer.Interval = TimeSpan.FromMilliseconds(300);
                calificacionArchivadaTypingTimer.Tick += new EventHandler(CalificacionArchivadaFilterTextBox_HandleTypingTimerTimeout);

            }

            calificacionArchivadaTypingTimer.Stop(); // Resets the timer
            calificacionArchivadaTypingTimer.Tag = (sender as TextBox).Text; // This should be done with EventArgs
            calificacionArchivadaTypingTimer.Start();
        }

        private void CalificacionArchivadaFilterTextBox_HandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer;
            if (timer == null)
                return;

            if (calificacionArchivadaCV != null)
                calificacionArchivadaCV.Refresh();

            timer.Stop();// The timer must be stopped! We want to act only once per keystroke.
        }
        #endregion

        #region calificacionGroupBox
        private bool CalificacionCV_Filter(object obj)
        {
            var o = obj as Data_calificacion_r;
            return calificacionFilterTextBox.Text.IsNoE()
                || (!o.asignatura_dis__nombre.IsNoE() && o.asignatura_dis__nombre.ToString().ToLower().Contains(calificacionFilterTextBox.Text.ToLower()));

        }

        private void CalificacionFilterTextBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            if (calificacionTypingTimer == null)
            {
                calificacionTypingTimer = new DispatcherTimer();
                calificacionTypingTimer.Interval = TimeSpan.FromMilliseconds(300);
                calificacionTypingTimer.Tick += new EventHandler(CalificacionFilterTextBox_HandleTypingTimerTimeout);

            }

            calificacionTypingTimer.Stop(); // Resets the timer
            calificacionTypingTimer.Tag = (sender as TextBox).Text; // This should be done with EventArgs
            calificacionTypingTimer.Start();
        }

        private void CalificacionFilterTextBox_HandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer;
            if (timer == null)
                return;

            if (calificacionCV != null)
                calificacionCV.Refresh();

            timer.Stop();// The timer must be stopped! We want to act only once per keystroke.
        }
        #endregion



        #region DisposicionGroupBox
        private void DisposicionComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            var cb = (ComboBox)sender;
            var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
                        
            calificacion.Disposiciones.Clear();
            calificacion.Disposiciones.AddRange(disposicionOC);            
        }

        private void DisposicionComboBox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            var cb = (ComboBox)sender;
            var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
            if (cb.SelectedIndex > -1)
            {
                if (!cb.SelectedValue.ToString()!.Equals(calificacion.disposicion))
                {
                    ContainerApp.db.Persist().UpdateValueIds("calificacion", "disposicion", cb.SelectedValue, calificacion.id!).Exec().RemoveCache();
                    calificacion.disposicion = (string)cb.SelectedValue;
                }

            }
        }

        private void DisposicionComboBox_LostFocus(object sender, RoutedEventArgs e)
        {
            var cb = (ComboBox)sender;
            var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
            cb.SelectedValue = calificacion.disposicion;
        }
        
        private void CursoComboBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            var cb = (ComboBox)sender;

            if (cb!.Text.IsNoE())
                cb.IsDropDownOpen = true;

            if (cb.SelectedIndex > -1)
            {
                if (cb.Text.Equals(((Data_curso_r)cb.SelectedItem).Label))
                    return;

                cb.Text = ""; //si hay seleccionado y cambio al texto, se blanquea el texto. Si no se incluye esta opción el texto se blanquea igual por defecto, pero tarda mas tiempo y es mas engorroso
            }

            calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
            calificacion.SearchCurso = cb.Text;

            if (cursoTypingTimer == null)
            {
                cursoTypingTimer = new DispatcherTimer();
                cursoTypingTimer.Interval = TimeSpan.FromMilliseconds(300);
                cursoTypingTimer.Tick += new EventHandler(CursoComboBox_HandleTypingTimerTimeout!);
            }

            cursoTypingTimer.Stop(); // Resets the timer
            cursoTypingTimer.Tag = calificacion.SearchCurso; // This should be done with EventArgs
            cursoTypingTimer.Start();
        }

        private void CursoComboBox_HandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer;
            if (timer == null)
                return;

            _CursoComboBox_TextChanged();

            timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
        }

        private void _CursoComboBox_TextChanged()
        {
            calificacion.Cursos.Clear();

            if (calificacion.SearchCurso.IsNoE() || calificacion.SearchCurso.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            IEnumerable<Dictionary<string, object?>> list = cursoDAO.BusquedaAproximadaQuery(calificacion.SearchCurso).Cache().ColOfDict(); //busqueda de valores a mostrar en funcion del texto
            foreach (var item in list)
            {
                var val = (CursoValues)ContainerApp.db.Values("curso").Set(item);
                var obj = val.Values().Obj<Data_curso_r>();
                obj.Label = val.ToStringDocente();
                calificacion.Cursos.Add(obj);
            }
        }
        private void CursoComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            (sender as ComboBox)!.IsDropDownOpen = true;
        }

        private void CursoComboBox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            var cb = (ComboBox)sender;
            var calificacion = (Calificacion)cb.DataContext; //se carga la asignacion que esta siendo editada
            if (cb.SelectedIndex > -1)
            {
                ContainerApp.db.Persist().UpdateValueIds("calificacion", "curso", cb.SelectedValue,  calificacion.id!).Exec().RemoveCache();
                calificacion.curso__Label = (cb.SelectedItem as Data_curso_r)!.Label;
            }

            else
            {
                calificacion.curso__Label = "";
                cb.IsDropDownOpen = true;
            }
        }


        private void CalificacionDataGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            string key = "";
            object? value = null;

            var column = e.Column as DataGridBoundColumn;
            if (column != null)
            {
                key = ((Binding)column.Binding).Path.Path; //column's binding
                value = (e.EditingElement as TextBox)!.Text;
            }

            var columnT = e.Column as DataGridTemplateColumn;
            if (columnT != null)
            {
                var datePicker = VisualTreeHelper.GetChild(e.EditingElement, 0) as DatePicker;
                if (datePicker != null)
                {
                    key = datePicker.Name;
                    value = datePicker.SelectedDate;
                }
            }

            if (key.IsNoE())
                return;

            ContainerApp.db.DataGridCellEditEndingEventArgs_CellEditEnding<Data_alumno_comision_r>(e, "calificacion", key, value);

        }
        #endregion

        private void DescargarArchivo_Click(object sender, RoutedEventArgs e)
        {
            var downloadPath = Path.Combine(Directory.GetCurrentDirectory(), ContainerApp.config.downloadPath);

            var dp = ((Hyperlink)e.OriginalSource).DataContext as DetallePersona;
            WebClient client = new WebClient();
            client.Credentials = new NetworkCredential(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword);

            SaveFileDialog saveFileDialog = new SaveFileDialog();
            saveFileDialog.InitialDirectory = downloadPath;
            saveFileDialog.RestoreDirectory = false;
            saveFileDialog.Title = "Descargar archivo de legajo";
            saveFileDialog.DefaultExt = Path.GetExtension(dp.archivo__name);
            saveFileDialog.FileName = dp.archivo__name;
            if (saveFileDialog.ShowDialog() == true)
                client.DownloadFile(ContainerApp.config.upload + dp.archivo__content, saveFileDialog.FileName);
        }

        private void BrowseButton_Click(object sender, RoutedEventArgs e)
        {
            Microsoft.Win32.OpenFileDialog openFileDlg = new Microsoft.Win32.OpenFileDialog();

            var button = (e.OriginalSource as Button);
            var dp = (DetallePersona)button!.DataContext;
            var alu = (Data_alumno)alumnoGroupBox.DataContext;
            bool? result = openFileDlg.ShowDialog();  // Launch OpenFileDialog by calling ShowDialog method

            if (result == true)
            {
                string year = DateTime.Now.Year.ToString();
                string month = DateTime.Now.Month.ToString();

                SqlOrganize.WebRequestUtils.Utils.CreateDirectoryIfNotExists(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword, ContainerApp.config.upload, year);
                SqlOrganize.WebRequestUtils.Utils.CreateDirectoryIfNotExists(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword, ContainerApp.config.upload + year + "/", month);

                string dir = year + "/" + month;
                FileInfo fileInfo = new(openFileDlg.FileName);

                var fileName = dp.archivo__id + fileInfo.Extension;
                
                try
                {
                    SqlOrganize.WebRequestUtils.Utils.UploadFile(ContainerApp.config.ftpUserName, ContainerApp.config.ftpUserPassword, ContainerApp.config.upload + dir + "/" + fileName, openFileDlg.FileName);
                    dp.archivo__name = openFileDlg.SafeFileName;
                    dp.archivo__content = dir + "/" + fileName;
                    dp.archivo__type = MimeTypeMap.GetMimeType(fileInfo.Extension);
                    dp.archivo__size = Convert.ToUInt32(fileInfo.Length);
                    dp.descripcion = dp.descripcion.IsNoE() ? dp.archivo__name : dp.descripcion;
                    dp.persona = alu.persona;

                    EntityValues archivoVal = ContainerApp.db.Values("file", "archivo").Set(dp).Default();
                    
                    ContainerApp.db.Persist().Persist(archivoVal)
                        .Persist("detalle_persona", dp)
                        .Transaction()
                        .RemoveCache();
                }
                catch (WebException ex)
                {
                    MessageBox.Show(ex.Message);
                }
            }
        }

        /// <summary>
        /// DataGrid Add Button v 2024-02
        /// </summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/68</remarks>
        private void AgregarArchivo_Click(object sender, RoutedEventArgs e)
        {
            var a = new DetallePersona(ContainerApp.db).DefaultData();
            a.DefaultRel("archivo");
            var alumno = (Data_alumno)alumnoGroupBox.DataContext;
            a.persona = alumno.id;
            detallePersonaOC.Add(a);
        }

        /// <summary>
        /// DataGrid Delete Button v 2024-02
        /// </summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/68#issuecomment-1878749790</remarks>
        private void EliminarDetallePersonaButton_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var data = (DetallePersona)button.DataContext;
            try
            {
                if (!data.id.IsNoE())
                    ContainerApp.db.Persist().
                        DeleteIds("detalle_persona", data.id!).
                        Exec().
                        RemoveCache();
                    
                detallePersonaOC.Remove(data);
                
                new ToastContentBuilder()
                    .AddText("Administración de Alumno")
                    .AddText("Legajo eliminado")
                    .Show();

            }
            catch (Exception ex)
            {
                new ToastContentBuilder()
                    .AddText("Administración de Alumno")
                    .AddText("Error al eliminar Legajo: " + ex.Message)
                    .Show();
            }
        }

        private void GenerarButton_Click(object sender, RoutedEventArgs e)
        {
        
            
            List<EntityPersist> persists = new();

            try
            {
                Data_alumno alumnoObj = (Data_alumno)alumnoGroupBox.DataContext;
                (ContainerApp.db.Values("alumno").
                    Set(alumnoObj) as AlumnoValues)!.GenerarCalificaciones();
                LoadCalificaciones(alumnoObj);
                LoadCalificacionesArchivadas(alumnoObj);
            }
            catch (Exception ex)
            {
                ToastUtils.ShowExceptionMessageWithFileNameAndLineNumber(ex);
            }
        }

        private void GenerarPedidoButton_Click(object sender, RoutedEventArgs e)
        {

            var persona = (Data_persona)personaGroupBox.DataContext;
            var html = descripcionTextBox.Text.ConvertTextToHtml();

            StringBuilder threads_body = new StringBuilder();
            threads_body.Append(persona.apellidos!.ToUpper());
            threads_body.Append(", ");
            threads_body.Append(persona.nombres!.ToTitleCase());
            threads_body.Append(" DNI N° ");
            threads_body.Append(persona.numero_documento);
            threads_body.Append("<br><br>");
            foreach (string text in descripcionTextBox.Text.Split("\r\n"))
            {
                threads_body.Append(html);
                threads_body.Append("<br>");
            }



            EntityValues ticketsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_tickets").Default().
               Set("subject", persona.apellidos!.ToUpper() + ", " + persona.nombres!.ToTitleCase() + ": " + tituloTextBox.Text).
               Set("status", 4). //cerrada
               Set("category", 2). //legajo
               Set("cust_24", persona.numero_documento).
               Set("cust_27", persona.telefono).
               Set("cust_28", comentarioTextBox.Text). //comentario
               Set("assigned_agent", "").Reset();

            EntityValues threadsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_threads").Default().
                Set("ticket", ticketsValues.Get("id")).
                Set("body", threads_body.ToString()).Reset();

            if (!ticketsValues.Check() && !threadsValues.Check())
            {
                throw new Exception("El chequeo de valores es incorrecto");
            }

            EntityPersist persist = ContainerApp.dbPedidos.Persist();

            persist.Insert(ticketsValues)
                .Insert(threadsValues)
                .Exec()
                .RemoveCache();


            var id = ticketsValues.Get("id").ToString();
            var authCode = ticketsValues.Get("auth_code").ToString();
            string url = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" + id + "&auth-code=" + authCode;

            new ToastContentBuilder()
                    .AddText("Se ha generado nuevo pedido con id " + id)
                    .Show();
        }

        #region eventos persona
        private void GuardarPersonaButton_Click(object sender, RoutedEventArgs e)
        {
            var persona = (Data_persona)personaGroupBox.DataContext;
            if (persona.Error.IsNoE())
            {
                var per = (Data_persona)personaGroupBox.DataContext;

                try
                {
                    ContainerApp.db.Persist().Persist("persona", per).Exec().RemoveCache();
                    var alu = (Data_alumno)alumnoGroupBox.DataContext;
                    MessageBox.Show("Registro de persona realizado");
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message);
                }
            }
            else
            {
                MessageBox.Show("Verificar formulario: " + persona.Error);
            }
            return;

        }


        private async void GuardarPfButton_Click(object sender, RoutedEventArgs e)
        {
            try
            {

                var persona = (Data_persona)personaGroupBox.DataContext;

                using (handler = ProgramaFines.NewHandler())
                {
                    using (client = new HttpClient(handler))
                    {
                        await ProgramaFines.PF_Login(client);

                        //Obtener datos del alumno del formulario de modificacion pf
                        IDictionary<string, string> dataForm = await ProgramaFines.PF_InfoAlumnoFormularioModificacion(client, persona.numero_documento);

                        if (!dataForm.ContainsKey("nombre"))
                            throw new Exception("No se pueden obtener datos del PF, debe revisarse desde el PF");

                        dataForm["nombre"] = persona.nombres!;
                        dataForm["apellido"] = persona.apellidos!;
                        dataForm["cuil1"] = persona.cuil1?.ToString() ?? "0";
                        dataForm["cuil2"] = persona.cuil2?.ToString() ?? "0";
                        dataForm["direccion"] = persona.descripcion_domicilio ?? "";
                        dataForm["departamento"] = persona.departamento ?? "";
                        dataForm["localidad"] = persona.localidad ?? "";
                        dataForm["partido"] = persona.partido ?? "";
                        dataForm["nacionalidad"] = persona.nacionalidad ?? "";
                        dataForm["email"] = persona.email ?? "";
                        dataForm["cod_area"] = persona.codigo_area ?? "";
                        dataForm["nro_telefono"] = persona.telefono ?? "";
                        if (!persona.fecha_nacimiento.IsNoE())
                        {
                            dataForm["dia_nac"] = ((DateTime)persona.fecha_nacimiento!).Day.ToString();
                            dataForm["mes_nac"] = ((DateTime)persona.fecha_nacimiento!).Month.ToString();
                            dataForm["ano_nac"] = ((DateTime)persona.fecha_nacimiento!).Year.ToString(); ;
                        }
                        dataForm["sexo"] = persona.sexo?.ToString() ?? "1";

                        await ProgramaFines.PF_ActualizarFormularioAlumno(client, dataForm);

                        new ToastContentBuilder()
                                        .AddText("Registro PF")
                                        .AddText("Registro PF realizado correctamente")
                                    .Show();
                    }
                }
            } catch (Exception ex)
            {
                ToastUtils.ShowExceptionMessageWithFileNameAndLineNumber(ex, "Error al actualizar PF");
            }
        }


        #endregion

        
    }

    public class EstadoData
    {
        public ObservableCollection<string> Estados()
        {
            return new()
            {
                "Activo",
                "No activo",
                "Mesa",
            };
        }
    }

  




}
