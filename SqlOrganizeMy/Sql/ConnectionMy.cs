	﻿using MySql.Data.MySqlClient;
using System.Data;
using System.Data.Common;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Ejecucion de consultas a la base de datos
    /// </summary>
    public class ConnectionMy : Connection
    {
        public ConnectionMy(string? connectionString = null) : base(connectionString)
        {
        }

        public override IDbConnection Create()
        {
            return new MySqlConnection(connectionString);
        }

     



    }

}
