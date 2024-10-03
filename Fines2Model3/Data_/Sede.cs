using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using Newtonsoft.Json;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Sede
    {
        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return (numero ?? "?") + " " + (nombre ?? "?") + " " + (domicilio_?.Label ?? "?");
            }
            set
            {
                _Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }

    }
}
