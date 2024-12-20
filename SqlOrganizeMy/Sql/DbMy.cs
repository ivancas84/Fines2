﻿using Microsoft.Extensions.Caching.Memory;
using MySql.Data.MySqlClient;
using SqlOrganize;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Contenedor principal para mysql
    /// </summary>
    public class DbMy : Db
    {

        /// <summary>
        /// Constructor
        /// </summary>
        /// <param name="config">
        /// Configuracion
        /// </param>
        /// <example>
        ///   connectionString = "server=127.0.0.1;uid=root;pwd=12345;database=test"
        /// </example>
        public DbMy(Config config, ISchema schema, IMemoryCache? cache = null) : base(config, schema, cache)
        {
            /*
            prueba de conexion
            Las conexiones se realizan directamente cuando se requiere la eje-
            cucion de una consulta a la base de datos.
            Este codigo esta como referencia, se deja como ejemplo por si es
            necesario verificar la conexion                       
            var conn = new MySqlConnection();
            conn.ConnectionString = (string)config.connectionString;
            conn.Open();
            conn.Close();
            */
        }

        public override SelectSql Sql()
        {
            return new SelectSqlMy(this);
        }

        public override PersistSql PersistSql()
        {
            return new PersistSqlMy(this);
        }

        public override Connection Connection()
        {
            return new ConnectionMy(this.config.connectionString);
        }

    }
}
