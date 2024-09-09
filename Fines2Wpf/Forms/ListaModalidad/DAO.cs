using Google.Protobuf.WellKnownTypes;
using SqlOrganize;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Forms.ListaModalidad
{
    internal class DAO
    {

        public IEnumerable<Dictionary<string, object>> AllModalidad()
        {
            return ContainerApp.db.Sql("modalidad").Cache().ColOfDict();
        }

        public IDictionary<string, object>? RowByEntityFieldValue(string entityName, string fieldName, object value)
        {
            return ContainerApp.db.Sql(entityName).Where("$"+fieldName+" = @0").Param("@0", value).Cache().Dict();
        }

        public IDictionary<string, object>? RowByEntityUnique(string entityName, IDictionary<string, object> source)
        {
            return ContainerApp.db.Sql(entityName).Unique(source).Cache().Dict();
        }


        public void UpdateValueRelModalidad(string key, object value, Dictionary<string, object> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("modalidad", key, value, source).Exec().RemoveCache();
        }



    }
}
