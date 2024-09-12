#nullable enable
using System.Windows.Threading;
using System.Windows.Input;
using SqlOrganize.Sql;
using SqlOrganize;
using System.Windows.Controls;
using System.Collections.ObjectModel;
using System.Windows.Data;
using System.ComponentModel;

namespace WpfUtils.Controls
{
    //<summary>Extensiones para ComboBox</summary>
    public static class ComboBoxUtils
    {
        public static void InitBooleanSiNoConstructor(this System.Windows.Controls.ComboBox comboBox)
        {
            comboBox.SelectedValuePath = "Key";
            comboBox.DisplayMemberPath = "Value";
            comboBox.Items.Add(new KeyValuePair<bool, string>(true, "Sí"));
            comboBox.Items.Add(new KeyValuePair<bool, string>(false, "No"));
        }

        public static void InitBooleanNullSiNoConstructor(this System.Windows.Controls.ComboBox comboBox)
        {
            comboBox.SelectedValuePath = "Key";
            comboBox.DisplayMemberPath = "Value";
            comboBox.Items.Add(new KeyValuePair<bool?, string>(null, "(Todos)"));
            comboBox.Items.Add(new KeyValuePair<bool, string>(true, "Sí"));
            comboBox.Items.Add(new KeyValuePair<bool, string>(false, "No"));
        }

        public static void SetKeyUp(this DispatcherTimer timer, KeyEventArgs e)
        {
            if (e.Key == Key.Left 
                || e.Key == Key.Right 
                || e.Key == Key.Up 
                || e.Key == Key.LeftShift 
                || e.Key == Key.RightShift
                || e.Key == Key.LeftCtrl
                || e.Key == Key.RightCtrl
                || e.Key == Key.Home
                || e.Key == Key.Back
                || e.Key == Key.End
                || e.Key == Key.LeftAlt
                || e.Key == Key.RightAlt
                || e.Key == Key.Tab)
               return; // Skip navigation keys

            if (Keyboard.IsKeyDown(Key.LeftCtrl) || Keyboard.IsKeyDown(Key.RightCtrl))
                return; // Skip navigation keys

            timer.Stop();
            timer.Start();
        }

        public static void InitComboBoxConstructor<T>(this System.Windows.Controls.ComboBox comboBox, ObservableCollection<T> oc, string displayMemberPath = "Label", string selectedValuePath = "id") where T : EntityData
        {
            comboBox.ItemsSource = oc;
            comboBox.SelectedValuePath = selectedValuePath;
            comboBox.DisplayMemberPath = displayMemberPath;
        }


        /// <summary>Inicializacion de autocomplete combobox</summary>
        public static (string?, TextBox?, int?) SetTimerTickInitializeItem<T>(this System.Windows.Controls.ComboBox comboBox, DispatcherTimer typingTimer, string propertyName = "Label") where T : EntityData
        {
            typingTimer.Stop();
            string? text = comboBox.Text;

            if (comboBox.SelectedIndex > -1)
                if (text == ((T)comboBox.SelectedItem).GetPropertyValue(propertyName)?.ToString())
                    return (null, null, null); //si el texto actual es el mismo que el label seleccionado no efectuamos ninguna busqueda

            comboBox.SelectedIndex = -1;

            if (text.IsNoE() || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return (null, null, null);

            var textBox = (TextBox)comboBox.Template.FindName("PART_EditableTextBox", comboBox);
            int textBoxPos = textBox.CaretIndex;

            return (text, textBox, textBoxPos);
        }


      
        /// <summary>Inicializacion de autocomplete combobox</summary>
        /// <example>(string? text, TextBox? textBox, int? textBoxPos) = temaComboBox.InitializeAutocompleteValue(temaTypingTimer)</example>
        public static (string?, TextBox?, int?) SetTimerTickInitializeValue(this System.Windows.Controls.ComboBox comboBox, DispatcherTimer typingTimer)
        {
            typingTimer.Stop();
            string? text = comboBox.Text;

            if (comboBox.SelectedIndex > -1)
                if (text == comboBox.SelectedValue.ToString())
                    return (null, null, null); //si el texto actual es el mismo que el label seleccionado no efectuamos ninguna busqueda

            //comboBox.SelectedIndex = -1;

            if (text.IsNoE() || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return (null, null, null);


          
            var textBox = (TextBox)comboBox.Template.FindName("PART_EditableTextBox", comboBox);
            int textBoxPos = textBox.CaretIndex;


            return (text, textBox, textBoxPos);
        }

        public static void SetTimerTickFinalize(this System.Windows.Controls.ComboBox comboBox, TextBox textBox, string text, int textBoxPos)
        {
            comboBox.IsDropDownOpen = true;
            comboBox.Text = text;
            textBox.CaretIndex = textBoxPos;
        }


        public static void ComboBoxUpdateSelectedValue(this Db db, object sender, string entityName, string fieldName)
        {
            var cb = (ComboBox)sender;
            string actualValue = cb.DataContext.GetPropertyValue(fieldName).ToString();
            if (cb.SelectedIndex < 0 && cb.SelectedValue.ToString().Equals(actualValue))
                return;
            db.Persist().UpdateValueIds(entityName, fieldName, cb.SelectedValue, cb.DataContext.GetPropertyValue("id")!).Exec().RemoveCache();
        }

        #region filter con delay v1
        public static (ICollectionView cv, DispatcherTimer timer) FilterTextBox_InitializeConstructor<T>(this DataGrid dg, ObservableCollection<T> oc) where T : EntityData
        {
            var cvs = new CollectionViewSource() { Source = oc };
            ICollectionView cv = cvs.View;
            dg.ItemsSource = cv;
            DispatcherTimer timer = new DispatcherTimer();
            timer.Interval = TimeSpan.FromMilliseconds(300);
            return (cv, timer);
        }

        public static void FilterTextBox_TextChanged(this DispatcherTimer timer, object sender)
        {
            timer.Stop(); // Resets the timer
            timer.Tag = (sender as TextBox)!.Text; // This should be done with EventArgs
            timer.Start();
        }

        public static void FilterTextBox_HandleTypingTimerTimeout(this ICollectionView cv, object sender)
        {
            var timer = sender as DispatcherTimer;
            if (timer == null)
                return;


            if (cv != null)
                cv.Refresh();

            timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
        }


        
        #endregion


    }
}
