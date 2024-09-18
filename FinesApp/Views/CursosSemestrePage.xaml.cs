using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;
using SqlOrganize.Sql;
using WpfUtils.Controls;
using SqlOrganize.CollectionUtils;


namespace FinesApp.Views;

public partial class CursosSemestrePage : Page, INotifyPropertyChanged
{
    private ObservableCollection<Curso> ocCurso = new();
    private ObservableCollection<Calendario> ocCalendario = new();

    public CursosSemestrePage()
    {
        InitializeComponent();
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        ContainerApp.db.Sql("calendario").Cache().ClearAndAddDataToOC(ocCalendario);
        DataContext = this;
        dgdCurso.ItemsSource = ocCurso;
    }

    private void LoadCursos()
    {
        if (cbxCalendario.SelectedIndex < 0)
        {
            ocCurso.Clear();
            return;
        }
        var data = ContainerApp.db.CursosAutorizadosCalendarioSql(cbxCalendario.SelectedValue).Cache().Dicts();
        var idCursos = data.ColOfVal<object>("id");
        var dataToma = ContainerApp.db.TomaAprobadaDeCursoQuery(idCursos).Cache().Dicts().DictOfDictByKeys("curso");

        ocCurso.Clear();
        foreach(var cursoData in data)
        {
            Curso curso = ContainerApp.db.ToData<Curso>(cursoData);
            if (dataToma.ContainsKey(curso.id))
                curso.toma_activa_ = ContainerApp.db.ToData<Toma>(dataToma[curso.id]);
            ocCurso.Add(curso);
        }
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
