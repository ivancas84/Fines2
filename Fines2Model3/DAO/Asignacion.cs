using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Model3.DAO
{
    public static class Asignacion
    {
        public static EntitySql AsignacionesDeComisionesSql(this Db db, params object[] id_comisiones)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $comision IN ( @0 )
                "
            )
               .Parameters(id_comisiones);
        }

        public static EntitySql AsignacionesDeComisionesAutorizadasDelPeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                "
                )
               .Parameters(anio, semestre);
        }
    }
}
