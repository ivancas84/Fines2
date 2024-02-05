using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection.Emit;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2App.Values
{
    public class Plan : EntityValues
    {
        public Plan(Db _db, string entityName, string? _fieldId = null) : base(_db, entityName, _fieldId)
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
