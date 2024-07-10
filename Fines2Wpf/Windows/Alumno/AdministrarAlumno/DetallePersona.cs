using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class DetallePersona : Data_detalle_persona_r
    {

        public DetallePersona() : base()
        {
        }

        public DetallePersona(Db db) : base(db)
        {
        }

        protected string? _arch = null;

        public string? arch
        {
            get { return _arch; }
            set { _arch = value; NotifyPropertyChanged(); }
        }

    }
}
