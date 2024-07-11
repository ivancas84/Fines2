
using SqlOrganize;
using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Windows.Toma.Contralor
{
    internal class Toma : Data_toma_r
    {
        public string cupof
        {
            get { return "S/N"; }
        }

        public string rev
        {
            get { return "P"; }
        }

        public string funcion
        {
            get { return "PF"; }
        }

        public string dia_desde
        {
            get { return "11"; }
        }

        public string mes_desde
        {
            get { return "03"; }
        }

        public string anio_desde
        {
            get { return "24"; }
        }

        public string dia_hasta
        {
            get { return "12"; }
        }

        public string mes_hasta
        {
            get { return "07"; }
        }

        public string anio_hasta
        {
            get { return "24"; }
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
                if (!value.IsNoE())
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



