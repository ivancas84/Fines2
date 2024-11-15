using System.Data;
using System.Data.Common;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Simplificar ejecucion de consultas a la base de datos y definicion de de parametros en las consultas de sql
    /// </summary>    
    public abstract class Query : IDisposable
    {
        protected DbConnection connection;
        protected DbTransaction? transaction;

        public Db db { get; }

        public string sql { get; set; } = "";

        public Dictionary<string, object?> Parameters { get; set; } = new();

        public Query(Db _db)
        {
            db = _db;
        }
    
        ~Query()
        {
            Dispose();
        }

        public Query SetParam(string key, object? value)
        {
            Parameters[key] = value;
            return this;
        }

        // Implement IDisposable interface
        public void Dispose()
        {
            CloseConnection();
            transaction?.Dispose();
            connection?.Dispose();
            GC.SuppressFinalize(this);
        }


        /// <summary>
        /// Centralized execution for all commands, with optional transaction.
        /// </summary>
        private void ExecuteCommand(DbCommand command)
        {
            command.Connection = OpenConnection();
            command.Transaction = transaction;
            AddParameters(command);
            command.CommandText = sql;
            command.ExecuteNonQuery();
        }

        /// <summary> Ejecutar sql y devolver resultado </summary>
        public IEnumerable<Dictionary<string, object?>> Dicts()
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader();
            return reader.Serialize();
            
        }

        public IEnumerable<T> Objs<T>() where T : class, new()
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader();
            return reader.Objs<T>();
        }

        public IDictionary<string, object?>? Dict()
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader(System.Data.CommandBehavior.SingleResult);
            return reader.SerializeRow();
        }

        public T? Obj<T>() where T : class, new()
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader(System.Data.CommandBehavior.SingleResult);
            return reader.Obj<T>();
        }

        public IEnumerable<T> Column<T>(string columnName)
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader();
            return reader.ColumnValues<T>(columnName);
        }

        public IEnumerable<T> Column<T>(int columnNumber = 0)
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader();
            return reader.ColumnValues<T>(columnNumber);
        }

        /// <summary>Value</summary>
        /// <remarks>La consulta debe retornar 1 o mas valores</remarks>
        public T? Value<T>(string columnName)
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader(System.Data.CommandBehavior.SingleResult);
            return reader.Read() ? (T)reader[columnName] : default(T);
        }

        /// <summary>Value</summary>
        /// <remarks>La consulta debe retornar 1 o mas valores</remarks>
        public T? Value<T>(int columnNumber = 0)
        {
            using DbCommand command = CreateCommand();
            ExecuteCommand(command);
            using DbDataReader reader = command.ExecuteReader(System.Data.CommandBehavior.SingleResult);
            return (reader.Read()) ? (T)reader.GetValue(columnNumber) : default(T);
        }

        /// <summary>
        /// Verifica conexion, si no existe la crea
        /// </summary>
        public void Exec()
        {
            using var command = CreateCommand();
            ExecuteCommand(command);
        }

        public abstract DbConnection CreateConnection();

        public DbConnection OpenConnection()
        {
            connection ??= CreateConnection();

            if (connection.State == ConnectionState.Closed)
                connection.Open();

            return connection;
        }

        public void CloseConnection()
        {
            if (connection?.State == ConnectionState.Open)
                connection.Close();
        }

        public void BeginTransaction()
        {
            OpenConnection();
            transaction = connection!.BeginTransaction();
        }

        public void CommitTransaction()
        {
            transaction!.Commit();
        }

        public void RollbackTransaction()
        {
            transaction!.Rollback();
        }

        public abstract DbCommand CreateCommand();

        protected abstract void AddWithValue(DbCommand command, string columnName, object value);

        /// <summary> Redefinir parametros y asignarlos a command </summary>
        protected void AddParameters(DbCommand command)
        {
            var keys = Parameters.Keys.ToList();
            var sortedKeys = keys.OrderByDescending(key => key.Length).ToList(); //recorremos los keys ordenados en forma descendiente para evitar renombrar keys similares

            foreach (var key in sortedKeys)
            {
                object? value = this.Parameters[key];

                if (!sql.Contains(key)) //control de que el sql posea el parametro
                    continue;

                List<Tuple<string, object>> _parameters = new();

                string k = key.Replace("@", "");

                if (value is IEnumerable<object>)
                {
                    int j = 0;

                    foreach (object item in (IEnumerable<object>)value)
                    {
                        var t = Tuple.Create($"@_{k}_{j}", item); //se le asigna un "_" adicional al nuevo nombre para evitar ser renombrado nuevamente.
                        _parameters.Add(t);
                        j++;
                    }

                    sql = sql.ReplaceFirst(key, string.Join(",", _parameters.Select(x => x.Item1)));
                    foreach (var parameter in _parameters)
                        AddWithValue(command, parameter.Item1, parameter.Item2);
                }
                else
                {
                    var p = (value == null) ? DBNull.Value : value;
                    sql = sql.Replace(key, "@_" + k); //renombro para evitar doble asignacion
                    AddWithValue(command, "@_" + k, p);
                }
            }
        }


        #region metodos especiales que generan sql y devuelven directamente el valor
        public abstract IEnumerable<string> GetTableNames();

        /// <summary>
        /// Cada motor debe tener su propia forma de definir Next Value!!! Derivar metodo a subclase
        /// </summary>
        /// <returns></returns>
        public abstract object GetNextValue(string entityName, string fieldName);

        /// <summary>Cada motor debe tener su propia forma de definir Max Value!!! Derivar metodo a subclase</summary>
        /// <remarks>Connection must be opened!</remarks>
        public object GetMaxValue(string entityName, string fieldName)
        {
            return db.Sql(entityName).SelectMaxValue(fieldName).Value<object>();
        }

        /// <summary>Conncection must be opened!</summary>
        public IEnumerable<Dictionary<string, object?>> LastServerChanges(string lastChecked)
        {
            var q = db.Query();
            q.connection = connection;
            q.sql = @"SELECT DISTINCT TableName, RecordId 
                    FROM AuditLog 
                    WHERE ChangeDateTime > '" + lastChecked + "';";
            return q.Dicts();
        }
        #endregion
    }
}
 
