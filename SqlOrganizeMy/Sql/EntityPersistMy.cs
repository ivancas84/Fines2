﻿using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql
{
    public class EntityPersistMy : EntityPersist
    {
        public EntityPersistMy(Db db) : base(db)
        {
        }

        /// <summary> Actualizar </summary>
        /// <returns> Retorna IDictionary de elementos que fueron actualizados </returns>
        protected override IDictionary<string, object?> _Update(string _entityName, IDictionary<string, object?> row)
        {
            string sna = Db.Entity(_entityName!).schemaNameAlias;
            sql += @"
UPDATE " + sna + @" SET
";
            List<string> fieldNames = Db.FieldNamesAdmin(_entityName!);

            Dictionary<string, object?> _row = new();

            foreach (string fieldName in fieldNames)
            {

                if (row.ContainsKey(fieldName))
                {
                    _row[fieldName] = row[fieldName];
                    sql += fieldName + " = @" + fieldName + ", ";
                    _parameters["@"+fieldName] = row[fieldName];
                }
            }
            sql = sql.RemoveLastChar(',');
            
            return _row;
        }

    }

}
