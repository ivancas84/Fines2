namespace SqlOrganize.Sql.Fines2Model3
{
    public class DesignacionValues : EntityValues
    {
        public DesignacionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public override string ToString()
        {
            var s = "";

            EntityValues? v = ValuesRel("persona");
            if(v != null)
            {
                s += v.GetOrNull("nombres")?.ToString() ?? "?";
                s += " ";
                s += v.GetOrNull("apellidos")?.ToString() ?? "?";
                s += " ";
                s += v.GetOrNull("telefono")?.ToString() ?? "?";
            } else
            {
                s = "?";
            }

            return s.Trim();
        }
    }
}

    