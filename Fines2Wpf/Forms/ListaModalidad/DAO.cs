﻿using Google.Protobuf.WellKnownTypes;
using SqlOrganize;
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
            return ContainerApp.db.Query("modalidad").ColOfDictCache();
        }

        public IDictionary<string, object>? RowByEntityFieldValue(string entityName, string fieldName, object value)
        {
            return ContainerApp.db.Query(entityName).Where("$"+fieldName+" = @0").Parameters(value).DictCache();
        }

        public IDictionary<string, object>? RowByEntityUnique(string entityName, IDictionary<string, object> source)
        {
            return ContainerApp.db.Query(entityName).Unique(source).DictCache();
        }


        public void UpdateValueRelModalidad(string key, object value, Dictionary<string, object> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("modalidad", key, value, source).Exec().RemoveCache();
        }



    }
}
