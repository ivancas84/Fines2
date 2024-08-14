using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class ComisionDAO
    {

        public static EntitySql BusquedaAproximadaComision(this Db db, string search)
        {
            return db.Sql("comision")
               .Fields()
               .Size(0)
               .Where(@"
                    $sede-nombre LIKE @0
                    OR
                    CONCAT($sede-numero, $division, '/', $planificacion-anio, $planificacion-semestre) LIKE @0
                    OR
                    $pfid LIKE @0
                    OR
                    CONCAT($calendario-anio, '-', $calendario-semestre) LIKE @0
                ")
               .Order("$sede-numero ASC, $division ASC, $calendario-anio DESC, $calendario-semestre DESC")
               .Parameters("%" + search + "%");

        }

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

        public static EntitySql ComisionesDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                ")
                .Parameters(anio, semestre);

        }

    }
}
