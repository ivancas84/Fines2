using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using WpfUtils;
using System.Collections.ObjectModel;
using WpfUtils.Controls;
using SqlOrganize;

namespace FinesApp.Views;

public partial class TomasSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Data_calendario> ocCalendario = new();
    private ObservableCollection<Data_toma_r> ocToma = new();
    IEnumerable<EntityPersist> persists;

    public TomasSemestrePage()
    {
        InitializeComponent();

        DataContext = this;

        dgdToma.ItemsSource = ocToma;
        dgdResultadoProcesamiento.ItemsSource = ocData;
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        cbxCalendario2.InitComboBoxConstructor(ocCalendario);
        var data = ContainerApp.db.Sql("calendario").Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, ocCalendario);
    }

    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        if (cbxCalendario.SelectedIndex < 0)
            return;

        var tomaData = ContainerApp.db.TomasAprobadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(tomaData, ocToma);
    }

    #region Pestaña Procesar Docentes PF
    ObservableCollection<Data> ocData = new();

    private void btnProcesarDocentesPF_Click(object sender, RoutedEventArgs e)
    {

        try { 
            if (cbxCalendario2.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var calendarioObj = (Data_calendario)cbxCalendario2.SelectedItem;
            persists = ContainerApp.db.PersistTomasPf(calendarioObj, tbxDocentesPF.Text);
            ocData.Clear();
            for(var i = 0; i < persists.Count(); i++)
            {
                Data obj = new();
                obj.Index = i;
                obj.Label = persists.ElementAt(i).logging.ToString();
                ocData.Add(obj);

            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGuardarDocentesPF_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (!persists.IsNoE())
                throw new Exception("No hay nada para persistir");

            persists.Transaction().RemoveCache();
            ToastExtensions.Show("Se han registrado las tomas");
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

    private void btnGenerarTomasPDF_Click(object sender, RoutedEventArgs e)
    {

    }
}
