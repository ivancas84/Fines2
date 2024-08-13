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

namespace FinesApp.Views;

public partial class ComisionesSemestrePage : Page, INotifyPropertyChanged
{
    private ObservableCollection<Data_comision_r> comisionOC = new();
    private ObservableCollection<Data_calendario> calendarioOC = new();


    public ComisionesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;
        dgComision.ItemsSource = comisionOC;

        cbxCalendario.InitComboBoxConstructor(calendarioOC);
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
        var data = ContainerApp.db.ComisionesDePeriodoSql(tbAnio.Text, tbSemestre.Text).Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, comisionOC);

    }

    private void btnGenerarComisionesSiguientes_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (tbAnio.Text.IsNoE() || tbSemestre.Text.IsNoE())
                throw new Exception("Verificar año o semestre seleccionado");


            List<EntityPersist> persists = ComisionValues.GenerarComisionesSemestreSiguiente(short.Parse(tbAnio.Text), short.Parse(tbSemestre.Text));
        
        } catch (Exception ex)
        {
            ex.ToastException()
        }



        EntityPersist persist = ContainerApp.db.Persist();
        foreach (Dictionary<string, object?> com in comisionesAutorizadasSemestreAnterior)
        {
            Data_comision_r comObj = com.Obj<Data_comision_r>();

            
            string idPlanificacionSiguiente = planificacionDAO.PlanificacionSiguiente(comObj.planificacion__anio!, comObj.planificacion__semestre!, comObj.plan__id!);

            EntityValues comisionVal = ContainerApp.db.Values("comision").
                Set(comObj).
                SetDefault("id").
                Set("planificacion", idPlanificacionSiguiente).
                Set("apertura", false).
                Set("configuracion", "Histórica").
                Set("calendario", idNuevoCalendarioTextBox.Text).
                SetDefault("alta").
                Reset();

            persist.Insert(comisionVal);
            persist.UpdateValueIds("comision", "comision_siguiente", comisionVal.Get("id"), comObj.id);
        }

        persist.Transaction().RemoveCache();

    }

    private void tbAnio_LostFocus(object sender, RoutedEventArgs e)
    {
        LoadCalendario();
    }

    private void tbSemestre_LostFocus(object sender, RoutedEventArgs e)
    {
        LoadCalendario();
    }


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
