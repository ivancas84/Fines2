using System.Data;
using System.Data.Common;
using Dapper;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Simplificar ejecucion de consultas a la base de datos y definicion de de parametros en las consultas de sql
    /// </summary>    
    public abstract class Connection
    {

        protected string connectionString;

        public Connection(string? connectionString = null) { 
            if(connectionString != null) {
                this.connectionString = connectionString; 
            }
        }

        /// <summary>
        /// Creates and returns a new database connection.
        /// </summary>
        public abstract IDbConnection Create();

        public  IDbConnection Open()
        {
            var connection = Create(); 
            connection.Open();
            return connection;
        }


    }
}
 
