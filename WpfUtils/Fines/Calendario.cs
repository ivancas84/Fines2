using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WpfUtils.Controls;

namespace WpfUtils.Fines
{
    public static class Calendario
    {
        public static void InitComboBoxConstructorCalendario(this Db db, System.Windows.Controls.ComboBox comboBox, ObservableCollection<Data_calendario> oc)
        {
            comboBox.InitComboBoxConstructor(oc);
            var data = db.Sql("calendario").Cache().ColOfDict();
            db.ClearAndAddDataToOC(data, oc);
        }

    }
}
