namespace SqlOrganize.Sql
{
    public class EntityFieldId
    {
        public Db db;

        public string entityName { get; }

        public string? fieldId { get; set; }

        public EntityFieldId(Db _db, string _entityName, string? _fieldId = null)
        {
            db = _db;
            entityName = _entityName;
            fieldId = _fieldId;
        }

        public string Pf()
        {
            return (!fieldId.IsNoE()) ? fieldId! + "-" : "";
        }

        public string Pt()
        {
            return (!fieldId.IsNoE()) ? fieldId! : db.Entity(entityName).alias;
        }

        public string CleanPf(string fieldName)
        {
            if (!Pf().IsNoE() && fieldName.Contains(Pf()))
                return fieldName.Replace(Pf(), "");
            return fieldName;
        }

       

    }
}
