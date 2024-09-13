namespace SqlOrganize.Sql.Fines2Model3
{
    public class SedeValues : EntityVal
    {
        public SedeValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public override T GetData<T>()
        {
            var obj = base.GetData<T>();


            if (obj is Sede_ c)
            {
                EntityVal domicilioVal = GetValuesCache("domicilio");
                c.domicilio__Label = domicilioVal.ToString(); 
            }

            return obj;
        }




    }
}
