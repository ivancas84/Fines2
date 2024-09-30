using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Designacion
    {
        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return (persona_?.Label ?? "?") + " " + (persona_?.telefono ?? "?");
            }
            set
            {
                Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }
    }
}
