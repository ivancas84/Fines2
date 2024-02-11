using SqlOrganize;
using Utils;

namespace Fines2Wpf.Values
{
    class Designacion : EntityValues
    {
        public Designacion(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public override string ToString()
        {
            var s = "";

            EntityValues? v = ValuesTree("persona");
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

    