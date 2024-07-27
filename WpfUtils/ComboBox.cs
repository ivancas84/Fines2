#nullable enable
using System.Windows.Threading;
using System.Windows.Input;
using SqlOrganize.Sql;
using SqlOrganize;
using static System.Net.Mime.MediaTypeNames;
using System.Windows.Controls;
using System.Collections.ObjectModel;

namespace WpfUtils.ComboBox
{
    //<summary>Extensiones para ComboBox</summary>
    public static class Utils
    {
        public static void InitBooleanSiNo(System.Windows.Controls.ComboBox comboBox)
        {
            #region pendienteComboBox
            comboBox.SelectedValuePath = "Key";
            comboBox.DisplayMemberPath = "Value";
            comboBox.Items.Add(new KeyValuePair<bool, string>(true, "Sí"));
            comboBox.Items.Add(new KeyValuePair<bool, string>(false, "No"));
            #endregion
        }

        public static void InitBooleanNullSiNo(System.Windows.Controls.ComboBox comboBox)
        {
            #region pendienteComboBox
            comboBox.SelectedValuePath = "Key";
            comboBox.DisplayMemberPath = "Value";
            comboBox.Items.Add(new KeyValuePair<bool?, string>(null, "(Todos)"));
            comboBox.Items.Add(new KeyValuePair<bool, string>(true, "Sí"));
            comboBox.Items.Add(new KeyValuePair<bool, string>(false, "No"));
            #endregion
        }

        public static void SetKeyUp(this DispatcherTimer timer, KeyEventArgs e)
        {
            if (e.Key == Key.Up || e.Key == Key.Down || e.Key == Key.Enter || e.Key == Key.LeftShift || e.Key == Key.RightShift || e.Key == Key.Home || e.Key == Key.End)
                return; // Skip navigation keys


            timer.Stop();
            timer.Start();
        }

        /// <summary>
        /// Comportamiento general para inicializar un autocomplete Item en el constructor
        /// </summary>
        public static void InitializeAutocompleteItemConstructor<T>(this System.Windows.Controls.ComboBox comboBox, ObservableCollection<T> oc, string valuePath = "id", string memberPath = "Label")
        {
            comboBox.ItemsSource = oc;
            comboBox.DisplayMemberPath = memberPath;
            comboBox.SelectedValuePath = valuePath;
        }

        /// <summary>Inicializacion de autocomplete combobox</summary>
        public static string? InitializeAutocompleteItem<T>(this System.Windows.Controls.ComboBox comboBox, DispatcherTimer typingTimer, string propertyName = "Label") where T : Data
        {
            typingTimer.Stop();
            string? text = comboBox.Text;

            if (comboBox.SelectedIndex > -1)
                if (text == ((T)comboBox.SelectedItem).GetPropertyValue(propertyName))
                    return null; //si el texto actual es el mismo que el label seleccionado no efectuamos ninguna busqueda

            comboBox.SelectedIndex = -1;

            if (text.IsNoE() || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return null;

            return text;
        }


        /// <summary>Inicializacion de autocomplete combobox</summary>
        public static string? InitializeAutocompleteValue(this System.Windows.Controls.ComboBox comboBox, DispatcherTimer typingTimer)
        {
            typingTimer.Stop();
            string? text = comboBox.Text;

            if (comboBox.SelectedIndex > -1)
                if (text == comboBox.SelectedValue.ToString())
                    return null; //si el texto actual es el mismo que el label seleccionado no efectuamos ninguna busqueda

            //comboBox.SelectedIndex = -1;

            if (text.IsNoE() || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return null;

            return text;
        }

        public static void FinalizeAutocomplete(this System.Windows.Controls.ComboBox comboBox, string text)
        {
            var textBox = (TextBox)comboBox.Template.FindName("PART_EditableTextBox", comboBox);

            comboBox.IsDropDownOpen = true;
            comboBox.Text = text;

            textBox.CaretIndex = text.Length; //posicionarlse al final del texto
        }

    }
}
