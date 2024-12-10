using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql
{
    public class PersistSqlMy : PersistSql
    {
        public PersistSqlMy (Db db) : base(db)
        {
        }

        /// <summary> Actualizar </summary>
        /// <returns> Retorna IDictionary de elementos que fueron actualizados </returns>
        protected override string _Update(string _entityName, IDictionary<string, object?> row)
        {

            string sna = Db.Entity(_entityName!).schemaNameAlias;
            string sql = @"   
UPDATE " + sna + @" SET
";
            List<string> fieldNames = Db.FieldNamesAdmin(_entityName!);

            foreach (string fieldName in fieldNames)
            {

                if (row.ContainsKey(fieldName))
                {
                    sql += fieldName + " = @" + fieldName + ", ";
                }
            }
            return sql.RemoveLastChar(',');
        }

    }

}
