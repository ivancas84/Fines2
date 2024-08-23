#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_disposicion_r : Data_disposicion
    {

        public void DefaultRel(params string[] fieldIds)
        {
            EntityValues val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "asignatura":
                        val = db!.Values("asignatura");
                        asignatura__id = (string?)val.GetDefault("id");
                    break;
                    case "planificacion":
                        val = db!.Values("planificacion");
                        planificacion__id = (string?)val.GetDefault("id");
                    break;
                    case "plan":
                        val = db!.Values("plan");
                        plan__id = (string?)val.GetDefault("id");
                    break;
                }
            }
        }
        protected string? _asignatura__Label = null;

        [JsonProperty("asignatura-Label")]
        public string? asignatura__Label
        {
            get { return _asignatura__Label; }
            set { _asignatura__Label = value; NotifyPropertyChanged(nameof(asignatura__Label)); }
        }

        protected string? _asignatura__id = null;

        [JsonProperty("asignatura-id")]
        public string? asignatura__id
        {
            get { return _asignatura__id; }
            set { _asignatura__id = value; asignatura = value; NotifyPropertyChanged(nameof(asignatura__id)); }
        }
        protected string? _asignatura__nombre = null;

        [JsonProperty("asignatura-nombre")]
        public string? asignatura__nombre
        {
            get { return _asignatura__nombre; }
            set { _asignatura__nombre = value; NotifyPropertyChanged(nameof(asignatura__nombre)); }
        }
        protected string? _asignatura__formacion = null;

        [JsonProperty("asignatura-formacion")]
        public string? asignatura__formacion
        {
            get { return _asignatura__formacion; }
            set { _asignatura__formacion = value; NotifyPropertyChanged(nameof(asignatura__formacion)); }
        }
        protected string? _asignatura__clasificacion = null;

        [JsonProperty("asignatura-clasificacion")]
        public string? asignatura__clasificacion
        {
            get { return _asignatura__clasificacion; }
            set { _asignatura__clasificacion = value; NotifyPropertyChanged(nameof(asignatura__clasificacion)); }
        }
        protected string? _asignatura__codigo = null;

        [JsonProperty("asignatura-codigo")]
        public string? asignatura__codigo
        {
            get { return _asignatura__codigo; }
            set { _asignatura__codigo = value; NotifyPropertyChanged(nameof(asignatura__codigo)); }
        }
        protected string? _asignatura__perfil = null;

        [JsonProperty("asignatura-perfil")]
        public string? asignatura__perfil
        {
            get { return _asignatura__perfil; }
            set { _asignatura__perfil = value; NotifyPropertyChanged(nameof(asignatura__perfil)); }
        }
        protected string? _planificacion__Label = null;

        [JsonProperty("planificacion-Label")]
        public string? planificacion__Label
        {
            get { return _planificacion__Label; }
            set { _planificacion__Label = value; NotifyPropertyChanged(nameof(planificacion__Label)); }
        }

        protected string? _planificacion__id = null;

        [JsonProperty("planificacion-id")]
        public string? planificacion__id
        {
            get { return _planificacion__id; }
            set { _planificacion__id = value; planificacion = value; NotifyPropertyChanged(nameof(planificacion__id)); }
        }
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
        protected string? _planificacion__plan = null;

        [JsonProperty("planificacion-plan")]
        public string? planificacion__plan
        {
            get { return _planificacion__plan; }
            set { _planificacion__plan = value; NotifyPropertyChanged(nameof(planificacion__plan)); }
        }
        protected string? _planificacion__pfid = null;

        [JsonProperty("planificacion-pfid")]
        public string? planificacion__pfid
        {
            get { return _planificacion__pfid; }
            set { _planificacion__pfid = value; NotifyPropertyChanged(nameof(planificacion__pfid)); }
        }
        protected string? _plan__Label = null;

        [JsonProperty("plan-Label")]
        public string? plan__Label
        {
            get { return _plan__Label; }
            set { _plan__Label = value; NotifyPropertyChanged(nameof(plan__Label)); }
        }

        protected string? _plan__id = null;

        [JsonProperty("plan-id")]
        public string? plan__id
        {
            get { return _plan__id; }
            set { _plan__id = value; planificacion__plan = value; NotifyPropertyChanged(nameof(plan__id)); }
        }
        protected string? _plan__orientacion = null;

        [JsonProperty("plan-orientacion")]
        public string? plan__orientacion
        {
            get { return _plan__orientacion; }
            set { _plan__orientacion = value; NotifyPropertyChanged(nameof(plan__orientacion)); }
        }
        protected string? _plan__resolucion = null;

        [JsonProperty("plan-resolucion")]
        public string? plan__resolucion
        {
            get { return _plan__resolucion; }
            set { _plan__resolucion = value; NotifyPropertyChanged(nameof(plan__resolucion)); }
        }
        protected string? _plan__distribucion_horaria = null;

        [JsonProperty("plan-distribucion_horaria")]
        public string? plan__distribucion_horaria
        {
            get { return _plan__distribucion_horaria; }
            set { _plan__distribucion_horaria = value; NotifyPropertyChanged(nameof(plan__distribucion_horaria)); }
        }
        protected string? _plan__pfid = null;

        [JsonProperty("plan-pfid")]
        public string? plan__pfid
        {
            get { return _plan__pfid; }
            set { _plan__pfid = value; NotifyPropertyChanged(nameof(plan__pfid)); }
        }
    }
}
