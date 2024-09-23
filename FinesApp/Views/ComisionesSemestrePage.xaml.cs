using SqlOrganize.Sql;
using System.ComponentModel;
using System.Reflection.PortableExecutable;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using WpfUtils.Controls;
using SqlOrganize;
using WpfUtils;
using SqlOrganize.CollectionUtils;
using System.Collections.Specialized;

namespace FinesApp.Views;

public partial class ComisionesSemestrePage : Page, INotifyPropertyChanged
{
    private ObservableCollection<Comision> comisionOC = new();
    private ObservableCollection<Calendario> ocCalendario = new();
    private ObservableCollection<Calendario> ocCalendarioComisionesSiguientes = new();
    private ObservableCollection<Calendario> calendarioPFOC = new();

    IEnumerable<PersistContext> persists;

    public ComisionesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;
        dgComision.ItemsSource = comisionOC;
        comisionOC.CollectionChanged += Items_CollectionChanged;
        dgdResultadoInformeGlobalPF.ItemsSource = ocResultadoInformeGlobal;

        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        cbxCalendarioComisionesSiguientes.InitComboBoxConstructor(ocCalendarioComisionesSiguientes);
        cbxCalendarioInformeGlobalPF.InitComboBoxConstructor(calendarioPFOC);

        Loaded += ComisionesSemestrePage_Loaded;
    }

    #region checkbox datagrid
    private void Items_CollectionChanged(object sender, NotifyCollectionChangedEventArgs e)
    {
        if (e.NewItems != null)
            foreach (Comision newItem in e.NewItems)
                newItem.PropertyChanged += Item_PropertyChanged;

        if (e.OldItems != null)
            foreach (Comision oldItem in e.OldItems)
                oldItem.PropertyChanged -= Item_PropertyChanged;
    }

    private void Item_PropertyChanged(object sender, PropertyChangedEventArgs e)
    {
        ContainerApp.db.Persist().
            UpdateFieldIds("comision", e.PropertyName, sender.GetPropertyValue(e.PropertyName), sender.GetPropertyValue("id")).
            Exec().
            RemoveCache();
    }
    #endregion

    private void ComisionesSemestrePage_Loaded(object sender, RoutedEventArgs e)
    {
        ContainerApp.db.Sql("calendario").Cache().AddDataToClearOC(calendarioPFOC);
    }

    private void LoadCalendarioComisionesSiguientes()
    {
        if (cbxCalendario.SelectedIndex < 0)
            return;

        Calendario cal = cbxCalendario.SelectedItem as Calendario;

        ContainerApp.db.Sql("calendario").Where("$anio >= @0 AND $semestre >= @1").
            Param("@0", cal.anio).Param("@1", cal.semestre).Cache().AddDataToClearOC(ocCalendarioComisionesSiguientes);
    }

    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        var dataComisiones = ContainerApp.db.ComisionesAutorizadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().Dicts();
        var idSedes = dataComisiones.ColOfVal<object>("sede");
        var dataReferentes = ContainerApp.db.ReferentesDeSedeQuery(idSedes).Cache().Dicts().DictOfListByKeys("sede");
        comisionOC.Clear();
        for (var i = 0; i < dataComisiones.Count(); i++)
        {
            Comision obj = ContainerApp.db.ToData<Comision>(dataComisiones.ElementAt(i));

            if (dataReferentes.ContainsKey(obj.sede))
                foreach (var dataReferente in dataReferentes[obj.sede])
                    ContainerApp.db.ToData<Designacion>(dataReferente).AddToOC(obj.sede_.Designacion_);
                
            obj.Index = i;
            comisionOC.Add(obj);
        }
    }

    private void btnGenerarComisionesSiguientes_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxCalendario.SelectedIndex < 0 || cbxCalendarioComisionesSiguientes.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            IEnumerable<PersistContext> persists = ContainerApp.db.Values<ComisionValues>().GenerarComisionesSemestreSiguiente(cbxCalendario.SelectedValue, cbxCalendarioComisionesSiguientes.SelectedValue);

            persists.Transaction().RemoveCache();
            ToastExtensions.Show("Comisiones agregadas");
        } catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGenerarCursosComisiones_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            List<PersistContext> persists = new();

            foreach(var comObj in comisionOC)
                comObj.GenerarCursos().AddTo(persists);

            persists.Transaction().RemoveCache();
            ToastExtensions.Show("Se han generado los cursos");

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }


    #region Pestaña Procesar Informe Global PF
    ObservableCollection<EntityData> ocResultadoInformeGlobal = new();

    private void btnProcesarInformeGlobalPF_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxCalendarioInformeGlobalPF.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var calendarioObj = (Calendario)cbxCalendarioInformeGlobalPF.SelectedItem;
            persists = ContainerApp.db.PersistComisionesPf(calendarioObj, tbxInformeGlobalPF.Text);
            ocResultadoInformeGlobal.Clear();
            for (var i = 0; i < persists.Count(); i++)
            {
                EntityData obj = new();
                obj.Index = i;
                obj.Label = persists.ElementAt(i).logging.ToString();
                ocResultadoInformeGlobal.Add(obj);

            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGuardarInformeGlobalPF_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (persists.IsNoE())
                throw new Exception("No hay nada para persistir");

            persists.Transaction().RemoveCache();
            ToastExtensions.Show("Registro realizado");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    #endregion

    #region InotifyPropertyChanged

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

    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        LoadCalendarioComisionesSiguientes();
    }
}
