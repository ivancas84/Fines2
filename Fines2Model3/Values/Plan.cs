using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3      
{
    public class PlanValues : EntityValues
    {
        public PlanValues(Db _db, string entityName, string? _fieldId = null) : base(_db, entityName, _fieldId)
        {
        }

        public override string ToString()
        {
            string s = "";
            s += GetOrNull("orientacion")?.ToString()?.Acronym() ?? "?";
            s += " ";
            s += GetOrNull("distribucion_horaria")?.ToString() ?? "?";
            return s;
        }
    }
}
