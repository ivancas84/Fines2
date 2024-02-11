using Fines2Wpf.Data;
using System.Collections.Generic;

namespace Fines2Wpf.Windows.Comision.ListaComisionesSemestre
{
    public class Comision : Data_comision_r
    {
        protected long? _cantidad_alumnos = null;
        public long? cantidad_alumnos
        {
            get { return _cantidad_alumnos; }
            set { _cantidad_alumnos = value; NotifyPropertyChanged(); }
        }

        protected long? _cantidad_alumnos_activos = null;
        public long? cantidad_alumnos_activos
        {
            get { return _cantidad_alumnos_activos; }
            set { _cantidad_alumnos_activos = value; NotifyPropertyChanged(); }
        }

        protected List<string> _referentes = new();


        public List<string>? referentes
        {
            get { return _referentes; }
            set { _referentes = value; NotifyPropertyChanged(); }
        }

    }
}
