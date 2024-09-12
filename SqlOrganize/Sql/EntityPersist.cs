using System.Data.Common;
using Newtonsoft.Json.Linq;
using SqlOrganize.CollectionUtils;
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

        /// <summary> cantidad de consultas registradas </summary>
        protected int count = 0; 

        public Logging logging = new Logging();

        public Db Db { get; }

        public Dictionary<string, object?> _parameters { get; set; } = new();

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

        public EntityPersist Param(string name, object? value)
        {
            _parameters[name] = value;
            return this;
        }
        
        /// <summary>
        /// Se separa el método WhereIds para procesar la cantidad de parametros
        /// </summary>
        /// <param name="ids"></param>
        /// <param name="_entityName"></param>
        /// <returns></returns>
        protected void WhereIds(string entityName, string method, params object[] ids)
        {
            string i = count.ToString();

            string idMap = Db.Mapping(entityName!).Map(Db.config.id);

            if ((ids.Count() + _parameters.Count()) > 2100) //SQL Server no admite mas de 2100 parametros, se define consulta alternativa para estos casos
            {
                List<object> ids_ = new();
                var v = Db.Values(entityName!);
                foreach (var id in ids)
                {
                    v.Set(Db.config.id, id);
                    var id_ = v.Sql(Db.config.id);
                    ids_.Add(id_);
                    detail.Add((entityName!, id, method));

                }
                sql += @"WHERE " + idMap + " IN (" + String.Join(",", ids_) + @");
";
            }
            else
            {
                sql += @"WHERE " + idMap + " IN (@" + idMap + i + @");
";
                Param("@" + idMap + i, ids);

                foreach (var id in ids)
                    detail.Add((entityName!, id, "delete"));
            }
        }

        public EntityPersist DeleteIds(string _entityName, params object[] ids)
        {
            count++;

            Entity e = Db.Entity(_entityName);

            sql += @"
DELETE " + e.alias + " FROM " + e.name + " " + e.alias + @"
";

            WhereIds(_entityName, "delete", ids);

            return this;
        }

        abstract protected IDictionary<string, object?> _Update(string _entityName, IDictionary<string, object?> row);

        public EntityPersist Update(EntityVal values)
        {
            var row = values.Values();

            IDictionary<string, object?> _row = _Update(values.entityName, row!);

            string i = count.ToString();

            string id = Db.Mapping(values.entityName!).Map(Db.config.id);
            sql += @"
WHERE " + id + " = @update_" + id + i + @";
";
            Param("@update_" + id + i, row[Db.config.id]!);
            detail.Add((values.entityName!, row[Db.config.id]!, "update"));
            logging.AddLog(values.entityName, "registro actualizado " + _row.ToStringKeyValuePair(), "update", Logging.Level.Info);

            return this;
        }

        public EntityPersist UpdateIds(string _entityName, Dictionary<string, object?> row, params object[] ids)
        {
            IDictionary<string, object?> _row = _Update(_entityName, row);

            string i = count.ToString();

            string idMap = Db.Mapping(_entityName!).Map(Db.config.id);

            if ((ids.Count() + _parameters.Count()) > 2100) //SQL Server no admite mas de 2100 parametros, se define consulta alternativa para estos casos
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
                sql += @"WHERE " + idMap + " IN (@map_" + idMap + i + @");
";
                Param("@map_" + idMap + i, ids);

                foreach (var id in ids)
                    detail.Add((_entityName!, id, "update"));
            }

            logging.AddLog(_entityName, "registros actualizados " + _row.ToStringKeyValuePair(), "update", Logging.Level.Info);

            return this;
        }

        public EntityPersist UpdateValue(EntityVal values, string fieldName, object? newValue)
        {
            UpdateValueIds(values.entityName, fieldName, newValue, values.Get("id"));
            values.Set(fieldName, newValue);
            return this;
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
            object[] ids = Db.Sql(_entityName).Fields(Db.config.id).Size(0).Column<object>().ToArray();
            return (ids.Count() > 0) ? UpdateIds(_entityName, row, ids) : this;
        }

        public EntityPersist UpdateValueWhere(string _entityName, string key, object value, string where, IDictionary<string, object>? parameters = null)
        {
            var q = Db.Sql(_entityName).Where(where);
            if (!parameters.IsNoE())
                q.Params(parameters!);
            
            object[] ids = q.Column<object>(Db.config.id).ToArray();
            if(ids.Any())
                return UpdateValueIds(_entityName, key, value, ids);
            return this;
        }

        /// <summary>
        /// Actualiza valor local o de relacion
        /// </summary>
        /// <param name="key">Nombre del campo a actualizar</param>
        /// <param name="value">Nuevo valor del campo a actualizar</param>
        /// <param name="source">Fuente con todos los valores sin actualizar</param>
        /// <param name="_entityName">Opcional nombre de la entidad, si no existe toma el atributo</param>
        /// <returns>Mismo objeto</returns>
        public EntityPersist UpdateValueRel(string _entityName, string key, object? value, IDictionary<string, object?> source)
        {
            string idKey = Db.config.id;
            if (key.Contains(Db.config.separator))
            {
                int indexSeparator = key.IndexOf(Db.config.separator);
                string fieldId = key.Substring(0, indexSeparator);
                _entityName = Db.Entity(_entityName!).relations[fieldId].refEntityName;
                idKey = fieldId + Db.config.separator + Db.config.id;
                key = key.Substring(indexSeparator + Db.config.separator.Length); //se suma la cantidad de caracteres del separador
            }

            return UpdateValueIds(_entityName, key, value, source[idKey]!);
        }


        public EntityPersist InsertIfNotExists(EntityVal values)
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



        /// <summary>Insercion de EntityVal</summary>
        /// <remarks>Define id si no existe</remarks>
        public EntityPersist Insert(EntityVal v)
        {
            if (v.GetOrNull(Db.config.id).IsNoE())
                v.SetDefault(Db.config.id);
            return Insert(v.entityName, v.Values()!);
        }


        /// <summary>Comportamiento basico de insercion</summary>
        /// <remarks>Debe estar definido el id</remarks>
        public EntityPersist Insert(string _entityName, IDictionary<string, object?> row)
        {
            count++;
            string i = count.ToString();

            List<string> fieldNames = Db.FieldNamesAdmin(_entityName!);
            Dictionary<string, object?> row_ = new();
            foreach (string key in row.Keys)
                if (fieldNames.Contains(key))
                    row_.Add(key, row[key]!);

            string sn = Db.Entity(_entityName!).schemaName;
            sql += "INSERT INTO " + sn + @" (" + String.Join(", ", row_.Keys) + @") 
VALUES (";

            foreach (var (key, value) in row_)
            {
                sql += "@" + key + i + ", ";
                Param("@" + key + i, value);
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


        /// <summary>Comportamiento general de persistencia</summary>
        /// <remarks>Dependiendo del contexto se puede evitar el codigo adicional generado por el comportamiento general</remarks>
        public EntityPersist Persist(EntityVal v)
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

                CompareParams cmp = new()
                {
                    IgnoreNonExistent = true,
                    IgnoreNull = false,
                    Data = row
                };

                if(!v.Compare(cmp).IsNoE())
                    return Update(v);

                logging.AddLog(v.entityName, "registro identico " + row.ToStringKeyValuePair(), "persist", Logging.Level.Info);
                return this;
            }

            if (!v.Values().ContainsKey(Db.config.id) || v.Values()[Db.config.id].IsNoE())
                v.SetDefault(Db.config.id);

            v.Default().Reset(Db.config.id).Check();

            if (v.Logging.HasErrors())
                throw new Exception("Los campos a insertar poseen errores: " + v.Logging.ToString());

            return Insert(v.entityName, v.Values());
        }

        public EntityPersist PersistCondition(EntityVal v, object? condition)
        {
            if (condition.IsNoE())
            {
                v.Default().Reset();
                if (!v.Check())
                    throw new Exception("INSERT ERROR: " + v.Logging.ToString());

                return Insert(v);
            }

            v.Reset();

            if (!v.Check())
                throw new Exception("UPDATE ERROR: " + v.Logging.ToString());

            return Update(v);
        } 

        public Query Query(Query q)
        {
            q.sql = Sql();
            q._parameters = _parameters;
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
        public EntityPersist PersistCompare(EntityVal values, CompareParams compare)
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

                compare.Data = row!;
                var response = values.Compare(compare);

                if (!response.IsNoE())
                    throw new Exception("Comparacion diferente: " + compare.Data.ToStringKeys(response.Keys.ToArray()));

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
                object[] ids = Db.Sql(field.entityName).Equal(field.name, origenId).Column<object>("id").ToArray();
                if (ids.Any())
                    UpdateValueIds(field.entityName, field.name, destinoId!, ids);
            }
            return this;
        }
    }

}
 
