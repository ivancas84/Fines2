using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2App.Views.ListaAlumnosSemestre
{
    public class EstadoInscripcionData
    {
        public ObservableCollection<string> Estados()
        {
            return new()
            {
                "Correcto",
                "Indeterminado",
                "Caso particular",
                "Titulado",
            };
        }
    }
}
