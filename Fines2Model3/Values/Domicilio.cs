using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class DomicilioValues : EntityVal
    {
        public DomicilioValues(Db _db, string entityName, string? _fieldId = null) : base(_db, entityName, _fieldId)
        {
        }

        public string ToStringShort()
        {
            string s = "";
            s += GetOrNull("calle")?.ToString() ?? "?";
            s += " e/ ";
            s += GetOrNull("entre")?.ToString() ?? "?";
            s += " n° ";
            s += GetOrNull("numero")?.ToString() ?? "?";
            return s;
        }

        public override string ToString()
        {
            string s = "";
            s += GetOrNull("calle")?.ToString() ?? "?";
            s += " e/ ";
            s += GetOrNull("entre")?.ToString() ?? "?";
            s += " n° ";
            s += GetOrNull("numero")?.ToString() ?? "?";
            s += " ";
            s += GetOrNull("barrio")?.ToString();
            s += " ";
            s += GetOrNull("localidad")?.ToString() ?? "?";
            return s.RemoveMultipleSpaces();
        }
    }
}
