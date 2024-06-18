using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.Informe.CantidadesPorComision
{
    internal class Item : SqlOrganize.Data
    {

        protected string? _planificacion__anio = null;

        [JsonProperty("planificacion-anio")]
        public string? planificacion__anio
        {
            get { return _planificacion__anio; }
            set { _planificacion__anio = value; NotifyPropertyChanged(nameof(planificacion__anio)); }
        }
        protected string? _planificacion__semestre = null;

        [JsonProperty("planificacion-semestre")]
        public string? planificacion__semestre
        {
            get { return _planificacion__semestre; }
            set { _planificacion__semestre = value; NotifyPropertyChanged(nameof(planificacion__semestre)); }
        }

        protected string? _plan__orientacion = null;

        [JsonProperty("plan-orientacion")]
        public string? plan__orientacion
        {
            get { return _plan__orientacion; }
            set { _plan__orientacion = value; NotifyPropertyChanged(nameof(plan__orientacion)); }
        }

        protected string? _edad = null;

        public string? edad
        {
            get { return _edad; }
            set { _edad = value; NotifyPropertyChanged(nameof(edad)); }
        }

        protected int? _cantidad = null;

        public int? cantidad
        {
            get { return _cantidad; }
            set { _cantidad = value; NotifyPropertyChanged(nameof(cantidad)); }
        }
    }
}
