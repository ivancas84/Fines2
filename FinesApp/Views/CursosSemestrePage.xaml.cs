using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using SqlOrganize.Sql;
using WpfUtils.Controls;
using SqlOrganize.CollectionUtils;


namespace FinesApp.Views;

public partial class CursosSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<CursoConTomaItem> cursoOC = new();
    private ObservableCollection<Calendario> ocCalendario = new();

    public CursosSemestrePage()
    {
        InitializeComponent();
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        var data = ContainerApp.db.Sql("calendario").Cache().Dicts();
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
        var data = ContainerApp.db.CursosAutorizadosCalendarioSql(cbxCalendario.SelectedValue).Cache().Dicts();
        var idCursos = data.EnumOfVal<object>("id");
        var dataToma = ContainerApp.db.TomaAprobadaDeCursoQuery(idCursos).Cache().Dicts().DictOfDictByKeys("curso");

        cursoOC.Clear();
        foreach(var cursoData in data)
        {
            CursoConTomaItem curso = ContainerApp.db.ToData<CursoConTomaItem>(cursoData);
            if (dataToma.ContainsKey(curso.id))
            {
                Toma_ toma = ContainerApp.db.ToData<Toma_>(dataToma[curso.id]);
                curso.toma_docente__Label = toma.docente__Label;
            }
            cursoOC.Add(curso);
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
