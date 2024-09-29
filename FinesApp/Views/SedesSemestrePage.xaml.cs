using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;
using SqlOrganize.Sql;
using WpfUtils.Controls;
using CommunityToolkit.WinUI.Notifications;
using Org.BouncyCastle.Asn1.Ocsp;
using System.Windows;
using SqlOrganize.CollectionUtils;
using WpfUtils;

namespace FinesApp.Views;

public partial class SedesSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Calendario> ocCalendario = new();
    private ObservableCollection<Sede> ocSede = new();

    public SedesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;

        #region cbxCalendario
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        var data = Context.db.Sql("calendario").Cache().Dicts();
        Context.db.AddEntityToClearOC(data, ocCalendario);
        #endregion

        dgdSedes.ItemsSource = ocSede;
    }

    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if(cbxCalendario.SelectedIndex == -1)
                throw new Exception("Debe seleccionar calendario");

            IEnumerable<object> idSedes = ComisionDAO.ComisionesAutorizadasDeCalendarioSql(cbxCalendario.SelectedValue).
                Cache().Dicts().ColOfVal<object>("sede");

            var sedeData = Context.db.Sql("sede").Where("$id IN (@0)").
                Order("$nombre ASC").Param("@0", idSedes).Cache().Dicts();
            Context.db.AddEntityToClearOC(sedeData, ocSede);

            ToastExtensions.Show("La consulta devolvió " + sedeData.Count() + " registros.");
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
