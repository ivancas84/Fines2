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
    private ObservableCollection<Data_persona> origenOC = new(); //datos consultados de la base de datos
    private DispatcherTimer origenTypingTimer; //timer para buscar
    #endregion

    #region Autocomplete v3 - destino
    private ObservableCollection<Data_persona> destinoOC = new(); //datos consultados de la base de datos
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
            (string? text, TextBox? origenTextBox, int? origenTextBoxPos) = origenComboBox.SetTimerTickInitializeItem<Data_persona>(origenTypingTimer);
            if (text == null)
                return;

            IEnumerable<Dictionary<string, object?>> list = ContainerApp.db.PersonaSearchLikeQuery(text).ColOfDict(); //busqueda de valores a mostrar en funcion del texto

            ContainerApp.db.ClearAndAddDataToOC(list, origenOC);

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
            (string? text, TextBox? destinoTextBox, int? destinoTextBoxPos) = destinoComboBox.SetTimerTickInitializeItem<Data_persona>(destinoTypingTimer);
            if (text == null)
                return;

            var textBox = (TextBox)destinoComboBox.Template.FindName("PART_EditableTextBox", destinoComboBox);

            IEnumerable<Dictionary<string, object?>> list = ContainerApp.db.PersonaSearchLikeQuery(text).ColOfDict(); //busqueda de valores a mostrar en funcion del texto

            ContainerApp.db.ClearAndAddDataToOC(list, destinoOC);

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
            var personaOrigenObj = (Data_persona)origenComboBox.SelectedItem;
            var personaDestinoObj = (Data_persona)destinoComboBox.SelectedItem;

            if (personaOrigenObj.IsNoE() || personaDestinoObj.IsNoE())
                throw new Exception("Debe seleccionar ambas personas");


            List<EntityPersist> persists = new();

            List<Field> fieldsOmPersona = ContainerApp.db.Entity("persona").FieldsOm();
            ContainerApp.db.Persist().TransferOm("persona", personaOrigenObj.id!, personaDestinoObj.id!).AddToIfSql(persists);

            IDictionary<string, object?>? alumnoOrigenData = ContainerApp.db.Sql("alumno").Where("$persona = @0").Parameters(personaOrigenObj.id!).Dict();
            IDictionary<string, object?>? alumnoDestinoData = ContainerApp.db.Sql("alumno").Where("$persona = @0").Parameters(personaDestinoObj.id!).Dict();

            if (!alumnoOrigenData.IsNoE())
            {
                if (alumnoDestinoData.IsNoE())
                {
                    ContainerApp.db.Persist().UpdateValueIds("alumno", "persona", personaOrigenObj.id!, personaDestinoObj.id!).AddToIfSql(persists);
                }
                else
                {
                    ContainerApp.db.Persist().TransferOm("alumno", alumnoOrigenData["id"]!, alumnoDestinoData["id"]!).AddToIfSql(persists);
                    ContainerApp.db.Persist().DeleteIds("alumno", alumnoOrigenData["id"]!).AddToIfSql(persists);

                }
            }
            ContainerApp.db.Persist().DeleteIds("persona", personaOrigenObj.id!).AddToIfSql(persists);
            persists.Transaction().RemoveCache();
            ToastExtensions.Show("Transferencia realizada");
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
