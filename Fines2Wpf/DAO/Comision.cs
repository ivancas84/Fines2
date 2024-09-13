using Org.BouncyCastle.Utilities;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Net;

namespace Fines2Wpf.DAO
{
    public class Comision
    {

        public void UpdateValueRel(string key, object value, Dictionary<string, object?> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("comision", key, value, source).Exec().RemoveCache();
        }

        public IEnumerable<Dictionary<string, object?>> ComisionesSemestre(object calendarioAnio, object calendarioSemestre, object? sede = null, bool? autorizada = null)
        {
            var q = ContainerApp.db.Sql("comision")
                .Fields()
                .Select("CONCAT($sede__numero, $division, '/', $planificacion__anio, $planificacion__semestre) AS numero")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                ")
                .Param("@0", calendarioAnio).Param("@1", calendarioSemestre);
            var count = 2;
            if (!autorizada.IsNoE())
            {
                q.Where("AND $autorizada = @" + count + " ");
                q.Param("@0", autorizada!);
                count++;
            }
            if (!sede.IsNoE())
            {
                q.Where("AND sede = @" + count + " ");
                q.Param("@0", sede!);
            }

            return q.Cache().Dicts();
        }



        public IEnumerable<Dictionary<string, object?>> ComisionesPorIds(List<object> ids)
        {
            return ContainerApp.db.Sql("comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $id IN ( @0 ) 
                ")
                .Param("@0", ids).Cache().Dicts();
            
        }

        public IEnumerable<Dictionary<string, object?>> ComisionesConSiguientePorCalendario(object anio, object semestre)
        {
            return ContainerApp.db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1 
                    AND $comision_siguiente IS NOT NULL
                ")
                .Param("@0", anio).Param("@0", semestre).Cache().Dicts();

        }

        public IEnumerable<object> IdsComisionesAutorizadasPorCalendario(object anio, object semestre)
        {
            return ContainerApp.db.Sql("comision")
                .Fields(ContainerApp.db.config.id)
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1
                    AND $autorizada = true
                ")
                .Param("@0", anio).Param("@1", semestre).Cache().Dicts().ColOfVal<object>(ContainerApp.db.config.id);

        }

        public IEnumerable<Dictionary<string, object>> ComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            IEnumerable<object> ids = IdsComisionesAutorizadasPorCalendario(anio, semestre);
            return ContainerApp.db.Sql("comision").Cache().Ids(ids.ToArray());
        }

        public EntitySql ComisionesAutorizadasPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Sql("comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1
                    AND $autorizada = true
                ")
                .Param("@0", anio).Param("@1", semestre);
        }


        public EntitySql BusquedaAproximadaQuery(string search)
        {
            return ContainerApp.db.Sql("comision")
               .Fields()
               .Size(0)
               .Where(@"
                    $sede__nombre LIKE @0
                    OR
                    CONCAT($sede__numero, $division, '/', $planificacion__anio, $planificacion__semestre) LIKE @0
                    OR
                    CONCAT($calendario__anio, '__', $calendario__semestre) LIKE @0
                ")
               .Order("$sede__numero ASC, $division ASC, $calendario__anio DESC, $calendario__semestre DESC")
               .Param("@0", "%"+search+"%");
        }


        public EntitySql HorariosQuery(params object[] idComisiones)
        {
            return ContainerApp.db.Sql("horario").
                Where("$curso__comision IN ( @0 )").
                Param("@0", idComisiones).
                Size(0);
        }

        

    }
}
