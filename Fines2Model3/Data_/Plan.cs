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
    public partial class Plan
    {
        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return (orientacion?.Acronym() ?? "?") + " " + (resolucion ?? "?");
            }
            set
            {
                Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }
    }
}
