using Fines2App.Data;
using System.Collections.ObjectModel;
using Utils;

namespace Fines2App.Views.ListaAlumnosSemestre
{
    public class SedeViewModel
    {
        public ObservableCollection<Data_sede> Sedes()
        {
            ObservableCollection<Data_sede> r = new ObservableCollection<Data_sede>();
            var data = ContainerApp.db.Query("sede").Size(0).Parameters().ColOfDictCache();
            r.Clear();
            r.AddRange(data);
            return r;
        }
    }

}
