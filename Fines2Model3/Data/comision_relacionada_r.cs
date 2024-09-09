#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_comision_relacionada_r : Data_comision_relacionada
    {

        public void DefaultRel(params string[] fieldIds)
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
                    case "relacion":
                        val = db!.Values("comision");
                        relacion__id = (string?)val.GetDefault("id");
                        relacion__alta = (DateTime?)val.GetDefault("alta");
                    break;
                    case "sede_rel":
                        val = db!.Values("sede");
                        sede_rel__id = (string?)val.GetDefault("id");
                        sede_rel__alta = (DateTime?)val.GetDefault("alta");
                    break;
                    case "domicilio_sed":
                        val = db!.Values("domicilio");
                        domicilio_sed__id = (string?)val.GetDefault("id");
                    break;
                    case "centro_educativo_sed":
                        val = db!.Values("centro_educativo");
                        centro_educativo_sed__id = (string?)val.GetDefault("id");
                    break;
                    case "domicilio_cen1":
                        val = db!.Values("domicilio");
                        domicilio_cen1__id = (string?)val.GetDefault("id");
                    break;
                    case "modalidad_rel":
                        val = db!.Values("modalidad");
                        modalidad_rel__id = (string?)val.GetDefault("id");
                    break;
                    case "planificacion_rel":
                        val = db!.Values("planificacion");
                        planificacion_rel__id = (string?)val.GetDefault("id");
                    break;
                    case "plan_pla":
                        val = db!.Values("plan");
                        plan_pla__id = (string?)val.GetDefault("id");
                    break;
                    case "calendario_rel":
                        val = db!.Values("calendario");
                        calendario_rel__id = (string?)val.GetDefault("id");
                        calendario_rel__anio = (short?)val.GetDefault("anio");
                        calendario_rel__semestre = (short?)val.GetDefault("semestre");
                        calendario_rel__insertado = (DateTime?)val.GetDefault("insertado");
                    break;
                }
            }
        }
        protected string? _comision__Label = null;

        public string? comision__Label
        {
            get { return _comision__Label; }
            set { _comision__Label = value; NotifyPropertyChanged(nameof(comision__Label)); }
        }

        protected string? _comision__id = null;

        public string? comision__id
        {
            get { return _comision__id; }
            set { _comision__id = value; comision = value; NotifyPropertyChanged(nameof(comision__id)); }
        }
        protected string? _comision__turno = null;

        public string? comision__turno
        {
            get { return _comision__turno; }
            set { _comision__turno = value; NotifyPropertyChanged(nameof(comision__turno)); }
        }
        protected string? _comision__division = null;

        public string? comision__division
        {
            get { return _comision__division; }
            set { _comision__division = value; NotifyPropertyChanged(nameof(comision__division)); }
        }
        protected string? _comision__comentario = null;

        public string? comision__comentario
        {
            get { return _comision__comentario; }
            set { _comision__comentario = value; NotifyPropertyChanged(nameof(comision__comentario)); }
        }
        protected bool? _comision__autorizada = null;

        public bool? comision__autorizada
        {
            get { return _comision__autorizada; }
            set { _comision__autorizada = value; NotifyPropertyChanged(nameof(comision__autorizada)); }
        }
        protected bool? _comision__apertura = null;

        public bool? comision__apertura
        {
            get { return _comision__apertura; }
            set { _comision__apertura = value; NotifyPropertyChanged(nameof(comision__apertura)); }
        }
        protected bool? _comision__publicada = null;

        public bool? comision__publicada
        {
            get { return _comision__publicada; }
            set { _comision__publicada = value; NotifyPropertyChanged(nameof(comision__publicada)); }
        }
        protected string? _comision__observaciones = null;

        public string? comision__observaciones
        {
            get { return _comision__observaciones; }
            set { _comision__observaciones = value; NotifyPropertyChanged(nameof(comision__observaciones)); }
        }
        protected DateTime? _comision__alta = null;

        public DateTime? comision__alta
        {
            get { return _comision__alta; }
            set { _comision__alta = value; NotifyPropertyChanged(nameof(comision__alta)); }
        }
        protected string? _comision__sede = null;

        public string? comision__sede
        {
            get { return _comision__sede; }
            set { _comision__sede = value; NotifyPropertyChanged(nameof(comision__sede)); }
        }
        protected string? _comision__modalidad = null;

        public string? comision__modalidad
        {
            get { return _comision__modalidad; }
            set { _comision__modalidad = value; NotifyPropertyChanged(nameof(comision__modalidad)); }
        }
        protected string? _comision__planificacion = null;

        public string? comision__planificacion
        {
            get { return _comision__planificacion; }
            set { _comision__planificacion = value; NotifyPropertyChanged(nameof(comision__planificacion)); }
        }
        protected string? _comision__comision_siguiente = null;

        public string? comision__comision_siguiente
        {
            get { return _comision__comision_siguiente; }
            set { _comision__comision_siguiente = value; NotifyPropertyChanged(nameof(comision__comision_siguiente)); }
        }
        protected string? _comision__calendario = null;

        public string? comision__calendario
        {
            get { return _comision__calendario; }
            set { _comision__calendario = value; NotifyPropertyChanged(nameof(comision__calendario)); }
        }
        protected string? _comision__identificacion = null;

        public string? comision__identificacion
        {
            get { return _comision__identificacion; }
            set { _comision__identificacion = value; NotifyPropertyChanged(nameof(comision__identificacion)); }
        }
        protected string? _comision__pfid = null;

        public string? comision__pfid
        {
            get { return _comision__pfid; }
            set { _comision__pfid = value; NotifyPropertyChanged(nameof(comision__pfid)); }
        }
        protected string? _sede__Label = null;

        public string? sede__Label
        {
            get { return _sede__Label; }
            set { _sede__Label = value; NotifyPropertyChanged(nameof(sede__Label)); }
        }

        protected string? _sede__id = null;

        public string? sede__id
        {
            get { return _sede__id; }
            set { _sede__id = value; comision__sede = value; NotifyPropertyChanged(nameof(sede__id)); }
        }
        protected string? _sede__numero = null;

        public string? sede__numero
        {
            get { return _sede__numero; }
            set { _sede__numero = value; NotifyPropertyChanged(nameof(sede__numero)); }
        }
        protected string? _sede__nombre = null;

        public string? sede__nombre
        {
            get { return _sede__nombre; }
            set { _sede__nombre = value; NotifyPropertyChanged(nameof(sede__nombre)); }
        }
        protected string? _sede__observaciones = null;

        public string? sede__observaciones
        {
            get { return _sede__observaciones; }
            set { _sede__observaciones = value; NotifyPropertyChanged(nameof(sede__observaciones)); }
        }
        protected DateTime? _sede__alta = null;

        public DateTime? sede__alta
        {
            get { return _sede__alta; }
            set { _sede__alta = value; NotifyPropertyChanged(nameof(sede__alta)); }
        }
        protected DateTime? _sede__baja = null;

        public DateTime? sede__baja
        {
            get { return _sede__baja; }
            set { _sede__baja = value; NotifyPropertyChanged(nameof(sede__baja)); }
        }
        protected string? _sede__domicilio = null;

        public string? sede__domicilio
        {
            get { return _sede__domicilio; }
            set { _sede__domicilio = value; NotifyPropertyChanged(nameof(sede__domicilio)); }
        }
        protected string? _sede__centro_educativo = null;

        public string? sede__centro_educativo
        {
            get { return _sede__centro_educativo; }
            set { _sede__centro_educativo = value; NotifyPropertyChanged(nameof(sede__centro_educativo)); }
        }
        protected DateTime? _sede__fecha_traspaso = null;

        public DateTime? sede__fecha_traspaso
        {
            get { return _sede__fecha_traspaso; }
            set { _sede__fecha_traspaso = value; NotifyPropertyChanged(nameof(sede__fecha_traspaso)); }
        }
        protected string? _sede__organizacion = null;

        public string? sede__organizacion
        {
            get { return _sede__organizacion; }
            set { _sede__organizacion = value; NotifyPropertyChanged(nameof(sede__organizacion)); }
        }
        protected string? _sede__pfid = null;

        public string? sede__pfid
        {
            get { return _sede__pfid; }
            set { _sede__pfid = value; NotifyPropertyChanged(nameof(sede__pfid)); }
        }
        protected string? _sede__pfid_organizacion = null;

        public string? sede__pfid_organizacion
        {
            get { return _sede__pfid_organizacion; }
            set { _sede__pfid_organizacion = value; NotifyPropertyChanged(nameof(sede__pfid_organizacion)); }
        }
        protected string? _domicilio__Label = null;

        public string? domicilio__Label
        {
            get { return _domicilio__Label; }
            set { _domicilio__Label = value; NotifyPropertyChanged(nameof(domicilio__Label)); }
        }

        protected string? _domicilio__id = null;

        public string? domicilio__id
        {
            get { return _domicilio__id; }
            set { _domicilio__id = value; sede__domicilio = value; NotifyPropertyChanged(nameof(domicilio__id)); }
        }
        protected string? _domicilio__calle = null;

        public string? domicilio__calle
        {
            get { return _domicilio__calle; }
            set { _domicilio__calle = value; NotifyPropertyChanged(nameof(domicilio__calle)); }
        }
        protected string? _domicilio__entre = null;

        public string? domicilio__entre
        {
            get { return _domicilio__entre; }
            set { _domicilio__entre = value; NotifyPropertyChanged(nameof(domicilio__entre)); }
        }
        protected string? _domicilio__numero = null;

        public string? domicilio__numero
        {
            get { return _domicilio__numero; }
            set { _domicilio__numero = value; NotifyPropertyChanged(nameof(domicilio__numero)); }
        }
        protected string? _domicilio__piso = null;

        public string? domicilio__piso
        {
            get { return _domicilio__piso; }
            set { _domicilio__piso = value; NotifyPropertyChanged(nameof(domicilio__piso)); }
        }
        protected string? _domicilio__departamento = null;

        public string? domicilio__departamento
        {
            get { return _domicilio__departamento; }
            set { _domicilio__departamento = value; NotifyPropertyChanged(nameof(domicilio__departamento)); }
        }
        protected string? _domicilio__barrio = null;

        public string? domicilio__barrio
        {
            get { return _domicilio__barrio; }
            set { _domicilio__barrio = value; NotifyPropertyChanged(nameof(domicilio__barrio)); }
        }
        protected string? _domicilio__localidad = null;

        public string? domicilio__localidad
        {
            get { return _domicilio__localidad; }
            set { _domicilio__localidad = value; NotifyPropertyChanged(nameof(domicilio__localidad)); }
        }
        protected string? _centro_educativo__Label = null;

        public string? centro_educativo__Label
        {
            get { return _centro_educativo__Label; }
            set { _centro_educativo__Label = value; NotifyPropertyChanged(nameof(centro_educativo__Label)); }
        }

        protected string? _centro_educativo__id = null;

        public string? centro_educativo__id
        {
            get { return _centro_educativo__id; }
            set { _centro_educativo__id = value; sede__centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo__id)); }
        }
        protected string? _centro_educativo__nombre = null;

        public string? centro_educativo__nombre
        {
            get { return _centro_educativo__nombre; }
            set { _centro_educativo__nombre = value; NotifyPropertyChanged(nameof(centro_educativo__nombre)); }
        }
        protected string? _centro_educativo__cue = null;

        public string? centro_educativo__cue
        {
            get { return _centro_educativo__cue; }
            set { _centro_educativo__cue = value; NotifyPropertyChanged(nameof(centro_educativo__cue)); }
        }
        protected string? _centro_educativo__domicilio = null;

        public string? centro_educativo__domicilio
        {
            get { return _centro_educativo__domicilio; }
            set { _centro_educativo__domicilio = value; NotifyPropertyChanged(nameof(centro_educativo__domicilio)); }
        }
        protected string? _centro_educativo__observaciones = null;

        public string? centro_educativo__observaciones
        {
            get { return _centro_educativo__observaciones; }
            set { _centro_educativo__observaciones = value; NotifyPropertyChanged(nameof(centro_educativo__observaciones)); }
        }
        protected string? _domicilio_cen__Label = null;

        public string? domicilio_cen__Label
        {
            get { return _domicilio_cen__Label; }
            set { _domicilio_cen__Label = value; NotifyPropertyChanged(nameof(domicilio_cen__Label)); }
        }

        protected string? _domicilio_cen__id = null;

        public string? domicilio_cen__id
        {
            get { return _domicilio_cen__id; }
            set { _domicilio_cen__id = value; centro_educativo__domicilio = value; NotifyPropertyChanged(nameof(domicilio_cen__id)); }
        }
        protected string? _domicilio_cen__calle = null;

        public string? domicilio_cen__calle
        {
            get { return _domicilio_cen__calle; }
            set { _domicilio_cen__calle = value; NotifyPropertyChanged(nameof(domicilio_cen__calle)); }
        }
        protected string? _domicilio_cen__entre = null;

        public string? domicilio_cen__entre
        {
            get { return _domicilio_cen__entre; }
            set { _domicilio_cen__entre = value; NotifyPropertyChanged(nameof(domicilio_cen__entre)); }
        }
        protected string? _domicilio_cen__numero = null;

        public string? domicilio_cen__numero
        {
            get { return _domicilio_cen__numero; }
            set { _domicilio_cen__numero = value; NotifyPropertyChanged(nameof(domicilio_cen__numero)); }
        }
        protected string? _domicilio_cen__piso = null;

        public string? domicilio_cen__piso
        {
            get { return _domicilio_cen__piso; }
            set { _domicilio_cen__piso = value; NotifyPropertyChanged(nameof(domicilio_cen__piso)); }
        }
        protected string? _domicilio_cen__departamento = null;

        public string? domicilio_cen__departamento
        {
            get { return _domicilio_cen__departamento; }
            set { _domicilio_cen__departamento = value; NotifyPropertyChanged(nameof(domicilio_cen__departamento)); }
        }
        protected string? _domicilio_cen__barrio = null;

        public string? domicilio_cen__barrio
        {
            get { return _domicilio_cen__barrio; }
            set { _domicilio_cen__barrio = value; NotifyPropertyChanged(nameof(domicilio_cen__barrio)); }
        }
        protected string? _domicilio_cen__localidad = null;

        public string? domicilio_cen__localidad
        {
            get { return _domicilio_cen__localidad; }
            set { _domicilio_cen__localidad = value; NotifyPropertyChanged(nameof(domicilio_cen__localidad)); }
        }
        protected string? _modalidad__Label = null;

        public string? modalidad__Label
        {
            get { return _modalidad__Label; }
            set { _modalidad__Label = value; NotifyPropertyChanged(nameof(modalidad__Label)); }
        }

        protected string? _modalidad__id = null;

        public string? modalidad__id
        {
            get { return _modalidad__id; }
            set { _modalidad__id = value; comision__modalidad = value; NotifyPropertyChanged(nameof(modalidad__id)); }
        }
        protected string? _modalidad__nombre = null;

        public string? modalidad__nombre
        {
            get { return _modalidad__nombre; }
            set { _modalidad__nombre = value; NotifyPropertyChanged(nameof(modalidad__nombre)); }
        }
        protected string? _modalidad__pfid = null;

        public string? modalidad__pfid
        {
            get { return _modalidad__pfid; }
            set { _modalidad__pfid = value; NotifyPropertyChanged(nameof(modalidad__pfid)); }
        }
        protected string? _planificacion__Label = null;

        public string? planificacion__Label
        {
            get { return _planificacion__Label; }
            set { _planificacion__Label = value; NotifyPropertyChanged(nameof(planificacion__Label)); }
        }

        protected string? _planificacion__id = null;

        public string? planificacion__id
        {
            get { return _planificacion__id; }
            set { _planificacion__id = value; comision__planificacion = value; NotifyPropertyChanged(nameof(planificacion__id)); }
        }
        protected string? _planificacion__anio = null;

        public string? planificacion__anio
        {
            get { return _planificacion__anio; }
            set { _planificacion__anio = value; NotifyPropertyChanged(nameof(planificacion__anio)); }
        }
        protected string? _planificacion__semestre = null;

        public string? planificacion__semestre
        {
            get { return _planificacion__semestre; }
            set { _planificacion__semestre = value; NotifyPropertyChanged(nameof(planificacion__semestre)); }
        }
        protected string? _planificacion__plan = null;

        public string? planificacion__plan
        {
            get { return _planificacion__plan; }
            set { _planificacion__plan = value; NotifyPropertyChanged(nameof(planificacion__plan)); }
        }
        protected string? _planificacion__pfid = null;

        public string? planificacion__pfid
        {
            get { return _planificacion__pfid; }
            set { _planificacion__pfid = value; NotifyPropertyChanged(nameof(planificacion__pfid)); }
        }
        protected string? _plan__Label = null;

        public string? plan__Label
        {
            get { return _plan__Label; }
            set { _plan__Label = value; NotifyPropertyChanged(nameof(plan__Label)); }
        }

        protected string? _plan__id = null;

        public string? plan__id
        {
            get { return _plan__id; }
            set { _plan__id = value; planificacion__plan = value; NotifyPropertyChanged(nameof(plan__id)); }
        }
        protected string? _plan__orientacion = null;

        public string? plan__orientacion
        {
            get { return _plan__orientacion; }
            set { _plan__orientacion = value; NotifyPropertyChanged(nameof(plan__orientacion)); }
        }
        protected string? _plan__resolucion = null;

        public string? plan__resolucion
        {
            get { return _plan__resolucion; }
            set { _plan__resolucion = value; NotifyPropertyChanged(nameof(plan__resolucion)); }
        }
        protected string? _plan__distribucion_horaria = null;

        public string? plan__distribucion_horaria
        {
            get { return _plan__distribucion_horaria; }
            set { _plan__distribucion_horaria = value; NotifyPropertyChanged(nameof(plan__distribucion_horaria)); }
        }
        protected string? _plan__pfid = null;

        public string? plan__pfid
        {
            get { return _plan__pfid; }
            set { _plan__pfid = value; NotifyPropertyChanged(nameof(plan__pfid)); }
        }
        protected string? _calendario__Label = null;

        public string? calendario__Label
        {
            get { return _calendario__Label; }
            set { _calendario__Label = value; NotifyPropertyChanged(nameof(calendario__Label)); }
        }

        protected string? _calendario__id = null;

        public string? calendario__id
        {
            get { return _calendario__id; }
            set { _calendario__id = value; comision__calendario = value; NotifyPropertyChanged(nameof(calendario__id)); }
        }
        protected DateTime? _calendario__inicio = null;

        public DateTime? calendario__inicio
        {
            get { return _calendario__inicio; }
            set { _calendario__inicio = value; NotifyPropertyChanged(nameof(calendario__inicio)); }
        }
        protected DateTime? _calendario__fin = null;

        public DateTime? calendario__fin
        {
            get { return _calendario__fin; }
            set { _calendario__fin = value; NotifyPropertyChanged(nameof(calendario__fin)); }
        }
        protected short? _calendario__anio = null;

        public short? calendario__anio
        {
            get { return _calendario__anio; }
            set { _calendario__anio = value; NotifyPropertyChanged(nameof(calendario__anio)); }
        }
        protected short? _calendario__semestre = null;

        public short? calendario__semestre
        {
            get { return _calendario__semestre; }
            set { _calendario__semestre = value; NotifyPropertyChanged(nameof(calendario__semestre)); }
        }
        protected DateTime? _calendario__insertado = null;

        public DateTime? calendario__insertado
        {
            get { return _calendario__insertado; }
            set { _calendario__insertado = value; NotifyPropertyChanged(nameof(calendario__insertado)); }
        }
        protected string? _calendario__descripcion = null;

        public string? calendario__descripcion
        {
            get { return _calendario__descripcion; }
            set { _calendario__descripcion = value; NotifyPropertyChanged(nameof(calendario__descripcion)); }
        }
        protected string? _relacion__Label = null;

        public string? relacion__Label
        {
            get { return _relacion__Label; }
            set { _relacion__Label = value; NotifyPropertyChanged(nameof(relacion__Label)); }
        }

        protected string? _relacion__id = null;

        public string? relacion__id
        {
            get { return _relacion__id; }
            set { _relacion__id = value; relacion = value; NotifyPropertyChanged(nameof(relacion__id)); }
        }
        protected string? _relacion__turno = null;

        public string? relacion__turno
        {
            get { return _relacion__turno; }
            set { _relacion__turno = value; NotifyPropertyChanged(nameof(relacion__turno)); }
        }
        protected string? _relacion__division = null;

        public string? relacion__division
        {
            get { return _relacion__division; }
            set { _relacion__division = value; NotifyPropertyChanged(nameof(relacion__division)); }
        }
        protected string? _relacion__comentario = null;

        public string? relacion__comentario
        {
            get { return _relacion__comentario; }
            set { _relacion__comentario = value; NotifyPropertyChanged(nameof(relacion__comentario)); }
        }
        protected bool? _relacion__autorizada = null;

        public bool? relacion__autorizada
        {
            get { return _relacion__autorizada; }
            set { _relacion__autorizada = value; NotifyPropertyChanged(nameof(relacion__autorizada)); }
        }
        protected bool? _relacion__apertura = null;

        public bool? relacion__apertura
        {
            get { return _relacion__apertura; }
            set { _relacion__apertura = value; NotifyPropertyChanged(nameof(relacion__apertura)); }
        }
        protected bool? _relacion__publicada = null;

        public bool? relacion__publicada
        {
            get { return _relacion__publicada; }
            set { _relacion__publicada = value; NotifyPropertyChanged(nameof(relacion__publicada)); }
        }
        protected string? _relacion__observaciones = null;

        public string? relacion__observaciones
        {
            get { return _relacion__observaciones; }
            set { _relacion__observaciones = value; NotifyPropertyChanged(nameof(relacion__observaciones)); }
        }
        protected DateTime? _relacion__alta = null;

        public DateTime? relacion__alta
        {
            get { return _relacion__alta; }
            set { _relacion__alta = value; NotifyPropertyChanged(nameof(relacion__alta)); }
        }
        protected string? _relacion__sede = null;

        public string? relacion__sede
        {
            get { return _relacion__sede; }
            set { _relacion__sede = value; NotifyPropertyChanged(nameof(relacion__sede)); }
        }
        protected string? _relacion__modalidad = null;

        public string? relacion__modalidad
        {
            get { return _relacion__modalidad; }
            set { _relacion__modalidad = value; NotifyPropertyChanged(nameof(relacion__modalidad)); }
        }
        protected string? _relacion__planificacion = null;

        public string? relacion__planificacion
        {
            get { return _relacion__planificacion; }
            set { _relacion__planificacion = value; NotifyPropertyChanged(nameof(relacion__planificacion)); }
        }
        protected string? _relacion__comision_siguiente = null;

        public string? relacion__comision_siguiente
        {
            get { return _relacion__comision_siguiente; }
            set { _relacion__comision_siguiente = value; NotifyPropertyChanged(nameof(relacion__comision_siguiente)); }
        }
        protected string? _relacion__calendario = null;

        public string? relacion__calendario
        {
            get { return _relacion__calendario; }
            set { _relacion__calendario = value; NotifyPropertyChanged(nameof(relacion__calendario)); }
        }
        protected string? _relacion__identificacion = null;

        public string? relacion__identificacion
        {
            get { return _relacion__identificacion; }
            set { _relacion__identificacion = value; NotifyPropertyChanged(nameof(relacion__identificacion)); }
        }
        protected string? _relacion__pfid = null;

        public string? relacion__pfid
        {
            get { return _relacion__pfid; }
            set { _relacion__pfid = value; NotifyPropertyChanged(nameof(relacion__pfid)); }
        }
        protected string? _sede_rel__Label = null;

        public string? sede_rel__Label
        {
            get { return _sede_rel__Label; }
            set { _sede_rel__Label = value; NotifyPropertyChanged(nameof(sede_rel__Label)); }
        }

        protected string? _sede_rel__id = null;

        public string? sede_rel__id
        {
            get { return _sede_rel__id; }
            set { _sede_rel__id = value; relacion__sede = value; NotifyPropertyChanged(nameof(sede_rel__id)); }
        }
        protected string? _sede_rel__numero = null;

        public string? sede_rel__numero
        {
            get { return _sede_rel__numero; }
            set { _sede_rel__numero = value; NotifyPropertyChanged(nameof(sede_rel__numero)); }
        }
        protected string? _sede_rel__nombre = null;

        public string? sede_rel__nombre
        {
            get { return _sede_rel__nombre; }
            set { _sede_rel__nombre = value; NotifyPropertyChanged(nameof(sede_rel__nombre)); }
        }
        protected string? _sede_rel__observaciones = null;

        public string? sede_rel__observaciones
        {
            get { return _sede_rel__observaciones; }
            set { _sede_rel__observaciones = value; NotifyPropertyChanged(nameof(sede_rel__observaciones)); }
        }
        protected DateTime? _sede_rel__alta = null;

        public DateTime? sede_rel__alta
        {
            get { return _sede_rel__alta; }
            set { _sede_rel__alta = value; NotifyPropertyChanged(nameof(sede_rel__alta)); }
        }
        protected DateTime? _sede_rel__baja = null;

        public DateTime? sede_rel__baja
        {
            get { return _sede_rel__baja; }
            set { _sede_rel__baja = value; NotifyPropertyChanged(nameof(sede_rel__baja)); }
        }
        protected string? _sede_rel__domicilio = null;

        public string? sede_rel__domicilio
        {
            get { return _sede_rel__domicilio; }
            set { _sede_rel__domicilio = value; NotifyPropertyChanged(nameof(sede_rel__domicilio)); }
        }
        protected string? _sede_rel__centro_educativo = null;

        public string? sede_rel__centro_educativo
        {
            get { return _sede_rel__centro_educativo; }
            set { _sede_rel__centro_educativo = value; NotifyPropertyChanged(nameof(sede_rel__centro_educativo)); }
        }
        protected DateTime? _sede_rel__fecha_traspaso = null;

        public DateTime? sede_rel__fecha_traspaso
        {
            get { return _sede_rel__fecha_traspaso; }
            set { _sede_rel__fecha_traspaso = value; NotifyPropertyChanged(nameof(sede_rel__fecha_traspaso)); }
        }
        protected string? _sede_rel__organizacion = null;

        public string? sede_rel__organizacion
        {
            get { return _sede_rel__organizacion; }
            set { _sede_rel__organizacion = value; NotifyPropertyChanged(nameof(sede_rel__organizacion)); }
        }
        protected string? _sede_rel__pfid = null;

        public string? sede_rel__pfid
        {
            get { return _sede_rel__pfid; }
            set { _sede_rel__pfid = value; NotifyPropertyChanged(nameof(sede_rel__pfid)); }
        }
        protected string? _sede_rel__pfid_organizacion = null;

        public string? sede_rel__pfid_organizacion
        {
            get { return _sede_rel__pfid_organizacion; }
            set { _sede_rel__pfid_organizacion = value; NotifyPropertyChanged(nameof(sede_rel__pfid_organizacion)); }
        }
        protected string? _domicilio_sed__Label = null;

        public string? domicilio_sed__Label
        {
            get { return _domicilio_sed__Label; }
            set { _domicilio_sed__Label = value; NotifyPropertyChanged(nameof(domicilio_sed__Label)); }
        }

        protected string? _domicilio_sed__id = null;

        public string? domicilio_sed__id
        {
            get { return _domicilio_sed__id; }
            set { _domicilio_sed__id = value; sede_rel__domicilio = value; NotifyPropertyChanged(nameof(domicilio_sed__id)); }
        }
        protected string? _domicilio_sed__calle = null;

        public string? domicilio_sed__calle
        {
            get { return _domicilio_sed__calle; }
            set { _domicilio_sed__calle = value; NotifyPropertyChanged(nameof(domicilio_sed__calle)); }
        }
        protected string? _domicilio_sed__entre = null;

        public string? domicilio_sed__entre
        {
            get { return _domicilio_sed__entre; }
            set { _domicilio_sed__entre = value; NotifyPropertyChanged(nameof(domicilio_sed__entre)); }
        }
        protected string? _domicilio_sed__numero = null;

        public string? domicilio_sed__numero
        {
            get { return _domicilio_sed__numero; }
            set { _domicilio_sed__numero = value; NotifyPropertyChanged(nameof(domicilio_sed__numero)); }
        }
        protected string? _domicilio_sed__piso = null;

        public string? domicilio_sed__piso
        {
            get { return _domicilio_sed__piso; }
            set { _domicilio_sed__piso = value; NotifyPropertyChanged(nameof(domicilio_sed__piso)); }
        }
        protected string? _domicilio_sed__departamento = null;

        public string? domicilio_sed__departamento
        {
            get { return _domicilio_sed__departamento; }
            set { _domicilio_sed__departamento = value; NotifyPropertyChanged(nameof(domicilio_sed__departamento)); }
        }
        protected string? _domicilio_sed__barrio = null;

        public string? domicilio_sed__barrio
        {
            get { return _domicilio_sed__barrio; }
            set { _domicilio_sed__barrio = value; NotifyPropertyChanged(nameof(domicilio_sed__barrio)); }
        }
        protected string? _domicilio_sed__localidad = null;

        public string? domicilio_sed__localidad
        {
            get { return _domicilio_sed__localidad; }
            set { _domicilio_sed__localidad = value; NotifyPropertyChanged(nameof(domicilio_sed__localidad)); }
        }
        protected string? _centro_educativo_sed__Label = null;

        public string? centro_educativo_sed__Label
        {
            get { return _centro_educativo_sed__Label; }
            set { _centro_educativo_sed__Label = value; NotifyPropertyChanged(nameof(centro_educativo_sed__Label)); }
        }

        protected string? _centro_educativo_sed__id = null;

        public string? centro_educativo_sed__id
        {
            get { return _centro_educativo_sed__id; }
            set { _centro_educativo_sed__id = value; sede_rel__centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo_sed__id)); }
        }
        protected string? _centro_educativo_sed__nombre = null;

        public string? centro_educativo_sed__nombre
        {
            get { return _centro_educativo_sed__nombre; }
            set { _centro_educativo_sed__nombre = value; NotifyPropertyChanged(nameof(centro_educativo_sed__nombre)); }
        }
        protected string? _centro_educativo_sed__cue = null;

        public string? centro_educativo_sed__cue
        {
            get { return _centro_educativo_sed__cue; }
            set { _centro_educativo_sed__cue = value; NotifyPropertyChanged(nameof(centro_educativo_sed__cue)); }
        }
        protected string? _centro_educativo_sed__domicilio = null;

        public string? centro_educativo_sed__domicilio
        {
            get { return _centro_educativo_sed__domicilio; }
            set { _centro_educativo_sed__domicilio = value; NotifyPropertyChanged(nameof(centro_educativo_sed__domicilio)); }
        }
        protected string? _centro_educativo_sed__observaciones = null;

        public string? centro_educativo_sed__observaciones
        {
            get { return _centro_educativo_sed__observaciones; }
            set { _centro_educativo_sed__observaciones = value; NotifyPropertyChanged(nameof(centro_educativo_sed__observaciones)); }
        }
        protected string? _domicilio_cen1__Label = null;

        public string? domicilio_cen1__Label
        {
            get { return _domicilio_cen1__Label; }
            set { _domicilio_cen1__Label = value; NotifyPropertyChanged(nameof(domicilio_cen1__Label)); }
        }

        protected string? _domicilio_cen1__id = null;

        public string? domicilio_cen1__id
        {
            get { return _domicilio_cen1__id; }
            set { _domicilio_cen1__id = value; centro_educativo_sed__domicilio = value; NotifyPropertyChanged(nameof(domicilio_cen1__id)); }
        }
        protected string? _domicilio_cen1__calle = null;

        public string? domicilio_cen1__calle
        {
            get { return _domicilio_cen1__calle; }
            set { _domicilio_cen1__calle = value; NotifyPropertyChanged(nameof(domicilio_cen1__calle)); }
        }
        protected string? _domicilio_cen1__entre = null;

        public string? domicilio_cen1__entre
        {
            get { return _domicilio_cen1__entre; }
            set { _domicilio_cen1__entre = value; NotifyPropertyChanged(nameof(domicilio_cen1__entre)); }
        }
        protected string? _domicilio_cen1__numero = null;

        public string? domicilio_cen1__numero
        {
            get { return _domicilio_cen1__numero; }
            set { _domicilio_cen1__numero = value; NotifyPropertyChanged(nameof(domicilio_cen1__numero)); }
        }
        protected string? _domicilio_cen1__piso = null;

        public string? domicilio_cen1__piso
        {
            get { return _domicilio_cen1__piso; }
            set { _domicilio_cen1__piso = value; NotifyPropertyChanged(nameof(domicilio_cen1__piso)); }
        }
        protected string? _domicilio_cen1__departamento = null;

        public string? domicilio_cen1__departamento
        {
            get { return _domicilio_cen1__departamento; }
            set { _domicilio_cen1__departamento = value; NotifyPropertyChanged(nameof(domicilio_cen1__departamento)); }
        }
        protected string? _domicilio_cen1__barrio = null;

        public string? domicilio_cen1__barrio
        {
            get { return _domicilio_cen1__barrio; }
            set { _domicilio_cen1__barrio = value; NotifyPropertyChanged(nameof(domicilio_cen1__barrio)); }
        }
        protected string? _domicilio_cen1__localidad = null;

        public string? domicilio_cen1__localidad
        {
            get { return _domicilio_cen1__localidad; }
            set { _domicilio_cen1__localidad = value; NotifyPropertyChanged(nameof(domicilio_cen1__localidad)); }
        }
        protected string? _modalidad_rel__Label = null;

        public string? modalidad_rel__Label
        {
            get { return _modalidad_rel__Label; }
            set { _modalidad_rel__Label = value; NotifyPropertyChanged(nameof(modalidad_rel__Label)); }
        }

        protected string? _modalidad_rel__id = null;

        public string? modalidad_rel__id
        {
            get { return _modalidad_rel__id; }
            set { _modalidad_rel__id = value; relacion__modalidad = value; NotifyPropertyChanged(nameof(modalidad_rel__id)); }
        }
        protected string? _modalidad_rel__nombre = null;

        public string? modalidad_rel__nombre
        {
            get { return _modalidad_rel__nombre; }
            set { _modalidad_rel__nombre = value; NotifyPropertyChanged(nameof(modalidad_rel__nombre)); }
        }
        protected string? _modalidad_rel__pfid = null;

        public string? modalidad_rel__pfid
        {
            get { return _modalidad_rel__pfid; }
            set { _modalidad_rel__pfid = value; NotifyPropertyChanged(nameof(modalidad_rel__pfid)); }
        }
        protected string? _planificacion_rel__Label = null;

        public string? planificacion_rel__Label
        {
            get { return _planificacion_rel__Label; }
            set { _planificacion_rel__Label = value; NotifyPropertyChanged(nameof(planificacion_rel__Label)); }
        }

        protected string? _planificacion_rel__id = null;

        public string? planificacion_rel__id
        {
            get { return _planificacion_rel__id; }
            set { _planificacion_rel__id = value; relacion__planificacion = value; NotifyPropertyChanged(nameof(planificacion_rel__id)); }
        }
        protected string? _planificacion_rel__anio = null;

        public string? planificacion_rel__anio
        {
            get { return _planificacion_rel__anio; }
            set { _planificacion_rel__anio = value; NotifyPropertyChanged(nameof(planificacion_rel__anio)); }
        }
        protected string? _planificacion_rel__semestre = null;

        public string? planificacion_rel__semestre
        {
            get { return _planificacion_rel__semestre; }
            set { _planificacion_rel__semestre = value; NotifyPropertyChanged(nameof(planificacion_rel__semestre)); }
        }
        protected string? _planificacion_rel__plan = null;

        public string? planificacion_rel__plan
        {
            get { return _planificacion_rel__plan; }
            set { _planificacion_rel__plan = value; NotifyPropertyChanged(nameof(planificacion_rel__plan)); }
        }
        protected string? _planificacion_rel__pfid = null;

        public string? planificacion_rel__pfid
        {
            get { return _planificacion_rel__pfid; }
            set { _planificacion_rel__pfid = value; NotifyPropertyChanged(nameof(planificacion_rel__pfid)); }
        }
        protected string? _plan_pla__Label = null;

        public string? plan_pla__Label
        {
            get { return _plan_pla__Label; }
            set { _plan_pla__Label = value; NotifyPropertyChanged(nameof(plan_pla__Label)); }
        }

        protected string? _plan_pla__id = null;

        public string? plan_pla__id
        {
            get { return _plan_pla__id; }
            set { _plan_pla__id = value; planificacion_rel__plan = value; NotifyPropertyChanged(nameof(plan_pla__id)); }
        }
        protected string? _plan_pla__orientacion = null;

        public string? plan_pla__orientacion
        {
            get { return _plan_pla__orientacion; }
            set { _plan_pla__orientacion = value; NotifyPropertyChanged(nameof(plan_pla__orientacion)); }
        }
        protected string? _plan_pla__resolucion = null;

        public string? plan_pla__resolucion
        {
            get { return _plan_pla__resolucion; }
            set { _plan_pla__resolucion = value; NotifyPropertyChanged(nameof(plan_pla__resolucion)); }
        }
        protected string? _plan_pla__distribucion_horaria = null;

        public string? plan_pla__distribucion_horaria
        {
            get { return _plan_pla__distribucion_horaria; }
            set { _plan_pla__distribucion_horaria = value; NotifyPropertyChanged(nameof(plan_pla__distribucion_horaria)); }
        }
        protected string? _plan_pla__pfid = null;

        public string? plan_pla__pfid
        {
            get { return _plan_pla__pfid; }
            set { _plan_pla__pfid = value; NotifyPropertyChanged(nameof(plan_pla__pfid)); }
        }
        protected string? _calendario_rel__Label = null;

        public string? calendario_rel__Label
        {
            get { return _calendario_rel__Label; }
            set { _calendario_rel__Label = value; NotifyPropertyChanged(nameof(calendario_rel__Label)); }
        }

        protected string? _calendario_rel__id = null;

        public string? calendario_rel__id
        {
            get { return _calendario_rel__id; }
            set { _calendario_rel__id = value; relacion__calendario = value; NotifyPropertyChanged(nameof(calendario_rel__id)); }
        }
        protected DateTime? _calendario_rel__inicio = null;

        public DateTime? calendario_rel__inicio
        {
            get { return _calendario_rel__inicio; }
            set { _calendario_rel__inicio = value; NotifyPropertyChanged(nameof(calendario_rel__inicio)); }
        }
        protected DateTime? _calendario_rel__fin = null;

        public DateTime? calendario_rel__fin
        {
            get { return _calendario_rel__fin; }
            set { _calendario_rel__fin = value; NotifyPropertyChanged(nameof(calendario_rel__fin)); }
        }
        protected short? _calendario_rel__anio = null;

        public short? calendario_rel__anio
        {
            get { return _calendario_rel__anio; }
            set { _calendario_rel__anio = value; NotifyPropertyChanged(nameof(calendario_rel__anio)); }
        }
        protected short? _calendario_rel__semestre = null;

        public short? calendario_rel__semestre
        {
            get { return _calendario_rel__semestre; }
            set { _calendario_rel__semestre = value; NotifyPropertyChanged(nameof(calendario_rel__semestre)); }
        }
        protected DateTime? _calendario_rel__insertado = null;

        public DateTime? calendario_rel__insertado
        {
            get { return _calendario_rel__insertado; }
            set { _calendario_rel__insertado = value; NotifyPropertyChanged(nameof(calendario_rel__insertado)); }
        }
        protected string? _calendario_rel__descripcion = null;

        public string? calendario_rel__descripcion
        {
            get { return _calendario_rel__descripcion; }
            set { _calendario_rel__descripcion = value; NotifyPropertyChanged(nameof(calendario_rel__descripcion)); }
        }
    }
}
