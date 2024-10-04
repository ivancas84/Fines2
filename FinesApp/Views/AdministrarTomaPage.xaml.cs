using CommunityToolkit.WinUI.Notifications;
using FinesApp.Contracts.Services;
using FinesApp.Contracts.Views;
using Org.BouncyCastle.Asn1.Ocsp;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Drawing;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using WpfUtils;
using WpfUtils.Controls;

namespace FinesApp.Views;

public partial class AdministrarTomaPage : Page, INotifyPropertyChanged, INavigationAware
{
    private readonly INavigationService _navigationService;

    private ObservableCollection<Calendario> ocCalendario = new();
    private ObservableCollection<Sede> ocSede = new();
    private ObservableCollection<Comision> ocComision = new();
    private ObservableCollection<Curso> ocCurso = new();
    private ObservableCollection<string> ocTomaEstado = new();
    private ObservableCollection<string> ocTomaTipoMovimiento = new();
    private ObservableCollection<string> ocTomaEstadoContralor = new();

    public AdministrarTomaPage(INavigationService navigationService)
    {
        _navigationService = navigationService;
        InitializeComponent();
        DataContext = this;

        #region cbxCalendario
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        CalendarioDAO.CalendariosSql().Cache().AddEntityToClearOC(ocCalendario);
        Entity.CreateEmpty<Calendario>().InsertFirst(ocCalendario);
        cbxCalendario.SelectedIndex = 0;
        cbxCalendario.SelectionChanged += CbxCalendario_SelectionChanged;
        #endregion

        #region cbxSede
        cbxSede.InitComboBoxConstructor(ocSede, "nombre");
        cbxSede.SelectionChanged += CbxSede_SelectionChanged;
        #endregion

        #region cbxComision
        cbxComision.InitComboBoxConstructor(ocComision, "pfid");
        cbxComision.SelectionChanged += CbxComision_SelectionChanged;
        #endregion

        cbxCurso.InitComboBoxConstructor(ocCurso, "disposicion_.Label");

        cbxEstado.ItemsSource = ocTomaEstado;
        Context.db.Sql("toma").Fields("$estado").Cache().AddColumnToClearOC("estado", ocTomaEstado);

        cbxEstadoContralor.ItemsSource = ocTomaEstadoContralor;
        Context.db.Sql("toma").Fields("$estado_contralor").Cache().AddColumnToClearOC("estado_contralor", ocTomaEstadoContralor);

        cbxTipoMovimiento.ItemsSource = ocTomaTipoMovimiento;
        Context.db.Sql("toma").Fields("$tipo_movimiento").Cache().AddColumnToClearOC("tipo_movimiento", ocTomaTipoMovimiento);

    }

    private void CbxComision_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        if (cbxComision.SelectedIndex < 1)
            return;

        CursoDAO.CursosDeComisionSql(cbxComision.SelectedValue).Cache().AddEntityToClearOC(ocCurso);
        var curso = Entity.CreateEmpty<Curso>();
        curso.disposicion_ = new();
        curso.disposicion_.Label = "-Seleccione curso-";
        ocCurso.Insert(0, curso);
        cbxCurso.SelectedIndex = 0;
    }

    private void CbxSede_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        if (cbxSede.SelectedIndex < 1)
            return;

        ComisionDAO.ComisionesAutorizadasDeCalendarioYSedeSql(cbxCalendario.SelectedValue, cbxSede.SelectedValue).Cache().AddEntityToClearOC(ocComision);
        Entity.CreateEmpty<Comision>("pfid").InsertFirst(ocComision);
        cbxComision.SelectedIndex = 0;
    }

    private void CbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        if (cbxCalendario.SelectedIndex < 1)
            return;

        SedeDAO.SedesDeCalendarioSql(cbxCalendario.SelectedValue).Cache().AddEntityToClearOC(ocSede);
        Entity.CreateEmpty<Sede>("nombre").InsertFirst(ocSede);
        cbxSede.SelectedIndex = 0;
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

    #region INavigationAware
    public void OnNavigatedTo(object parameter)
    {
        Toma toma;
        if (!parameter.IsNoE())
            toma = Entity.CreateFromId<Toma>(parameter);
        else
            toma = new Toma();

        if (toma.docente_ == null)
            toma.docente_ = new();

        gbxToma.DataContext = toma;
        gbxDocente.DataContext = toma.docente_;
    }

    public void OnNavigatedFrom()
    {
        //throw new NotImplementedException();
    }
    #endregion

    private void BtnGuardarToma_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            Toma toma = (Toma)DataContext;
            using (Context.db.CreateQueue())
            {
                toma.Persist();
                Context.db.ProcessQueue();
            }
            ToastExtensions.Show("Toma registrada");

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void tbxNumeroDocumento_LostFocus(object sender, RoutedEventArgs e)
    {
        LoadPersonaFromSender(sender, "numero_documento");
    }

    private void tbxNumeroDocumento_PreviewKeyDown(object sender, System.Windows.Input.KeyEventArgs e)
    {
        if (e.Key == Key.Enter)
            LoadPersonaFromSender(sender, "numero_documento");
    }

    private void tbxCuil_LostFocus(object sender, RoutedEventArgs e)
    {
        LoadPersonaFromSender(sender, "cuil");
    }

    private void tbxCuil_PreviewKeyDown(object sender, System.Windows.Input.KeyEventArgs e)
    {
        if (e.Key == Key.Enter)
            LoadPersonaFromSender(sender, "cuil");
    }

    private void LoadPersonaFromSender(object sender, string key)
    {
        try
        {
            TextBox textBox = sender as TextBox;

            if (textBox.Text.IsNoE())
                return;

            Persona persona = (gbxDocente.DataContext as Persona);


            Persona? personaDB = Context.db.Sql("persona").Equal(key, textBox.Text).Cache().ToEntity<Persona>();

            if (!personaDB.IsNoE() && !persona.id.Equals(personaDB.id))
            {
                gbxDocente.DataContext = personaDB;
                ToastExtensions.Show("Se recargaron los datos del docente");
            }

        } catch(Exception exception)
        {
            exception.ToastException();
        }
    }
}
