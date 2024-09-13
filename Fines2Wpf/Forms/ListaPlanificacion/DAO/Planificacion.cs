using SqlOrganize.Sql;
using System.Collections.Generic;

namespace Fines2Wpf.Forms.ListaPlanificacion.DAO
{
    internal class Planificacion
    {

        public IEnumerable<Dictionary<string, object>> All()
        {
            return ContainerApp.db.Sql("planificacion").Cache().Dicts();
        }

        public void UpdateValueRel(string key, object value, Dictionary<string, object> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("planificacion", key, value, source).Exec().RemoveCache();
        }



    }
}
