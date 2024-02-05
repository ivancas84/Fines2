using Fines2App.Data;
using SqlOrganize;

namespace Fines2App.Views.Main
{
    internal class DetallePersona : Data_detalle_persona_r
    {

        public DetallePersona() : base()
        {
        }

        public DetallePersona(DataInitMode mode = DataInitMode.Default) : base(mode)
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
