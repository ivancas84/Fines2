using Fines2Wpf.Model;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using System.Windows.Threading;
using Utils;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesComision
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private ObservableCollection<Data_calendario> calendarioOC = new(); //datos consultados de la base de datos
        private ObservableCollection<Data_persona> cursoOC = new(); //datos consultados de la base de datos
        private DispatcherTimer cursoTypingTimer;
        private DAO.Curso cursoDAO = new();

        public Window1()
        {
            InitializeComponent();

            #region calendarioComboBox
            calendarioComboBox.ItemsSource = calendarioOC;
            calendarioComboBox.DisplayMemberPath = "Label";
            calendarioComboBox.SelectedValuePath = "id";

            var data = ContainerApp.db.Query("calendario").ColOfDictCache();
            calendarioOC.Clear();
            foreach(var item in data)
            {
                var cal = item.Obj<Data_calendario>();
                cal.Label = ContainerApp.db.Values("calendario").ToString();
                calendarioOC.Add(cal);
            }
            #endregion

            #region cursoComboBox
            cursoComboBox.ItemsSource = cursoOC;
            cursoComboBox.DisplayMemberPath = "Label";
            cursoComboBox.SelectedValuePath = "id";
            #endregion
        }

        private void CursoComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            cursoComboBox.IsDropDownOpen = true;
        }

        private void CursoComboBox_TextChanged(object sender, TextChangedEventArgs e)
        { 
            if (cursoComboBox.Text.IsNullOrEmpty())
                cursoComboBox.IsDropDownOpen = true;
            
            if (cursoComboBox.SelectedIndex > -1)
            {
                if (this.cursoComboBox.Text.Equals(((Data_curso)this.cursoComboBox.SelectedItem).Label))
                return;
            
                cursoComboBox.Text = "";
            }

            if (cursoTypingTimer == null)
            {
                cursoTypingTimer = new DispatcherTimer();
                cursoTypingTimer.Interval = TimeSpan.FromMilliseconds(300);
                cursoTypingTimer.Tick += new EventHandler(CursoHandleTypingTimerTimeout);
            }

            cursoTypingTimer.Stop(); // Resets the timer
            cursoTypingTimer.Tag = (sender as ComboBox).Text; // This should be done with EventArgs
            cursoTypingTimer.Start();
        }

        private void CursoHandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer; // WPF
            if (timer == null)
                return;

            _CursoComboBox_TextChanged();

            // The timer must be stopped! We want to act only once per keystroke.
            timer.Stop();
        }

        private void _CursoComboBox_TextChanged()
        {

            cursoOC.Clear();

            if (string.IsNullOrEmpty(cursoComboBox.Text) || cursoComboBox.Text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
            {
                return;
            }

            IEnumerable<Dictionary<string, object>> list = cursoDAO.SearchLikeQuery(cursoComboBox.Text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

            foreach (var item in list)
            {
                var v = (Values.Persona)ContainerApp.db.Values("persona").Set(item!);
                var o = item.Obj<Data_persona>();
                o.Label = v.ToString();
                personaData.Add(o);
            }
        }

        private void CursoComboBox_SelectionChanged(object sender, RoutedEventArgs e)
        {

        }
    }
}
