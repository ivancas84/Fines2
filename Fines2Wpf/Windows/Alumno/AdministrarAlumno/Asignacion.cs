using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class Asignacion : Data_alumno_comision_r
    {
        public Asignacion() : base()
        {
        }

        public Asignacion(Db db) : base(db)
        {
        }

        public ObservableCollection<Data_comision_r> Comisiones { get; set; } = new();
        public string SearchComision { get; set; } = "";
    }
}
