using Fines2Wpf.Data;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;
using Utils;

namespace Fines2Wpf.Windows.Comision.AdministrarComision
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        #region Autocomplete v2 sede
        private ObservableCollection<Data_sede_r> sedeOC = new(); //datos consultados de la base de datos
        private DispatcherTimer sedeTypingTimer; //timer para buscar
        private DAO.Sede sedeDAO = new();
        #endregion Autocomplete v2

        private ObservableCollection<Data_modalidad> modalidadOC = new();

        private ObservableCollection<Data_planificacion_r> planificacionOC = new();

        public Window1()
        {
            InitializeComponent();

            #region Autocomplete 2 sede
            sedeComboBox.ItemsSource = sedeOC;
            sedeComboBox.DisplayMemberPath = "Label";
            sedeComboBox.SelectedValuePath = "id";
            #endregion Autocomplete 2

            #region Autocomplete 2 sede (valor inicial)
            //en construccion
            #endregion Autocomplete 2

            #region turnoComboBox
            turnoComboBox.SelectedValuePath = "Key";
            turnoComboBox.DisplayMemberPath = "Value";
            turnoComboBox.Items.Add(new KeyValuePair<string?, string>(null, "(No especificado)")); //quitar esta linea si no permite valor null
            turnoComboBox.Items.Add(new KeyValuePair<string, string>("Mañana", "Mañana"));
            turnoComboBox.Items.Add(new KeyValuePair<string, string>("Tarde", "Tarde"));
            turnoComboBox.Items.Add(new KeyValuePair<string, string>("Vespertino", "Vespertino"));
            #endregion

            #region modalidadComboBox
            modalidadComboBox.ItemsSource = modalidadOC;
            modalidadComboBox.DisplayMemberPath = "nombre";
            modalidadComboBox.SelectedValuePath = "id";

            var data = ContainerApp.db.Query("modalidad").
                Order("$nombre").
                ColOfDictCache();

            modalidadOC.Clear();
            modalidadOC.AddRange(data);
            #endregion

            #region planificacionComboBox
            planificacionComboBox.ItemsSource = planificacionOC;
            planificacionComboBox.DisplayMemberPath = "Label";
            planificacionComboBox.SelectedValuePath = "id";

            data = ContainerApp.db.Query("planificacion").
                Order("$plan-distribucion_horaria DESC, $anio ASC, $semestre ASC").
                ColOfDictCache();

            planificacionOC.Clear();
            foreach (var item in data)
            {
                Data_planificacion_r obj = new();
                obj.SetData(item);
                obj.Label = obj.plan__distribucion_horaria + " " + obj.anio + "/" + obj.semestre;
                planificacionOC.Add(obj);
            }
            #endregion

        }

        private void GuardarComisionButton_Click(object sender, RoutedEventArgs e)
        {


        }


        /// <summary>Autocomplete 2 - GotFocus - 2023.11</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private void SedeComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            (sender as ComboBox).IsDropDownOpen = true;
        }

        /// <summary>Autocomplete 2 - TextChanged - v2023.11</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private void SedeComboBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            ComboBox cb = (sender as ComboBox);
            DispatcherTimer timer = sedeTypingTimer;

            if (cb.Text.IsNullOrEmpty())
                cb.IsDropDownOpen = true;
            if (cb.SelectedIndex > -1)
            {
                if (cb.Text.Equals(((Data_sede)cb.SelectedItem).Label))
                    return;
                cb.Text = "";
            }

            if (timer == null)
            {
                timer = new DispatcherTimer();
                timer.Interval = TimeSpan.FromMilliseconds(300);
                timer.Tick += new EventHandler(SedeHandleTypingTimerTimeout);
            }

            timer.Stop(); // Resets the timer
            timer.Tag = cb.Text; // This should be done with EventArgs
            timer.Start();
        }


        /// <summary>Autocomplete 2 - HandleTypingTimerTimeout - v2023.11</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790603741</remarks>
        private void SedeHandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer; // WPF
            if (timer == null)
                return;

            _SedeComboBox_TextChanged();

            // The timer must be stopped! We want to act only once per keystroke.
            timer.Stop();
        }

        // <summary>Autocomplete 2 - _TextChanged - v 2023.11</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790603853</remarks>
        private void _SedeComboBox_TextChanged()
        {

            sedeOC.Clear();
            string text = sedeComboBox.Text;

            if (string.IsNullOrEmpty(text) || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
            {
                return;
            }

            IEnumerable<Dictionary<string, object?>> list = sedeDAO.BusquedaAproximadaQuery(text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

            foreach (var item in list)
            {
                var o = new Data_sede_r();
                o.SetData(item);
                o.Label = o.numero + " " + o.nombre;
                sedeOC.Add(o);
            }
        }

        /// <summary>Autocomplete 2 - SelectionChanged- v 2023.11</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790603853</remarks>
        private void SedeComboBox_SelectionChanged(object sender, RoutedEventArgs e)
        {
            var cb = (ComboBox)sender;

            if (cb.SelectedIndex < 0)
            {
                cb.IsDropDownOpen = true;
                divisionesTextBox.Text = "";
            }

            else
            {
                IEnumerable<string> divisiones = ContainerApp.db.Query("comision").
                    Fields("$division").
                    Where("$sede = @0").
                    Parameters(cb.SelectedValue).
                    Column<string>(0);

                divisionesTextBox.Text = String.Join(", ", divisiones);
            }
        }
    }
}
