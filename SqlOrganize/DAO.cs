using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace SqlOrganize
{

    /// <summary>
    /// Metodos de acceso a datos de uso general
    /// </summary>
    public class DAO
    {
        protected Db Db;

        public DAO(Db db) {
            this.Db = db;
        }

        public IEnumerable<Dictionary<string, object>> Search<T>(string entityName, T param) where T : class
        {
            return Db.Query(entityName).Search(param).Size(0).ColOfDictCache();
        }

        public EntityPersist UpdateValueRel(string entityName, string key, object value, Dictionary<string, object> source)
        {
            return Db.Persist(entityName).UpdateValueRel(key, value, source).Exec().RemoveCache();
        }

        public IDictionary<string, object> Get(string entityName, object id)
        {
            return Db.Query(entityName).CacheByIds(id).ElementAt(0);
        }

        public IDictionary<string, object>? RowByFieldValue(string entityName, string fieldName, object value)
        {
            return Db.Query(entityName).Where("$" + fieldName + " = @0").Parameters(value).DictCache();
        }

        public IDictionary<string, object>? RowByUniqueWithoutIdIfExists(string entityName, IDictionary<string, object?> source)
        {

            var q = Db.Query(entityName).Unique(source);

            if (source.ContainsKey(Db.config.id) && !source[Db.config.id]!.IsNullOrEmptyOrDbNull())
                q.Where("($" + Db.config.id + " != @0)").Parameters(source[Db.config.id]!);

            return q.DictCache();
        }

        public void Persist(EntityValues v)
        {
            if (v.Get(Db.config.id).IsNullOrEmptyOrDbNull())
            {
                v.Default().Reset();
                var p = Db.Persist(v.entityName).Insert(v.values).Exec().RemoveCache();
            }
            else
            {
                v.Reset();
                var p = Db.Persist(v.entityName).Update(v.values).Exec().RemoveCache();
            }
        }


        public IDictionary<string, object>? RowByUniqueFieldOrValues(string fieldName, EntityValues values)
        {
            try { 
                if (Db.Field(values.entityName, fieldName).IsUnique())
                    return RowByFieldValue(values.entityName, fieldName, values.Get(fieldName));
                else
                    return RowByUniqueWithoutIdIfExists(values.entityName, values.Get());
            } catch (Exception ex)
            {
                return null;
            }
        }

    }
}
