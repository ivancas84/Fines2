using Fines2Wpf.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.Windows.Toma.Contralor
{
    internal class Toma : Data_toma_r
    {
        public string cupof
        {
            get { return "S/N"; }
        }

        protected string? _prefijo_cuil = null;
        public string? prefijo_cuil
        {
            get { return _prefijo_cuil; }
        }

        protected string? _sufijo_cuil = null;
        public string? sufijo_cuil
        {
            get { return _sufijo_cuil; }
        }

        public new string? docente__cuil
        {
            get { return _docente__cuil; }
            set
            {
                _docente__cuil = value;
                _prefijo_cuil = null;
                _sufijo_cuil = null;
                if (!value.IsNullOrEmptyOrDbNull())
                {
                    _prefijo_cuil = _docente__cuil.Substring(0, 2);
                    if(_docente__cuil.Length > 10) 
                        _sufijo_cuil = _docente__cuil.Substring(10, 1);
                }

                NotifyPropertyChanged();
            }
        }
    }
}



