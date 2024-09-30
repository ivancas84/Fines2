using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Planificacion
    {
        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return (anio ?? "?") + "/" + (semestre ?? "?") + " " + (plan_?.Label ?? "?");
            }
            set
            {
                Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }

        public static (string anio, string semestre) AnioSemestreAnterior(string anio, string semestre)
        {
            if (semestre.Equals("1"))
            {
                semestre = "2";
                short anio_ = Convert.ToInt16(anio);
                anio_--;
                if (anio_ == 0) throw new Exception("Se esta queriendo obtener año y semestre anterior a 1/1");
                anio = anio_.ToString();
            }
            else
            {
                semestre = "1";
            }

            return (anio, semestre);
        }

        public static (string anio, string semestre) AnioSemestreSiguiente(object anio, object semestre)
        {

            if (semestre.ToString().Equals("2"))
            {
                semestre = "1";
                short anio_ = Convert.ToInt16(anio);
                anio_++;
                if (anio_ == 4) throw new Exception("Se esta queriendo obtener año y semestre anterior a 3/2");
                anio = anio_.ToString();
            }
            else
            {
                semestre = "2";
            }

            return (anio.ToString(), semestre.ToString());
        }
    }
}
