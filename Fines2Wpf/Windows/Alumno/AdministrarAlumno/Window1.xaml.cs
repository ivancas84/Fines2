using Fines2Wpf.DAO;
using Fines2Wpf.Model;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Globalization;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;
using Utils;


namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{


    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private DAO.Persona personaDAO = new(); //objeto de acceso a datos
        private ObservableCollection<Data_persona> personaOC = new(); //datos consultados de la base de datos
        private ObservableCollection<Data_resolucion_r> resolucionOC = new(); //datos consultados de la base de datos
        private ObservableCollection<Data_plan_r> planOC = new(); //datos consultados de la base de datos
        private DispatcherTimer _typingTimer;

        public Window1()
        {
            InitializeComponent();
            ContentRendered += Window1_ContentRendered;

            #region personaComboBox
            personaComboBox.ItemsSource = personaOC;
            personaComboBox.DisplayMemberPath = "Label";
            personaComboBox.SelectedValuePath = "id";
            personaGroupBox.DataContext = new Data_persona(SqlOrganize.DataInitMode.Default);
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
            var data = ContainerApp.db.Query("resolucion").Order("$numero ASC").ColOfDictCache();
            resolucionOC.Clear();
            resolucionOC.AddRange(data);
            #endregion

            #region plan
            planComboBox.SelectedValuePath = "id";
            planComboBox.DisplayMemberPath = "Label";
            planComboBox.ItemsSource = planOC;
            var dataPlan = ContainerApp.db.Query("plan").Order("$orientacion ASC").ColOfDictCache();

            planOC.Clear();
            foreach (var item in dataPlan)
            {
                var o = item.Obj<Data_plan_r>();
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



        }

        private void SetPersonaGroupBox(Data_persona? persona = null)
        {
            if (persona.IsNullOrEmpty())
            {
                persona = new Data_persona(SqlOrganize.DataInitMode.Default);
            }

            personaGroupBox.DataContext = persona;
            persona.Validate = true;

        }
        private void SetAlumnoGroupBox(Data_alumno? alumno = null)
        {
            if (alumno.IsNullOrEmpty())
            {
                alumno = new Data_alumno(SqlOrganize.DataInitMode.Default);
                var per = (Data_persona)personaGroupBox.DataContext;
                alumno.persona = per.id;
            }
            alumnoGroupBox.DataContext = alumno;
        }

        private void Window1_ContentRendered(object? sender, EventArgs e)
        {
            SetPersonaGroupBox();
            SetAlumnoGroupBox();
        }

        
        private void GuardarPersonaButton_Click(object sender, RoutedEventArgs e)
        {
            var persona = (Data_persona)personaGroupBox.DataContext;
            if (persona.Error.IsNullOrEmpty())
            {
                var per = (Data_persona)personaGroupBox.DataContext;
                EntityPersist p = ContainerApp.db.Persist("persona");
                try
                {
                    p.PersistObj(per).Exec().RemoveCache();
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

        private void GuardarAlumnoButton_Click(object sender, RoutedEventArgs e)
        {
            var alu = (Data_alumno)alumnoGroupBox.DataContext;
            
            EntityPersist p = ContainerApp.db.Persist("alumno");
            try
            {
                p.PersistObj(alu).Exec().RemoveCache();
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
            if (this.personaComboBox.Text.IsNullOrEmpty())
                personaComboBox.IsDropDownOpen = true;
            if (this.personaComboBox.SelectedIndex > -1)
            { 
                if (this.personaComboBox.Text.Equals(((Data_persona)this.personaComboBox.SelectedItem).Label))
                    return;
                else
                    this.personaComboBox.Text = "";
            }

            if (_typingTimer == null)
            {
                _typingTimer = new DispatcherTimer();
                _typingTimer.Interval = TimeSpan.FromMilliseconds(300);
                _typingTimer.Tick += new EventHandler(PersonaComboBox_HandleTypingTimerTimeout);
            }

            _typingTimer.Stop(); // Resets the timer
            _typingTimer.Tag = (sender as ComboBox).Text; // This should be done with EventArgs
            _typingTimer.Start();
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

            if (string.IsNullOrEmpty(this.personaComboBox.Text) || this.personaComboBox.Text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            IEnumerable<Dictionary<string, object>> list = personaDAO.SearchLikeQuery(this.personaComboBox.Text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto
            personaOC.Clear();

            foreach (var item in list)
            {
                var v = (Values.Persona)ContainerApp.db.Values("persona").Set(item!);
                var o = item.Obj<Data_persona>();
                o.Label = v.ToString();
                personaOC.Add(o);
            }
        }


        private void PersonaComboBox_SelectionChanged(object sender, RoutedEventArgs e)
        {
            if (this.personaComboBox.SelectedIndex > -1)
            {
                var p = (Data_persona)this.personaComboBox.SelectedItem;                
                var a = ContainerApp.db.Query("alumno").Where("$persona = @0").Parameters(p.id!).Obj<Data_alumno_r>();
                SetPersonaGroupBox(p);
                SetAlumnoGroupBox(a);
            } else
            {
                SetPersonaGroupBox();
                SetAlumnoGroupBox();
                this.personaComboBox.IsDropDownOpen = true;
            }
        }
    }

    
}
