using Fines2App.Data;
using System.Collections.ObjectModel;

namespace Fines2App.Views.Main
{
    internal class Calificacion : Data_calificacion_r
    {
        public ObservableCollection<Data_disposicion_r> Disposiciones { get; set; } = new();



        public ObservableCollection<Data_curso_r> Cursos { get; set; } = new();
        public string SearchCurso { get; set; } = "";        
    }
}
