using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Model3.Item
{
    public class AsignacionConAsignaturasItem : Data_alumno_comision_r
    {


        protected string _asignatura0 = "";
        public string asignatura0
        {
            get { return _asignatura0; }
            set { _asignatura0 = value; NotifyPropertyChanged(nameof(asignatura0)); }
        }

        protected string _asignatura1 = "";

        public string asignatura1
        {
            get { return _asignatura1; }
            set { _asignatura1 = value; NotifyPropertyChanged(nameof(asignatura1)); }
        }

        protected string _asignatura2 = "";

        public string asignatura2
        {
            get { return _asignatura2; }
            set { _asignatura2 = value; NotifyPropertyChanged(nameof(asignatura2)); }
        }

        protected string _asignatura3 = "";

        public string asignatura3
        {
            get { return _asignatura3; }
            set { _asignatura3 = value; NotifyPropertyChanged(nameof(asignatura3)); }
        }

        protected string _asignatura4 = "";

        public string asignatura4
        {
            get { return _asignatura4; }
            set { _asignatura4 = value; NotifyPropertyChanged(nameof(asignatura4)); }
        }

        protected int _cantidad_aprobadas = 0;
        public int cantidad_aprobadas
        {
            get { return _cantidad_aprobadas; }
            set { _cantidad_aprobadas = value; NotifyPropertyChanged(nameof(cantidad_aprobadas)); }
        }


    }
}
