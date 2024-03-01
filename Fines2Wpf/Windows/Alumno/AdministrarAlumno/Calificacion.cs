using Fines2Wpf.Data;
using System.Collections.ObjectModel;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class Calificacion : Data_calificacion_r
    {
        public ObservableCollection<Data_disposicion_r> Disposiciones { get; set; } = new();

        public ObservableCollection<Data_curso_r> Cursos { get; set; } = new();
        
        public string SearchCurso { get; set; } = "";

        public string color_nota_final { get; set; } = "";
        public string color_crec { get; set; } = "";
    }
}
