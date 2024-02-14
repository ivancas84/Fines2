using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.RightsManagement;
using System.Text;
using System.Threading.Tasks;
using Utils;

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
            var q = ContainerApp.db.Query("comision")
                .Fields()
                .Select("CONCAT($sede-numero, $division, '/', $planificacion-anio, $planificacion-semestre) AS numero")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
            var count = 2;
            if (!autorizada.IsNullOrEmpty())
            {
                q.Where("AND $autorizada = @" + count + " ");
                q.Parameters(autorizada!);
                count++;
            }
            if (!sede.IsNullOrEmpty())
            {
                q.Where("AND sede = @" + count + " ");
                q.Parameters(sede!);
            }

            return q.ColOfDictCache();
        }



        public IEnumerable<Dictionary<string, object?>> ComisionesPorIds(List<object> ids)
        {
            return ContainerApp.db.Query("comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $id IN ( @0 ) 
                ")
                .Parameters(ids).ColOfDictCache();
            
        }

        public IEnumerable<Dictionary<string, object?>> ComisionesConSiguientePorCalendario(object anio, object semestre)
        {
            return ContainerApp.db.Query("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision_siguiente IS NOT NULL
                ")
                .Parameters(anio, semestre).ColOfDictCache();

        }

        public IEnumerable<object> IdsComisionesAutorizadasPorCalendario(object anio, object semestre)
        {
            return ContainerApp.db.Query("comision")
                .Fields(ContainerApp.db.config.id)
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre).ColOfDictCache().ColOfVal<object>(ContainerApp.db.config.id);

        }

        public IEnumerable<Dictionary<string, object>> ComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            IEnumerable<object> ids = IdsComisionesAutorizadasPorCalendario(anio, semestre);
            return ContainerApp.db.Query("comision").CacheByIds(ids);
        }

        public EntityQuery ComisionesAutorizadasPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Query("comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre);
        }


        public EntityQuery BusquedaAproximadaQuery(string search)
        {
            return ContainerApp.db.Query("comision")
               .Fields()
               .Size(0)
               .Where(@"
                    $sede-nombre LIKE @0
                    OR
                    CONCAT($sede-numero, $division, '/', $planificacion-anio, $planificacion-semestre) LIKE @0
                    OR
                    CONCAT($calendario-anio, '-', $calendario-semestre) LIKE @0
                ")
               .Order("$sede-numero ASC, $division ASC, $calendario-anio, $calendario-semestre")
               .Parameters("%"+search+"%");
        }


        public EntityQuery HorariosQuery(params object[] idComisiones)
        {
            return ContainerApp.db.Query("horario").
                Where("$curso-comision IN ( @0 )").
                Parameters(idComisiones).
                Size(0);
        }

    }
}
