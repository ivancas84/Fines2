using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql
{
    public class EntityPersistMy : EntityPersist
    {
        public EntityPersistMy(Db db) : base(db)
        {
        }

        protected override EntityPersist _Update(string _entityName, IDictionary<string, object?> row)
        {
            string sna = Db.Entity(_entityName!).schemaNameAlias;
            sql += @"
UPDATE " + sna + @" SET
";
            List<string> fieldNames = Db.FieldNamesAdmin(_entityName!);

            foreach (string fieldName in fieldNames)
                if (row.ContainsKey(fieldName))
                {
                    sql += fieldName + " = @" + count + ", ";
                    count++;
                    parameters.Add(row[fieldName]);
                }
            sql = sql.RemoveLastChar(',');
            return this;
        }

    }

}
