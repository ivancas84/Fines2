using Fines2Wpf.Data;
using System.Collections.Generic;

namespace Fines2Wpf.Windows.Comision.ListaComisionesSemestre
{
    public class Comision : Data_comision_r
    {

        protected List<string> _referentes = new();

        public List<string>? referentes
        {
            get { return _referentes; }
            set { _referentes = value; NotifyPropertyChanged(); }
        }

    }
}
