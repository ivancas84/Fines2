using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;
using WpfUtils.Controls;
using WpfUtils.Fines;
using SqlOrganize.Sql;

namespace FinesApp.Views;

public partial class AsignacionesSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Data_calendario> ocCalendario = new();
    private ObservableCollection<Data_alumno_comision_r> ocAsignacion = new();

    public AsignacionesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;

        dgdAsignacion.ItemsSource = ocAsignacion;
        ContainerApp.db.InitComboBoxConstructorCalendario(cbxCalendario, ocCalendario);
    }

    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        if (cbxCalendario.SelectedIndex < 0)
            return;

        var asignacionesData = ContainerApp.db.AsignacionesDeComisionesAutorizadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(asignacionesData, ocAsignacion);
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
