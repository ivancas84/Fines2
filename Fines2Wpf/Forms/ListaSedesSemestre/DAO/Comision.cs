using SqlOrganize;
using SqlOrganize.Sql;
using System.Collections.Generic;

namespace Fines2Wpf.Forms.ListaSedesSemestre.DAO
{
    class Comision
    {

        public IEnumerable<Dictionary<string, object?>> Search(ComisionSearch search)
        {
            var q = ContainerApp.db.Sql("comision")
                .Fields("sede__*, domicilio__*")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                ")
                .Param("@0", search.calendario__anio).Param("@1", search.calendario__semestre);
            var count = 2;
            if (!search.autorizada.IsNoE())
            {
                q.Where("AND $autorizada = @" + count + " ");
                q.Param("@0", search.autorizada!);
                count++;
            }
    

            return q.Cache().Dicts();
        }

        public void UpdateValueRel(string key, object value, Dictionary<string, object?> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("comision", key, value, source).Exec().RemoveCache();
        }

    }
}
