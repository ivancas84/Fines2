﻿using System.Data.Common;
using Newtonsoft.Json.Linq;
using SqlOrganize.Model;
using SqlOrganize.Sql.Exceptions;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql
{

    /// <summary>
    /// Persistencia de datos
    /// </summary>
    public abstract class EntityPersist
    {
        /// <summary>conexion opcional</summary>
        protected DbConnection? connection;

        /// <summary>transaccion opcional</summary>
        protected DbTransaction transaction;

        public Logging logging = new Logging();

        public Db Db { get; }

        public List<object?> parameters { get; set; } = new List<object?> { };

        public int count = 0;

        /// <summary>
        /// El SQL se genera a medida que se invocan los distintos metodos de generacion.
        /// </summary>
        public string sql { get; set; } = "";

        /// <summary>
        /// Sintesis de elementos persistidos
        /// </summary>
        /// <remarks>
        /// Para poder identificar rapidamente todas las entidades que se modificaron de la base de datos
        /// </remarks>
        public List<(string entityName, object id, string action)> detail = new();


        public EntityPersist SetConn(DbConnection connection)
        {
            this.connection = connection;
            return this;
        }

        public EntityPersist(Db db)
        {
            Db = db;
        }

        public EntityPersist Parameters(params object[] parameters)
        {
            this.parameters.AddRange(parameters.ToList());
            return this;
        }

        /// <summary>
        /// Se separa el método WhereIds para procesar la cantidad de parametros
        /// </summary>
        /// <param name="ids"></param>
        /// <param name="_entityName"></param>
        /// <returns></returns>
        protected void WhereIds(string entityName, params object[] ids)
        {
            string idMap = Db.Mapping(entityName!).Map(Db.config.id);

            if ((ids.Count() + count) > 2100) //SQL Server no admite mas de 2100 parametros, se define consulta alternativa para estos casos
            {
                List<object> ids_ = new();
                var v = Db.Values(entityName!);
                foreach (var id in ids)
                {
                    v.Set(Db.config.id, id);
                    var id_ = v.Sql(Db.config.id);
                    ids_.Add(id_);
                    detail.Add((entityName!, id, "delete"));

                }
                sql += @"WHERE " + idMap + " IN (" + String.Join(",", ids_) + @");
";
            }
            else
            {
                sql += @"WHERE " + idMap + " IN (@" + count + @");
";
                count++;
                parameters.Add(ids.ToList());

                foreach (var id in ids)
                    detail.Add((entityName!, id, "delete"));
            }
        }

        public EntityPersist DeleteIds(string _entityName, params object[] ids)
        {
            Entity e = Db.Entity(_entityName);

            sql += @"
DELETE " + e.alias + " FROM " + e.name + " " + e.alias + @"
";

            WhereIds(_entityName, ids);

            return this;
        }

        abstract protected IDictionary<string, object?> _Update(string _entityName, IDictionary<string, object?> row);

        public EntityPersist Update(EntityValues values)
        {
            return Update(values.entityName, values.Values());
        }

        public EntityPersist Update(string _entityName, IDictionary<string, object?> row)
        {
            IDictionary<string, object?> _row = _Update(_entityName, row);
            string id = Db.Mapping(_entityName!).Map(Db.config.id);
            sql += @"
WHERE " + id + " = @" + count + @";
";
            count++;
            parameters.Add(row[Db.config.id]!);
            detail.Add((_entityName!, row[Db.config.id]!, "update"));
            logging.AddLog(_entityName, "registro actualizado " + _row.ToStringKeyValuePair(), "update", Logging.Level.Info);

            return this;
        }

        public EntityPersist UpdateIds(string _entityName, Dictionary<string, object?> row, params object[] ids)
        {
            IDictionary<string, object?> _row = _Update(_entityName, row);

            string idMap = Db.Mapping(_entityName!).Map(Db.config.id);

            if ((ids.Count() + count) > 2100) //SQL Server no admite mas de 2100 parametros, se define consulta alternativa para estos casos
            {
                List<object> ids_ = new();
                var v = Db.Values(_entityName!);
                foreach (var id in ids)
                {
                    v.Set(Db.config.id, id);
                    var id_ = v.Sql(Db.config.id);
                    ids_.Add(id_);
                    detail.Add((_entityName!, id, "update"));

                }
                sql += @"WHERE " + idMap + " IN (" + String.Join(", ", ids_) + @");
";
            } else
            {
                sql += @"WHERE " + idMap + " IN (@" + count + @");
";
                count++;
                parameters.Add(ids);

                foreach (var id in ids)
                    detail.Add((_entityName!, id, "update"));
            }

            logging.AddLog(_entityName, "registros actualizados " + _row.ToStringKeyValuePair(), "update", Logging.Level.Info);

            return this;
        }

        /// <summary>
        /// Actualizar valores de todas las entradas de una tabla
        /// </summary>
        /// <param name="row"></param>
        /// <param name="_entityName"></param>
        /// <remarks>USAR CON PRECAUCIÓN!!!</remarks>
        /// <returns></returns>
        public EntityPersist UpdateAll(string _entityName, Dictionary<string, object?> row)
        {
            object[] ids = Db.Sql(_entityName).Fields(Db.config.id).Size(0).Column<object>().ToArray();
            return (ids.Count() > 0) ? UpdateIds(_entityName, row, ids) : this;
        }

        public EntityPersist UpdateValue(EntityValues values, string fieldName, object? newValue)
        {
            return UpdateValueIds(values.entityName, fieldName, newValue, values.Get("id"));
        }


        /// <summary>
        /// Actualizar un unico campo
        /// </summary>
        /// <param name="key">Nombre del campo a actualizar</param>
        /// <param name="value">Valor del campo a actualizar</param>
        /// <param name="id">Identificacion de la fila a actualizar</param>
        /// <param name="_entityName">Nombre de la entidad, si no se especifica se toma el atributo</param>
        /// <returns>Mismo objeto</returns>
        public EntityPersist UpdateValueIds(string _entityName, string key, object? value, params object[] ids)
        {
            Dictionary<string, object?> row = new Dictionary<string, object?>()
            {
                { key, value }
            };
            return UpdateIds(_entityName, row, ids);
        }

        /// <summary>
        /// Actualizar un unico campo todas las entradas existentes en la base de datos
        /// </summary>
        /// <param name="key">Nombre del campo a actualizar</param>
        /// <param name="value">Valor del campo a actualizar</param>
        /// <param name="id">Identificacion de la fila a actualizar</param>
        /// <param name="_entityName">Nombre de la entidad, si no se especifica se toma el atributo</param>
        /// <returns>Mismo objeto</returns>
        public EntityPersist UpdateValueAll(string _entityName, string key, object value)
        {
            Dictionary<string, object> row = new Dictionary<string, object>() { { key, value } };
            return UpdateAll(_entityName, row);
        }


        public EntityPersist UpdateValueWhere(string _entityName, string key, object value, string where, params object[] parameters)
        {
            object[] ids = Db.Sql(_entityName).Where(where).Parameters(parameters).Column<object>(Db.config.id).ToArray();
            if(ids.Any())
                return UpdateValueIds(_entityName, key, value, ids);
            return this;
        }


        public EntityPersist UpdateKeyFromData<T>(string entityName, string key, T data) where T : Data
        {
            string separator = null;
            string idKey = Db.config.id;
            string _key = key;

            if (key.Contains("__"))
                separator = "__";

            if (!separator.IsNoE())
            {
                int indexSeparator = key.IndexOf(separator!);
                string fieldId = key.Substring(0, indexSeparator);
                entityName = Db.Entity(entityName!).relations[fieldId].refEntityName;
                idKey = fieldId + separator + Db.config.id;
                _key = key.Substring(indexSeparator + separator!.Length); //se suma la cantidad de caracteres del separador
            }

            return UpdateValueIds(entityName, _key, data.GetPropertyValue(key), data.GetPropertyValue(idKey)!);
        }
        /// <summary>
        /// Actualiza valor local o de relacion
        /// </summary>
        /// <param name="key">Nombre del campo a actualizar</param>
        /// <param name="value">Nuevo valor del campo a actualizar</param>
        /// <param name="source">Fuente con todos los valores sin actualizar</param>
        /// <param name="_entityName">Opcional nombre de la entidad, si no existe toma el atributo</param>
        /// <returns>Mismo objeto</returns>
        public EntityPersist UpdateKeyValueFromSourceRel(string _entityName, string key, object? value, IDictionary<string, object?> source)
        {
            string separator = null;
            if (key.Contains("__"))
                separator = "__";
            else if (key.Contains("-"))
                separator = "-";

            string idKey = Db.config.id;

            if(!separator.IsNoE())
            {
                int indexSeparator = key.IndexOf(separator!);
                string fieldId = key.Substring(0, indexSeparator);
                _entityName = Db.Entity(_entityName!).relations[fieldId].refEntityName;
                idKey = fieldId + separator + Db.config.id;
                key = key.Substring(indexSeparator + separator!.Length); //se suma la cantidad de caracteres del separador
            }

            return UpdateValueIds(_entityName, key, value, source[idKey]!);
        }

        /// <summary>Insercion de objeto</summary>
        /// <remarks>Transforma el objeto en un diccionario y ejecuta insercion basica</remarks>
        public EntityPersist Insert(string _entityName, Data obj)
        {
            IDictionary<string, object?> dict = obj.Dict();
            return Insert(_entityName, dict);
        }

        public EntityPersist InsertIfNotExists(EntityValues values)
        {
            values.Reset();

            IDictionary<string, object?>? row = values.SqlUnique().DictOne() ?? null;

            if (row.IsNoE()) //actualizar
            {
                if (!values.Default().Reset().Check())
                    throw new Exception("Los campos a insertar poseen errores: " + values.Logging.ToString());

                return Insert(values);
            }

            logging.AddLog(values.entityName, "Registro existente " + values.ToString(), "insert_if_not_exists", Logging.Level.Info);
            values.Sset("id", row!["id"]);
            return this;
        }



        /// <summary>Insercion de EntityValues</summary>
        /// <remarks>Define id si no existe</remarks>
        public EntityPersist Insert(EntityValues v)
        {
            if (v.GetOrNull(Db.config.id).IsNoE())
                v.SetDefault(Db.config.id);
            return Insert(v.entityName, v.Values()!);
        }


        /// <summary>Comportamiento basico de insercion</summary>
        /// <remarks>Debe estar definido el id</remarks>
        public EntityPersist Insert(string _entityName, IDictionary<string, object?> row)
        {
            List<string> fieldNames = Db.FieldNamesAdmin(_entityName!);
            Dictionary<string, object?> row_ = new();
            foreach (string key in row.Keys)
                if (fieldNames.Contains(key))
                    row_.Add(key, row[key]!);

            string sn = Db.Entity(_entityName!).schemaName;
            sql += "INSERT INTO " + sn + @" (" + String.Join(", ", row_.Keys) + @") 
VALUES (";


            foreach (object? value in row_.Values)
            {
                sql += "@" + count + ", ";
                parameters.Add(value);
                count++;
            }

            sql = sql.RemoveLastChar(',');
            sql += @");
";
            detail.Add((_entityName!, row[Db.config.id]!, "insert"));

            logging.AddLog(_entityName, "registro insertado " + row_.ToStringKeyValuePair(), "insert", Logging.Level.Info);

            return this;
        }

        public string Sql()
        {
            return sql;
        }


        public EntityPersist Persist(string entityName, Data obj)
        {
            IDictionary<string, object?> row = obj.Dict();
            return Persist(entityName, row);
        }


        /// <summary>
        /// Verifica existencia de valor unico en base a la configuracion de la entidad
        /// Si encuentra resultado, actualiza
        /// Si no encuentra resultado, inserta
        /// </summary>
        /// <param name="row">Conjunto de valores a persistir</param>
        /// <param name="_entityName">Nombre de la entidad, si no existe toma el atributo</param>
        /// <returns>El mismo objeto</returns>
        /// <exception cref="Exception">Si encuentra mas de un conjunto de valores a partir de los campos unicos</exception>
        /// <exception cref="Exception">Si encuentra errores de configuracion en los campos a actualizar</exception>
        /// <exception cref="Exception">Si encuentra errores de configuracion en los campos a insertar</exception>
        public EntityPersist Persist(string _entityName, IDictionary<string, object?> row)
        {
            EntityValues v = Db.Values(_entityName!).Set(row);
            return Persist(v);
        }


        /// <summary>Comportamiento general de persistencia</summary>
        /// <remarks>Dependiendo del contexto se puede evitar el codigo adicional generado por el comportamiento general</remarks>
        public EntityPersist Persist(EntityValues v)
        {

            v.Reset();
            IDictionary<string, object?>? row = null;
            try {
                row = v.SqlUnique().DictOne();
            } catch(UniqueException) { }

            if (!row.IsNoE())
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");

                v.Set(Db.config.id, row![Db.config.id]);
                if (!v.Check())
                    throw new Exception("Los campos a actualizar poseen errores: " + v.Logging.ToString());

                return Update(v.entityName, v.Values()!);
            }

            if (!v.Values().ContainsKey(Db.config.id) || v.Values()[Db.config.id].IsNoE())
                v.SetDefault(Db.config.id);

            v.Default().Reset(Db.config.id).Check();

            if (v.Logging.HasErrors())
                throw new Exception("Los campos a insertar poseen errores: " + v.Logging.ToString());

            return Insert(v.entityName, v.Values());
        }

        public EntityPersist PersistCondition(EntityValues v, object? condition)
        {
            if (condition.IsNoE())
            {
                v.Default().Reset();
                if (!v.Check())
                    throw new Exception("INSERT ERROR - " + v.Logging.ToString());

                return Insert(v);
            }

            v.Reset();

            if (!v.Check())
                throw new Exception("UPDATE ERROR - " + v.Logging.ToString());

            return Update(v);
        } 

        public Query Query(Query q)
        {
            q.sql = Sql();
            q.parameters = parameters;
            return q;
        }

        public Query Query()
        {
            return Query(Db.Query());
        }


        public EntityPersist AddTo(List<EntityPersist> persists)
        {
            persists.Add(this);
            return this;
        }

        public EntityPersist AddToIfSql(List<EntityPersist> persists)
        {
            if (!this.Sql().IsNoE())
                persists.Add(this);

            return this;
        }

        /// <summary> Si la comparación es diferente, no actualiza! sino actualiza todo! </summary>
        public EntityPersist PersistCompare(EntityValues values, CompareParams compare)
        {
            values.Reset();

            IDictionary<string, object?> row = null;
            try
            {
                row = values.SqlUnique().DictOne();
            }
            catch (UniqueException) { }

            if (!row.IsNoE()) //actualizar
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");

                compare.val = Db.Values(values.entityName).Set(row);
                var response = values.Compare(compare);

                if (!response.IsNoE())
                    throw new Exception("Comparacion diferente: " + compare.val.ToStringFields(response.Keys.ToArray()));

                values.Set(Db.config.id, row[Db.config.id]);
                if (!values.Check())
                    throw new Exception("Los campos a actualizar poseen errores: " + values.Logging.ToString());

                return Update(values);

            }
            else
            {
                if (!values.Default().Reset().Check())
                    throw new Exception("Los campos a insertar poseen errores: " + values.Logging.ToString());

                return Insert(values);
            }
        }

        public EntityPersist TransferOm(string entityName, object origenId, object destinoId)
        {
            List<Field> fieldsOmPersona = Db.Entity(entityName).FieldsOm();
            foreach (var field in fieldsOmPersona)
            {
                object[] ids = Db.Sql(field.entityName).Where(field.name + " = @0").Parameters(origenId).Column<object>("id").ToArray();
                if (ids.Any())
                    UpdateValueIds(field.entityName, field.name, destinoId!, ids);
            }
            return this;
        }
    }

}
 
