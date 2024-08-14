using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using WpfUtils;
using SqlOrganize;
using Org.BouncyCastle.Utilities;
using System.Collections.ObjectModel;
using WpfUtils.Controls;
using FinesApp.Views.TomasSemestre;
using Newtonsoft.Json;

namespace FinesApp.Views;

public partial class TomasSemestrePage : Page, INotifyPropertyChanged
{
    public TomasSemestrePage()
    {
        InitializeComponent();
        DataContext = this;

        cbxCalendario2.InitComboBoxConstructor(ocCalendario);
        var data = ContainerApp.db.Sql("calendario").Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, ocCalendario);

    }

    #region Pestaña Procesar Docentes PF
    private ObservableCollection<Data_calendario> ocCalendario = new();

    private void btnProcesarDocentesPF_Click(object sender, RoutedEventArgs e)
    {

        try { 
            if (cbxCalendario2.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var docentes = JsonConvert.DeserializeObject<List<DocentePF>>(tbxDocentesPF.Text)!;

            IEnumerable<string> pfidComisiones = ContainerApp.db.Sql("comision")
                .Size(0)
                .Where("$calendario = @0 AND $pfid IS NOT NULL")
                .Parameters(cbxCalendario2.SelectedValue).Column<string>("pfid");

            Data_calendario calObj = (Data_calendario)cbxCalendario2.SelectedItem;
            CalendarioValues calVal = ContainerApp.db.ToValues<CalendarioValues>(calObj);
            

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
    #endregion
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
