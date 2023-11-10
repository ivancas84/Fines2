using Fines2Wpf.Model;
using System.Collections.ObjectModel;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class Calificacion : Data_calificacion_r
    {
        public ObservableCollection<Data_disposicion_r> Disposiciones { get; set; } = new();
    }
}
