using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AlumnoComision
    {

        public object Persist1()
        {
            var persist = Context.db.Persist();
            alumno_!.plan = comision_!.planificacion_!.plan;
            alumno_!.persona = (string)persist.Persist(alumno_!.persona_!)!;
            alumno = (string)persist.Persist(alumno_!)!;
            return persist.Persist(this);
        }

        public override string? Label
        {
            get {
                if (!_Label.IsNoE())
                    return _Label;

                return (estado ?? "?") + " " + (comision_?.Label ?? "?") + " " + (alumno_?.Label ?? "?");
            }
            set {
                Label = value;
                NotifyPropertyChanged(nameof(Label)); }
        }


        public long? _CantidadAprobadas11;

        public long? CantidadAprobadas11 {
            get { return _CantidadAprobadas11; }

            set {
                if (_CantidadAprobadas11 != value)
                {
                    _CantidadAprobadas11 = value; NotifyPropertyChanged(nameof(CantidadAprobadas11));
                }

            }
        }

        public long? _CantidadAprobadas12;

        public long? CantidadAprobadas12
        {
            get { return _CantidadAprobadas12; }

            set
            {
                if (_CantidadAprobadas12 != value)
                {
                    _CantidadAprobadas12 = value; NotifyPropertyChanged(nameof(CantidadAprobadas12));
                }

            }
        }

        public long? _CantidadAprobadas21;

        public long? CantidadAprobadas21
        {
            get { return _CantidadAprobadas21; }

            set
            {
                if (_CantidadAprobadas21 != value)
                {
                    _CantidadAprobadas21 = value; NotifyPropertyChanged(nameof(CantidadAprobadas21));
                }

            }
        }

        public long? _CantidadAprobadas22;

        public long? CantidadAprobadas22
        {
            get { return _CantidadAprobadas22; }

            set
            {
                if (_CantidadAprobadas22 != value)
                {
                    _CantidadAprobadas22 = value; NotifyPropertyChanged(nameof(CantidadAprobadas22));
                }

            }
        }

        public long? _CantidadAprobadas31;

        public long? CantidadAprobadas31
        {
            get { return _CantidadAprobadas31; }

            set
            {
                if (_CantidadAprobadas31 != value)
                {
                    _CantidadAprobadas31 = value; NotifyPropertyChanged(nameof(CantidadAprobadas31));
                }

            }
        }

        public long? _CantidadAprobadas32;

        public long? CantidadAprobadas32
        {
            get { return _CantidadAprobadas32; }

            set
            {
                if (_CantidadAprobadas32 != value)
                {
                    _CantidadAprobadas32 = value; NotifyPropertyChanged(nameof(CantidadAprobadas32));
                }

            }
        }


    }


}
