using CommunityToolkit.WinUI.Notifications;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;

namespace Fines2Wpf.Windows.Comision.AdministrarComision
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        DAO.Curso cursoDAO = new();

        #region Autocomplete v2 sede
        private ObservableCollection<Data_sede_r> sedeOC = new(); //datos consultados de la base de datos
        private DispatcherTimer sedeTypingTimer; //timer para buscar
        private DAO.Sede sedeDAO = new();
        #endregion Autocomplete v2

        #region Autocomplete v2 comision
        private ObservableCollection<Data_comision_r> comisionOC = new(); //datos consultados de la base de datos
        private DispatcherTimer comisionTypingTimer; //timer para buscar
        private DAO.Comision comisionDAO = new();
        #endregion Autocomplete v2

        private ObservableCollection<Data_modalidad> modalidadOC = new();

        private ObservableCollection<Data_planificacion_r> planificacionOC = new();

        private ObservableCollection<Data_calendario> calendarioOC = new();

        private ObservableCollection<Data_curso_r> cursoOC = new();



        public Window1(string? idComision = null)
        {
            InitializeComponent();

            

            #region Autocomplete 2 sede
            sedeComboBox.ItemsSource = sedeOC;
            sedeComboBox.DisplayMemberPath = "Label";
            sedeComboBox.SelectedValuePath = "id";
            #endregion Autocomplete 2

            #region Autocomplete 2 comision
            comisionSiguienteComboBox.ItemsSource = comisionOC;
            comisionSiguienteComboBox.DisplayMemberPath = "Label";
            comisionSiguienteComboBox.SelectedValuePath = "id";
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

            var data = ContainerApp.db.Sql("modalidad").
                Order("$nombre").
                Cache().ColOfDict();

            ContainerApp.db.ClearAndAddDataToOC(data, modalidadOC);
            #endregion

            #region planificacionComboBox
            planificacionComboBox.ItemsSource = planificacionOC;
            planificacionComboBox.DisplayMemberPath = "Label";
            planificacionComboBox.SelectedValuePath = "id";

            data = ContainerApp.db.Sql("planificacion").
                Order("$plan-distribucion_horaria DESC, $anio ASC, $semestre ASC").
                Cache().ColOfDict();

            planificacionOC.Clear();
            foreach (var item in data)
            {
                var obj = item.Obj<Data_planificacion_r>();
                obj.Label = obj.plan__distribucion_horaria + " " + obj.anio + "/" + obj.semestre + " " + obj.plan__orientacion!.Acronym();
                planificacionOC.Add(obj);
            }
            #endregion

            #region calendarioComboBox
            calendarioComboBox.ItemsSource = calendarioOC;
            calendarioComboBox.DisplayMemberPath = "Label";
            calendarioComboBox.SelectedValuePath = "id";

            data = ContainerApp.db.Sql("calendario").
                Order("$anio DESC, $semestre DESC, $inicio DESC, $fin DESC, $descripcion ASC").
                Cache().ColOfDict();

            calendarioOC.Clear();
            foreach (var item in data)
            {
                Data_calendario obj = item.Obj<Data_calendario>();
                obj.Label = obj.anio.ToString() + "-" + obj.semestre.ToString() + " " + obj.descripcion;
                calendarioOC.Add(obj);
            }
            #endregion



            #region definir datos iniciales
            cursosDataGrid.ItemsSource = cursoOC;
            cursoOC.Clear();

            if (idComision.IsNoE()) { 
                comisionGroupBox.DataContext = ContainerApp.db.Data<Data_comision>();
            }
            else
            {
                var comision = ContainerApp.db.Sql("comision").Cache().Id(idComision!).Obj<Data_comision_r>();
                comisionGroupBox.DataContext = comision;
                sedeOC.Clear();
                var sedeInicial = new Data_sede_r();
                sedeInicial.id = comision.sede__id;
                sedeInicial.Label = comision.sede__numero + " " + comision.sede__nombre;
                sedeOC.Add(sedeInicial);

                comisionOC.Clear();
                if (!comision.comision_siguiente.IsNoE())
                {
                    var comisionSiguienteInicial = ContainerApp.db.Sql("comision").Cache().Id(comision.comision_siguiente!).Obj<Data_comision_r>();
                    comisionSiguienteInicial.Label = comisionSiguienteInicial.sede__numero + comisionSiguienteInicial.division + "/" + comisionSiguienteInicial.planificacion__anio + comisionSiguienteInicial.planificacion__semestre + " " + comisionSiguienteInicial.calendario__anio + "-" + comisionSiguienteInicial.calendario__semestre;
                    comisionOC.Add(comisionSiguienteInicial);
                }

                LoadCursos();
            }
            #endregion


        }

        private void LoadCursos()
        {
            var comision = (Data_comision)comisionGroupBox.DataContext;
            cursoOC.Clear();
            cursoOC.AddRange(cursoDAO.CursosDeComisionQuery(comision.id!).Cache().ColOfDict().ColOfObj<Data_curso_r>());

        }

        private void GuardarComisionButton_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                var comisionData = (Data_comision)comisionGroupBox.DataContext;
                EntityPersist persist = ContainerApp.db.Persist().
                    Persist("comision", comisionData).
                    Exec().
                    RemoveCache();
            }
            catch (Exception ex)
            {
                new ToastContentBuilder()
                    .AddText("Búsqueda de Causas del WS")
                    .AddText(ex.Message)
                    .Show();
            }


        }


        /// <summary>Autocomplete 2.1 - GotFocus</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private void ComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            (sender as ComboBox).IsDropDownOpen = true;
        }

        /// <summary>Autocomplete 2.1 - TextChangedCompare</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private bool ComboBox_TextChangedCompare(ComboBox cb, string? label)
        {
            if (cb.Text.IsNoE())
                cb.IsDropDownOpen = true;
            if (cb.SelectedIndex > -1)
            {
                if (cb.Text.Equals(label))
                    return false;
                cb.Text = "";
                return true;
            }

            return true;
        }

        /// <summary>Autocomplete 2.1 - TextChangedTimer</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private void ComboBox_TextChangedTimer(ComboBox cb, DispatcherTimer? timer, EventHandler e)
        {
            if (timer == null)
            {
                timer = new DispatcherTimer();
                timer.Interval = TimeSpan.FromMilliseconds(300);
                timer.Tick += e;
            }

            timer.Stop(); // Resets the timer
            timer.Tag = cb.Text; // This should be done with EventArgs
            timer.Start();
        }


        /// <summary>Autocomplete 2.1 - TextChanged</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private void SedeComboBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            DispatcherTimer timer = sedeTypingTimer;
            ComboBox cb = (sender as ComboBox)!;
            bool compare = ComboBox_TextChangedCompare(cb, ((Data_sede)cb.SelectedItem)?.Label ?? null);
            if (compare)
                ComboBox_TextChangedTimer(cb, timer, new EventHandler(SedeHandleTypingTimerTimeout!));
        }

        /// <summary>Autocomplete 2.1 - HandleTypingTimerTimeout</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790603741</remarks>
        private void SedeHandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer; // WPF
            if (timer == null)
                return;

            sedeOC.Clear();
            string text = sedeComboBox.Text;

            if (text.IsNoE() || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            IEnumerable<Dictionary<string, object?>> list = sedeDAO.BusquedaAproximadaQuery(text).Cache().ColOfDict(); //busqueda de valores a mostrar en funcion del texto

            foreach (var item in list)
            {
                var o = item.Obj<Data_sede_r>();
                o.Label = o.numero + " " + o.nombre;
                sedeOC.Add(o);
            }

            // The timer must be stopped! We want to act only once per keystroke.
            timer.Stop();
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
                IEnumerable<string> divisiones = ContainerApp.db.Sql("comision").
                    Fields("$division").
                    Where("$sede = @0").
                    Parameters(cb.SelectedValue).
                    Column<string>(0);

                divisionesTextBox.Text = String.Join(", ", divisiones);
            }
        }


        /// <summary>Autocomplete 2 - TextChanged - v2023.11</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private void ComisionComboBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            DispatcherTimer timer = comisionTypingTimer;
            ComboBox cb = (sender as ComboBox)!;
            bool compare = ComboBox_TextChangedCompare(cb, ((Data_comision_r)cb.SelectedItem)?.Label ?? null);
            if (compare)
                ComboBox_TextChangedTimer(cb, timer, new EventHandler(ComisionHandleTypingTimerTimeout!));
        }

        /// <summary>Autocomplete 2 - HandleTypingTimerTimeout - v2023.11</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790603741</remarks>
        private void ComisionHandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer; // WPF
            if (timer == null)
                return;

            comisionOC.Clear();
            string text = comisionSiguienteComboBox.Text;

            if (text.IsNoE() || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            IEnumerable<Dictionary<string, object?>> list = comisionDAO.BusquedaAproximadaQuery(text).Cache().ColOfDict(); //busqueda de valores a mostrar en funcion del texto

            foreach (var item in list)
            {
                Data_comision_r o = item.Obj<Data_comision_r>();
                o.Label = o.sede__numero + o.division + "/" + o.planificacion__anio + o.planificacion__semestre + " " + o.calendario__anio + "-" + o.calendario__semestre;
                comisionOC.Add(o);
            }

            // The timer must be stopped! We want to act only once per keystroke.
            timer.Stop();
        }


        /// <summary>Autocomplete 2.1 - SelectionChanged</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790603853</remarks>
        private void ComboBox_SelectionChanged(object sender, RoutedEventArgs e)
        {
            var cb = (ComboBox)sender;

            if (cb.SelectedIndex < 0)
                cb.IsDropDownOpen = true;
        }

        private void GenerarCursosButton_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                (ContainerApp.db.Values("comision").Set((Data_comision)comisionGroupBox.DataContext) as ComisionValues)!.GenerarCursos();
                LoadCursos();
            }catch(Exception ex) {
                new ToastContentBuilder()
                    .AddText(this.Title)
                    .AddText("Error: " + ex.Message)
                    .Show();
            }
        }
    }
}
