#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_calificacion_r : Data_calificacion
    {

        public Data_calificacion_r () : base()
        {
        }

        public Data_calificacion_r (Db db) : base(db)
        {
        }

        public void DefaultRel(params string[] fieldIds)
        {
            EntityValues val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "curso":
                        val = db!.Values("curso");
                        curso__id = (string?)val.GetDefault("id");
                        curso__alta = (DateTime?)val.GetDefault("alta");
                    break;
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
                    case "domicilio_cen":
                        val = db!.Values("domicilio");
                        domicilio_cen__id = (string?)val.GetDefault("id");
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
                    case "asignatura":
                        val = db!.Values("asignatura");
                        asignatura__id = (string?)val.GetDefault("id");
                    break;
                    case "alumno":
                        val = db!.Values("alumno");
                        alumno__id = (string?)val.GetDefault("id");
                        alumno__anio_ingreso = (string?)val.GetDefault("anio_ingreso");
                        alumno__semestre_ingreso = (short?)val.GetDefault("semestre_ingreso");
                        alumno__tiene_dni = (bool?)val.GetDefault("tiene_dni");
                        alumno__tiene_constancia = (bool?)val.GetDefault("tiene_constancia");
                        alumno__tiene_certificado = (bool?)val.GetDefault("tiene_certificado");
                        alumno__previas_completas = (bool?)val.GetDefault("previas_completas");
                        alumno__tiene_partida = (bool?)val.GetDefault("tiene_partida");
                        alumno__creado = (DateTime?)val.GetDefault("creado");
                        alumno__confirmado_direccion = (bool?)val.GetDefault("confirmado_direccion");
                    break;
                    case "persona":
                        val = db!.Values("persona");
                        persona__id = (string?)val.GetDefault("id");
                        persona__alta = (DateTime?)val.GetDefault("alta");
                        persona__telefono_verificado = (bool?)val.GetDefault("telefono_verificado");
                        persona__email_verificado = (bool?)val.GetDefault("email_verificado");
                        persona__info_verificada = (bool?)val.GetDefault("info_verificada");
                    break;
                    case "domicilio_per":
                        val = db!.Values("domicilio");
                        domicilio_per__id = (string?)val.GetDefault("id");
                    break;
                    case "plan_alu":
                        val = db!.Values("plan");
                        plan_alu__id = (string?)val.GetDefault("id");
                    break;
                    case "resolucion_inscripcion":
                        val = db!.Values("resolucion");
                        resolucion_inscripcion__id = (string?)val.GetDefault("id");
                    break;
                    case "disposicion":
                        val = db!.Values("disposicion");
                        disposicion__id = (string?)val.GetDefault("id");
                    break;
                    case "asignatura_dis":
                        val = db!.Values("asignatura");
                        asignatura_dis__id = (string?)val.GetDefault("id");
                    break;
                    case "planificacion_dis":
                        val = db!.Values("planificacion");
                        planificacion_dis__id = (string?)val.GetDefault("id");
                    break;
                    case "plan_pla":
                        val = db!.Values("plan");
                        plan_pla__id = (string?)val.GetDefault("id");
                    break;
                }
            }
        }

        public string? curso__Label { get; set; }

        protected string? _curso__id = null;

        [JsonProperty("curso-id")]
        public string? curso__id
        {
            get { return _curso__id; }
            set { _curso__id = value; curso = value; NotifyPropertyChanged(nameof(curso__id)); }
        }
        protected int? _curso__horas_catedra = null;

        [JsonProperty("curso-horas_catedra")]
        public int? curso__horas_catedra
        {
            get { return _curso__horas_catedra; }
            set { _curso__horas_catedra = value; NotifyPropertyChanged(nameof(curso__horas_catedra)); }
        }
        protected string? _curso__ige = null;

        [JsonProperty("curso-ige")]
        public string? curso__ige
        {
            get { return _curso__ige; }
            set { _curso__ige = value; NotifyPropertyChanged(nameof(curso__ige)); }
        }
        protected string? _curso__comision = null;

        [JsonProperty("curso-comision")]
        public string? curso__comision
        {
            get { return _curso__comision; }
            set { _curso__comision = value; NotifyPropertyChanged(nameof(curso__comision)); }
        }
        protected string? _curso__asignatura = null;

        [JsonProperty("curso-asignatura")]
        public string? curso__asignatura
        {
            get { return _curso__asignatura; }
            set { _curso__asignatura = value; NotifyPropertyChanged(nameof(curso__asignatura)); }
        }
        protected DateTime? _curso__alta = null;

        [JsonProperty("curso-alta")]
        public DateTime? curso__alta
        {
            get { return _curso__alta; }
            set { _curso__alta = value; NotifyPropertyChanged(nameof(curso__alta)); }
        }
        protected string? _curso__descripcion_horario = null;

        [JsonProperty("curso-descripcion_horario")]
        public string? curso__descripcion_horario
        {
            get { return _curso__descripcion_horario; }
            set { _curso__descripcion_horario = value; NotifyPropertyChanged(nameof(curso__descripcion_horario)); }
        }
        protected string? _curso__codigo = null;

        [JsonProperty("curso-codigo")]
        public string? curso__codigo
        {
            get { return _curso__codigo; }
            set { _curso__codigo = value; NotifyPropertyChanged(nameof(curso__codigo)); }
        }

        public string? comision__Label { get; set; }

        protected string? _comision__id = null;

        [JsonProperty("comision-id")]
        public string? comision__id
        {
            get { return _comision__id; }
            set { _comision__id = value; curso__comision = value; NotifyPropertyChanged(nameof(comision__id)); }
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

        public string? domicilio_cen__Label { get; set; }

        protected string? _domicilio_cen__id = null;

        [JsonProperty("domicilio_cen-id")]
        public string? domicilio_cen__id
        {
            get { return _domicilio_cen__id; }
            set { _domicilio_cen__id = value; centro_educativo__domicilio = value; NotifyPropertyChanged(nameof(domicilio_cen__id)); }
        }
        protected string? _domicilio_cen__calle = null;

        [JsonProperty("domicilio_cen-calle")]
        public string? domicilio_cen__calle
        {
            get { return _domicilio_cen__calle; }
            set { _domicilio_cen__calle = value; NotifyPropertyChanged(nameof(domicilio_cen__calle)); }
        }
        protected string? _domicilio_cen__entre = null;

        [JsonProperty("domicilio_cen-entre")]
        public string? domicilio_cen__entre
        {
            get { return _domicilio_cen__entre; }
            set { _domicilio_cen__entre = value; NotifyPropertyChanged(nameof(domicilio_cen__entre)); }
        }
        protected string? _domicilio_cen__numero = null;

        [JsonProperty("domicilio_cen-numero")]
        public string? domicilio_cen__numero
        {
            get { return _domicilio_cen__numero; }
            set { _domicilio_cen__numero = value; NotifyPropertyChanged(nameof(domicilio_cen__numero)); }
        }
        protected string? _domicilio_cen__piso = null;

        [JsonProperty("domicilio_cen-piso")]
        public string? domicilio_cen__piso
        {
            get { return _domicilio_cen__piso; }
            set { _domicilio_cen__piso = value; NotifyPropertyChanged(nameof(domicilio_cen__piso)); }
        }
        protected string? _domicilio_cen__departamento = null;

        [JsonProperty("domicilio_cen-departamento")]
        public string? domicilio_cen__departamento
        {
            get { return _domicilio_cen__departamento; }
            set { _domicilio_cen__departamento = value; NotifyPropertyChanged(nameof(domicilio_cen__departamento)); }
        }
        protected string? _domicilio_cen__barrio = null;

        [JsonProperty("domicilio_cen-barrio")]
        public string? domicilio_cen__barrio
        {
            get { return _domicilio_cen__barrio; }
            set { _domicilio_cen__barrio = value; NotifyPropertyChanged(nameof(domicilio_cen__barrio)); }
        }
        protected string? _domicilio_cen__localidad = null;

        [JsonProperty("domicilio_cen-localidad")]
        public string? domicilio_cen__localidad
        {
            get { return _domicilio_cen__localidad; }
            set { _domicilio_cen__localidad = value; NotifyPropertyChanged(nameof(domicilio_cen__localidad)); }
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

        public string? asignatura__Label { get; set; }

        protected string? _asignatura__id = null;

        [JsonProperty("asignatura-id")]
        public string? asignatura__id
        {
            get { return _asignatura__id; }
            set { _asignatura__id = value; curso__asignatura = value; NotifyPropertyChanged(nameof(asignatura__id)); }
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

        public string? alumno__Label { get; set; }

        protected string? _alumno__id = null;

        [JsonProperty("alumno-id")]
        public string? alumno__id
        {
            get { return _alumno__id; }
            set { _alumno__id = value; alumno = value; NotifyPropertyChanged(nameof(alumno__id)); }
        }
        protected string? _alumno__anio_ingreso = null;

        [JsonProperty("alumno-anio_ingreso")]
        public string? alumno__anio_ingreso
        {
            get { return _alumno__anio_ingreso; }
            set { _alumno__anio_ingreso = value; NotifyPropertyChanged(nameof(alumno__anio_ingreso)); }
        }
        protected string? _alumno__observaciones = null;

        [JsonProperty("alumno-observaciones")]
        public string? alumno__observaciones
        {
            get { return _alumno__observaciones; }
            set { _alumno__observaciones = value; NotifyPropertyChanged(nameof(alumno__observaciones)); }
        }
        protected string? _alumno__persona = null;

        [JsonProperty("alumno-persona")]
        public string? alumno__persona
        {
            get { return _alumno__persona; }
            set { _alumno__persona = value; NotifyPropertyChanged(nameof(alumno__persona)); }
        }
        protected string? _alumno__estado_inscripcion = null;

        [JsonProperty("alumno-estado_inscripcion")]
        public string? alumno__estado_inscripcion
        {
            get { return _alumno__estado_inscripcion; }
            set { _alumno__estado_inscripcion = value; NotifyPropertyChanged(nameof(alumno__estado_inscripcion)); }
        }
        protected DateTime? _alumno__fecha_titulacion = null;

        [JsonProperty("alumno-fecha_titulacion")]
        public DateTime? alumno__fecha_titulacion
        {
            get { return _alumno__fecha_titulacion; }
            set { _alumno__fecha_titulacion = value; NotifyPropertyChanged(nameof(alumno__fecha_titulacion)); }
        }
        protected string? _alumno__plan = null;

        [JsonProperty("alumno-plan")]
        public string? alumno__plan
        {
            get { return _alumno__plan; }
            set { _alumno__plan = value; NotifyPropertyChanged(nameof(alumno__plan)); }
        }
        protected string? _alumno__resolucion_inscripcion = null;

        [JsonProperty("alumno-resolucion_inscripcion")]
        public string? alumno__resolucion_inscripcion
        {
            get { return _alumno__resolucion_inscripcion; }
            set { _alumno__resolucion_inscripcion = value; NotifyPropertyChanged(nameof(alumno__resolucion_inscripcion)); }
        }
        protected short? _alumno__anio_inscripcion = null;

        [JsonProperty("alumno-anio_inscripcion")]
        public short? alumno__anio_inscripcion
        {
            get { return _alumno__anio_inscripcion; }
            set { _alumno__anio_inscripcion = value; NotifyPropertyChanged(nameof(alumno__anio_inscripcion)); }
        }
        protected short? _alumno__semestre_inscripcion = null;

        [JsonProperty("alumno-semestre_inscripcion")]
        public short? alumno__semestre_inscripcion
        {
            get { return _alumno__semestre_inscripcion; }
            set { _alumno__semestre_inscripcion = value; NotifyPropertyChanged(nameof(alumno__semestre_inscripcion)); }
        }
        protected short? _alumno__semestre_ingreso = null;

        [JsonProperty("alumno-semestre_ingreso")]
        public short? alumno__semestre_ingreso
        {
            get { return _alumno__semestre_ingreso; }
            set { _alumno__semestre_ingreso = value; NotifyPropertyChanged(nameof(alumno__semestre_ingreso)); }
        }
        protected string? _alumno__adeuda_legajo = null;

        [JsonProperty("alumno-adeuda_legajo")]
        public string? alumno__adeuda_legajo
        {
            get { return _alumno__adeuda_legajo; }
            set { _alumno__adeuda_legajo = value; NotifyPropertyChanged(nameof(alumno__adeuda_legajo)); }
        }
        protected string? _alumno__adeuda_deudores = null;

        [JsonProperty("alumno-adeuda_deudores")]
        public string? alumno__adeuda_deudores
        {
            get { return _alumno__adeuda_deudores; }
            set { _alumno__adeuda_deudores = value; NotifyPropertyChanged(nameof(alumno__adeuda_deudores)); }
        }
        protected string? _alumno__documentacion_inscripcion = null;

        [JsonProperty("alumno-documentacion_inscripcion")]
        public string? alumno__documentacion_inscripcion
        {
            get { return _alumno__documentacion_inscripcion; }
            set { _alumno__documentacion_inscripcion = value; NotifyPropertyChanged(nameof(alumno__documentacion_inscripcion)); }
        }
        protected bool? _alumno__anio_inscripcion_completo = null;

        [JsonProperty("alumno-anio_inscripcion_completo")]
        public bool? alumno__anio_inscripcion_completo
        {
            get { return _alumno__anio_inscripcion_completo; }
            set { _alumno__anio_inscripcion_completo = value; NotifyPropertyChanged(nameof(alumno__anio_inscripcion_completo)); }
        }
        protected string? _alumno__establecimiento_inscripcion = null;

        [JsonProperty("alumno-establecimiento_inscripcion")]
        public string? alumno__establecimiento_inscripcion
        {
            get { return _alumno__establecimiento_inscripcion; }
            set { _alumno__establecimiento_inscripcion = value; NotifyPropertyChanged(nameof(alumno__establecimiento_inscripcion)); }
        }
        protected string? _alumno__libro_folio = null;

        [JsonProperty("alumno-libro_folio")]
        public string? alumno__libro_folio
        {
            get { return _alumno__libro_folio; }
            set { _alumno__libro_folio = value; NotifyPropertyChanged(nameof(alumno__libro_folio)); }
        }
        protected string? _alumno__libro = null;

        [JsonProperty("alumno-libro")]
        public string? alumno__libro
        {
            get { return _alumno__libro; }
            set { _alumno__libro = value; NotifyPropertyChanged(nameof(alumno__libro)); }
        }
        protected string? _alumno__folio = null;

        [JsonProperty("alumno-folio")]
        public string? alumno__folio
        {
            get { return _alumno__folio; }
            set { _alumno__folio = value; NotifyPropertyChanged(nameof(alumno__folio)); }
        }
        protected string? _alumno__comentarios = null;

        [JsonProperty("alumno-comentarios")]
        public string? alumno__comentarios
        {
            get { return _alumno__comentarios; }
            set { _alumno__comentarios = value; NotifyPropertyChanged(nameof(alumno__comentarios)); }
        }
        protected bool? _alumno__tiene_dni = null;

        [JsonProperty("alumno-tiene_dni")]
        public bool? alumno__tiene_dni
        {
            get { return _alumno__tiene_dni; }
            set { _alumno__tiene_dni = value; NotifyPropertyChanged(nameof(alumno__tiene_dni)); }
        }
        protected bool? _alumno__tiene_constancia = null;

        [JsonProperty("alumno-tiene_constancia")]
        public bool? alumno__tiene_constancia
        {
            get { return _alumno__tiene_constancia; }
            set { _alumno__tiene_constancia = value; NotifyPropertyChanged(nameof(alumno__tiene_constancia)); }
        }
        protected bool? _alumno__tiene_certificado = null;

        [JsonProperty("alumno-tiene_certificado")]
        public bool? alumno__tiene_certificado
        {
            get { return _alumno__tiene_certificado; }
            set { _alumno__tiene_certificado = value; NotifyPropertyChanged(nameof(alumno__tiene_certificado)); }
        }
        protected bool? _alumno__previas_completas = null;

        [JsonProperty("alumno-previas_completas")]
        public bool? alumno__previas_completas
        {
            get { return _alumno__previas_completas; }
            set { _alumno__previas_completas = value; NotifyPropertyChanged(nameof(alumno__previas_completas)); }
        }
        protected bool? _alumno__tiene_partida = null;

        [JsonProperty("alumno-tiene_partida")]
        public bool? alumno__tiene_partida
        {
            get { return _alumno__tiene_partida; }
            set { _alumno__tiene_partida = value; NotifyPropertyChanged(nameof(alumno__tiene_partida)); }
        }
        protected DateTime? _alumno__creado = null;

        [JsonProperty("alumno-creado")]
        public DateTime? alumno__creado
        {
            get { return _alumno__creado; }
            set { _alumno__creado = value; NotifyPropertyChanged(nameof(alumno__creado)); }
        }
        protected bool? _alumno__confirmado_direccion = null;

        [JsonProperty("alumno-confirmado_direccion")]
        public bool? alumno__confirmado_direccion
        {
            get { return _alumno__confirmado_direccion; }
            set { _alumno__confirmado_direccion = value; NotifyPropertyChanged(nameof(alumno__confirmado_direccion)); }
        }

        public string? persona__Label { get; set; }

        protected string? _persona__id = null;

        [JsonProperty("persona-id")]
        public string? persona__id
        {
            get { return _persona__id; }
            set { _persona__id = value; alumno__persona = value; NotifyPropertyChanged(nameof(persona__id)); }
        }
        protected string? _persona__nombres = null;

        [JsonProperty("persona-nombres")]
        public string? persona__nombres
        {
            get { return _persona__nombres; }
            set { _persona__nombres = value; NotifyPropertyChanged(nameof(persona__nombres)); }
        }
        protected string? _persona__apellidos = null;

        [JsonProperty("persona-apellidos")]
        public string? persona__apellidos
        {
            get { return _persona__apellidos; }
            set { _persona__apellidos = value; NotifyPropertyChanged(nameof(persona__apellidos)); }
        }
        protected DateTime? _persona__fecha_nacimiento = null;

        [JsonProperty("persona-fecha_nacimiento")]
        public DateTime? persona__fecha_nacimiento
        {
            get { return _persona__fecha_nacimiento; }
            set { _persona__fecha_nacimiento = value; NotifyPropertyChanged(nameof(persona__fecha_nacimiento)); }
        }
        protected string? _persona__numero_documento = null;

        [JsonProperty("persona-numero_documento")]
        public string? persona__numero_documento
        {
            get { return _persona__numero_documento; }
            set { _persona__numero_documento = value; NotifyPropertyChanged(nameof(persona__numero_documento)); }
        }
        protected string? _persona__cuil = null;

        [JsonProperty("persona-cuil")]
        public string? persona__cuil
        {
            get { return _persona__cuil; }
            set { _persona__cuil = value; NotifyPropertyChanged(nameof(persona__cuil)); }
        }
        protected string? _persona__genero = null;

        [JsonProperty("persona-genero")]
        public string? persona__genero
        {
            get { return _persona__genero; }
            set { _persona__genero = value; NotifyPropertyChanged(nameof(persona__genero)); }
        }
        protected string? _persona__apodo = null;

        [JsonProperty("persona-apodo")]
        public string? persona__apodo
        {
            get { return _persona__apodo; }
            set { _persona__apodo = value; NotifyPropertyChanged(nameof(persona__apodo)); }
        }
        protected string? _persona__telefono = null;

        [JsonProperty("persona-telefono")]
        public string? persona__telefono
        {
            get { return _persona__telefono; }
            set { _persona__telefono = value; NotifyPropertyChanged(nameof(persona__telefono)); }
        }
        protected string? _persona__email = null;

        [JsonProperty("persona-email")]
        public string? persona__email
        {
            get { return _persona__email; }
            set { _persona__email = value; NotifyPropertyChanged(nameof(persona__email)); }
        }
        protected string? _persona__email_abc = null;

        [JsonProperty("persona-email_abc")]
        public string? persona__email_abc
        {
            get { return _persona__email_abc; }
            set { _persona__email_abc = value; NotifyPropertyChanged(nameof(persona__email_abc)); }
        }
        protected DateTime? _persona__alta = null;

        [JsonProperty("persona-alta")]
        public DateTime? persona__alta
        {
            get { return _persona__alta; }
            set { _persona__alta = value; NotifyPropertyChanged(nameof(persona__alta)); }
        }
        protected string? _persona__domicilio = null;

        [JsonProperty("persona-domicilio")]
        public string? persona__domicilio
        {
            get { return _persona__domicilio; }
            set { _persona__domicilio = value; NotifyPropertyChanged(nameof(persona__domicilio)); }
        }
        protected string? _persona__lugar_nacimiento = null;

        [JsonProperty("persona-lugar_nacimiento")]
        public string? persona__lugar_nacimiento
        {
            get { return _persona__lugar_nacimiento; }
            set { _persona__lugar_nacimiento = value; NotifyPropertyChanged(nameof(persona__lugar_nacimiento)); }
        }
        protected bool? _persona__telefono_verificado = null;

        [JsonProperty("persona-telefono_verificado")]
        public bool? persona__telefono_verificado
        {
            get { return _persona__telefono_verificado; }
            set { _persona__telefono_verificado = value; NotifyPropertyChanged(nameof(persona__telefono_verificado)); }
        }
        protected bool? _persona__email_verificado = null;

        [JsonProperty("persona-email_verificado")]
        public bool? persona__email_verificado
        {
            get { return _persona__email_verificado; }
            set { _persona__email_verificado = value; NotifyPropertyChanged(nameof(persona__email_verificado)); }
        }
        protected bool? _persona__info_verificada = null;

        [JsonProperty("persona-info_verificada")]
        public bool? persona__info_verificada
        {
            get { return _persona__info_verificada; }
            set { _persona__info_verificada = value; NotifyPropertyChanged(nameof(persona__info_verificada)); }
        }
        protected string? _persona__descripcion_domicilio = null;

        [JsonProperty("persona-descripcion_domicilio")]
        public string? persona__descripcion_domicilio
        {
            get { return _persona__descripcion_domicilio; }
            set { _persona__descripcion_domicilio = value; NotifyPropertyChanged(nameof(persona__descripcion_domicilio)); }
        }
        protected byte? _persona__cuil1 = null;

        [JsonProperty("persona-cuil1")]
        public byte? persona__cuil1
        {
            get { return _persona__cuil1; }
            set { _persona__cuil1 = value; NotifyPropertyChanged(nameof(persona__cuil1)); }
        }
        protected byte? _persona__cuil2 = null;

        [JsonProperty("persona-cuil2")]
        public byte? persona__cuil2
        {
            get { return _persona__cuil2; }
            set { _persona__cuil2 = value; NotifyPropertyChanged(nameof(persona__cuil2)); }
        }
        protected string? _persona__departamento = null;

        [JsonProperty("persona-departamento")]
        public string? persona__departamento
        {
            get { return _persona__departamento; }
            set { _persona__departamento = value; NotifyPropertyChanged(nameof(persona__departamento)); }
        }
        protected string? _persona__localidad = null;

        [JsonProperty("persona-localidad")]
        public string? persona__localidad
        {
            get { return _persona__localidad; }
            set { _persona__localidad = value; NotifyPropertyChanged(nameof(persona__localidad)); }
        }
        protected string? _persona__partido = null;

        [JsonProperty("persona-partido")]
        public string? persona__partido
        {
            get { return _persona__partido; }
            set { _persona__partido = value; NotifyPropertyChanged(nameof(persona__partido)); }
        }
        protected string? _persona__codigo_area = null;

        [JsonProperty("persona-codigo_area")]
        public string? persona__codigo_area
        {
            get { return _persona__codigo_area; }
            set { _persona__codigo_area = value; NotifyPropertyChanged(nameof(persona__codigo_area)); }
        }
        protected string? _persona__nacionalidad = null;

        [JsonProperty("persona-nacionalidad")]
        public string? persona__nacionalidad
        {
            get { return _persona__nacionalidad; }
            set { _persona__nacionalidad = value; NotifyPropertyChanged(nameof(persona__nacionalidad)); }
        }
        protected byte? _persona__sexo = null;

        [JsonProperty("persona-sexo")]
        public byte? persona__sexo
        {
            get { return _persona__sexo; }
            set { _persona__sexo = value; NotifyPropertyChanged(nameof(persona__sexo)); }
        }
        protected byte? _persona__dia_nacimiento = null;

        [JsonProperty("persona-dia_nacimiento")]
        public byte? persona__dia_nacimiento
        {
            get { return _persona__dia_nacimiento; }
            set { _persona__dia_nacimiento = value; NotifyPropertyChanged(nameof(persona__dia_nacimiento)); }
        }
        protected byte? _persona__mes_nacimiento = null;

        [JsonProperty("persona-mes_nacimiento")]
        public byte? persona__mes_nacimiento
        {
            get { return _persona__mes_nacimiento; }
            set { _persona__mes_nacimiento = value; NotifyPropertyChanged(nameof(persona__mes_nacimiento)); }
        }
        protected ushort? _persona__anio_nacimiento = null;

        [JsonProperty("persona-anio_nacimiento")]
        public ushort? persona__anio_nacimiento
        {
            get { return _persona__anio_nacimiento; }
            set { _persona__anio_nacimiento = value; NotifyPropertyChanged(nameof(persona__anio_nacimiento)); }
        }

        public string? domicilio_per__Label { get; set; }

        protected string? _domicilio_per__id = null;

        [JsonProperty("domicilio_per-id")]
        public string? domicilio_per__id
        {
            get { return _domicilio_per__id; }
            set { _domicilio_per__id = value; persona__domicilio = value; NotifyPropertyChanged(nameof(domicilio_per__id)); }
        }
        protected string? _domicilio_per__calle = null;

        [JsonProperty("domicilio_per-calle")]
        public string? domicilio_per__calle
        {
            get { return _domicilio_per__calle; }
            set { _domicilio_per__calle = value; NotifyPropertyChanged(nameof(domicilio_per__calle)); }
        }
        protected string? _domicilio_per__entre = null;

        [JsonProperty("domicilio_per-entre")]
        public string? domicilio_per__entre
        {
            get { return _domicilio_per__entre; }
            set { _domicilio_per__entre = value; NotifyPropertyChanged(nameof(domicilio_per__entre)); }
        }
        protected string? _domicilio_per__numero = null;

        [JsonProperty("domicilio_per-numero")]
        public string? domicilio_per__numero
        {
            get { return _domicilio_per__numero; }
            set { _domicilio_per__numero = value; NotifyPropertyChanged(nameof(domicilio_per__numero)); }
        }
        protected string? _domicilio_per__piso = null;

        [JsonProperty("domicilio_per-piso")]
        public string? domicilio_per__piso
        {
            get { return _domicilio_per__piso; }
            set { _domicilio_per__piso = value; NotifyPropertyChanged(nameof(domicilio_per__piso)); }
        }
        protected string? _domicilio_per__departamento = null;

        [JsonProperty("domicilio_per-departamento")]
        public string? domicilio_per__departamento
        {
            get { return _domicilio_per__departamento; }
            set { _domicilio_per__departamento = value; NotifyPropertyChanged(nameof(domicilio_per__departamento)); }
        }
        protected string? _domicilio_per__barrio = null;

        [JsonProperty("domicilio_per-barrio")]
        public string? domicilio_per__barrio
        {
            get { return _domicilio_per__barrio; }
            set { _domicilio_per__barrio = value; NotifyPropertyChanged(nameof(domicilio_per__barrio)); }
        }
        protected string? _domicilio_per__localidad = null;

        [JsonProperty("domicilio_per-localidad")]
        public string? domicilio_per__localidad
        {
            get { return _domicilio_per__localidad; }
            set { _domicilio_per__localidad = value; NotifyPropertyChanged(nameof(domicilio_per__localidad)); }
        }

        public string? plan_alu__Label { get; set; }

        protected string? _plan_alu__id = null;

        [JsonProperty("plan_alu-id")]
        public string? plan_alu__id
        {
            get { return _plan_alu__id; }
            set { _plan_alu__id = value; alumno__plan = value; NotifyPropertyChanged(nameof(plan_alu__id)); }
        }
        protected string? _plan_alu__orientacion = null;

        [JsonProperty("plan_alu-orientacion")]
        public string? plan_alu__orientacion
        {
            get { return _plan_alu__orientacion; }
            set { _plan_alu__orientacion = value; NotifyPropertyChanged(nameof(plan_alu__orientacion)); }
        }
        protected string? _plan_alu__resolucion = null;

        [JsonProperty("plan_alu-resolucion")]
        public string? plan_alu__resolucion
        {
            get { return _plan_alu__resolucion; }
            set { _plan_alu__resolucion = value; NotifyPropertyChanged(nameof(plan_alu__resolucion)); }
        }
        protected string? _plan_alu__distribucion_horaria = null;

        [JsonProperty("plan_alu-distribucion_horaria")]
        public string? plan_alu__distribucion_horaria
        {
            get { return _plan_alu__distribucion_horaria; }
            set { _plan_alu__distribucion_horaria = value; NotifyPropertyChanged(nameof(plan_alu__distribucion_horaria)); }
        }
        protected string? _plan_alu__pfid = null;

        [JsonProperty("plan_alu-pfid")]
        public string? plan_alu__pfid
        {
            get { return _plan_alu__pfid; }
            set { _plan_alu__pfid = value; NotifyPropertyChanged(nameof(plan_alu__pfid)); }
        }

        public string? resolucion_inscripcion__Label { get; set; }

        protected string? _resolucion_inscripcion__id = null;

        [JsonProperty("resolucion_inscripcion-id")]
        public string? resolucion_inscripcion__id
        {
            get { return _resolucion_inscripcion__id; }
            set { _resolucion_inscripcion__id = value; alumno__resolucion_inscripcion = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__id)); }
        }
        protected string? _resolucion_inscripcion__numero = null;

        [JsonProperty("resolucion_inscripcion-numero")]
        public string? resolucion_inscripcion__numero
        {
            get { return _resolucion_inscripcion__numero; }
            set { _resolucion_inscripcion__numero = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__numero)); }
        }
        protected short? _resolucion_inscripcion__anio = null;

        [JsonProperty("resolucion_inscripcion-anio")]
        public short? resolucion_inscripcion__anio
        {
            get { return _resolucion_inscripcion__anio; }
            set { _resolucion_inscripcion__anio = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__anio)); }
        }
        protected string? _resolucion_inscripcion__tipo = null;

        [JsonProperty("resolucion_inscripcion-tipo")]
        public string? resolucion_inscripcion__tipo
        {
            get { return _resolucion_inscripcion__tipo; }
            set { _resolucion_inscripcion__tipo = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__tipo)); }
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

        public string? asignatura_dis__Label { get; set; }

        protected string? _asignatura_dis__id = null;

        [JsonProperty("asignatura_dis-id")]
        public string? asignatura_dis__id
        {
            get { return _asignatura_dis__id; }
            set { _asignatura_dis__id = value; disposicion__asignatura = value; NotifyPropertyChanged(nameof(asignatura_dis__id)); }
        }
        protected string? _asignatura_dis__nombre = null;

        [JsonProperty("asignatura_dis-nombre")]
        public string? asignatura_dis__nombre
        {
            get { return _asignatura_dis__nombre; }
            set { _asignatura_dis__nombre = value; NotifyPropertyChanged(nameof(asignatura_dis__nombre)); }
        }
        protected string? _asignatura_dis__formacion = null;

        [JsonProperty("asignatura_dis-formacion")]
        public string? asignatura_dis__formacion
        {
            get { return _asignatura_dis__formacion; }
            set { _asignatura_dis__formacion = value; NotifyPropertyChanged(nameof(asignatura_dis__formacion)); }
        }
        protected string? _asignatura_dis__clasificacion = null;

        [JsonProperty("asignatura_dis-clasificacion")]
        public string? asignatura_dis__clasificacion
        {
            get { return _asignatura_dis__clasificacion; }
            set { _asignatura_dis__clasificacion = value; NotifyPropertyChanged(nameof(asignatura_dis__clasificacion)); }
        }
        protected string? _asignatura_dis__codigo = null;

        [JsonProperty("asignatura_dis-codigo")]
        public string? asignatura_dis__codigo
        {
            get { return _asignatura_dis__codigo; }
            set { _asignatura_dis__codigo = value; NotifyPropertyChanged(nameof(asignatura_dis__codigo)); }
        }
        protected string? _asignatura_dis__perfil = null;

        [JsonProperty("asignatura_dis-perfil")]
        public string? asignatura_dis__perfil
        {
            get { return _asignatura_dis__perfil; }
            set { _asignatura_dis__perfil = value; NotifyPropertyChanged(nameof(asignatura_dis__perfil)); }
        }

        public string? planificacion_dis__Label { get; set; }

        protected string? _planificacion_dis__id = null;

        [JsonProperty("planificacion_dis-id")]
        public string? planificacion_dis__id
        {
            get { return _planificacion_dis__id; }
            set { _planificacion_dis__id = value; disposicion__planificacion = value; NotifyPropertyChanged(nameof(planificacion_dis__id)); }
        }
        protected string? _planificacion_dis__anio = null;

        [JsonProperty("planificacion_dis-anio")]
        public string? planificacion_dis__anio
        {
            get { return _planificacion_dis__anio; }
            set { _planificacion_dis__anio = value; NotifyPropertyChanged(nameof(planificacion_dis__anio)); }
        }
        protected string? _planificacion_dis__semestre = null;

        [JsonProperty("planificacion_dis-semestre")]
        public string? planificacion_dis__semestre
        {
            get { return _planificacion_dis__semestre; }
            set { _planificacion_dis__semestre = value; NotifyPropertyChanged(nameof(planificacion_dis__semestre)); }
        }
        protected string? _planificacion_dis__plan = null;

        [JsonProperty("planificacion_dis-plan")]
        public string? planificacion_dis__plan
        {
            get { return _planificacion_dis__plan; }
            set { _planificacion_dis__plan = value; NotifyPropertyChanged(nameof(planificacion_dis__plan)); }
        }
        protected string? _planificacion_dis__pfid = null;

        [JsonProperty("planificacion_dis-pfid")]
        public string? planificacion_dis__pfid
        {
            get { return _planificacion_dis__pfid; }
            set { _planificacion_dis__pfid = value; NotifyPropertyChanged(nameof(planificacion_dis__pfid)); }
        }

        public string? plan_pla__Label { get; set; }

        protected string? _plan_pla__id = null;

        [JsonProperty("plan_pla-id")]
        public string? plan_pla__id
        {
            get { return _plan_pla__id; }
            set { _plan_pla__id = value; planificacion_dis__plan = value; NotifyPropertyChanged(nameof(plan_pla__id)); }
        }
        protected string? _plan_pla__orientacion = null;

        [JsonProperty("plan_pla-orientacion")]
        public string? plan_pla__orientacion
        {
            get { return _plan_pla__orientacion; }
            set { _plan_pla__orientacion = value; NotifyPropertyChanged(nameof(plan_pla__orientacion)); }
        }
        protected string? _plan_pla__resolucion = null;

        [JsonProperty("plan_pla-resolucion")]
        public string? plan_pla__resolucion
        {
            get { return _plan_pla__resolucion; }
            set { _plan_pla__resolucion = value; NotifyPropertyChanged(nameof(plan_pla__resolucion)); }
        }
        protected string? _plan_pla__distribucion_horaria = null;

        [JsonProperty("plan_pla-distribucion_horaria")]
        public string? plan_pla__distribucion_horaria
        {
            get { return _plan_pla__distribucion_horaria; }
            set { _plan_pla__distribucion_horaria = value; NotifyPropertyChanged(nameof(plan_pla__distribucion_horaria)); }
        }
        protected string? _plan_pla__pfid = null;

        [JsonProperty("plan_pla-pfid")]
        public string? plan_pla__pfid
        {
            get { return _plan_pla__pfid; }
            set { _plan_pla__pfid = value; NotifyPropertyChanged(nameof(plan_pla__pfid)); }
        }
    }
}
