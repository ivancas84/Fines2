using Dapper;
using MySql.Data.MySqlClient;
using System.Data.Common;

namespace SqlOrganize.Sql
{
    public class SelectSqlMy : SelectSql
    {

        public SelectSqlMy(Db db) : base(db)
        {
        }

        public override IEnumerable<string> GetTableNames()
        {
            using (var connection = Db.Connection().Open())
            {
                string sql = @"SHOW TABLES FROM " + Db.config.dbName;

                return connection.Query<string>(sql);
            }
        }

        public override object? GetNextValue(string entityName, string fieldName)
        {

            using (var connection = Db.Connection().Open())
            {
                using (var command = connection.CreateCommand())
                {
                    var sql = @"
                       SELECT auto_increment 
                       FROM INFORMATION_SCHEMA.TABLES 
                       WHERE TABLE_NAME = @TableName";
                    
                    return connection.ExecuteScalar<object>(sql, new {TableName = entityName });
                }

            }

        }
 
    }   

}
