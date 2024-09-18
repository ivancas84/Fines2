using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;
using SqlOrganize;
using System.Windows;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.CollectionUtils;
using System.Net.WebSockets;
using SqlOrganize.Sql;


namespace FinesApp.Views;

public partial class ProcesarRegistroAlumnosPage : Page, INotifyPropertyChanged
{
    private ObservableCollection<AlumnoComision> asignacionRegistroOC = new();

    public ProcesarRegistroAlumnosPage()
    {
        InitializeComponent();
        headersTextBox.Text = "persona-nombres, persona-apellidos, persona-cuil1, persona-numero_documento, persona-cuil2, persona-fecha_nacimiento, persona-email, persona-area, persona-telefono, persona-genero, persona-nacionalidad, persona-domicilio, persona-departamento, persona-localidad, persona-sexo, persona-nacionalidad, persona-domicilio, persona-departamento, persona-localidad, persona-partido, persona-foto_dni, persona-foto_constancia";
        DataContext = this;
    }

    

    private void ProcesarButton_Click(object sender, RoutedEventArgs e)
    {
        asignacionRegistroOC.Clear();

        IDictionary<string, AlumnoComision> asignacionesDb = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, 1).
            Cache().Dicts().
            Objs<AlumnoComision>().
            DictOfObjByPropertyNames("persona__numero_documento");

        IEnumerable<string> _headers = headersTextBox.Text.Split(", ").Select(s => s.Trim());

        string[] _data = dataTextBox.Text.Split("\r\n");

        for (var j = 0; j < _data.Length; j++)
        {

            if (_data[j].IsNoE())
                continue;

            var values = _data[j].Split("\t");

            AlumnoComision asignacionForm = new();
            for (var i = 0; i < _headers.Count(); i++)
                asignacionForm.SetPropertyValue(_headers.ElementAt(i), values.ElementAt(i));

            if (asignacionesDb.ContainsKey(asignacionForm.alumno_.persona_.numero_documento))
            {
                var asignacionDb = asignacionesDb[asignacionForm.alumno_.persona_.numero_documento];
                var personaDbVal = (PersonaValues)ContainerApp.db.Values("persona", "persona").Set(asignacionDb);
                var personaFormVal = (PersonaValues)ContainerApp.db.Values("persona", "persona").Set(asignacionForm);

                CompareParams cp = new() { Data = personaFormVal.Values(), IgnoreNull = false };
                var comp = personaDbVal.Compare(cp);

                Dictionary<string, object?> updatePersonaDb = new(); //datos a actualizar de la base local
                Dictionary<string, object?> verificarPersonaDb = new(); //datos a verificar de la base local

                foreach (string key in comp.Keys)
                {
                    if (key.Equals("nombres") || key.Equals("apellidos") || key.Equals("numero_documento"))
                    {
                        verificarPersonaDb[key] = personaFormVal.Get(key);
                    }
                    else
                    {
                        updatePersonaDb[key] = personaFormVal.Get(key);
                    }

                    asignacionForm.Msg = "Verificar: " + verificarPersonaDb.ToString() + " - Actualizar: " + updatePersonaDb.ToString(); 
                }


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
