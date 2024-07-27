using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class Curso
    {

        public static EntitySql BusquedaAproximadaCurso(this Db db, string search)
        {
            return db.Sql("curso")
               .Fields()
               .Size(0)
               .Where(@"
                    (CONCAT($sede-numero, $comision-division, '/', $planificacion-anio, $planificacion-semestre, ' ', $calendario-anio, '-', $calendario-semestre) LIKE @0)
                    OR ($sede-nombre LIKE @0)
                    OR (CONCAT($comision-pfid, '/', $planificacion-anio, $planificacion-semestre, ' ', $calendario-anio, '-', $calendario-semestre) LIKE @0)
                    OR (CONCAT($comision-pfid, '/', $calendario-anio, '-', $calendario-semestre) LIKE @0)

                ")
               .Order("$sede-numero ASC, $comision-division ASC, $planificacion-anio ASC, $planificacion-semestre ASC")
               .Parameters("%" + search + "%");
        }

        
    
    }
}
