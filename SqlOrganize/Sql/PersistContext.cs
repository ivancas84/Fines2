using System.Data.Common;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Exceptions;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql
{

    /// <summary>
    /// Contexto de persistencia de datos
    /// </summary>
    public abstract class PersistContext
    {
        /// <summary> Conexion opcional </summary>
        protected DbConnection? connection;

        /// <summary> Transaccion opcional </summary>
        protected DbTransaction transaction;

        /// <summary> Cantidad de consultas registradas </summary>
        protected int count = 0;

        /// <summary> Logging </summary>
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

        public PersistContext SetConn(DbConnection connection)
        {
            this.connection = connection;
            return this;
        }

        public PersistContext(Db db)
        {
            Db = db;
        }

        public PersistContext Param(string name, object? value)
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
                foreach (var id in ids)
                {
                    var id_ = Db.SqlValue(entityName, Db.config.id, id);
                    ids_.Add(id_);
                    detail.Add((entityName!, id, method));

                }
                sql += @"WHERE " + idMap + " IN (" + System.String.Join(",", ids_) + @");
";
            }
            else
            {
                sql += @"WHERE " + idMap + " IN (@delete_id" + i + @");
";
                Param("@delete_id" + i, ids);

                foreach (var id in ids)
                    detail.Add((entityName!, id, "delete"));
            }
        }

        public PersistContext DeleteIds(string _entityName, params object[] ids)
        {
            count++;

            EntityMetadata e = Db.Entity(_entityName);

            sql += @"
DELETE " + e.alias + " FROM " + e.name + " " + e.alias + @"
";

            WhereIds(_entityName, "delete", ids);

            return this;
        }

        abstract protected IDictionary<string, object?> _Update(string _entityName, IDictionary<string, object?> row);

        public object Update(string entityName, IDictionary<string, object?> row)
        {
            IDictionary<string, object?> _row = _Update(entityName, row!);

            string i = count.ToString();

            string id = Db.Mapping(entityName!).Map(Db.config.id);
            sql += @"
WHERE " + id + " = @update_" + i + @";
";
            Param("@update_" + i, row[Db.config.id]!);
            detail.Add((entityName!, row[Db.config.id]!, "update"));
            logging.AddLog(entityName, "registro actualizado " + _row.ToStringKeyValuePair(), "update", Logging.Level.Info);

            return row[Db.config.id];
        }


        public PersistContext UpdateIds(string _entityName, Dictionary<string, object?> row, params object[] ids)
        {
            IDictionary<string, object?> _row = _Update(_entityName, row);

            string i = count.ToString();

            string idMap = Db.Mapping(_entityName!).Map(Db.config.id);

            if ((ids.Count() + _parameters.Count()) > 2100) //SQL Server no admite mas de 2100 parametros, se define consulta alternativa para estos casos
            {
                List<object> ids_ = new();
                foreach (var id in ids)
                {
                    var id_ = Db.SqlValue(_entityName, Db.config.id, id);
                    ids_.Add(id_);
                    detail.Add((_entityName!, id, "update"));

                }
                sql += @"WHERE " + idMap + " IN (" + String.Join(", ", ids_) + @");
";
            } else
            {
                sql += @"WHERE " + idMap + " IN (@map_id" + i + @");
";
                Param("@map_id" + i, ids);

                foreach (var id in ids)
                    detail.Add((_entityName!, id, "update"));
            }

            logging.AddLog(_entityName, "registros actualizados " + _row.ToStringKeyValuePair(), "update", Logging.Level.Info);

            return this;
        }

        public PersistContext UpdateField(Entity data , string fieldName)
        {
            return UpdateFieldIds(data.entityName, fieldName, data.GetPropertyValue(fieldName), data.GetPropertyValue(Db.config.id));
        }

        public PersistContext UpdateField(Entity data, string fieldName, object? newValue)
        {
            UpdateFieldIds(data.entityName, fieldName, newValue, data.GetPropertyValue(Db.config.id));
            data.Sset(fieldName, newValue);
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
        public PersistContext UpdateFieldIds(string _entityName, string key, object? value, params object[] ids)
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
        public PersistContext UpdateValueAll(string _entityName, string key, object value)
        {
            Dictionary<string, object> row = new Dictionary<string, object>() { { key, value } };
            object[] ids = Db.Sql(_entityName).Fields(Db.config.id).Size(0).Column<object>().ToArray();
            return (ids.Count() > 0) ? UpdateIds(_entityName, row, ids) : this;
        }

        public object Update(Entity data)
        {
            return Update(data.entityName, data.ToDict()!);
        }

        /// <summary>
        /// Actualiza valor local o de relacion
        /// </summary>
        /// <param name="key">Nombre del campo a actualizar</param>
        /// <param name="value">Nuevo valor del campo a actualizar</param>
        /// <param name="source">Fuente con todos los valores sin actualizar</param>
        /// <param name="_entityName">Opcional nombre de la entidad, si no existe toma el atributo</param>
        /// <returns>Mismo objeto</returns>
        public PersistContext UpdateValueRel(string _entityName, string key, object? value, IDictionary<string, object?> source)
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

            return UpdateFieldIds(_entityName, key, value, source[idKey]!);
        }


        public object InsertIfNotExists(Entity data)
        {
            data.Reset();

            IDictionary<string, object?>? row = data.SqlUnique().DictOne() ?? null;

            if (row.IsNoE()) //insertar
            {
                if (!data.Check())
                    throw new Exception("Los campos a insertar poseen errores: " + data.Logging.ToString());

                return Insert(data);
            }

            data.Sset(Db.config.id, row[Db.config.id]);

            logging.AddLog(data.entityName, "Registro existente " + data.Label, "insert_if_not_exists", Logging.Level.Info);

            return row[Db.config.id];
        }

        

        /// <summary>
        /// Verifica, a traves de campos unicos, si el registro existe.
        /// Si existe, realiza una comparacion, 
        /// </summary>
        /// <param name="data"></param>
        /// <param name="compare"></param>
        /// <returns></returns>
        /// <exception cref="Exception"></exception>
        public object InsertIfNotExistsCompare(Entity data, CompareParams compare)
        {
            data.Reset();

            IDictionary<string, object?>? row = data.SqlUnique().DictOne() ?? null;

            if (row.IsNoE()) //actualizar
            {
                if (!data.Check())
                    throw new Exception("Los campos a insertar poseen errores: " + data.Logging.ToString());

                return Insert(data);
            } else
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");
                compare.Data = row!;
                var response = data.Compare(compare);

                if (!response.IsNoE())
                    throw new Exception("Comparacion diferente: " + response.ToStringDict());

                data.Sset(Db.config.id, row[Db.config.id]);
            }

            logging.AddLog(data.entityName, "Registro existente " + data.Label, "insert_if_not_exists_compare", Logging.Level.Info);

            return row[Db.config.id];
        }

        /// <summary>Insercion de EntityVal</summary>
        /// <remarks>Define id si no existe</remarks>
        public object Insert(Entity data)
        {
            return Insert(data.entityName, data.ToDict()!);
        }

        /// <summary>Comportamiento basico de insercion</summary>
        /// <remarks>Debe estar definido el id</remarks>
        public object Insert(string _entityName, IDictionary<string, object?> row)
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

            return row[Db.config.id]!;
        }

        public string Sql()
        {
            return sql;
        }

        public object Persist(Entity data)
        {
            data.Reset();

            if (!data.Check())
                throw new Exception("Los campos a persistir poseen errores: " + data.Logging.ToString());

            IDictionary<string, object?>? row = null;
            row = data.SqlUnique().DictOne();

            if (!row.IsNoE())
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");

                data.SetPropertyValue(Db.config.id, row![Db.config.id]);

                CompareParams cmp = new()
                {
                    IgnoreNonExistent = true,
                    IgnoreNull = false,
                    Data = row
                };

                if (!data.Compare(cmp).IsNoE())
                {
                    Update(data);
                    return data.Get("id");
                }

                logging.AddLog(data.entityName, "registro identico " + row.ToStringKeyValuePair(), "persist", Logging.Level.Info);
                return data.Get("id");
            }

            Insert(data);
            return data.Get("id");
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

        


        public object UpdateCompare(Entity dataToUpdate, Entity dataToCompare)
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

            logging.AddLog(dataToUpdate.entityName, "registro identico " + cmp.Data.ToStringKeyValuePair(), "persist", Logging.Level.Info);
            return dataToUpdate.Get(Db.config.id);
        }

        /// <summary> Si la comparación es diferente, no actualiza! sino actualiza todo! </summary>
        public object PersistCompare(Entity data, CompareParams compare)
        {
            data.Reset();

            if (!data.Check())
                throw new Exception("Los campos a persistir poseen errores: " + data.Logging.ToString());

            IDictionary<string, object?> row = data.SqlUnique().DictOne();

            if (!row.IsNoE()) //actualizar
            {
                //Se controla la existencia de id diferente? No! Se reasigna el id, dejo el codigo comentado
                //if (v.values.ContainsKey(Db.config.id) && v.Get(Db.config.id).ToString() != rows.ElementAt(0)[Db.config.id].ToString())
                //    throw new Exception("Los id son diferentes");
                compare.Data = row!;
                var response = data.Compare(compare);

                if (!response.IsNoE())
                    throw new Exception("Comparacion diferente: " + compare.Data.ToStringKeys(response.Keys.ToArray()));

                data.SetPropertyValue(Db.config.id, row[Db.config.id]);
                
                return Update(data);
            }

            return Insert(data);
        }

        public PersistContext TransferOm(string entityName, object origenId, object destinoId)
        {
            List<Field> fieldsOmPersona = Db.Entity(entityName).FieldsOm();
            foreach (var field in fieldsOmPersona)
            {
                object[] ids = Db.Sql(field.entityName).Equal(field.name, origenId).Column<object>("id").ToArray();
                if (ids.Any())
                    UpdateFieldIds(field.entityName, field.name, destinoId!, ids);
            }
            return this;
        }

        /// <summary>
        /// Limpia los atributos sql 
        /// </summary>
        public void Clear()
        {
            sql = "";
            count = 0;
            _parameters = new();
        }
    }

}
 
