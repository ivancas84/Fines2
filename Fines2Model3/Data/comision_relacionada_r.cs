#nullable enable
using SqlOrganize;
using System;
using Newtonsoft.Json;

namespace Fines2Model3.Data
{
    public class Data_comision_relacionada_r : Data_comision_relacionada
    {

        public Data_comision_relacionada_r () : base()
        {
        }

        public Data_comision_relacionada_r (Db db, bool init = true) : base(db, init)
        {
        }

        public Data_comision_relacionada_r (Db db, bool init = true, params string[] fieldIds) : this(db, init)
        {
            Init(fieldIds);
        }

        protected void Init(params string[] fieldIds)
        {
            EntityValues val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "comision":
                        val = db!.Values("comision");
                        comision__id = (string?)val.GetDefault("id");
                        comision__alta = (DateTime?)val.GetDefault("alta");
                    break;
                    case "sede":
                        val = db!.Values("sede");
                        sede__id = (string?)val.GetDefault("id");
                        sede__alta = (DateTime?)val.GetDefault("alta");
                    break;
                    case "domicilio":
                        val = db!.Values("domicilio");
                        domicilio__id = (string?)val.GetDefault("id");
                    break;
                    case "centro_educativo":
                        val = db!.Values("centro_educativo");
                        centro_educativo__id = (string?)val.GetDefault("id");
                    break;
                    case "domicilio_centro_educativo":
                        val = db!.Values("domicilio");
                        domicilio_centro_educativo__id = (string?)val.GetDefault("id");
                    break;
                    case "modalidad":
                        val = db!.Values("modalidad");
                        modalidad__id = (string?)val.GetDefault("id");
                    break;
                    case "planificacion":
                        val = db!.Values("planificacion");
                        planificacion__id = (string?)val.GetDefault("id");
                    break;
                    case "plan":
                        val = db!.Values("plan");
                        plan__id = (string?)val.GetDefault("id");
                    break;
                    case "calendario":
                        val = db!.Values("calendario");
                        calendario__id = (string?)val.GetDefault("id");
                        calendario__anio = (short?)val.GetDefault("anio");
                        calendario__semestre = (short?)val.GetDefault("semestre");
                        calendario__insertado = (DateTime?)val.GetDefault("insertado");
                    break;
                    case "relacion":
                        val = db!.Values("comision");
                        relacion__id = (string?)val.GetDefault("id");
                        relacion__alta = (DateTime?)val.GetDefault("alta");
                    break;
                    case "sede_relacion":
                        val = db!.Values("sede");
                        sede_relacion__id = (string?)val.GetDefault("id");
                        sede_relacion__alta = (DateTime?)val.GetDefault("alta");
                    break;
                    case "domicilio_sede_relacion":
                        val = db!.Values("domicilio");
                        domicilio_sede_relacion__id = (string?)val.GetDefault("id");
                    break;
                    case "centro_educativo_sede_relacion":
                        val = db!.Values("centro_educativo");
                        centro_educativo_sede_relacion__id = (string?)val.GetDefault("id");
                    break;
                    case "domicilio_centro_educativo_sede_relacion":
                        val = db!.Values("domicilio");
                        domicilio_centro_educativo_sede_relacion__id = (string?)val.GetDefault("id");
                    break;
                    case "modalidad_relacion":
                        val = db!.Values("modalidad");
                        modalidad_relacion__id = (string?)val.GetDefault("id");
                    break;
                    case "planificacion_relacion":
                        val = db!.Values("planificacion");
                        planificacion_relacion__id = (string?)val.GetDefault("id");
                    break;
                    case "plan_planificacion_relacion":
                        val = db!.Values("plan");
                        plan_planificacion_relacion__id = (string?)val.GetDefault("id");
                    break;
                    case "calendario_relacion":
                        val = db!.Values("calendario");
                        calendario_relacion__id = (string?)val.GetDefault("id");
                        calendario_relacion__anio = (short?)val.GetDefault("anio");
                        calendario_relacion__semestre = (short?)val.GetDefault("semestre");
                        calendario_relacion__insertado = (DateTime?)val.GetDefault("insertado");
                    break;
                }
            }
        }

        public string? comision__Label { get; set; }

        protected string? _comision__id = null;

        [JsonProperty("comision-id")]
        public string? comision__id
        {
            get { return _comision__id; }
            set { _comision__id = value; comision = value; NotifyPropertyChanged(nameof(comision__id)); }
        }
        protected string? _comision__turno = null;

        [JsonProperty("comision-turno")]
        public string? comision__turno
        {
            get { return _comision__turno; }
            set { _comision__turno = value; NotifyPropertyChanged(nameof(comision__turno)); }
        }
        protected string? _comision__division = null;

        [JsonProperty("comision-division")]
        public string? comision__division
        {
            get { return _comision__division; }
            set { _comision__division = value; NotifyPropertyChanged(nameof(comision__division)); }
        }
        protected string? _comision__comentario = null;

        [JsonProperty("comision-comentario")]
        public string? comision__comentario
        {
            get { return _comision__comentario; }
            set { _comision__comentario = value; NotifyPropertyChanged(nameof(comision__comentario)); }
        }
        protected bool? _comision__autorizada = null;

        [JsonProperty("comision-autorizada")]
        public bool? comision__autorizada
        {
            get { return _comision__autorizada; }
            set { _comision__autorizada = value; NotifyPropertyChanged(nameof(comision__autorizada)); }
        }
        protected bool? _comision__apertura = null;

        [JsonProperty("comision-apertura")]
        public bool? comision__apertura
        {
            get { return _comision__apertura; }
            set { _comision__apertura = value; NotifyPropertyChanged(nameof(comision__apertura)); }
        }
        protected bool? _comision__publicada = null;

        [JsonProperty("comision-publicada")]
        public bool? comision__publicada
        {
            get { return _comision__publicada; }
            set { _comision__publicada = value; NotifyPropertyChanged(nameof(comision__publicada)); }
        }
        protected string? _comision__observaciones = null;

        [JsonProperty("comision-observaciones")]
        public string? comision__observaciones
        {
            get { return _comision__observaciones; }
            set { _comision__observaciones = value; NotifyPropertyChanged(nameof(comision__observaciones)); }
        }
        protected DateTime? _comision__alta = null;

        [JsonProperty("comision-alta")]
        public DateTime? comision__alta
        {
            get { return _comision__alta; }
            set { _comision__alta = value; NotifyPropertyChanged(nameof(comision__alta)); }
        }
        protected string? _comision__sede = null;

        [JsonProperty("comision-sede")]
        public string? comision__sede
        {
            get { return _comision__sede; }
            set { _comision__sede = value; NotifyPropertyChanged(nameof(comision__sede)); }
        }
        protected string? _comision__modalidad = null;

        [JsonProperty("comision-modalidad")]
        public string? comision__modalidad
        {
            get { return _comision__modalidad; }
            set { _comision__modalidad = value; NotifyPropertyChanged(nameof(comision__modalidad)); }
        }
        protected string? _comision__planificacion = null;

        [JsonProperty("comision-planificacion")]
        public string? comision__planificacion
        {
            get { return _comision__planificacion; }
            set { _comision__planificacion = value; NotifyPropertyChanged(nameof(comision__planificacion)); }
        }
        protected string? _comision__comision_siguiente = null;

        [JsonProperty("comision-comision_siguiente")]
        public string? comision__comision_siguiente
        {
            get { return _comision__comision_siguiente; }
            set { _comision__comision_siguiente = value; NotifyPropertyChanged(nameof(comision__comision_siguiente)); }
        }
        protected string? _comision__calendario = null;

        [JsonProperty("comision-calendario")]
        public string? comision__calendario
        {
            get { return _comision__calendario; }
            set { _comision__calendario = value; NotifyPropertyChanged(nameof(comision__calendario)); }
        }
        protected string? _comision__identificacion = null;

        [JsonProperty("comision-identificacion")]
        public string? comision__identificacion
        {
            get { return _comision__identificacion; }
            set { _comision__identificacion = value; NotifyPropertyChanged(nameof(comision__identificacion)); }
        }
        protected string? _comision__pfid = null;

        [JsonProperty("comision-pfid")]
        public string? comision__pfid
        {
            get { return _comision__pfid; }
            set { _comision__pfid = value; NotifyPropertyChanged(nameof(comision__pfid)); }
        }

        public string? sede__Label { get; set; }

        protected string? _sede__id = null;

        [JsonProperty("sede-id")]
        public string? sede__id
        {
            get { return _sede__id; }
            set { _sede__id = value; comision__sede = value; NotifyPropertyChanged(nameof(sede__id)); }
        }
        protected string? _sede__numero = null;

        [JsonProperty("sede-numero")]
        public string? sede__numero
        {
            get { return _sede__numero; }
            set { _sede__numero = value; NotifyPropertyChanged(nameof(sede__numero)); }
        }
        protected string? _sede__nombre = null;

        [JsonProperty("sede-nombre")]
        public string? sede__nombre
        {
            get { return _sede__nombre; }
            set { _sede__nombre = value; NotifyPropertyChanged(nameof(sede__nombre)); }
        }
        protected string? _sede__observaciones = null;

        [JsonProperty("sede-observaciones")]
        public string? sede__observaciones
        {
            get { return _sede__observaciones; }
            set { _sede__observaciones = value; NotifyPropertyChanged(nameof(sede__observaciones)); }
        }
        protected DateTime? _sede__alta = null;

        [JsonProperty("sede-alta")]
        public DateTime? sede__alta
        {
            get { return _sede__alta; }
            set { _sede__alta = value; NotifyPropertyChanged(nameof(sede__alta)); }
        }
        protected DateTime? _sede__baja = null;

        [JsonProperty("sede-baja")]
        public DateTime? sede__baja
        {
            get { return _sede__baja; }
            set { _sede__baja = value; NotifyPropertyChanged(nameof(sede__baja)); }
        }
        protected string? _sede__domicilio = null;

        [JsonProperty("sede-domicilio")]
        public string? sede__domicilio
        {
            get { return _sede__domicilio; }
            set { _sede__domicilio = value; NotifyPropertyChanged(nameof(sede__domicilio)); }
        }
        protected string? _sede__centro_educativo = null;

        [JsonProperty("sede-centro_educativo")]
        public string? sede__centro_educativo
        {
            get { return _sede__centro_educativo; }
            set { _sede__centro_educativo = value; NotifyPropertyChanged(nameof(sede__centro_educativo)); }
        }
        protected DateTime? _sede__fecha_traspaso = null;

        [JsonProperty("sede-fecha_traspaso")]
        public DateTime? sede__fecha_traspaso
        {
            get { return _sede__fecha_traspaso; }
            set { _sede__fecha_traspaso = value; NotifyPropertyChanged(nameof(sede__fecha_traspaso)); }
        }
        protected string? _sede__organizacion = null;

        [JsonProperty("sede-organizacion")]
        public string? sede__organizacion
        {
            get { return _sede__organizacion; }
            set { _sede__organizacion = value; NotifyPropertyChanged(nameof(sede__organizacion)); }
        }
        protected string? _sede__pfid = null;

        [JsonProperty("sede-pfid")]
        public string? sede__pfid
        {
            get { return _sede__pfid; }
            set { _sede__pfid = value; NotifyPropertyChanged(nameof(sede__pfid)); }
        }
        protected string? _sede__pfid_organizacion = null;

        [JsonProperty("sede-pfid_organizacion")]
        public string? sede__pfid_organizacion
        {
            get { return _sede__pfid_organizacion; }
            set { _sede__pfid_organizacion = value; NotifyPropertyChanged(nameof(sede__pfid_organizacion)); }
        }

        public string? domicilio__Label { get; set; }

        protected string? _domicilio__id = null;

        [JsonProperty("domicilio-id")]
        public string? domicilio__id
        {
            get { return _domicilio__id; }
            set { _domicilio__id = value; sede__domicilio = value; NotifyPropertyChanged(nameof(domicilio__id)); }
        }
        protected string? _domicilio__calle = null;

        [JsonProperty("domicilio-calle")]
        public string? domicilio__calle
        {
            get { return _domicilio__calle; }
            set { _domicilio__calle = value; NotifyPropertyChanged(nameof(domicilio__calle)); }
        }
        protected string? _domicilio__entre = null;

        [JsonProperty("domicilio-entre")]
        public string? domicilio__entre
        {
            get { return _domicilio__entre; }
            set { _domicilio__entre = value; NotifyPropertyChanged(nameof(domicilio__entre)); }
        }
        protected string? _domicilio__numero = null;

        [JsonProperty("domicilio-numero")]
        public string? domicilio__numero
        {
            get { return _domicilio__numero; }
            set { _domicilio__numero = value; NotifyPropertyChanged(nameof(domicilio__numero)); }
        }
        protected string? _domicilio__piso = null;

        [JsonProperty("domicilio-piso")]
        public string? domicilio__piso
        {
            get { return _domicilio__piso; }
            set { _domicilio__piso = value; NotifyPropertyChanged(nameof(domicilio__piso)); }
        }
        protected string? _domicilio__departamento = null;

        [JsonProperty("domicilio-departamento")]
        public string? domicilio__departamento
        {
            get { return _domicilio__departamento; }
            set { _domicilio__departamento = value; NotifyPropertyChanged(nameof(domicilio__departamento)); }
        }
        protected string? _domicilio__barrio = null;

        [JsonProperty("domicilio-barrio")]
        public string? domicilio__barrio
        {
            get { return _domicilio__barrio; }
            set { _domicilio__barrio = value; NotifyPropertyChanged(nameof(domicilio__barrio)); }
        }
        protected string? _domicilio__localidad = null;

        [JsonProperty("domicilio-localidad")]
        public string? domicilio__localidad
        {
            get { return _domicilio__localidad; }
            set { _domicilio__localidad = value; NotifyPropertyChanged(nameof(domicilio__localidad)); }
        }

        public string? centro_educativo__Label { get; set; }

        protected string? _centro_educativo__id = null;

        [JsonProperty("centro_educativo-id")]
        public string? centro_educativo__id
        {
            get { return _centro_educativo__id; }
            set { _centro_educativo__id = value; sede__centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo__id)); }
        }
        protected string? _centro_educativo__nombre = null;

        [JsonProperty("centro_educativo-nombre")]
        public string? centro_educativo__nombre
        {
            get { return _centro_educativo__nombre; }
            set { _centro_educativo__nombre = value; NotifyPropertyChanged(nameof(centro_educativo__nombre)); }
        }
        protected string? _centro_educativo__cue = null;

        [JsonProperty("centro_educativo-cue")]
        public string? centro_educativo__cue
        {
            get { return _centro_educativo__cue; }
            set { _centro_educativo__cue = value; NotifyPropertyChanged(nameof(centro_educativo__cue)); }
        }
        protected string? _centro_educativo__domicilio = null;

        [JsonProperty("centro_educativo-domicilio")]
        public string? centro_educativo__domicilio
        {
            get { return _centro_educativo__domicilio; }
            set { _centro_educativo__domicilio = value; NotifyPropertyChanged(nameof(centro_educativo__domicilio)); }
        }
        protected string? _centro_educativo__observaciones = null;

        [JsonProperty("centro_educativo-observaciones")]
        public string? centro_educativo__observaciones
        {
            get { return _centro_educativo__observaciones; }
            set { _centro_educativo__observaciones = value; NotifyPropertyChanged(nameof(centro_educativo__observaciones)); }
        }

        public string? domicilio_centro_educativo__Label { get; set; }

        protected string? _domicilio_centro_educativo__id = null;

        [JsonProperty("domicilio_centro_educativo-id")]
        public string? domicilio_centro_educativo__id
        {
            get { return _domicilio_centro_educativo__id; }
            set { _domicilio_centro_educativo__id = value; centro_educativo__domicilio = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__id)); }
        }
        protected string? _domicilio_centro_educativo__calle = null;

        [JsonProperty("domicilio_centro_educativo-calle")]
        public string? domicilio_centro_educativo__calle
        {
            get { return _domicilio_centro_educativo__calle; }
            set { _domicilio_centro_educativo__calle = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__calle)); }
        }
        protected string? _domicilio_centro_educativo__entre = null;

        [JsonProperty("domicilio_centro_educativo-entre")]
        public string? domicilio_centro_educativo__entre
        {
            get { return _domicilio_centro_educativo__entre; }
            set { _domicilio_centro_educativo__entre = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__entre)); }
        }
        protected string? _domicilio_centro_educativo__numero = null;

        [JsonProperty("domicilio_centro_educativo-numero")]
        public string? domicilio_centro_educativo__numero
        {
            get { return _domicilio_centro_educativo__numero; }
            set { _domicilio_centro_educativo__numero = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__numero)); }
        }
        protected string? _domicilio_centro_educativo__piso = null;

        [JsonProperty("domicilio_centro_educativo-piso")]
        public string? domicilio_centro_educativo__piso
        {
            get { return _domicilio_centro_educativo__piso; }
            set { _domicilio_centro_educativo__piso = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__piso)); }
        }
        protected string? _domicilio_centro_educativo__departamento = null;

        [JsonProperty("domicilio_centro_educativo-departamento")]
        public string? domicilio_centro_educativo__departamento
        {
            get { return _domicilio_centro_educativo__departamento; }
            set { _domicilio_centro_educativo__departamento = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__departamento)); }
        }
        protected string? _domicilio_centro_educativo__barrio = null;

        [JsonProperty("domicilio_centro_educativo-barrio")]
        public string? domicilio_centro_educativo__barrio
        {
            get { return _domicilio_centro_educativo__barrio; }
            set { _domicilio_centro_educativo__barrio = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__barrio)); }
        }
        protected string? _domicilio_centro_educativo__localidad = null;

        [JsonProperty("domicilio_centro_educativo-localidad")]
        public string? domicilio_centro_educativo__localidad
        {
            get { return _domicilio_centro_educativo__localidad; }
            set { _domicilio_centro_educativo__localidad = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo__localidad)); }
        }

        public string? modalidad__Label { get; set; }

        protected string? _modalidad__id = null;

        [JsonProperty("modalidad-id")]
        public string? modalidad__id
        {
            get { return _modalidad__id; }
            set { _modalidad__id = value; comision__modalidad = value; NotifyPropertyChanged(nameof(modalidad__id)); }
        }
        protected string? _modalidad__nombre = null;

        [JsonProperty("modalidad-nombre")]
        public string? modalidad__nombre
        {
            get { return _modalidad__nombre; }
            set { _modalidad__nombre = value; NotifyPropertyChanged(nameof(modalidad__nombre)); }
        }
        protected string? _modalidad__pfid = null;

        [JsonProperty("modalidad-pfid")]
        public string? modalidad__pfid
        {
            get { return _modalidad__pfid; }
            set { _modalidad__pfid = value; NotifyPropertyChanged(nameof(modalidad__pfid)); }
        }

        public string? planificacion__Label { get; set; }

        protected string? _planificacion__id = null;

        [JsonProperty("planificacion-id")]
        public string? planificacion__id
        {
            get { return _planificacion__id; }
            set { _planificacion__id = value; comision__planificacion = value; NotifyPropertyChanged(nameof(planificacion__id)); }
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

        public string? calendario__Label { get; set; }

        protected string? _calendario__id = null;

        [JsonProperty("calendario-id")]
        public string? calendario__id
        {
            get { return _calendario__id; }
            set { _calendario__id = value; comision__calendario = value; NotifyPropertyChanged(nameof(calendario__id)); }
        }
        protected DateTime? _calendario__inicio = null;

        [JsonProperty("calendario-inicio")]
        public DateTime? calendario__inicio
        {
            get { return _calendario__inicio; }
            set { _calendario__inicio = value; NotifyPropertyChanged(nameof(calendario__inicio)); }
        }
        protected DateTime? _calendario__fin = null;

        [JsonProperty("calendario-fin")]
        public DateTime? calendario__fin
        {
            get { return _calendario__fin; }
            set { _calendario__fin = value; NotifyPropertyChanged(nameof(calendario__fin)); }
        }
        protected short? _calendario__anio = null;

        [JsonProperty("calendario-anio")]
        public short? calendario__anio
        {
            get { return _calendario__anio; }
            set { _calendario__anio = value; NotifyPropertyChanged(nameof(calendario__anio)); }
        }
        protected short? _calendario__semestre = null;

        [JsonProperty("calendario-semestre")]
        public short? calendario__semestre
        {
            get { return _calendario__semestre; }
            set { _calendario__semestre = value; NotifyPropertyChanged(nameof(calendario__semestre)); }
        }
        protected DateTime? _calendario__insertado = null;

        [JsonProperty("calendario-insertado")]
        public DateTime? calendario__insertado
        {
            get { return _calendario__insertado; }
            set { _calendario__insertado = value; NotifyPropertyChanged(nameof(calendario__insertado)); }
        }
        protected string? _calendario__descripcion = null;

        [JsonProperty("calendario-descripcion")]
        public string? calendario__descripcion
        {
            get { return _calendario__descripcion; }
            set { _calendario__descripcion = value; NotifyPropertyChanged(nameof(calendario__descripcion)); }
        }

        public string? relacion__Label { get; set; }

        protected string? _relacion__id = null;

        [JsonProperty("relacion-id")]
        public string? relacion__id
        {
            get { return _relacion__id; }
            set { _relacion__id = value; relacion = value; NotifyPropertyChanged(nameof(relacion__id)); }
        }
        protected string? _relacion__turno = null;

        [JsonProperty("relacion-turno")]
        public string? relacion__turno
        {
            get { return _relacion__turno; }
            set { _relacion__turno = value; NotifyPropertyChanged(nameof(relacion__turno)); }
        }
        protected string? _relacion__division = null;

        [JsonProperty("relacion-division")]
        public string? relacion__division
        {
            get { return _relacion__division; }
            set { _relacion__division = value; NotifyPropertyChanged(nameof(relacion__division)); }
        }
        protected string? _relacion__comentario = null;

        [JsonProperty("relacion-comentario")]
        public string? relacion__comentario
        {
            get { return _relacion__comentario; }
            set { _relacion__comentario = value; NotifyPropertyChanged(nameof(relacion__comentario)); }
        }
        protected bool? _relacion__autorizada = null;

        [JsonProperty("relacion-autorizada")]
        public bool? relacion__autorizada
        {
            get { return _relacion__autorizada; }
            set { _relacion__autorizada = value; NotifyPropertyChanged(nameof(relacion__autorizada)); }
        }
        protected bool? _relacion__apertura = null;

        [JsonProperty("relacion-apertura")]
        public bool? relacion__apertura
        {
            get { return _relacion__apertura; }
            set { _relacion__apertura = value; NotifyPropertyChanged(nameof(relacion__apertura)); }
        }
        protected bool? _relacion__publicada = null;

        [JsonProperty("relacion-publicada")]
        public bool? relacion__publicada
        {
            get { return _relacion__publicada; }
            set { _relacion__publicada = value; NotifyPropertyChanged(nameof(relacion__publicada)); }
        }
        protected string? _relacion__observaciones = null;

        [JsonProperty("relacion-observaciones")]
        public string? relacion__observaciones
        {
            get { return _relacion__observaciones; }
            set { _relacion__observaciones = value; NotifyPropertyChanged(nameof(relacion__observaciones)); }
        }
        protected DateTime? _relacion__alta = null;

        [JsonProperty("relacion-alta")]
        public DateTime? relacion__alta
        {
            get { return _relacion__alta; }
            set { _relacion__alta = value; NotifyPropertyChanged(nameof(relacion__alta)); }
        }
        protected string? _relacion__sede = null;

        [JsonProperty("relacion-sede")]
        public string? relacion__sede
        {
            get { return _relacion__sede; }
            set { _relacion__sede = value; NotifyPropertyChanged(nameof(relacion__sede)); }
        }
        protected string? _relacion__modalidad = null;

        [JsonProperty("relacion-modalidad")]
        public string? relacion__modalidad
        {
            get { return _relacion__modalidad; }
            set { _relacion__modalidad = value; NotifyPropertyChanged(nameof(relacion__modalidad)); }
        }
        protected string? _relacion__planificacion = null;

        [JsonProperty("relacion-planificacion")]
        public string? relacion__planificacion
        {
            get { return _relacion__planificacion; }
            set { _relacion__planificacion = value; NotifyPropertyChanged(nameof(relacion__planificacion)); }
        }
        protected string? _relacion__comision_siguiente = null;

        [JsonProperty("relacion-comision_siguiente")]
        public string? relacion__comision_siguiente
        {
            get { return _relacion__comision_siguiente; }
            set { _relacion__comision_siguiente = value; NotifyPropertyChanged(nameof(relacion__comision_siguiente)); }
        }
        protected string? _relacion__calendario = null;

        [JsonProperty("relacion-calendario")]
        public string? relacion__calendario
        {
            get { return _relacion__calendario; }
            set { _relacion__calendario = value; NotifyPropertyChanged(nameof(relacion__calendario)); }
        }
        protected string? _relacion__identificacion = null;

        [JsonProperty("relacion-identificacion")]
        public string? relacion__identificacion
        {
            get { return _relacion__identificacion; }
            set { _relacion__identificacion = value; NotifyPropertyChanged(nameof(relacion__identificacion)); }
        }
        protected string? _relacion__pfid = null;

        [JsonProperty("relacion-pfid")]
        public string? relacion__pfid
        {
            get { return _relacion__pfid; }
            set { _relacion__pfid = value; NotifyPropertyChanged(nameof(relacion__pfid)); }
        }

        public string? sede_relacion__Label { get; set; }

        protected string? _sede_relacion__id = null;

        [JsonProperty("sede_relacion-id")]
        public string? sede_relacion__id
        {
            get { return _sede_relacion__id; }
            set { _sede_relacion__id = value; relacion__sede = value; NotifyPropertyChanged(nameof(sede_relacion__id)); }
        }
        protected string? _sede_relacion__numero = null;

        [JsonProperty("sede_relacion-numero")]
        public string? sede_relacion__numero
        {
            get { return _sede_relacion__numero; }
            set { _sede_relacion__numero = value; NotifyPropertyChanged(nameof(sede_relacion__numero)); }
        }
        protected string? _sede_relacion__nombre = null;

        [JsonProperty("sede_relacion-nombre")]
        public string? sede_relacion__nombre
        {
            get { return _sede_relacion__nombre; }
            set { _sede_relacion__nombre = value; NotifyPropertyChanged(nameof(sede_relacion__nombre)); }
        }
        protected string? _sede_relacion__observaciones = null;

        [JsonProperty("sede_relacion-observaciones")]
        public string? sede_relacion__observaciones
        {
            get { return _sede_relacion__observaciones; }
            set { _sede_relacion__observaciones = value; NotifyPropertyChanged(nameof(sede_relacion__observaciones)); }
        }
        protected DateTime? _sede_relacion__alta = null;

        [JsonProperty("sede_relacion-alta")]
        public DateTime? sede_relacion__alta
        {
            get { return _sede_relacion__alta; }
            set { _sede_relacion__alta = value; NotifyPropertyChanged(nameof(sede_relacion__alta)); }
        }
        protected DateTime? _sede_relacion__baja = null;

        [JsonProperty("sede_relacion-baja")]
        public DateTime? sede_relacion__baja
        {
            get { return _sede_relacion__baja; }
            set { _sede_relacion__baja = value; NotifyPropertyChanged(nameof(sede_relacion__baja)); }
        }
        protected string? _sede_relacion__domicilio = null;

        [JsonProperty("sede_relacion-domicilio")]
        public string? sede_relacion__domicilio
        {
            get { return _sede_relacion__domicilio; }
            set { _sede_relacion__domicilio = value; NotifyPropertyChanged(nameof(sede_relacion__domicilio)); }
        }
        protected string? _sede_relacion__centro_educativo = null;

        [JsonProperty("sede_relacion-centro_educativo")]
        public string? sede_relacion__centro_educativo
        {
            get { return _sede_relacion__centro_educativo; }
            set { _sede_relacion__centro_educativo = value; NotifyPropertyChanged(nameof(sede_relacion__centro_educativo)); }
        }
        protected DateTime? _sede_relacion__fecha_traspaso = null;

        [JsonProperty("sede_relacion-fecha_traspaso")]
        public DateTime? sede_relacion__fecha_traspaso
        {
            get { return _sede_relacion__fecha_traspaso; }
            set { _sede_relacion__fecha_traspaso = value; NotifyPropertyChanged(nameof(sede_relacion__fecha_traspaso)); }
        }
        protected string? _sede_relacion__organizacion = null;

        [JsonProperty("sede_relacion-organizacion")]
        public string? sede_relacion__organizacion
        {
            get { return _sede_relacion__organizacion; }
            set { _sede_relacion__organizacion = value; NotifyPropertyChanged(nameof(sede_relacion__organizacion)); }
        }
        protected string? _sede_relacion__pfid = null;

        [JsonProperty("sede_relacion-pfid")]
        public string? sede_relacion__pfid
        {
            get { return _sede_relacion__pfid; }
            set { _sede_relacion__pfid = value; NotifyPropertyChanged(nameof(sede_relacion__pfid)); }
        }
        protected string? _sede_relacion__pfid_organizacion = null;

        [JsonProperty("sede_relacion-pfid_organizacion")]
        public string? sede_relacion__pfid_organizacion
        {
            get { return _sede_relacion__pfid_organizacion; }
            set { _sede_relacion__pfid_organizacion = value; NotifyPropertyChanged(nameof(sede_relacion__pfid_organizacion)); }
        }

        public string? domicilio_sede_relacion__Label { get; set; }

        protected string? _domicilio_sede_relacion__id = null;

        [JsonProperty("domicilio_sede_relacion-id")]
        public string? domicilio_sede_relacion__id
        {
            get { return _domicilio_sede_relacion__id; }
            set { _domicilio_sede_relacion__id = value; sede_relacion__domicilio = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__id)); }
        }
        protected string? _domicilio_sede_relacion__calle = null;

        [JsonProperty("domicilio_sede_relacion-calle")]
        public string? domicilio_sede_relacion__calle
        {
            get { return _domicilio_sede_relacion__calle; }
            set { _domicilio_sede_relacion__calle = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__calle)); }
        }
        protected string? _domicilio_sede_relacion__entre = null;

        [JsonProperty("domicilio_sede_relacion-entre")]
        public string? domicilio_sede_relacion__entre
        {
            get { return _domicilio_sede_relacion__entre; }
            set { _domicilio_sede_relacion__entre = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__entre)); }
        }
        protected string? _domicilio_sede_relacion__numero = null;

        [JsonProperty("domicilio_sede_relacion-numero")]
        public string? domicilio_sede_relacion__numero
        {
            get { return _domicilio_sede_relacion__numero; }
            set { _domicilio_sede_relacion__numero = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__numero)); }
        }
        protected string? _domicilio_sede_relacion__piso = null;

        [JsonProperty("domicilio_sede_relacion-piso")]
        public string? domicilio_sede_relacion__piso
        {
            get { return _domicilio_sede_relacion__piso; }
            set { _domicilio_sede_relacion__piso = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__piso)); }
        }
        protected string? _domicilio_sede_relacion__departamento = null;

        [JsonProperty("domicilio_sede_relacion-departamento")]
        public string? domicilio_sede_relacion__departamento
        {
            get { return _domicilio_sede_relacion__departamento; }
            set { _domicilio_sede_relacion__departamento = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__departamento)); }
        }
        protected string? _domicilio_sede_relacion__barrio = null;

        [JsonProperty("domicilio_sede_relacion-barrio")]
        public string? domicilio_sede_relacion__barrio
        {
            get { return _domicilio_sede_relacion__barrio; }
            set { _domicilio_sede_relacion__barrio = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__barrio)); }
        }
        protected string? _domicilio_sede_relacion__localidad = null;

        [JsonProperty("domicilio_sede_relacion-localidad")]
        public string? domicilio_sede_relacion__localidad
        {
            get { return _domicilio_sede_relacion__localidad; }
            set { _domicilio_sede_relacion__localidad = value; NotifyPropertyChanged(nameof(domicilio_sede_relacion__localidad)); }
        }

        public string? centro_educativo_sede_relacion__Label { get; set; }

        protected string? _centro_educativo_sede_relacion__id = null;

        [JsonProperty("centro_educativo_sede_relacion-id")]
        public string? centro_educativo_sede_relacion__id
        {
            get { return _centro_educativo_sede_relacion__id; }
            set { _centro_educativo_sede_relacion__id = value; sede_relacion__centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo_sede_relacion__id)); }
        }
        protected string? _centro_educativo_sede_relacion__nombre = null;

        [JsonProperty("centro_educativo_sede_relacion-nombre")]
        public string? centro_educativo_sede_relacion__nombre
        {
            get { return _centro_educativo_sede_relacion__nombre; }
            set { _centro_educativo_sede_relacion__nombre = value; NotifyPropertyChanged(nameof(centro_educativo_sede_relacion__nombre)); }
        }
        protected string? _centro_educativo_sede_relacion__cue = null;

        [JsonProperty("centro_educativo_sede_relacion-cue")]
        public string? centro_educativo_sede_relacion__cue
        {
            get { return _centro_educativo_sede_relacion__cue; }
            set { _centro_educativo_sede_relacion__cue = value; NotifyPropertyChanged(nameof(centro_educativo_sede_relacion__cue)); }
        }
        protected string? _centro_educativo_sede_relacion__domicilio = null;

        [JsonProperty("centro_educativo_sede_relacion-domicilio")]
        public string? centro_educativo_sede_relacion__domicilio
        {
            get { return _centro_educativo_sede_relacion__domicilio; }
            set { _centro_educativo_sede_relacion__domicilio = value; NotifyPropertyChanged(nameof(centro_educativo_sede_relacion__domicilio)); }
        }
        protected string? _centro_educativo_sede_relacion__observaciones = null;

        [JsonProperty("centro_educativo_sede_relacion-observaciones")]
        public string? centro_educativo_sede_relacion__observaciones
        {
            get { return _centro_educativo_sede_relacion__observaciones; }
            set { _centro_educativo_sede_relacion__observaciones = value; NotifyPropertyChanged(nameof(centro_educativo_sede_relacion__observaciones)); }
        }

        public string? domicilio_centro_educativo_sede_relacion__Label { get; set; }

        protected string? _domicilio_centro_educativo_sede_relacion__id = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-id")]
        public string? domicilio_centro_educativo_sede_relacion__id
        {
            get { return _domicilio_centro_educativo_sede_relacion__id; }
            set { _domicilio_centro_educativo_sede_relacion__id = value; centro_educativo_sede_relacion__domicilio = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__id)); }
        }
        protected string? _domicilio_centro_educativo_sede_relacion__calle = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-calle")]
        public string? domicilio_centro_educativo_sede_relacion__calle
        {
            get { return _domicilio_centro_educativo_sede_relacion__calle; }
            set { _domicilio_centro_educativo_sede_relacion__calle = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__calle)); }
        }
        protected string? _domicilio_centro_educativo_sede_relacion__entre = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-entre")]
        public string? domicilio_centro_educativo_sede_relacion__entre
        {
            get { return _domicilio_centro_educativo_sede_relacion__entre; }
            set { _domicilio_centro_educativo_sede_relacion__entre = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__entre)); }
        }
        protected string? _domicilio_centro_educativo_sede_relacion__numero = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-numero")]
        public string? domicilio_centro_educativo_sede_relacion__numero
        {
            get { return _domicilio_centro_educativo_sede_relacion__numero; }
            set { _domicilio_centro_educativo_sede_relacion__numero = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__numero)); }
        }
        protected string? _domicilio_centro_educativo_sede_relacion__piso = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-piso")]
        public string? domicilio_centro_educativo_sede_relacion__piso
        {
            get { return _domicilio_centro_educativo_sede_relacion__piso; }
            set { _domicilio_centro_educativo_sede_relacion__piso = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__piso)); }
        }
        protected string? _domicilio_centro_educativo_sede_relacion__departamento = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-departamento")]
        public string? domicilio_centro_educativo_sede_relacion__departamento
        {
            get { return _domicilio_centro_educativo_sede_relacion__departamento; }
            set { _domicilio_centro_educativo_sede_relacion__departamento = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__departamento)); }
        }
        protected string? _domicilio_centro_educativo_sede_relacion__barrio = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-barrio")]
        public string? domicilio_centro_educativo_sede_relacion__barrio
        {
            get { return _domicilio_centro_educativo_sede_relacion__barrio; }
            set { _domicilio_centro_educativo_sede_relacion__barrio = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__barrio)); }
        }
        protected string? _domicilio_centro_educativo_sede_relacion__localidad = null;

        [JsonProperty("domicilio_centro_educativo_sede_relacion-localidad")]
        public string? domicilio_centro_educativo_sede_relacion__localidad
        {
            get { return _domicilio_centro_educativo_sede_relacion__localidad; }
            set { _domicilio_centro_educativo_sede_relacion__localidad = value; NotifyPropertyChanged(nameof(domicilio_centro_educativo_sede_relacion__localidad)); }
        }

        public string? modalidad_relacion__Label { get; set; }

        protected string? _modalidad_relacion__id = null;

        [JsonProperty("modalidad_relacion-id")]
        public string? modalidad_relacion__id
        {
            get { return _modalidad_relacion__id; }
            set { _modalidad_relacion__id = value; relacion__modalidad = value; NotifyPropertyChanged(nameof(modalidad_relacion__id)); }
        }
        protected string? _modalidad_relacion__nombre = null;

        [JsonProperty("modalidad_relacion-nombre")]
        public string? modalidad_relacion__nombre
        {
            get { return _modalidad_relacion__nombre; }
            set { _modalidad_relacion__nombre = value; NotifyPropertyChanged(nameof(modalidad_relacion__nombre)); }
        }
        protected string? _modalidad_relacion__pfid = null;

        [JsonProperty("modalidad_relacion-pfid")]
        public string? modalidad_relacion__pfid
        {
            get { return _modalidad_relacion__pfid; }
            set { _modalidad_relacion__pfid = value; NotifyPropertyChanged(nameof(modalidad_relacion__pfid)); }
        }

        public string? planificacion_relacion__Label { get; set; }

        protected string? _planificacion_relacion__id = null;

        [JsonProperty("planificacion_relacion-id")]
        public string? planificacion_relacion__id
        {
            get { return _planificacion_relacion__id; }
            set { _planificacion_relacion__id = value; relacion__planificacion = value; NotifyPropertyChanged(nameof(planificacion_relacion__id)); }
        }
        protected string? _planificacion_relacion__anio = null;

        [JsonProperty("planificacion_relacion-anio")]
        public string? planificacion_relacion__anio
        {
            get { return _planificacion_relacion__anio; }
            set { _planificacion_relacion__anio = value; NotifyPropertyChanged(nameof(planificacion_relacion__anio)); }
        }
        protected string? _planificacion_relacion__semestre = null;

        [JsonProperty("planificacion_relacion-semestre")]
        public string? planificacion_relacion__semestre
        {
            get { return _planificacion_relacion__semestre; }
            set { _planificacion_relacion__semestre = value; NotifyPropertyChanged(nameof(planificacion_relacion__semestre)); }
        }
        protected string? _planificacion_relacion__plan = null;

        [JsonProperty("planificacion_relacion-plan")]
        public string? planificacion_relacion__plan
        {
            get { return _planificacion_relacion__plan; }
            set { _planificacion_relacion__plan = value; NotifyPropertyChanged(nameof(planificacion_relacion__plan)); }
        }
        protected string? _planificacion_relacion__pfid = null;

        [JsonProperty("planificacion_relacion-pfid")]
        public string? planificacion_relacion__pfid
        {
            get { return _planificacion_relacion__pfid; }
            set { _planificacion_relacion__pfid = value; NotifyPropertyChanged(nameof(planificacion_relacion__pfid)); }
        }

        public string? plan_planificacion_relacion__Label { get; set; }

        protected string? _plan_planificacion_relacion__id = null;

        [JsonProperty("plan_planificacion_relacion-id")]
        public string? plan_planificacion_relacion__id
        {
            get { return _plan_planificacion_relacion__id; }
            set { _plan_planificacion_relacion__id = value; planificacion_relacion__plan = value; NotifyPropertyChanged(nameof(plan_planificacion_relacion__id)); }
        }
        protected string? _plan_planificacion_relacion__orientacion = null;

        [JsonProperty("plan_planificacion_relacion-orientacion")]
        public string? plan_planificacion_relacion__orientacion
        {
            get { return _plan_planificacion_relacion__orientacion; }
            set { _plan_planificacion_relacion__orientacion = value; NotifyPropertyChanged(nameof(plan_planificacion_relacion__orientacion)); }
        }
        protected string? _plan_planificacion_relacion__resolucion = null;

        [JsonProperty("plan_planificacion_relacion-resolucion")]
        public string? plan_planificacion_relacion__resolucion
        {
            get { return _plan_planificacion_relacion__resolucion; }
            set { _plan_planificacion_relacion__resolucion = value; NotifyPropertyChanged(nameof(plan_planificacion_relacion__resolucion)); }
        }
        protected string? _plan_planificacion_relacion__distribucion_horaria = null;

        [JsonProperty("plan_planificacion_relacion-distribucion_horaria")]
        public string? plan_planificacion_relacion__distribucion_horaria
        {
            get { return _plan_planificacion_relacion__distribucion_horaria; }
            set { _plan_planificacion_relacion__distribucion_horaria = value; NotifyPropertyChanged(nameof(plan_planificacion_relacion__distribucion_horaria)); }
        }
        protected string? _plan_planificacion_relacion__pfid = null;

        [JsonProperty("plan_planificacion_relacion-pfid")]
        public string? plan_planificacion_relacion__pfid
        {
            get { return _plan_planificacion_relacion__pfid; }
            set { _plan_planificacion_relacion__pfid = value; NotifyPropertyChanged(nameof(plan_planificacion_relacion__pfid)); }
        }

        public string? calendario_relacion__Label { get; set; }

        protected string? _calendario_relacion__id = null;

        [JsonProperty("calendario_relacion-id")]
        public string? calendario_relacion__id
        {
            get { return _calendario_relacion__id; }
            set { _calendario_relacion__id = value; relacion__calendario = value; NotifyPropertyChanged(nameof(calendario_relacion__id)); }
        }
        protected DateTime? _calendario_relacion__inicio = null;

        [JsonProperty("calendario_relacion-inicio")]
        public DateTime? calendario_relacion__inicio
        {
            get { return _calendario_relacion__inicio; }
            set { _calendario_relacion__inicio = value; NotifyPropertyChanged(nameof(calendario_relacion__inicio)); }
        }
        protected DateTime? _calendario_relacion__fin = null;

        [JsonProperty("calendario_relacion-fin")]
        public DateTime? calendario_relacion__fin
        {
            get { return _calendario_relacion__fin; }
            set { _calendario_relacion__fin = value; NotifyPropertyChanged(nameof(calendario_relacion__fin)); }
        }
        protected short? _calendario_relacion__anio = null;

        [JsonProperty("calendario_relacion-anio")]
        public short? calendario_relacion__anio
        {
            get { return _calendario_relacion__anio; }
            set { _calendario_relacion__anio = value; NotifyPropertyChanged(nameof(calendario_relacion__anio)); }
        }
        protected short? _calendario_relacion__semestre = null;

        [JsonProperty("calendario_relacion-semestre")]
        public short? calendario_relacion__semestre
        {
            get { return _calendario_relacion__semestre; }
            set { _calendario_relacion__semestre = value; NotifyPropertyChanged(nameof(calendario_relacion__semestre)); }
        }
        protected DateTime? _calendario_relacion__insertado = null;

        [JsonProperty("calendario_relacion-insertado")]
        public DateTime? calendario_relacion__insertado
        {
            get { return _calendario_relacion__insertado; }
            set { _calendario_relacion__insertado = value; NotifyPropertyChanged(nameof(calendario_relacion__insertado)); }
        }
        protected string? _calendario_relacion__descripcion = null;

        [JsonProperty("calendario_relacion-descripcion")]
        public string? calendario_relacion__descripcion
        {
            get { return _calendario_relacion__descripcion; }
            set { _calendario_relacion__descripcion = value; NotifyPropertyChanged(nameof(calendario_relacion__descripcion)); }
        }
    }
}
