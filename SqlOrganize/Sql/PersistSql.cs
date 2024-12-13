using Microsoft.Extensions.Caching.Memory;
using Newtonsoft.Json.Linq;
using SqlOrganize.CollectionUtils;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;

namespace SqlOrganize.Sql
{
    public abstract class PersistSql
    {
        /// <summary> Detalle de elementos persistidos </summary>
        /// <remarks> Para poder identificar rapidamente todas las entidades que se modificaron de la base de datosc</remarks>
        public List<(string entityName, object id, string action)> detail = new();


        public Db Db { get; }

        public PersistSql(Db db)
        {
            Db = db;
        }

        public string Persist<T>(T data) where T : Entity
        {
            data.Reset();

            if (!data.Check())
                throw new Exception("Los campos a persistir poseen errores: " + data.Logging.ToString());

            T? row = data.Unique<T>();

            if (!row.IsNoE())
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");

                data.Set(Db.config.id, row!.Get(Db.config.id));

                CompareParams cmp = new()
                {
                    IgnoreNonExistent = true,
                    IgnoreNull = false,
                    Data = row.ToDict()
                };

                if (!data.Compare(cmp).IsNoE())
                {
                    return Update(data!);
                }

                return ""; //registro identico
            }

            return Insert(data);
        }

        public object PersistCondition(Entity data, object? condition)
        {
            data.Reset();

            if (!data.Check())
                throw new Exception("Error al persistir: " + data.Logging.ToString());


            if (condition.IsNoE())
                return Insert(data);

            return Update(data);
        }

        /// <summary> Si la comparación es diferente, no actualiza! sino actualiza todo! </summary>
        public object PersistCompare<T>(T data, CompareParams compare) where T : Entity
        {
            data.Reset();

            if (!data.Check())
                throw new Exception("Los campos a persistir poseen errores: " + data.Logging.ToString());

            T? row = data.Unique<T>();

            if (!row.IsNoE()) //actualizar
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");
                compare.Data = row.ToDict()!;
                var response = data.Compare(compare);

                if (!response.IsNoE())
                    throw new Exception("Comparacion diferente: " + compare.Data.ToStringKeys(response.Keys.ToArray()));

                data.SetPropertyValue(Db.config.id, row[Db.config.id]);

                return Update(data);
            }

            return Insert(data);
        }


        public string Update(Entity data)
        {
            return Update(data.entityName, data.ToDict()!);
        }

        protected string Update(string entityName, IDictionary<string, object?> row)
        {
            string sql = _Update(entityName, row!);

            string id = Db.Mapping(entityName!).Map(Db.config.id);
            sql += @"
WHERE " + id + " = @" + Db.config.id + @";
";

            detail.Add((entityName!, row[Db.config.id]!, "update"));

            return sql;
        }

        protected abstract string _Update(string entityName, IDictionary<string, object?> row);


        public string UpdateCompare(Entity dataToUpdate, Entity dataToCompare)
        {
            dataToUpdate.Set(Db.config.id, dataToCompare.Get(Db.config.id));

            CompareParams cmp = new()
            {
                IgnoreNonExistent = true,
                IgnoreNull = false,
                Data = dataToCompare.ToDict()
            };

            if (!dataToUpdate.Compare(cmp).IsNoE())
                return Update(dataToUpdate);

            return ""; //registro identico
        }

        public string UpdateKeyId(Entity entity, string key, object? id = null)
        {
            return UpdateKeyId(entity.entityName, key, id);
        }

        public string UpdateKeyId(string entityName, string key, object? id = null)
        {
            EntityMetadata e = Db.Entity(entityName);
            string idMap = Db.Mapping(entityName!).Map(Db.config.id);

            string sql = @"
                UPDATE " + e.alias + @" SET " + key + @" = @Key
                FROM " + e.schemaNameAlias + @";
                WHERE " + idMap + " = @Id;";

            if(!id.IsNoE())
                detail.Add((entityName!, id, "update"));

            return sql;
        }

        public string UpdateKeyIds(Entity entity, string key)
        {
            return UpdateKeyIds(entity.entityName, key);
        }

        public string UpdateKeyIds(string entityName, string key, params object[] ids)
        {
            EntityMetadata e = Db.Entity(entityName);
            string idMap = Db.Mapping(entityName!).Map(Db.config.id);

            string sql = @"
                UPDATE " + e.alias + @" SET " + key + @" = @Keys
                FROM " + e.schemaNameAlias + @";
                WHERE " + idMap + " IN (@Ids);";

            foreach(var id in ids)
                detail.Add((entityName!, id, "update"));

            return sql;
        }


        /// <summary> Actualizar sin parametros </summary>
        /// <remarks> Transforma los ids y actualiza sin utilizar parametros. Recomendado cuando el sql tiene mas de 2600 parametros </remarks>
        public string UpdateRowIds(string entityName, Dictionary<string, object?> row, params object[] ids)
        {
            string sql = _Update(entityName, row);

            string idMap = Db.Mapping(entityName!).Map(Db.config.id);

            List<object> ids_ = new();
            foreach (var id in ids)
            {
                var id_ = Db.SqlValue(entityName, Db.config.id, id);
                ids_.Add(id_);
                detail.Add((entityName!, id, "update"));

            }
            sql += @"WHERE " + idMap + " IN (" + String.Join(", ", ids_) + @");
";

            return sql;
        }

        /// <summary>Insercion de EntityVal</summary>
        public string Insert(Entity data)
        {
            var row = data.ToDict();

            List<string> fieldNames = Db.FieldNamesAdmin(data.entityName!);
            Dictionary<string, object?> row_ = new();
            foreach (string key in row.Keys)
                if (fieldNames.Contains(key))
                    row_.Add(key, row[key]!);

            string sn = Db.Entity(data.entityName).schemaName;
            string sql = "INSERT INTO " + sn + @" (" + String.Join(", ", row_.Keys) + @") 
VALUES (";

            foreach (var (key, value) in row_)
                sql += "@" + key + ", ";

            sql = sql.RemoveLastChar(',');
            sql += @");
";

            detail.Add((data.entityName!, data.Get(Db.config.id)!, "insert"));

            return sql;
        }


        /// <summary>
        /// Verifica, a traves de campos unicos, si el registro existe.
        /// Si existe, realiza una comparacion, 
        /// </summary>
        public string InsertIfNotExistsCompare<T>(T data, CompareParams compare) where T : Entity
        {
            data.Reset();

            T? row = data.Unique<T>();

            if (row.IsNoE()) //actualizar
            {
                if (!data.Check())
                    throw new Exception("Los campos a insertar poseen errores: " + data.Logging.ToString());

                return Insert(data);
            }
            else
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");
                compare.Data = row.ToDict()!;
                var response = data.Compare(compare);

                if (!response.IsNoE())
                    throw new Exception("Comparacion diferente: " + response.ToStringDict());

                data.Sset(Db.config.id, row[Db.config.id]);
            }

            return row[Db.config.id];
        }

        public string DeleteIds(Entity entity)
        {
            return DeleteIds(entity.entityName);
        }

        /// <summary>
        /// Eliminar
        /// </summary>
        /// <param name="entityName"> nombre de la entidad </param>
        /// <param name="ids"> opcional para registrar el detalle y actualizar cache posteriormente </param>
        /// <returns> sql para eliminar con parametro Ids para asignar </returns>
        public string DeleteIds(string entityName, params object[] ids)
        {
            EntityMetadata e = Db.Entity(entityName);
            string idMap = Db.Mapping(entityName!).Map(Db.config.id);

            foreach(var id in ids)
                detail.Add((entityName!, id, "delete"));

            return @"
                DELETE " + e.alias + " FROM " + e.name + " " + e.alias + @"
                WHERE " + idMap + @" IN (@Ids);
";
        }

        /// <summary>
        /// Eliminar
        /// </summary>
        /// <param name="entity"> Entidad </param>
        /// <param name="id"> opcional para registrar el detalle y actualizar cache posteriormente </param>
        /// <returns> sql para eliminar con parametro Id para asignar </returns>
        public string DeleteId(Entity entity, object? id = null)
        {
            return DeleteId(entity.entityName, id);
        }

        public string DeleteId(string entityName, object? id = null)
        {
            EntityMetadata e = Db.Entity(entityName);
            string idMap = Db.Mapping(entityName!).Map(Db.config.id);

            if (!id.IsNoE())
                detail.Add((entityName!, id, "delete"));

            return @"
                DELETE " + e.alias + " FROM " + e.name + " " + e.alias + @"
                WHERE " + idMap + @" = (@Id);
";
        }

        public void RemoveCache()
        {
            if(detail.Any())
                Db.Cache!.Remove("queries");

            foreach (var d in detail)
                Db.Cache!.Remove(d.entityName + d.id);

        }

        
    }
}
