using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3      
{
    public class PlanValues : EntityVal
    {
        public PlanValues(Db _db, string entityName, string? _fieldId = null) : base(_db, entityName, _fieldId)
        {
        }

        public override string ToString()
        {
            string s = "";
            s += GetStr("orientacion", "?")?.Acronym();
            s += " ";
            s += GetStr("resolucion", "?");
            return s;
        }
    }
}
