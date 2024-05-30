using Fines2Model3.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones
{
    internal class AsignacionDbItem : Data_alumno_comision_r
    {
        public bool existeEnPf { get; set; } = false;
    }
}
