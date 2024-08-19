using Microsoft.Extensions.Caching.Memory;
using Newtonsoft.Json.Linq;
using SqlOrganize.Sql.Exceptions;
using System.Collections.ObjectModel;
using System.Data.Common;
using System.Net;

namespace SqlOrganize.Sql
{
    public static class ExtensionMethods
    {
        #region EntitySql + Query
        /// <summary>Ejecucion rapida de EntitySql</summary>
        public static IEnumerable<Dictionary<string, object?>> ColOfDict(this EntitySql esql)
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.ColOfDict();
        }

        /// <summary>Ejecucion rapida de EntitySql</summary>
        public static IDictionary<string, object?>? Dict(this EntitySql esql)
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.Dict();
        }

        public static T? Obj<T>(this EntitySql esql) where T : class, new()
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.Obj<T>();
        }

        public static T? Data<T>(this EntitySql esql) where T : Data, new()
        {
            var data = esql.Dict();
            if (data.IsNoE())
                return null;
            return esql.Db.ToData<T>(data!);
        }
        
        public static IEnumerable<T> Column<T>(this EntitySql esql, string columnName)
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.Column<T>(columnName);
        }

        public static IEnumerable<T> Column<T>(this EntitySql esql, int columnNumber = 0)
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.Column<T>(columnNumber);
        }


        public static T? Value<T>(this EntitySql esql, string columnName)
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.Value<T>(columnName);
        }

        public static T? Value<T>(this EntitySql esql, int columnNumber = 0)
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.Value<T>(columnNumber);
        }

        public static IDictionary<string, object?>? DictOne(this EntitySql entitySql)
        {
            IEnumerable<Dictionary<string, object?>> rows = entitySql.ColOfDict();

            if (rows.Count() > 1)
                throw new Exception("La consulta de uno retorno mas de un resultado");

            if (rows.Count() == 1)
                return rows.ElementAt(0);

            else
                return null;
        }
        #endregion

        #region EntityPersist + Query
        /// <summary>Ejecución persistencia</summary>
        public static EntityPersist Exec(this EntityPersist persist)
        {
            if (persist.Sql().IsNoE())
                return persist;

            var query = persist.Query();
            using DbConnection connection = query.OpenConnection();
            query.BeginTransaction();
            try
            {
                query.ExecTransaction();
                query.CommitTransaction();
            }
            catch (Exception ex)
            {
                query.RollbackTransaction();
                throw;
            }
            return persist;
        }

        public static EntityPersist Transaction(this EntityPersist persist)
        {
            return persist.Exec();
        }

        /// <summary>Ejecución de IEnumerable de persistencias</summary>
        public static IEnumerable<EntityPersist> Exec(this IEnumerable<EntityPersist> persists)
        {
            if (persists.IsNoE())
                return persists;

            var query = persists.ElementAt(0).Db.Query();
            using DbConnection connection = query.OpenConnection();
            query.BeginTransaction();
            try
            {
                foreach (EntityPersist persist in persists)
                {
                    if (persist.Sql().IsNoE())
                        continue;
                    persist.Query(query).ExecTransaction();
                }

                query.CommitTransaction();
            }
            catch (Exception ex)
            {
                query.RollbackTransaction();
                throw ex;
            }

            return persists;
        }

        public static IEnumerable<EntityPersist> Transaction(this IEnumerable<EntityPersist> persists)
        {
            return persists.Exec();
        }
        #endregion

        #region EntityPersist + Cache
        public static void RemoveCache(this IEnumerable<EntityPersist> persists)
        {

            if (persists.IsNoE())
                return;

            persists.ElementAt(0).RemoveCacheQueries();

            foreach (EntityPersist persist in persists)
                persist.RemoveCacheDetail();
        }

        public static IMemoryCache RemoveCacheQueries(this IMemoryCache cache)
        {
            List<string> queries;
            object _queries;

            bool res = cache!.TryGetValue("queries", out _queries);
            if (res)
            {
                if (_queries is JArray)
                    queries = (_queries as JArray).ToObject<List<string>>();
                else
                    queries = (List<string>)_queries;

                foreach (string q in (queries as List<string>)!)
                    cache.Remove(q);
            }

            return cache;
        }

        public static EntityPersist RemoveCacheQueries(this EntityPersist persist)
        {
            persist.Db.cache!.RemoveCacheQueries();
            return persist;
        }

        /// <summary>
        /// Remover de la cache todas las consultas y las entidades indicadas en el parametro
        /// </summary>
        public static EntityPersist RemoveCache(this EntityPersist persist)
        {
            return persist.RemoveCacheQueries().RemoveCacheDetail();
        }

        public static EntityPersist RemoveCacheDetail(this EntityPersist persist)
        {
            foreach (var d in persist.detail)
                persist.Db.cache!.Remove(d.entityName + d.id);
            return persist;
        }

        public static EntityPersist RemoveCache(this EntityPersist persist, string entityName, object id)
        {
            persist.RemoveCacheQueries();
            persist.Db.cache!.Remove(entityName + id);
            return persist;
        }

        public static EntityPersist RemoveCache(this EntityPersist persist, EntityValues values)
        {
            persist.RemoveCacheQueries();
            persist.Db.cache!.Remove(values.entityName + values.Get(persist.Db.config.id));
            return persist;
        }
        #endregion

        #region EntitySql + EntityValues
        public static IDictionary<string, object?>? DictUniqueFieldOrValues(this EntityValues values, string fieldName)
        {
            try
            {
                return values.SqlUniqueFieldsOrValues(fieldName).Dict();
            }
            catch (UniqueException ex)
            {
                return null;
            }
        }
        #endregion

        #region Data
        /// <summary> Metodos especiales para facilitar el uso de subclases sin necesidad de cast </summary>
        public static T DefaultData<T>(this T data) where T : Data
        {
            data.Default();
            return data;
        }
        #endregion

        #region Data + EntityValues
        public static IEnumerable<T> ColOfData<T>(this Db db, IEnumerable<Dictionary<string, object?>> rows) where T : Data, new()
        {
            var results = new List<T>();

            foreach (var item in rows)
            {
                T obj = db.ToData<T>(item);
                results.Add(obj);
            }
            return results;
        }


        public static void ClearAndAddDataToOC<T>(this Db db, IEnumerable<Dictionary<string, object?>> source, ObservableCollection<T> oc) where T : Data, new()
        {
            oc.Clear();
            db.AddDataToOC(source, oc);
        }

        public static void AddDataToOC<T>(this Db db, IEnumerable<Dictionary<string, object?>> source, ObservableCollection<T> oc) where T : Data, new()
        {
            for(var i = 0; i < source.Count(); i++) {
                T obj = db.ToData<T>(source.ElementAt(i));
                obj.Index = i;
                oc.Add(obj);
            }
        }


        /// <summary>Obtiene una clase Data a partir de una instancia de Values</summary>
        /// <remarks>Incorpora codigo adicional redefinido en las clases values</remarks>
        public static T ToData<T>(this Db db, IDictionary<string, object?> item) where T : Data, new()
        {
            T _obj = new T(); //crear objeto vacio para obtener el entityName
            return db.Values(_obj.entityName).
                SetValues(item).
                GetData<T>();
        }

        public static EntityValues ToValues(this Db db, Data data, string? fieldId = null)
        {
            return db.Values(data.entityName, fieldId).SetValues(data);
        }

        public static T ToValues<T>(this Db db, Data data, string? fieldId = null) where T : EntityValues
        {
            return (T)db.Values(data.entityName, fieldId).SetValues(data);
        }
        #endregion


    }
}
