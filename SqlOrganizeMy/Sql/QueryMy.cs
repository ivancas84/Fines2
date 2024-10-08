﻿using MySql.Data.MySqlClient;
using System.Data.Common;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Ejecucion de consultas a la base de datos
    /// </summary>
    public class QueryMy : Query
    {

        public QueryMy(Db db) : base(db)
        {
        }

        public override DbCommand NewCommand()
        {
            return new MySqlCommand();
        }

        public override DbConnection NewConnection()
        {
            connection = new MySqlConnection(db.config.connectionString);
            return connection;
        }

        protected override void AddWithValue(DbCommand command, string columnName, object value)
        {
            (command as MySqlCommand)!.Parameters.AddWithValue(columnName, value);
        }

        public override IEnumerable<string> GetTableNames()
        {
            using DbConnection connection = OpenConnection();
            using DbCommand command = NewCommand();
            command.CommandText = @"SHOW TABLES FROM " + db.config.dbName;
            command.Connection = connection;
            command.ExecuteNonQuery();
            using DbDataReader reader = command.ExecuteReader();
            return reader.ColumnValues<string>(0);
        }
    }

}
