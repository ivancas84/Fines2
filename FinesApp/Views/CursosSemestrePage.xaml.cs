using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using SqlOrganize.Sql;
using WpfUtils.Controls;


namespace FinesApp.Views;

public partial class CursosSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Data_curso_r> cursoOC = new();
    private ObservableCollection<Data_calendario> ocCalendario = new();

    public CursosSemestrePage()
    {
        InitializeComponent();
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        var data = ContainerApp.db.Sql("calendario").Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, ocCalendario);
        DataContext = this;
        dgdCurso.ItemsSource = cursoOC;

    }

    private void LoadCursos()
    {
        if (cbxCalendario.SelectedIndex < 0)
        {
            cursoOC.Clear();
            return;
        }
        var data = ContainerApp.db.CursosAutorizadosCalendarioSql(cbxCalendario.SelectedValue).Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, cursoOC);
    }

    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        LoadCursos();
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
