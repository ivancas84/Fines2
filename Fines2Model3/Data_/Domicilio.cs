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
    public partial class Domicilio
    {
        protected string _LabelShort = "";

        public string? LabelShort
        {
            get
            {
                if (!_LabelShort.IsNoE())
                    return _LabelShort;

                return "Calle" + calle ?? "?" + " e/" + entre ?? "?" + " N° " + numero ?? "?";
            }
            set
            {
                _LabelShort = value;
                NotifyPropertyChanged(nameof(LabelShort));
            }
        }

        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return "Calle " + (calle ?? "?") + " e/" + (entre ?? "?") + " N° " + (numero ?? "?") + " " + (barrio ?? "?") + " " + (localidad ?? "?");
            }
            set
            {
                Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }
    }
}
