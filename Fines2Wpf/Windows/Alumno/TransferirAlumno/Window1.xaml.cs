using Fines2Model3.Data;
using Fines2Model3.DAO;
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
using SqlOrganize;
using WpfUtils;

namespace Fines2Wpf.Windows.Alumno.TransferirAlumno
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        #region Autocomplete v3 - origen
        private ObservableCollection<Data_persona> origenOC = new(); //datos consultados de la base de datos
        private DispatcherTimer origenTypingTimer; //timer para buscar
        #endregion

        #region Autocomplete v3 - destino
        private ObservableCollection<Data_persona> destinoOC = new(); //datos consultados de la base de datos
        private DispatcherTimer destinoTypingTimer; //timer para buscar
        #endregion

        public Window1()
        {
            InitializeComponent();

            #region Autocomplete v3 - origen
            origenComboBox.ItemsSource = origenOC;
            origenComboBox.DisplayMemberPath = "Label";
            origenComboBox.SelectedValuePath = "id";
            origenTypingTimer = new DispatcherTimer
            {
                Interval = TimeSpan.FromMilliseconds(300)
            };
            origenTypingTimer.Tick += OrigenTimer_Tick;
            #endregion

            #region Autocomplete v3 - destino
            destinoComboBox.ItemsSource = destinoOC;
            destinoComboBox.DisplayMemberPath = "Label";
            destinoComboBox.SelectedValuePath = "id";
            destinoTypingTimer = new DispatcherTimer
            {
                Interval = TimeSpan.FromMilliseconds(300)
            };
            destinoTypingTimer.Tick += DestinoTimer_Tick;
            #endregion
        }

        #region Autocomplete v3 - origen
        private void OrigenTimer_Tick(object sender, EventArgs e)
        {
            origenTypingTimer.Stop();
            string text = origenComboBox.Text;

            if (origenComboBox.SelectedIndex > -1)
                if (text == ((Data_persona)origenComboBox.SelectedItem).Label)
                    return; //si el texto es igual al Label no se realiza la busqueda

            if (string.IsNullOrEmpty(text) || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            var textBox = (TextBox)origenComboBox.Template.FindName("PART_EditableTextBox", origenComboBox);

            IEnumerable<Dictionary<string, object?>> list = ContainerApp.db.PersonaSearchLikeQuery(text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

            origenOC.Clear(); //al vaciar, si existe elemento seleccionado, se borra el texto por eso
            foreach (var item in list)
            {
                var o = item.Obj<Data_persona>();
                o.Label = o.nombres + " " + o.apellidos + " " + o.numero_documento + " " + o.cuil;
                origenOC.Add(o);
            }

            origenComboBox.IsDropDownOpen = true;
            origenComboBox.Text = text;

            textBox.CaretIndex = text.Length; //posicionarlse al final del texto
        }

        private void OrigenAutoCompleteComboBox_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Up || e.Key == Key.Down || e.Key == Key.Enter || e.Key == Key.LeftShift || e.Key == Key.RightShift || e.Key == Key.Home || e.Key == Key.End)
                return; // Skip navigation keys


            origenTypingTimer.Stop();
            origenTypingTimer.Start();
        }
        #endregion

        #region Autocomplete v3 - destino
        private void DestinoTimer_Tick(object sender, EventArgs e)
        {
            destinoTypingTimer.Stop();
            string text = destinoComboBox.Text;

            if (destinoComboBox.SelectedIndex > -1)
                if (text == ((Data_persona)destinoComboBox.SelectedItem).Label)
                    return; //si el texto es igual al Label no se realiza la busqueda

            if (string.IsNullOrEmpty(text) || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            var textBox = (TextBox)destinoComboBox.Template.FindName("PART_EditableTextBox", destinoComboBox);

            IEnumerable<Dictionary<string, object?>> list = ContainerApp.db.PersonaSearchLikeQuery(text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

            destinoOC.Clear(); //al vaciar, si existe elemento seleccionado, se borra el texto por eso
            foreach (var item in list)
            {
                var o = item.Obj<Data_persona>();
                o.Label = o.nombres + " " + o.apellidos + " " + o.numero_documento + " " + o.cuil;
                destinoOC.Add(o);

            }

            destinoComboBox.IsDropDownOpen = true;
            destinoComboBox.Text = text;

            textBox.CaretIndex = text.Length; //posicionarlse al final del texto
        }

        private void DestinoAutoCompleteComboBox_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Up || e.Key == Key.Down || e.Key == Key.Enter || e.Key == Key.LeftShift || e.Key == Key.RightShift || e.Key == Key.Home || e.Key == Key.End)
                return; // Skip navigation keys


            destinoTypingTimer.Stop();
            destinoTypingTimer.Start();
        }
        #endregion

        private void TransferirButton_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                var personaOrigenObj = (Data_persona)origenComboBox.SelectedItem;
                var personaDestinoObj = (Data_persona)destinoComboBox.SelectedItem;

                if (personaOrigenObj.IsNullOrEmptyOrDbNull() || personaDestinoObj.IsNullOrEmptyOrDbNull())
                    throw new Exception("Debe seleccionar ambas personas");

                var persist = ContainerApp.db.Persist();
                List < Field > fieldsOmPersona = ContainerApp.db.Entity("persona").FieldsOm();
                persist.TransferOm("persona",  personaDestinoObj.id!, personaOrigenObj.id!);

                IDictionary<string, object?>? alumnoOrigenData = ContainerApp.db.Sql("alumno").Where("$persona = @0").Parameters(personaOrigenObj.id!).Dict();
                IDictionary<string, object?>? alumnoDestinoData = ContainerApp.db.Sql("alumno").Where("$persona = @0").Parameters(personaDestinoObj.id!).Dict();

                if (!alumnoOrigenData.IsNullOrEmptyOrDbNull())
                {
                    if (alumnoDestinoData.IsNullOrEmptyOrDbNull())
                    { 
                        persist.UpdateValueIds("alumno", "persona", personaOrigenObj.id!, personaDestinoObj.id!);
                    }
                    else
                    {
                        persist.TransferOm("alumno", alumnoOrigenData["id"]!, alumnoDestinoData["id"]!);
                        persist.DeleteIds("alumno", alumnoOrigenData["id"]!);

                    }
                }
                persist.DeleteIds("persona", personaOrigenObj.id!);
                persist.Transaction().RemoveCache();
            } 
            catch(Exception ex)
            {
                ToastUtils.ShowExceptionMessageWithFileNameAndLineNumber(ex);
            }
            
        }
    }
}
