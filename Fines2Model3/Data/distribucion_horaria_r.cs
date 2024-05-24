#nullable enable
using SqlOrganize;
using System;
using Newtonsoft.Json;

namespace Fines2Model3.Data
{
    public class Data_distribucion_horaria_r : Data_distribucion_horaria
    {

        public Data_distribucion_horaria_r () : base()
        {
        }

        public Data_distribucion_horaria_r (Db db) : base(db)
        {
        }

        public Data_distribucion_horaria_r (Db db, params string[] fieldIds) : this(db)
        {
            Init(fieldIds);
        }

        protected void Init(params string[] fieldIds)
        {
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "disposicion":
                        disposicion__id = (string?)db!.Values("disposicion").GetDefault("id");
                    break;
                    case "asignatura":
                        asignatura__id = (string?)db!.Values("asignatura").GetDefault("id");
                    break;
                    case "planificacion":
                        planificacion__id = (string?)db!.Values("planificacion").GetDefault("id");
                    break;
                    case "plan":
                        plan__id = (string?)db!.Values("plan").GetDefault("id");
                    break;
                }
            }
        }

        public string? disposicion__Label { get; set; }

        protected string? _disposicion__id = null;

        [JsonProperty("disposicion-id")]
        public string? disposicion__id
        {
            get { return _disposicion__id; }
            set { _disposicion__id = value; disposicion = value; NotifyPropertyChanged(nameof(disposicion__id)); }
        }
        protected string? _disposicion__asignatura = null;

        [JsonProperty("disposicion-asignatura")]
        public string? disposicion__asignatura
        {
            get { return _disposicion__asignatura; }
            set { _disposicion__asignatura = value; NotifyPropertyChanged(nameof(disposicion__asignatura)); }
        }
        protected string? _disposicion__planificacion = null;

        [JsonProperty("disposicion-planificacion")]
        public string? disposicion__planificacion
        {
            get { return _disposicion__planificacion; }
            set { _disposicion__planificacion = value; NotifyPropertyChanged(nameof(disposicion__planificacion)); }
        }
        protected int? _disposicion__orden_informe_coordinacion_distrital = null;

        [JsonProperty("disposicion-orden_informe_coordinacion_distrital")]
        public int? disposicion__orden_informe_coordinacion_distrital
        {
            get { return _disposicion__orden_informe_coordinacion_distrital; }
            set { _disposicion__orden_informe_coordinacion_distrital = value; NotifyPropertyChanged(nameof(disposicion__orden_informe_coordinacion_distrital)); }
        }

        public string? asignatura__Label { get; set; }

        protected string? _asignatura__id = null;

        [JsonProperty("asignatura-id")]
        public string? asignatura__id
        {
            get { return _asignatura__id; }
            set { _asignatura__id = value; disposicion__asignatura = value; NotifyPropertyChanged(nameof(asignatura__id)); }
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

        public string? planificacion__Label { get; set; }

        protected string? _planificacion__id = null;

        [JsonProperty("planificacion-id")]
        public string? planificacion__id
        {
            get { return _planificacion__id; }
            set { _planificacion__id = value; disposicion__planificacion = value; NotifyPropertyChanged(nameof(planificacion__id)); }
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

        public string? plan__Label { get; set; }

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
