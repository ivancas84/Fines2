using Fines2Wpf.Data;
using SqlOrganize;
using System.Collections.ObjectModel;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class Asignacion : Data_alumno_comision_r
    {
        public Asignacion() : base()
        {
        }

        public Asignacion(DataInitMode mode) : base(mode)
        {
        }

        public ObservableCollection<Data_comision_r> Comisiones { get; set; } = new();
        public string SearchComision { get; set; } = "";
    }
}
