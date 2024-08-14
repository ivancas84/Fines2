using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using SqlOrganize.Sql;

namespace FinesApp.Views;

public partial class CursosSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Data_curso_r> cursoOC = new();

    public CursosSemestrePage()
    {
        InitializeComponent();
        DataContext = this;
        dgdCurso.ItemsSource = cursoOC;

    }

    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        var data = ContainerApp.db.CursosAutorizadosPeriodoSql(tbAnio.Text, tbSemestre.Text).Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, cursoOC);
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
