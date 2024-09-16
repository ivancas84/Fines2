using FinesModel4.DAO;
using FinesModel4.Models;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using WpfUtils.Controls;

namespace FinesApp2.Views;

public partial class ComisionesSemestrePage : Page, INotifyPropertyChanged
{
    private ObservableCollection<string> ocComision = new();

    public ComisionesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;

        List<Calendario> calendarios = CalendarioDAO.GetCalendarios();
        cbxCalendario.InitComboBoxConstructor(calendarios);
    }


    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        LoadComisiones();
    }

    private void LoadComisiones()
    {
        if (cbxCalendario.SelectedIndex < 0)
        {
            //cursoOC.Clear();
            //return;
        }
        /*
        var data = ContainerApp.db.CursosAutorizadosCalendarioSql(cbxCalendario.SelectedValue).Cache().Dicts();
        var idCursos = data.EnumOfVal<object>("id");
        var dataToma = ContainerApp.db.TomaAprobadaDeCursoQuery(idCursos).Cache().Dicts().DictOfDictByKeys("curso");

        cursoOC.Clear();
        foreach (var cursoData in data)
        {
            CursoConTomaItem curso = ContainerApp.db.ToData<CursoConTomaItem>(cursoData);
            if (dataToma.ContainsKey(curso.id))
            {
                Toma_ toma = ContainerApp.db.ToData<Toma_>(dataToma[curso.id]);
                curso.toma_docente__Label = toma.docente__Label;
            }
            cursoOC.Add(curso);
        }*/

    }


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
}
