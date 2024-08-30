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
    private ObservableCollection<ComisionConReferentesItem> comisionOC = new();
    private ObservableCollection<Data_calendario> calendarioOC = new();
    private ObservableCollection<Data_calendario> calendarioPFOC = new();

    IEnumerable<EntityPersist> persists;

    public ComisionesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;
        dgComision.ItemsSource = comisionOC;
        comisionOC.CollectionChanged += Items_CollectionChanged;
        dgdResultadoInformeGlobalPF.ItemsSource = ocResultadoInformeGlobal;

        cbxCalendario.InitComboBoxConstructor(calendarioOC);
        cbxCalendarioInformeGlobalPF.InitComboBoxConstructor(calendarioPFOC);

        Loaded += ComisionesSemestrePage_Loaded;
    }

    private void Items_CollectionChanged(object sender, NotifyCollectionChangedEventArgs e)
    {
        if (e.NewItems != null)
            foreach (ComisionConReferentesItem newItem in e.NewItems)
                newItem.PropertyChanged += Item_PropertyChanged;

        if (e.OldItems != null)
            foreach (ComisionConReferentesItem oldItem in e.OldItems)
                oldItem.PropertyChanged -= Item_PropertyChanged;
    }
    private void ComisionesSemestrePage_Loaded(object sender, RoutedEventArgs e)
    {
        var data = ContainerApp.db.Sql("calendario").Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, calendarioPFOC);
    }

    private void LoadCalendario()
    {
        if (tbAnio.Text.IsNoE() || tbSemestre.Text.IsNoE())
            return;

        var data = ContainerApp.db.Sql("calendario").Where("$anio >= @0 AND $semestre >= @1").Parameters(tbAnio.Text, tbSemestre.Text).Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, calendarioOC);
    }

    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        var dataComisiones = ContainerApp.db.ComisionesDePeriodoSql(tbAnio.Text, tbSemestre.Text).Cache().ColOfDict();
        var idSedes = dataComisiones.ColOfVal<object>("sede");
        var dataReferentes = ContainerApp.db.ReferentesDeSedeQuery(idSedes).Cache().ColOfDict().DictOfListByKeys("sede");
        ContainerApp.db.ClearAndAddDataToOC(dataComisiones, comisionOC);
        comisionOC.Clear();
        for (var i = 0; i < dataReferentes.Count(); i++)
        {
            ComisionConReferentesItem obj = ContainerApp.db.ToData<ComisionConReferentesItem>(dataComisiones.ElementAt(i));
            //obj.PropertyChanged += Item_PropertyChanged;

            if (dataReferentes.ContainsKey(obj.sede))
                foreach (var dataReferente in dataReferentes[obj.sede])
                    obj.referentes.Add(ContainerApp.db.ToData<Data_designacion>(dataReferente).Label);
                
            obj.Index = i;
            comisionOC.Add(obj);
        }
    }

    private void Item_PropertyChanged(object sender, PropertyChangedEventArgs e)
    {
        if (e.PropertyName == nameof(ComisionConReferentesItem.apertura))
        {
            var item = (sender as ComisionConReferentesItem);
            ContainerApp.db.Persist().UpdateValueIds("comision", "apertura", item.autorizada, item.id);
        }
    }
    private void btnGenerarComisionesSiguientes_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (tbAnio.Text.IsNoE() || tbSemestre.Text.IsNoE() || cbxCalendario.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            IEnumerable<EntityPersist> persists = ContainerApp.db.Values<ComisionValues>().GenerarComisionesSemestreSiguiente(short.Parse(tbAnio.Text), short.Parse(tbSemestre.Text), cbxCalendario.SelectedValue);

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
            List<EntityPersist> persists = new();

            foreach(var comObj in comisionOC)
            {
                ComisionValues comVal = (ComisionValues)comObj.GetValues();
                comVal.GenerarCursos().AddTo(persists);
            }

            persists.Transaction().RemoveCache();
            ToastExtensions.Show("Se han generado los cursos");

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void tbAnio_LostFocus(object sender, RoutedEventArgs e)
    {
        LoadCalendario();
    }

    private void tbSemestre_LostFocus(object sender, RoutedEventArgs e)
    {
        LoadCalendario();
    }

    #region Pestaña Procesar Informe Global PF
    ObservableCollection<Data> ocResultadoInformeGlobal = new();

    private void btnProcesarInformeGlobalPF_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxCalendarioInformeGlobalPF.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var calendarioObj = (Data_calendario)cbxCalendarioInformeGlobalPF.SelectedItem;
            persists = ContainerApp.db.PersistComisionesPf(calendarioObj, tbxInformeGlobalPF.Text);
            ocResultadoInformeGlobal.Clear();
            for (var i = 0; i < persists.Count(); i++)
            {
                Data obj = new();
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

    
}
