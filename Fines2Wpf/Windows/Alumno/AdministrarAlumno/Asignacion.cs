using Fines2Wpf.Model;
using System.Collections.ObjectModel;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class Asignacion : Data_alumno_comision_r
    {
        public ObservableCollection<Data_comision_r> Comisiones { get; set; } = new();
        public string SearchComision { get; set; } = "";
    }
}
