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
    public partial class Disposicion
    {
        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return asignatura_.Label ?? "?" + " " + planificacion_.Label ?? "?";
            }
            set
            {
                Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }
    }
}
