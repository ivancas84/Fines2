using Fines2Wpf.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesCurso
{
    class Calificacion : Data_calificacion_r
    {
        public bool procesar { set; get; } = true;
        public bool agregar_persona { set; get; } = true;
    }
}
