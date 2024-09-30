using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SqlOrganize.DateTimeUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AlumnoComision : Entity
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

    }
}
