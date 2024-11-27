using Newtonsoft.Json;
using SqlOrganize.Sql.Exceptions;
using System.Text.RegularExpressions;
using SqlOrganize.ValueTypesUtils;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Model;
using Newtonsoft.Json.Linq;
using Microsoft.Extensions.Caching.Memory;
using Dapper;

namespace SqlOrganize.Sql
{

    /// <summary>
    /// Selección de datos de una entidad
    /// </summary>
    /// <remarks>
    /// Los fields se traducen con los metodos de mapeo, deben indicarse con el prefijo. Ej "($ingreso = %p1) AND (MAX($persona__nombres) = %p1)"
    /// </remarks>
    public abstract class SelectSql
    {

        public Db Db { get; }

        public SelectSql(Db db)
        {
            Db = db;
        }


        /// <summary>  Definir sql a partir de los campos unicos de una entidad </summary>
        /// <param name="obj"></param>
        /// <returns></returns>
        public string Unique(Entity obj)
        {
            var row = obj.ToDict();

            EntityMetadata metadata = Db.Entity(obj.entityName);

            if (row.IsNoE())
                throw new UniqueException("El parametro de condicion unica esta vacio");

            List<string> whereUniqueList = new();
            foreach (string fieldName in metadata.unique)
            {
                foreach (var (key, value) in row)
                {
                    string k = key.Replace("$", "");

                    if (k == fieldName)
                    {
                        if (value.IsNoE())
                        {
                            continue; // por el momento se ignoral los campos unicos nulos!!!
                            whereUniqueList.Add(k + " IS NULL");
                            break;
                        }
                        whereUniqueList.Add(k + " = @" + k);
                        break;
                    }
                }
            }

            string w = "";
            if (whereUniqueList.Count > 0)
                w = "(" + String.Join(") OR (", whereUniqueList) + ")";

            string ww;
            foreach (var um in metadata.uniqueMultiple)
            {
                ww = UniqueMultiple(um, row);
                if (!ww.IsNoE())
                    w += (w.IsNoE()) ? ww : " OR " + ww;
            }

            ww = UniqueMultiple(metadata.pk, row);
            if (!ww.IsNoE())
                w += (w.IsNoE()) ? ww : " OR " + ww;

            if (w.IsNoE())
                throw new UniqueException("No se pudo definir condicion de campo unico con el parametro indicado");

            var sql = "SELECT DISTINCT ";
            sql += SqlFieldsSimple(obj.entityName);
            sql += SqlFrom(obj.entityName);
            sql += " WITH (NOLOCK)";
            sql += " WHERE " + w;

            return sql;
        }

        protected string UniqueMultiple(List<string> fields, IDictionary<string, object?> param)
        {
            if (fields.IsNoE())
                return "";

            bool existsUniqueMultiple = true;
            List<string> whereMultipleList = new();
            foreach (string field in fields)
            {
                if (!existsUniqueMultiple)
                    break;

                existsUniqueMultiple = false;

                foreach (var (key, value) in param)
                {
                    string k = key.Replace("$", "");
                    if (k == field)
                    {
                        existsUniqueMultiple = true;
                        if (value == null)
                        {
                            whereMultipleList.Add(k + " IS NULL");
                            break;
                        }
                        whereMultipleList.Add(k + " = @" + k);
                        break;
                    }
                }


            }
            if (existsUniqueMultiple && whereMultipleList.Count > 0)
                return "(" + String.Join(") AND (", whereMultipleList) + ")";

            return "";
        }

        protected string SqlFieldsSimple(string entityName)
        {
            EntityMetadata metadata = Db.Entity(entityName);
            string sql = "";

            foreach (var fieldName in metadata.fieldNames)
                sql += Db.Mapping(metadata.name).Map(fieldName) + ", ";
            return sql.RemoveLastChar(',');

        }


        protected string SqlFrom(string entityName)
        {
            EntityMetadata metadata = Db.Entity(entityName);
            return @"FROM " + metadata.schemaName + " AS " + metadata.alias + @"
";
        }

        public string ById(string entityName)
        {
            var sql = "SELECT DISTINCT ";
            sql += SqlFieldsSimple(entityName);
            sql += SqlFrom(entityName);
            sql += " WHERE " + Db.config.id + " = @Id";

            return sql;
        }

        public string ByKey(string entityName, string key)
        {
            var sql = "SELECT DISTINCT ";
            sql += SqlFieldsSimple(entityName);
            sql += SqlFrom(entityName);
            sql += " WHERE " + key + " = @Key";

            return sql;
        }

        /// <summary> Existencia de key = value </summary>
        /// <remarks> Utilizar connection.ExecuteScalar<bool>(sql); </remarks>
        public string ExistsKey(string entityName, string key)
        {
            var sql = "SELECT 1 ";
            sql += SqlFrom(entityName);
            sql += " WHERE " + key + " = @Key";

            return sql;
        }

        /// <summary> Existencia de key = value </summary>
        /// <remarks> Utilizar connection.QuerySingleOrDefault<Guid?> </remarks>
        public string IdKey(string entityName, string key)
        {
            return @"
                SELECT id 
                FROM " + entityName + @"
                WHERE " + key + " = @Key;";
        }

        public string MaxValue(string entityName, string fieldName)
        {
            return @"
                SELECT ISNULL( MAX($" + fieldName + @"), 0)
                FROM " + entityName + @";
            ";
        }



        public string SelectDapper(string entityName)
        {
            var sql = "SELECT DISTINCT ";
            sql += SqlFieldsDapper(entityName);
            sql += SqlFrom(entityName);
            sql += SqlJoin(entityName);

            return sql;
        }

        public string SelectIdDapper(string entityName)
        {
            var sql = "SELECT DISTINCT " + Db.config.id + @"
";
            sql += SqlFrom(entityName);
            sql += SqlJoin(entityName);

            return sql;
        }

        public string SplitOn(string entityName)
        {
            List<string> fieldSplitOn = new();
            if (!Db.Entity(entityName).tree.IsNoE())
                foreach ((string fieldId, EntityRelation er) in Db.Entity(entityName).relations)
                    fieldSplitOn.Add(fieldId + Db.config.separator + Db.config.id);

            return String.Join(",", fieldSplitOn);
        }

        protected string SqlFieldsDapper(string entityName)
        {
            List<string> fields = Db.FieldNamesRel(entityName);


            string sql = "";

            foreach (var fieldName in fields)
            {
                if (fieldName.Contains(Db.config.separator))
                {
                    List<string> ff = fieldName.Split(Db.config.separator).ToList();
                    string refEntityName = Db.Entity(entityName).relations[ff[0]].refEntityName;
                    sql += Db.Mapping(refEntityName, ff[0]).Map(ff[1]);

                    if (ff[1].Equals(Db.config.id))
                        sql += " AS '" + fieldName + "'";
                    sql += ", ";
                }
                else
                    sql += Db.Mapping(entityName).Map(fieldName) + ", ";
            }
            sql = sql.RemoveLastChar(',');

            return sql + @"
";
        }

        protected string TraduceFieldsAs(string entityName, string _sql)
        {
            if (_sql.IsNoE())
                return "";

            List<string> fields = _sql!.Split(',').ToList();

            string sql = "";

            foreach (var fieldName in fields)
            {
                if (fieldName.Contains(Db.config.separator))
                {
                    List<string> ff = fieldName.Split(Db.config.separator).ToList();
                    sql += Db.Mapping(Db.Entity(entityName).relations[ff[0]].refEntityName, ff[0]).Map(ff[1]) + " AS '" + fieldName + "', ";
                }
                else
                    sql += Db.Mapping(entityName).Map(fieldName) + " AS '" + fieldName + "', ";
            }
            sql = sql.RemoveLastChar(',');

            return sql;
        }

        public string SqlJoin(string entityName)
        {
            string sql = "";
            if (!Db.Entity(entityName).tree.IsNoE())
                sql += SqlJoinFk(Db.Entity(entityName).tree!, "", entityName, true);

            return sql;
        }

        protected string SqlJoinFk(Dictionary<string, EntityTree> tree, string table_id, string entityName, bool checkInner)
        {
            if (table_id.IsNoE())
                table_id = Db.Entity(entityName).alias;

            string sql = "";
            string schema_name;
            foreach (var (field_id, entity_tree) in tree)
            {
                schema_name = Db.Entity(entity_tree.refEntityName).schemaName;
                Field field = Db.Field(entityName, entity_tree.fieldName);
                string join = "";
                if (field.IsRequired() && checkInner)
                    join = "INNER";
                else
                {
                    join = "LEFT OUTER";
                    checkInner = false;
                }

                //string join = Db.Field(entityName, entity_tree.fieldName).IsRequired() ? "INNER" : "LEFT OUTER";
                sql += join + " JOIN " + schema_name + " AS " + field_id + " ON (" + table_id + "." + entity_tree.fieldName + " = " + field_id + "." + entity_tree.refFieldName + @")
";

                if (!entity_tree.children.IsNoE()) sql += SqlJoinFk(entity_tree.children, field_id, entity_tree.refEntityName, checkInner);
            }
            return sql;
        }

        public static string And(string where, string cond)
        {
            if (where.IsNoE())
                where += " WHERE " + cond;
            else
                where += " AND (" + cond + ")";

            return where;
        }

        #region metodos especiales que generan sql y devuelven directamente el valor
        public abstract IEnumerable<string> GetTableNames();

        /// <summary> Cada motor debe tener su propia forma de definir Next Value!!! Derivar metodo a subclase  </summary>
        /// <returns></returns>
        public abstract object GetNextValue(string entityName, string fieldName);

        #endregion


        
    }
}