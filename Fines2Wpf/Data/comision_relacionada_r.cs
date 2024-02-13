#nullable enable
using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_comision_relacionada_r : Data_comision_relacionada
    {

        public Data_comision_relacionada_r () : base()
        {
            Initialize();
        }

        public Data_comision_relacionada_r (DataInitMode mode = DataInitMode.Default) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
                    comision__id = (string?)ContainerApp.db.Values("comision").Default("id").Get("id");
                    comision__alta = (DateTime?)ContainerApp.db.Values("comision").Default("alta").Get("alta");
                    sede__id = (string?)ContainerApp.db.Values("sede").Default("id").Get("id");
                    sede__alta = (DateTime?)ContainerApp.db.Values("sede").Default("alta").Get("alta");
                    domicilio__id = (string?)ContainerApp.db.Values("domicilio").Default("id").Get("id");
                    centro_educativo__id = (string?)ContainerApp.db.Values("centro_educativo").Default("id").Get("id");
                    domicilio_cen__id = (string?)ContainerApp.db.Values("domicilio").Default("id").Get("id");
                    modalidad__id = (string?)ContainerApp.db.Values("modalidad").Default("id").Get("id");
                    planificacion__id = (string?)ContainerApp.db.Values("planificacion").Default("id").Get("id");
                    plan__id = (string?)ContainerApp.db.Values("plan").Default("id").Get("id");
                    calendario__id = (string?)ContainerApp.db.Values("calendario").Default("id").Get("id");
                    calendario__anio = (short?)ContainerApp.db.Values("calendario").Default("anio").Get("anio");
                    calendario__semestre = (short?)ContainerApp.db.Values("calendario").Default("semestre").Get("semestre");
                    calendario__insertado = (DateTime?)ContainerApp.db.Values("calendario").Default("insertado").Get("insertado");
                    relacion__id = (string?)ContainerApp.db.Values("comision").Default("id").Get("id");
                    relacion__alta = (DateTime?)ContainerApp.db.Values("comision").Default("alta").Get("alta");
                    sede_rel__id = (string?)ContainerApp.db.Values("sede").Default("id").Get("id");
                    sede_rel__alta = (DateTime?)ContainerApp.db.Values("sede").Default("alta").Get("alta");
                    domicilio_sed__id = (string?)ContainerApp.db.Values("domicilio").Default("id").Get("id");
                    centro_educativo_sed__id = (string?)ContainerApp.db.Values("centro_educativo").Default("id").Get("id");
                    domicilio_cen1__id = (string?)ContainerApp.db.Values("domicilio").Default("id").Get("id");
                    modalidad_rel__id = (string?)ContainerApp.db.Values("modalidad").Default("id").Get("id");
                    planificacion_rel__id = (string?)ContainerApp.db.Values("planificacion").Default("id").Get("id");
                    plan_pla__id = (string?)ContainerApp.db.Values("plan").Default("id").Get("id");
                    calendario_rel__id = (string?)ContainerApp.db.Values("calendario").Default("id").Get("id");
                    calendario_rel__anio = (short?)ContainerApp.db.Values("calendario").Default("anio").Get("anio");
                    calendario_rel__semestre = (short?)ContainerApp.db.Values("calendario").Default("semestre").Get("semestre");
                    calendario_rel__insertado = (DateTime?)ContainerApp.db.Values("calendario").Default("insertado").Get("insertado");
                break;
            }
        }

        public string? comision__Label { get; set; }

        protected string? _comision__id = null;
        public string? comision__id
        {
            get { return _comision__id; }
            set { _comision__id = value; _comision = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__turno = null;
        public string? comision__turno
        {
            get { return _comision__turno; }
            set { _comision__turno = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__division = null;
        public string? comision__division
        {
            get { return _comision__division; }
            set { _comision__division = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__comentario = null;
        public string? comision__comentario
        {
            get { return _comision__comentario; }
            set { _comision__comentario = value; NotifyPropertyChanged(); }
        }
        protected bool? _comision__autorizada = null;
        public bool? comision__autorizada
        {
            get { return _comision__autorizada; }
            set { _comision__autorizada = value; NotifyPropertyChanged(); }
        }
        protected bool? _comision__apertura = null;
        public bool? comision__apertura
        {
            get { return _comision__apertura; }
            set { _comision__apertura = value; NotifyPropertyChanged(); }
        }
        protected bool? _comision__publicada = null;
        public bool? comision__publicada
        {
            get { return _comision__publicada; }
            set { _comision__publicada = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__observaciones = null;
        public string? comision__observaciones
        {
            get { return _comision__observaciones; }
            set { _comision__observaciones = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _comision__alta = null;
        public DateTime? comision__alta
        {
            get { return _comision__alta; }
            set { _comision__alta = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__sede = null;
        public string? comision__sede
        {
            get { return _comision__sede; }
            set { _comision__sede = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__modalidad = null;
        public string? comision__modalidad
        {
            get { return _comision__modalidad; }
            set { _comision__modalidad = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__planificacion = null;
        public string? comision__planificacion
        {
            get { return _comision__planificacion; }
            set { _comision__planificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__comision_siguiente = null;
        public string? comision__comision_siguiente
        {
            get { return _comision__comision_siguiente; }
            set { _comision__comision_siguiente = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__calendario = null;
        public string? comision__calendario
        {
            get { return _comision__calendario; }
            set { _comision__calendario = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__identificacion = null;
        public string? comision__identificacion
        {
            get { return _comision__identificacion; }
            set { _comision__identificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__pfid = null;
        public string? comision__pfid
        {
            get { return _comision__pfid; }
            set { _comision__pfid = value; NotifyPropertyChanged(); }
        }

        public string? sede__Label { get; set; }

        protected string? _sede__id = null;
        public string? sede__id
        {
            get { return _sede__id; }
            set { _sede__id = value; _comision__sede = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__numero = null;
        public string? sede__numero
        {
            get { return _sede__numero; }
            set { _sede__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__nombre = null;
        public string? sede__nombre
        {
            get { return _sede__nombre; }
            set { _sede__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__observaciones = null;
        public string? sede__observaciones
        {
            get { return _sede__observaciones; }
            set { _sede__observaciones = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _sede__alta = null;
        public DateTime? sede__alta
        {
            get { return _sede__alta; }
            set { _sede__alta = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _sede__baja = null;
        public DateTime? sede__baja
        {
            get { return _sede__baja; }
            set { _sede__baja = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__domicilio = null;
        public string? sede__domicilio
        {
            get { return _sede__domicilio; }
            set { _sede__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__centro_educativo = null;
        public string? sede__centro_educativo
        {
            get { return _sede__centro_educativo; }
            set { _sede__centro_educativo = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _sede__fecha_traspaso = null;
        public DateTime? sede__fecha_traspaso
        {
            get { return _sede__fecha_traspaso; }
            set { _sede__fecha_traspaso = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__organizacion = null;
        public string? sede__organizacion
        {
            get { return _sede__organizacion; }
            set { _sede__organizacion = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__pfid = null;
        public string? sede__pfid
        {
            get { return _sede__pfid; }
            set { _sede__pfid = value; NotifyPropertyChanged(); }
        }
        protected string? _sede__pfid_organizacion = null;
        public string? sede__pfid_organizacion
        {
            get { return _sede__pfid_organizacion; }
            set { _sede__pfid_organizacion = value; NotifyPropertyChanged(); }
        }

        public string? domicilio__Label { get; set; }

        protected string? _domicilio__id = null;
        public string? domicilio__id
        {
            get { return _domicilio__id; }
            set { _domicilio__id = value; _sede__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__calle = null;
        public string? domicilio__calle
        {
            get { return _domicilio__calle; }
            set { _domicilio__calle = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__entre = null;
        public string? domicilio__entre
        {
            get { return _domicilio__entre; }
            set { _domicilio__entre = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__numero = null;
        public string? domicilio__numero
        {
            get { return _domicilio__numero; }
            set { _domicilio__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__piso = null;
        public string? domicilio__piso
        {
            get { return _domicilio__piso; }
            set { _domicilio__piso = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__departamento = null;
        public string? domicilio__departamento
        {
            get { return _domicilio__departamento; }
            set { _domicilio__departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__barrio = null;
        public string? domicilio__barrio
        {
            get { return _domicilio__barrio; }
            set { _domicilio__barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__localidad = null;
        public string? domicilio__localidad
        {
            get { return _domicilio__localidad; }
            set { _domicilio__localidad = value; NotifyPropertyChanged(); }
        }

        public string? centro_educativo__Label { get; set; }

        protected string? _centro_educativo__id = null;
        public string? centro_educativo__id
        {
            get { return _centro_educativo__id; }
            set { _centro_educativo__id = value; _sede__centro_educativo = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo__nombre = null;
        public string? centro_educativo__nombre
        {
            get { return _centro_educativo__nombre; }
            set { _centro_educativo__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo__cue = null;
        public string? centro_educativo__cue
        {
            get { return _centro_educativo__cue; }
            set { _centro_educativo__cue = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo__domicilio = null;
        public string? centro_educativo__domicilio
        {
            get { return _centro_educativo__domicilio; }
            set { _centro_educativo__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo__observaciones = null;
        public string? centro_educativo__observaciones
        {
            get { return _centro_educativo__observaciones; }
            set { _centro_educativo__observaciones = value; NotifyPropertyChanged(); }
        }

        public string? domicilio_cen__Label { get; set; }

        protected string? _domicilio_cen__id = null;
        public string? domicilio_cen__id
        {
            get { return _domicilio_cen__id; }
            set { _domicilio_cen__id = value; _centro_educativo__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen__calle = null;
        public string? domicilio_cen__calle
        {
            get { return _domicilio_cen__calle; }
            set { _domicilio_cen__calle = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen__entre = null;
        public string? domicilio_cen__entre
        {
            get { return _domicilio_cen__entre; }
            set { _domicilio_cen__entre = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen__numero = null;
        public string? domicilio_cen__numero
        {
            get { return _domicilio_cen__numero; }
            set { _domicilio_cen__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen__piso = null;
        public string? domicilio_cen__piso
        {
            get { return _domicilio_cen__piso; }
            set { _domicilio_cen__piso = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen__departamento = null;
        public string? domicilio_cen__departamento
        {
            get { return _domicilio_cen__departamento; }
            set { _domicilio_cen__departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen__barrio = null;
        public string? domicilio_cen__barrio
        {
            get { return _domicilio_cen__barrio; }
            set { _domicilio_cen__barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen__localidad = null;
        public string? domicilio_cen__localidad
        {
            get { return _domicilio_cen__localidad; }
            set { _domicilio_cen__localidad = value; NotifyPropertyChanged(); }
        }

        public string? modalidad__Label { get; set; }

        protected string? _modalidad__id = null;
        public string? modalidad__id
        {
            get { return _modalidad__id; }
            set { _modalidad__id = value; _comision__modalidad = value; NotifyPropertyChanged(); }
        }
        protected string? _modalidad__nombre = null;
        public string? modalidad__nombre
        {
            get { return _modalidad__nombre; }
            set { _modalidad__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _modalidad__pfid = null;
        public string? modalidad__pfid
        {
            get { return _modalidad__pfid; }
            set { _modalidad__pfid = value; NotifyPropertyChanged(); }
        }

        public string? planificacion__Label { get; set; }

        protected string? _planificacion__id = null;
        public string? planificacion__id
        {
            get { return _planificacion__id; }
            set { _planificacion__id = value; _comision__planificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__anio = null;
        public string? planificacion__anio
        {
            get { return _planificacion__anio; }
            set { _planificacion__anio = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__semestre = null;
        public string? planificacion__semestre
        {
            get { return _planificacion__semestre; }
            set { _planificacion__semestre = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__plan = null;
        public string? planificacion__plan
        {
            get { return _planificacion__plan; }
            set { _planificacion__plan = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__pfid = null;
        public string? planificacion__pfid
        {
            get { return _planificacion__pfid; }
            set { _planificacion__pfid = value; NotifyPropertyChanged(); }
        }

        public string? plan__Label { get; set; }

        protected string? _plan__id = null;
        public string? plan__id
        {
            get { return _plan__id; }
            set { _plan__id = value; _planificacion__plan = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__orientacion = null;
        public string? plan__orientacion
        {
            get { return _plan__orientacion; }
            set { _plan__orientacion = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__resolucion = null;
        public string? plan__resolucion
        {
            get { return _plan__resolucion; }
            set { _plan__resolucion = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__distribucion_horaria = null;
        public string? plan__distribucion_horaria
        {
            get { return _plan__distribucion_horaria; }
            set { _plan__distribucion_horaria = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__pfid = null;
        public string? plan__pfid
        {
            get { return _plan__pfid; }
            set { _plan__pfid = value; NotifyPropertyChanged(); }
        }

        public string? calendario__Label { get; set; }

        protected string? _calendario__id = null;
        public string? calendario__id
        {
            get { return _calendario__id; }
            set { _calendario__id = value; _comision__calendario = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _calendario__inicio = null;
        public DateTime? calendario__inicio
        {
            get { return _calendario__inicio; }
            set { _calendario__inicio = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _calendario__fin = null;
        public DateTime? calendario__fin
        {
            get { return _calendario__fin; }
            set { _calendario__fin = value; NotifyPropertyChanged(); }
        }
        protected short? _calendario__anio = null;
        public short? calendario__anio
        {
            get { return _calendario__anio; }
            set { _calendario__anio = value; NotifyPropertyChanged(); }
        }
        protected short? _calendario__semestre = null;
        public short? calendario__semestre
        {
            get { return _calendario__semestre; }
            set { _calendario__semestre = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _calendario__insertado = null;
        public DateTime? calendario__insertado
        {
            get { return _calendario__insertado; }
            set { _calendario__insertado = value; NotifyPropertyChanged(); }
        }
        protected string? _calendario__descripcion = null;
        public string? calendario__descripcion
        {
            get { return _calendario__descripcion; }
            set { _calendario__descripcion = value; NotifyPropertyChanged(); }
        }

        public string? relacion__Label { get; set; }

        protected string? _relacion__id = null;
        public string? relacion__id
        {
            get { return _relacion__id; }
            set { _relacion__id = value; _relacion = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__turno = null;
        public string? relacion__turno
        {
            get { return _relacion__turno; }
            set { _relacion__turno = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__division = null;
        public string? relacion__division
        {
            get { return _relacion__division; }
            set { _relacion__division = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__comentario = null;
        public string? relacion__comentario
        {
            get { return _relacion__comentario; }
            set { _relacion__comentario = value; NotifyPropertyChanged(); }
        }
        protected bool? _relacion__autorizada = null;
        public bool? relacion__autorizada
        {
            get { return _relacion__autorizada; }
            set { _relacion__autorizada = value; NotifyPropertyChanged(); }
        }
        protected bool? _relacion__apertura = null;
        public bool? relacion__apertura
        {
            get { return _relacion__apertura; }
            set { _relacion__apertura = value; NotifyPropertyChanged(); }
        }
        protected bool? _relacion__publicada = null;
        public bool? relacion__publicada
        {
            get { return _relacion__publicada; }
            set { _relacion__publicada = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__observaciones = null;
        public string? relacion__observaciones
        {
            get { return _relacion__observaciones; }
            set { _relacion__observaciones = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _relacion__alta = null;
        public DateTime? relacion__alta
        {
            get { return _relacion__alta; }
            set { _relacion__alta = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__sede = null;
        public string? relacion__sede
        {
            get { return _relacion__sede; }
            set { _relacion__sede = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__modalidad = null;
        public string? relacion__modalidad
        {
            get { return _relacion__modalidad; }
            set { _relacion__modalidad = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__planificacion = null;
        public string? relacion__planificacion
        {
            get { return _relacion__planificacion; }
            set { _relacion__planificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__comision_siguiente = null;
        public string? relacion__comision_siguiente
        {
            get { return _relacion__comision_siguiente; }
            set { _relacion__comision_siguiente = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__calendario = null;
        public string? relacion__calendario
        {
            get { return _relacion__calendario; }
            set { _relacion__calendario = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__identificacion = null;
        public string? relacion__identificacion
        {
            get { return _relacion__identificacion; }
            set { _relacion__identificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _relacion__pfid = null;
        public string? relacion__pfid
        {
            get { return _relacion__pfid; }
            set { _relacion__pfid = value; NotifyPropertyChanged(); }
        }

        public string? sede_rel__Label { get; set; }

        protected string? _sede_rel__id = null;
        public string? sede_rel__id
        {
            get { return _sede_rel__id; }
            set { _sede_rel__id = value; _relacion__sede = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__numero = null;
        public string? sede_rel__numero
        {
            get { return _sede_rel__numero; }
            set { _sede_rel__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__nombre = null;
        public string? sede_rel__nombre
        {
            get { return _sede_rel__nombre; }
            set { _sede_rel__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__observaciones = null;
        public string? sede_rel__observaciones
        {
            get { return _sede_rel__observaciones; }
            set { _sede_rel__observaciones = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _sede_rel__alta = null;
        public DateTime? sede_rel__alta
        {
            get { return _sede_rel__alta; }
            set { _sede_rel__alta = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _sede_rel__baja = null;
        public DateTime? sede_rel__baja
        {
            get { return _sede_rel__baja; }
            set { _sede_rel__baja = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__domicilio = null;
        public string? sede_rel__domicilio
        {
            get { return _sede_rel__domicilio; }
            set { _sede_rel__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__centro_educativo = null;
        public string? sede_rel__centro_educativo
        {
            get { return _sede_rel__centro_educativo; }
            set { _sede_rel__centro_educativo = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _sede_rel__fecha_traspaso = null;
        public DateTime? sede_rel__fecha_traspaso
        {
            get { return _sede_rel__fecha_traspaso; }
            set { _sede_rel__fecha_traspaso = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__organizacion = null;
        public string? sede_rel__organizacion
        {
            get { return _sede_rel__organizacion; }
            set { _sede_rel__organizacion = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__pfid = null;
        public string? sede_rel__pfid
        {
            get { return _sede_rel__pfid; }
            set { _sede_rel__pfid = value; NotifyPropertyChanged(); }
        }
        protected string? _sede_rel__pfid_organizacion = null;
        public string? sede_rel__pfid_organizacion
        {
            get { return _sede_rel__pfid_organizacion; }
            set { _sede_rel__pfid_organizacion = value; NotifyPropertyChanged(); }
        }

        public string? domicilio_sed__Label { get; set; }

        protected string? _domicilio_sed__id = null;
        public string? domicilio_sed__id
        {
            get { return _domicilio_sed__id; }
            set { _domicilio_sed__id = value; _sede_rel__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_sed__calle = null;
        public string? domicilio_sed__calle
        {
            get { return _domicilio_sed__calle; }
            set { _domicilio_sed__calle = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_sed__entre = null;
        public string? domicilio_sed__entre
        {
            get { return _domicilio_sed__entre; }
            set { _domicilio_sed__entre = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_sed__numero = null;
        public string? domicilio_sed__numero
        {
            get { return _domicilio_sed__numero; }
            set { _domicilio_sed__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_sed__piso = null;
        public string? domicilio_sed__piso
        {
            get { return _domicilio_sed__piso; }
            set { _domicilio_sed__piso = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_sed__departamento = null;
        public string? domicilio_sed__departamento
        {
            get { return _domicilio_sed__departamento; }
            set { _domicilio_sed__departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_sed__barrio = null;
        public string? domicilio_sed__barrio
        {
            get { return _domicilio_sed__barrio; }
            set { _domicilio_sed__barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_sed__localidad = null;
        public string? domicilio_sed__localidad
        {
            get { return _domicilio_sed__localidad; }
            set { _domicilio_sed__localidad = value; NotifyPropertyChanged(); }
        }

        public string? centro_educativo_sed__Label { get; set; }

        protected string? _centro_educativo_sed__id = null;
        public string? centro_educativo_sed__id
        {
            get { return _centro_educativo_sed__id; }
            set { _centro_educativo_sed__id = value; _sede_rel__centro_educativo = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo_sed__nombre = null;
        public string? centro_educativo_sed__nombre
        {
            get { return _centro_educativo_sed__nombre; }
            set { _centro_educativo_sed__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo_sed__cue = null;
        public string? centro_educativo_sed__cue
        {
            get { return _centro_educativo_sed__cue; }
            set { _centro_educativo_sed__cue = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo_sed__domicilio = null;
        public string? centro_educativo_sed__domicilio
        {
            get { return _centro_educativo_sed__domicilio; }
            set { _centro_educativo_sed__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo_sed__observaciones = null;
        public string? centro_educativo_sed__observaciones
        {
            get { return _centro_educativo_sed__observaciones; }
            set { _centro_educativo_sed__observaciones = value; NotifyPropertyChanged(); }
        }

        public string? domicilio_cen1__Label { get; set; }

        protected string? _domicilio_cen1__id = null;
        public string? domicilio_cen1__id
        {
            get { return _domicilio_cen1__id; }
            set { _domicilio_cen1__id = value; _centro_educativo_sed__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen1__calle = null;
        public string? domicilio_cen1__calle
        {
            get { return _domicilio_cen1__calle; }
            set { _domicilio_cen1__calle = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen1__entre = null;
        public string? domicilio_cen1__entre
        {
            get { return _domicilio_cen1__entre; }
            set { _domicilio_cen1__entre = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen1__numero = null;
        public string? domicilio_cen1__numero
        {
            get { return _domicilio_cen1__numero; }
            set { _domicilio_cen1__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen1__piso = null;
        public string? domicilio_cen1__piso
        {
            get { return _domicilio_cen1__piso; }
            set { _domicilio_cen1__piso = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen1__departamento = null;
        public string? domicilio_cen1__departamento
        {
            get { return _domicilio_cen1__departamento; }
            set { _domicilio_cen1__departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen1__barrio = null;
        public string? domicilio_cen1__barrio
        {
            get { return _domicilio_cen1__barrio; }
            set { _domicilio_cen1__barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_cen1__localidad = null;
        public string? domicilio_cen1__localidad
        {
            get { return _domicilio_cen1__localidad; }
            set { _domicilio_cen1__localidad = value; NotifyPropertyChanged(); }
        }

        public string? modalidad_rel__Label { get; set; }

        protected string? _modalidad_rel__id = null;
        public string? modalidad_rel__id
        {
            get { return _modalidad_rel__id; }
            set { _modalidad_rel__id = value; _relacion__modalidad = value; NotifyPropertyChanged(); }
        }
        protected string? _modalidad_rel__nombre = null;
        public string? modalidad_rel__nombre
        {
            get { return _modalidad_rel__nombre; }
            set { _modalidad_rel__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _modalidad_rel__pfid = null;
        public string? modalidad_rel__pfid
        {
            get { return _modalidad_rel__pfid; }
            set { _modalidad_rel__pfid = value; NotifyPropertyChanged(); }
        }

        public string? planificacion_rel__Label { get; set; }

        protected string? _planificacion_rel__id = null;
        public string? planificacion_rel__id
        {
            get { return _planificacion_rel__id; }
            set { _planificacion_rel__id = value; _relacion__planificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion_rel__anio = null;
        public string? planificacion_rel__anio
        {
            get { return _planificacion_rel__anio; }
            set { _planificacion_rel__anio = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion_rel__semestre = null;
        public string? planificacion_rel__semestre
        {
            get { return _planificacion_rel__semestre; }
            set { _planificacion_rel__semestre = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion_rel__plan = null;
        public string? planificacion_rel__plan
        {
            get { return _planificacion_rel__plan; }
            set { _planificacion_rel__plan = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion_rel__pfid = null;
        public string? planificacion_rel__pfid
        {
            get { return _planificacion_rel__pfid; }
            set { _planificacion_rel__pfid = value; NotifyPropertyChanged(); }
        }

        public string? plan_pla__Label { get; set; }

        protected string? _plan_pla__id = null;
        public string? plan_pla__id
        {
            get { return _plan_pla__id; }
            set { _plan_pla__id = value; _planificacion_rel__plan = value; NotifyPropertyChanged(); }
        }
        protected string? _plan_pla__orientacion = null;
        public string? plan_pla__orientacion
        {
            get { return _plan_pla__orientacion; }
            set { _plan_pla__orientacion = value; NotifyPropertyChanged(); }
        }
        protected string? _plan_pla__resolucion = null;
        public string? plan_pla__resolucion
        {
            get { return _plan_pla__resolucion; }
            set { _plan_pla__resolucion = value; NotifyPropertyChanged(); }
        }
        protected string? _plan_pla__distribucion_horaria = null;
        public string? plan_pla__distribucion_horaria
        {
            get { return _plan_pla__distribucion_horaria; }
            set { _plan_pla__distribucion_horaria = value; NotifyPropertyChanged(); }
        }
        protected string? _plan_pla__pfid = null;
        public string? plan_pla__pfid
        {
            get { return _plan_pla__pfid; }
            set { _plan_pla__pfid = value; NotifyPropertyChanged(); }
        }

        public string? calendario_rel__Label { get; set; }

        protected string? _calendario_rel__id = null;
        public string? calendario_rel__id
        {
            get { return _calendario_rel__id; }
            set { _calendario_rel__id = value; _relacion__calendario = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _calendario_rel__inicio = null;
        public DateTime? calendario_rel__inicio
        {
            get { return _calendario_rel__inicio; }
            set { _calendario_rel__inicio = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _calendario_rel__fin = null;
        public DateTime? calendario_rel__fin
        {
            get { return _calendario_rel__fin; }
            set { _calendario_rel__fin = value; NotifyPropertyChanged(); }
        }
        protected short? _calendario_rel__anio = null;
        public short? calendario_rel__anio
        {
            get { return _calendario_rel__anio; }
            set { _calendario_rel__anio = value; NotifyPropertyChanged(); }
        }
        protected short? _calendario_rel__semestre = null;
        public short? calendario_rel__semestre
        {
            get { return _calendario_rel__semestre; }
            set { _calendario_rel__semestre = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _calendario_rel__insertado = null;
        public DateTime? calendario_rel__insertado
        {
            get { return _calendario_rel__insertado; }
            set { _calendario_rel__insertado = value; NotifyPropertyChanged(); }
        }
        protected string? _calendario_rel__descripcion = null;
        public string? calendario_rel__descripcion
        {
            get { return _calendario_rel__descripcion; }
            set { _calendario_rel__descripcion = value; NotifyPropertyChanged(); }
        }
    }
}
