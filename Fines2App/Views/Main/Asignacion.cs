using Fines2App.Data;
using System.Collections.ObjectModel;

namespace Fines2App.Views.Main
{
    internal class Asignacion : Data_alumno_comision_r
    {
        public ObservableCollection<Data_comision_r> Comisiones { get; set; } = new(); //DataGrid Autocomplete v1: Resultado de la búsqueda de comisiones a través de un autocomplete dentro del datagrid
        public string SearchComision { get; set; } = ""; //DataGrid Autocomplete v1: Combobox para buscar
    }
}
