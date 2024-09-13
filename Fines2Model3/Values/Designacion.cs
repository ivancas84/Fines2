namespace SqlOrganize.Sql.Fines2Model3
{
    public class DesignacionValues : EntityVal
    {
        public DesignacionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public override string ToString()
        {
            var s = "";

            EntityVal? v = GetValuesCache("persona");
            if(v != null)
            {
                s += v.GetStr("nombres", "?");
                s += " ";
                s += v.GetStr("apellidos", "?");
                s += " ";
                s += v.GetStr("telefono", "?");
            } else
            {
                s = "?";
            }

            return s.Trim();
        }
    }
}

    