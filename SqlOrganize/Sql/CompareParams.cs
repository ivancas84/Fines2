using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql
{
    public class CompareParams
    {
        public IDictionary<string, object?> Data { get; set; } //datos a comparar
        public IEnumerable<string>? IgnoreFields { get; set; }
        public bool IgnoreNull { get; set; }
        public bool IgnoreNonExistent { get; set; }
        public IEnumerable<string>? FieldsToCompare { get; set; }

        
    }
}
