using Fines2Wpf.Data;
using MaterialDesignColors;
using SqlOrganize;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class DetallePersona : Data_detalle_persona_r
    {

        public DetallePersona() : base()
        {
        }

        public DetallePersona(DataInitMode mode) : base(mode)
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
