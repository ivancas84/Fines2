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
    IEnumerable<EntityPersist> persists;

    public ComisionesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;
        dgComision.ItemsSource = comisionOC;

        cbxCalendario.InitComboBoxConstructor(calendarioOC);
        cbxCalendarioInformeGlobalPF.InitComboBoxConstructor(calendarioOC);
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
            persists = ContainerApp.db.PersistTomasPf(calendarioObj, tbxInformeGlobalPF.Text);
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
            if (!persists.IsNoE())
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
