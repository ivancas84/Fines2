using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class ComisionDAO
    {

        public static EntitySql BusquedaAproximadaComision(string search)
        {
            return Context.db.Sql("comision")
               .Fields()
               .Size(0)
               .Where(@"
                    $sede__nombre LIKE @0
                    OR
                    CONCAT($sede__numero, $division, '/', $planificacion__anio, $planificacion__semestre) LIKE @0
                    OR
                    $pfid LIKE @0
                    OR
                    CONCAT($calendario__anio, '/', $calendario__semestre) LIKE @0
                ")
               .Order("$sede__numero ASC, $division ASC, $calendario__anio DESC, $calendario__semestre DESC")
               .Param("@0", "%" + search + "%");

        }

        public static EntitySql ComisionesAutorizadasDeCalendarioSql(object idCalendario)
        {
            return Context.db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario = @0
                    AND $autorizada = true
                ")
                .Param("@0", idCalendario);

        }

        public static EntitySql ComisionesAutorizadasDeCalendarioYSedeSql(object idCalendario, object idSede)
        {
            return Context.db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario = @0
                    AND $sede = @1
                    AND $autorizada = true
                ")
                .Param("@0", idCalendario)
                .Param("@1", idSede);


        }

        public static EntitySql ComisionesAutorizadasDePeriodoSql(object anio, object semestre)
        {
            return Context.db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1
                    AND $autorizada = true
                ")
                .Param("@0", anio).Param("@1", semestre);

        }

        public static EntitySql ComisionesDePeriodoSql(object anio, object semestre)
        {
            return Context.db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1
                ").
                Order("$pfid ASC")
                .Param("@0",anio).Param("@1", semestre);

        }



        
    }
}
