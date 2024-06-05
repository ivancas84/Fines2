using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones
{
    internal class FormItem : SqlOrganize.Data
    {
        public string? comision {  get; set; }

        public int _alumnosProcesados = 0;

        public int alumnosProcesados
        {
            get { return _alumnosProcesados; }
            set
            {
                if (_alumnosProcesados != value)
                {
                    _alumnosProcesados = value;
                    NotifyPropertyChanged(nameof(alumnosProcesados));
                }
            }
        }
    }
}
