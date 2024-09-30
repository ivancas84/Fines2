using Microsoft.Extensions.Caching.Memory;
using Newtonsoft.Json.Linq;
using SqlOrganize.Model;
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
        public static IEnumerable<Dictionary<string, object?>> Dicts(this EntitySql esql)
        {
            using Query query = esql.Query();
            using DbConnection connection = query.OpenConnection();
            return query.Dicts();
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

        public static T? ToEntity<T>(this EntitySql esql) where T : Entity, new()
        {
            var data = esql.Dict();
            if (data.IsNoE())
                return null;
            return Entity.CreateFromDict<T>(data!);
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
            IEnumerable<Dictionary<string, object?>> rows = entitySql.Dicts();

            if (rows.Count() > 1)
                throw new Exception("La consulta de uno retorno mas de un resultado para " + entitySql.entityName);

            if (rows.Count() == 1)
                return rows.ElementAt(0);

            else
                return null;
        }
        #endregion

     
        #region Cache
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
        #endregion

        #region EntitySql + EntityVal
        public static IDictionary<string, object?>? DictUniqueFieldOrValues(this Entity data, string fieldName)
        {
            try
            {
                return data.SqlUniqueFieldsOrValues(fieldName).Dict();
            }
            catch (UniqueException ex)
            {
                return null;
            }
        }
        #endregion

        #region Data + EntityVal
        public static IEnumerable<T> Entities<T>(this Db db, IEnumerable<Dictionary<string, object?>> rows) where T : Entity, new()
        {
            var results = new List<T>();

            foreach (var item in rows)
            {
                T obj = Entity.CreateFromDict<T>(item);
                results.Add(obj);
            }
            return results;
        }


        public static void AddEntityToClearOC<T>(this Db db, IEnumerable<Dictionary<string, object?>> source, ObservableCollection<T> oc) where T : Entity, new()
        {
            oc.Clear();
            db.AddEntityToOC(source, oc);
        }

        public static void AddEntityToOC<T>(this Db db, IEnumerable<Dictionary<string, object?>> source, ObservableCollection<T> oc) where T : Entity, new()
        {
            for (var i = 0; i < source.Count(); i++) {
                T obj = Entity.CreateFromDict<T>(source.ElementAt(i));
                obj.Index = i;
                oc.Add(obj);
            }
        }        
        #endregion


        #region EntityVal
        public static IDictionary<string, object?> ValuesTree(this Db db, IDictionary<string, object?> values, string entityName)
        {
            Dictionary<string, object?> response = new();

            if (values.ContainsKey("Label"))
                response["Label"] = values["Label"];

            foreach (string fieldName in db.FieldNames(entityName))
            {
                if (values.ContainsKey(fieldName))
                    response[fieldName] = values[fieldName];

            }

            db.ValuesTreeRecursive(values, db.Entity(entityName).tree, response);

            return response;
        }

        public static void ValuesTreeRecursive(this Db db, IDictionary<string, object?> values, IDictionary<string, EntityTree> tree, IDictionary<string, object?> response)
        {
            foreach (var (fieldId, et) in tree)
            {
                if (response.ContainsKey(et.fieldName) && !response[et.fieldName].IsNoE())
                {
                    response[et.fieldName + "_"] = new Dictionary<string, object?>();

                    if (values.ContainsKey(fieldId + db.config.separator + "Label"))
                        (response[et.fieldName + "_"] as Dictionary<string, object?>)!["Label"] = values[fieldId + db.config.separator + "Label"];

                    foreach (string fieldName in db.FieldNames(et.refEntityName))
                    {
                        if (values.ContainsKey(fieldId + db.config.separator + fieldName))
                            (response[et.fieldName + "_"] as Dictionary<string, object?>)![fieldName] = values[fieldId + db.config.separator + fieldName];
                    }

                    if (!et.children.IsNoE())
                        db.ValuesTreeRecursive(values, et.children, (response[et.fieldName + "_"] as Dictionary<string, object?>)!);
                }
            }
        }

        /// <summary>Retorna una lista de los fields principales</summary>
        /// <remarks>Utilizados principalmente para Label</remarks>
        public static List<string> MainKeys(this Db db, string entityName)
        {
            var entity = db.Entity(entityName);
            List<string> fields = new();
            foreach (string f in entity.unique)
                if (entity.notNull.Contains(f))
                    fields.Add(f);

            bool uniqueMultipleFlag = true;
            foreach (List<string> um in entity.uniqueMultiple)
            {
                foreach (string f in um)
                    if (!entity.notNull.Contains(f))
                    {
                        uniqueMultipleFlag = false;
                        break;
                    }

                if (uniqueMultipleFlag)
                    foreach (var f in um)
                        fields.Add(f);

                uniqueMultipleFlag = true;
            }

            if (fields.IsNoE())
                fields = entity.notNull;

            if (fields.IsNoE())
                fields = entity.fieldNames;

            if (fields.Count() > 1 && fields.Contains(db.config.id))
                fields.Remove(db.config.id);

            return fields;
        }


        public static (string? fieldId, string fieldName, string entityName, object? value) ParentVariables(this Db db, string mainEntityName, IDictionary<string, object?> values, string fieldId)
        {
            object? value;
            string fieldName;
            string entityName = mainEntityName;
            string? newFieldId = null;

            string? parentId = db.Entity(mainEntityName).relations[fieldId!].parentId;
            if (parentId != null)
            {
                //sea por ejemplo alumnoT.personaF (con fieldId alumno) = personaT.id (con fieldId = persona), entones:
                //parentFieldName = personaF
                //value = personaValues.values["id"]
                //fieldId = alumno
                //fieldName = personaF
                //entityName = alumnoT
                string parentFieldName = db.Entity(mainEntityName).relations[fieldId!].fieldName;
                value = values[db.Entity(mainEntityName).relations[fieldId!].refFieldName];
                newFieldId = parentId;
                fieldName = parentFieldName;
                entityName = db.Entity(mainEntityName).relations[parentId].refEntityName;

            }
            else
            {
                fieldName = db.Entity(mainEntityName).relations[fieldId!].fieldName;
                value = values[db.Entity(mainEntityName).relations[fieldId!].refFieldName];
            }

            return (newFieldId, fieldName, entityName, value);
        }

        /// <summary>
        /// Reset _Id
        /// </summary>
        /// <remarks>_Id depende de otros valores de la misma entidad, se reasigna luego de definir el resto de los valores</remarks>
        /// <example>db.Values("entityName").Set(source).Set("_Id", null).Reset("_Id"); //inicializa y reasigna _Id individualmente //<br/>
        /// db.Values("entityName").Set(source).Default().Reset() //inicializa y reasigna _Id conjuntamente</example>
        /// <returns></returns>
        public static void ResetId(this Db db, string entityName, IDictionary<string, object?> values)
        {
            List<string> fieldsId = db.Entity(entityName).id;
            foreach (string fieldName in fieldsId)
                if (!values.ContainsKey(fieldName) || values[fieldName].IsNoE())
                    return; //no se reasigna si no esta definido o si es distinto de null

            if (fieldsId.Count == 1)
            {
                values["_Id"] = values[fieldsId[0]].ToString();
                return;
            }

            List<string> valuesId = new();
            foreach (string fieldName in fieldsId)
                valuesId.Add(values[fieldName].ToString()!);

            values["_Id"] = String.Join(db.config.concatString, valuesId);
        }
        #endregion


        /// <summary>Formato SQL</summary>
        /// <remarks>La conversion de formato es realizada directamente por la libreria SQL, pero para ciertos casos puede ser necesario una transformación directa</remarks>
        public static object SqlValue(this Db db, string entityName, string fieldName, object value)
        {
            if (value == null)
                return "null";

            Field field = db.Field(entityName, fieldName);

            switch (field.dataType) //solo funciona para tipos especificos, para mapear correctamente deberia almacenarse en field, el tipo original sql.
            {
                case "varchar":
                    return "'" + (string)value + "'";

                case "datetime": //puede que no funcione correctamente, es necesario almacenar el tipo original sql
                    return "'" + ((DateTime)value).ToString("u");

                default:
                    return value;

            }

        }

    

    }
}
