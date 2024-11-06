namespace SqlOrganize.Sql
{
    public class EntitySqlMy : EntitySql
    {

        public EntitySqlMy(Db db, string entityName) : base(db, entityName)
        {
        }

        protected override string SqlLimit()
        {
            if (size == 0) return "";
            page = page == 0 ? 1 : page;
            return "LIMIT " + size + " OFFSET " + ((page - 1) * size) + @"
";
        }

        protected override string SqlOrder()
        {
            if (order.IsNoE())
            {
                var o = Db.Entity(entityName).orderDefault;
                order = o.IsNoE() ? "" : string.Join(", ", o.Select(x => "$" + x));
            }

            return (order.IsNoE()) ? "" : "ORDER BY " + Traduce(order!) + @"
";
        }

        public override EntitySql Clone()
        {
            var eq = new EntitySqlMy(Db, entityName);
            return _Clone(eq);
        }
		
	protected override string SqlFields()
	{

            var f = _SqlFieldsInit();
            return f + @"
";
        }

        public override EntitySql SelectMaxValue(string fieldName)
        {
            select += "IFNULL( MAX($" + fieldName + "), 0)";
            return this;
        } 
		
		 public override EntitySql SelectNextValue(string fieldName)
		{
			select += @"
				SELECT auto_increment 
				FROM INFORMATION_SCHEMA.TABLES 
				WHERE TABLE_NAME = '" + entityName + "'";				
			return this;
		}
    }   

}
