using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AlumnoComision : Entity
    {
        
        public object Persist1(PersistContext persist)
        {
            alumno_!.plan = comision_!.planificacion_!.plan;
            alumno_!.persona = (string)alumno_!.persona_!.Persist(persist)!;
            alumno = (string)alumno_!.Persist(persist)!;
            return Persist(persist);
        }

    }
}
