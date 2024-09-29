using SqlOrganize;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using System.Windows.Threading;
using WpfUtils;
using WpfUtils.Controls;


namespace FinesApp.Views;

public partial class TransferirAlumnoPage : Page, INotifyPropertyChanged
{
    #region Autocomplete v3 - origen
    private ObservableCollection<Persona> origenOC = new(); //datos consultados de la base de datos
    private DispatcherTimer origenTypingTimer; //timer para buscar
    #endregion

    #region Autocomplete v3 - destino
    private ObservableCollection<Persona> destinoOC = new(); //datos consultados de la base de datos
    private DispatcherTimer destinoTypingTimer; //timer para buscar
    #endregion

    public TransferirAlumnoPage()
    {
        InitializeComponent();
        DataContext = this;

        #region Autocomplete v3 - origen
        origenComboBox.InitComboBoxConstructor(origenOC);
        origenTypingTimer = new DispatcherTimer { Interval = TimeSpan.FromMilliseconds(300) };
        origenTypingTimer.Tick += OrigenTimer_Tick;
        #endregion

        #region Autocomplete v3 - destino
        destinoComboBox.InitComboBoxConstructor(destinoOC);
        destinoTypingTimer = new DispatcherTimer{ Interval = TimeSpan.FromMilliseconds(300) };
        destinoTypingTimer.Tick += DestinoTimer_Tick;
        #endregion
    }

    #region Autocomplete v3 - origen
    private void OrigenTimer_Tick(object sender, EventArgs e)
    {
        try
        {
            (string? text, TextBox? origenTextBox, int? origenTextBoxPos) = origenComboBox.SetTimerTickInitializeItem<Persona>(origenTypingTimer);
            if (text == null)
                return;

            IEnumerable<Dictionary<string, object?>> list = Context.db.PersonaSearchLikeQuery(text).Dicts(); //busqueda de valores a mostrar en funcion del texto

            Context.db.AddEntityToClearOC(list, origenOC);

            origenComboBox.SetTimerTickFinalize(origenTextBox!, text, (int)origenTextBoxPos!);
        }
        catch (Exception ex)
        {
            ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex);
        }
    }

    private void OrigenAutoCompleteComboBox_KeyUp(object sender, KeyEventArgs e)
    {
        origenTypingTimer.SetKeyUp(e);
    }
    #endregion

    #region Autocomplete v3 - origen
    private void DestinoTimer_Tick(object sender, EventArgs e)
    {
        try
        {
            (string? text, TextBox? destinoTextBox, int? destinoTextBoxPos) = destinoComboBox.SetTimerTickInitializeItem<Persona>(destinoTypingTimer);
            if (text == null)
                return;

            var textBox = (TextBox)destinoComboBox.Template.FindName("PART_EditableTextBox", destinoComboBox);

            IEnumerable<Dictionary<string, object?>> list = Context.db.PersonaSearchLikeQuery(text).Dicts(); //busqueda de valores a mostrar en funcion del texto

            Context.db.AddEntityToClearOC(list, destinoOC);

            destinoComboBox.SetTimerTickFinalize(destinoTextBox!, text, (int)destinoTextBoxPos!);
        }
        catch (Exception ex)
        {
            ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex);
        }
    }

    private void DestinoAutoCompleteComboBox_KeyUp(object sender, KeyEventArgs e)
    {
        destinoTypingTimer.SetKeyUp(e);
    }
    #endregion


    private void TransferirButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            using (Context.db.CreateQueue())
            {
                var personaOrigenObj = (Persona)origenComboBox.SelectedItem;
                var personaDestinoObj = (Persona)destinoComboBox.SelectedItem;

                if (personaOrigenObj.IsNoE() || personaDestinoObj.IsNoE())
                    throw new Exception("Debe seleccionar ambas personas");



                List<Field> fieldsOmPersona = Context.db.Entity("persona").FieldsOm();
                PersistContext persist = Context.db.Persist();
                persist.TransferOm("persona", personaOrigenObj.id!, personaDestinoObj.id!);

                IDictionary<string, object?>? alumnoOrigenData = Context.db.Sql("alumno").Where("$persona = @0").Param("@0", personaOrigenObj.id!).Dict();
                IDictionary<string, object?>? alumnoDestinoData = Context.db.Sql("alumno").Where("$persona = @0").Param("@0", personaDestinoObj.id!).Dict();

                if (!alumnoOrigenData.IsNoE())
                {
                    if (alumnoDestinoData.IsNoE())
                    {
                        persist.UpdateFieldIds("alumno", "persona", personaOrigenObj.id!, personaDestinoObj.id!);
                    }
                    else
                    {
                        persist.TransferOm("alumno", alumnoOrigenData["id"]!, alumnoDestinoData["id"]!);
                        persist.DeleteIds("alumno", alumnoOrigenData["id"]!);

                    }
                }
                persist.DeleteIds("persona", personaOrigenObj.id!);

                Context.db.ProcessQueue();
                ToastExtensions.Show("Transferencia realizada");
            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }

    }


    #region INotifyPropertyChanged
    public event PropertyChangedEventHandler PropertyChanged;

    private void Set<T>(ref T storage, T value, [CallerMemberName] string propertyName = null)
    {
        if (Equals(storage, value))
        {
            return;
        }

        storage = value;
        OnPropertyChanged(propertyName);
    }

    private void OnPropertyChanged(string propertyName) => PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    #endregion
}
