using SqlOrganize;
using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Windows.ListaTomas
{
    internal class TomaContralor : Data_toma_r
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

        public string _dia_desde;
        public string dia_desde
        {
            get { return _dia_desde; }
            set { _dia_desde = value; }
        }

        public string _mes_desde;
        public string mes_desde
        {
            get { return _mes_desde; }
            set { _mes_desde = value; }
        }

        public string _anio_desde;
        public string anio_desde
        {
            get { return _anio_desde; }
            set { _anio_desde = value; }
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
            set { _prefijo_cuil = value; }

        }

        protected string? _sufijo_cuil = null;
        public string? sufijo_cuil
        {
            get { return _sufijo_cuil; }
            set { _sufijo_cuil = value; }
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



