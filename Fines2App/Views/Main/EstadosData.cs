using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2App.Views.Main
{
    public class EstadoData
    {
        public ObservableCollection<string> Estados()
        {
            return new()
            {
                "Activo",
                "No activo",
                "Mesa",
            };
        }
    }

}
