using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;
using SqlOrganize;
using System.Windows;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.CollectionUtils;


namespace FinesApp.Views;

public partial class ProcesarRegistroAlumnosPage : Page, INotifyPropertyChanged
{
    private ObservableCollection<Data_alumno_comision_r> asignacionRegistroOC = new();

    public ProcesarRegistroAlumnosPage()
    {
        InitializeComponent();
        headersTextBox.Text = "persona-nombres, persona-apellidos, persona-cuil1, persona-numero_documento, persona-cuil2, persona-fecha_nacimiento, persona-email, persona-area, persona-telefono, persona-genero, persona-nacionalidad, persona-domicilio, persona-departamento, persona-localidad, persona-sexo, persona-nacionalidad, persona-domicilio, persona-departamento, persona-localidad, persona-partido, persona-foto_dni, persona-foto_constancia";
        DataContext = this;
    }

    private (IDictionary<string, object?> pfidsComisiones, IDictionary<string, Data_alumno_comision_r> asignacionesDb) ConsultarDatosIniciales()
    {
        IDictionary<string, object?> pfidsComisiones = ContainerApp.db.
            ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, 1).
            Cache().ColOfDict().
            DictOfDictByKeysValue("id", "pfid");

        IDictionary<string, Data_alumno_comision_r> asignacionesDb = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, 1).
            Cache().ColOfDict().
            ColOfObj<Data_alumno_comision_r>().
            DictOfObjByPropertyNames("persona__numero_documento");

            return (pfidsComisiones, asignacionesDb);
    }

    private void ProcesarButton_Click(object sender, RoutedEventArgs e)
    {
        asignacionRegistroOC.Clear();

        var datosIniciales = ConsultarDatosIniciales();

        IEnumerable<string> _headers = headersTextBox.Text.Split(", ").Select(s => s.Trim());

        string[] _data = dataTextBox.Text.Split("\r\n");

        for (var j = 0; j < _data.Length; j++)
        {

            if (_data[j].IsNoE())
                continue;

            var values = _data[j].Split("\t");

            Data_alumno_comision_r asignacionForm = new();
            for (var i = 0; i < _headers.Count(); i++)
                asignacionForm.SetPropertyValue(_headers.ElementAt(i), values.ElementAt(i));

            if (datosIniciales.asignacionesDb.ContainsKey(asignacionForm.persona__numero_documento))
            {

            }










        }
    }

    private void GuardarButton_Click(object sender, RoutedEventArgs e)
    {

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
