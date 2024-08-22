using Fines2Model3.Item;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class TomaValues : EntityValues
    {
        public TomaValues(Db _db, string? _field_id) : base(_db, "toma", _field_id)
        {
        }

        public override T GetData<T>()
        {
            var obj = base.GetData<T>();


            if (obj is Data_toma_r c)
            {
                EntityValues sedeVal = GetValuesCache("sede");
                c.sede__Label = sedeVal.ToString();

                EntityValues domicilioVal = GetValuesCache("domicilio");
                c.domicilio__Label = domicilioVal.ToString(); 
            }

            return obj;
        }




    }
}
