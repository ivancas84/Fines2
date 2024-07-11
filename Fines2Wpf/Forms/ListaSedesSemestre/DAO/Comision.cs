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
                .Fields("sede-*, domicilio-*")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(search.calendario__anio, search.calendario__semestre);
            var count = 2;
            if (!search.autorizada.IsNoE())
            {
                q.Where("AND $autorizada = @" + count + " ");
                q.Parameters(search.autorizada!);
                count++;
            }
    

            return q.Cache().ColOfDict();
        }

        public void UpdateValueRel(string key, object value, Dictionary<string, object?> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("comision", key, value, source).Exec().RemoveCache();
        }

    }
}
