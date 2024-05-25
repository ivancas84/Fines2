using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Model3.DAO
{
    public static class Comision
    {

        public static EntitySql ComisionesAutorizadasDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre);

        }
    }
}
