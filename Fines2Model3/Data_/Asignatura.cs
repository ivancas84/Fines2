using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Asignatura
    {

        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return nombre ?? "?" + " " + codigo ?? "?";
            }
            set
            {
                Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }

    }
}
