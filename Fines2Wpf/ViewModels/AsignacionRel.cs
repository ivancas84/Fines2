using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Fines2Wpf.Data;

namespace Fines2Wpf.ViewModels
{
    internal class AsignacionRel : Data_alumno_comision_r
    {
        public string? comision__numero { get; set; }
        public string? domicilio__label { get; set; }

    }
}
