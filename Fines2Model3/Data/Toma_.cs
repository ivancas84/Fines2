#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Toma_ : Toma
    {

        public void DefaultRel(params string[] fieldIds)
        {
            EntityVal val;
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
                    case "disposicion":
                        val = db!.Values("disposicion");
                        disposicion__id = (string?)val.GetDefault("id");
                    break;
                    case "asignatura":
                        val = db!.Values("asignatura");
                        asignatura__id = (string?)val.GetDefault("id");
                    break;
                    case "planificacion_dis":
                        val = db!.Values("planificacion");
                        planificacion_dis__id = (string?)val.GetDefault("id");
                    break;
                    case "plan_pla":
                        val = db!.Values("plan");
                        plan_pla__id = (string?)val.GetDefault("id");
                    break;
                    case "docente":
                        val = db!.Values("persona");
                        docente__id = (string?)val.GetDefault("id");
                        docente__alta = (DateTime?)val.GetDefault("alta");
                        docente__telefono_verificado = (bool?)val.GetDefault("telefono_verificado");
                        docente__email_verificado = (bool?)val.GetDefault("email_verificado");
                        docente__info_verificada = (bool?)val.GetDefault("info_verificada");
                    break;
                    case "domicilio_doc":
                        val = db!.Values("domicilio");
                        domicilio_doc__id = (string?)val.GetDefault("id");
                    break;
                    case "reemplazo":
                        val = db!.Values("persona");
                        reemplazo__id = (string?)val.GetDefault("id");
                        reemplazo__alta = (DateTime?)val.GetDefault("alta");
                        reemplazo__telefono_verificado = (bool?)val.GetDefault("telefono_verificado");
                        reemplazo__email_verificado = (bool?)val.GetDefault("email_verificado");
                        reemplazo__info_verificada = (bool?)val.GetDefault("info_verificada");
                    break;
                    case "domicilio_ree":
                        val = db!.Values("domicilio");
                        domicilio_ree__id = (string?)val.GetDefault("id");
                    break;
                    case "planilla_docente":
                        val = db!.Values("planilla_docente");
                        planilla_docente__id = (string?)val.GetDefault("id");
                        planilla_docente__insertado = (DateTime?)val.GetDefault("insertado");
                    break;
                }
            }
        }
        protected string? _curso__Label = null;

        public string? curso__Label
        {
            get { return _curso__Label; }
            set { _curso__Label = value; NotifyPropertyChanged(nameof(curso__Label)); }
        }

        protected string? _curso__id = null;

        public string? curso__id
        {
            get { return _curso__id; }
            set { _curso__id = value; curso = value; NotifyPropertyChanged(nameof(curso__id)); }
        }
        protected int? _curso__horas_catedra = null;

        public int? curso__horas_catedra
        {
            get { return _curso__horas_catedra; }
            set { _curso__horas_catedra = value; NotifyPropertyChanged(nameof(curso__horas_catedra)); }
        }
        protected string? _curso__ige = null;

        public string? curso__ige
        {
            get { return _curso__ige; }
            set { _curso__ige = value; NotifyPropertyChanged(nameof(curso__ige)); }
        }
        protected string? _curso__comision = null;

        public string? curso__comision
        {
            get { return _curso__comision; }
            set { _curso__comision = value; NotifyPropertyChanged(nameof(curso__comision)); }
        }
        protected DateTime? _curso__alta = null;

        public DateTime? curso__alta
        {
            get { return _curso__alta; }
            set { _curso__alta = value; NotifyPropertyChanged(nameof(curso__alta)); }
        }
        protected string? _curso__descripcion_horario = null;

        public string? curso__descripcion_horario
        {
            get { return _curso__descripcion_horario; }
            set { _curso__descripcion_horario = value; NotifyPropertyChanged(nameof(curso__descripcion_horario)); }
        }
        protected string? _curso__codigo = null;

        public string? curso__codigo
        {
            get { return _curso__codigo; }
            set { _curso__codigo = value; NotifyPropertyChanged(nameof(curso__codigo)); }
        }
        protected string? _curso__disposicion = null;

        public string? curso__disposicion
        {
            get { return _curso__disposicion; }
            set { _curso__disposicion = value; NotifyPropertyChanged(nameof(curso__disposicion)); }
        }
        protected string? _curso__observaciones = null;

        public string? curso__observaciones
        {
            get { return _curso__observaciones; }
            set { _curso__observaciones = value; NotifyPropertyChanged(nameof(curso__observaciones)); }
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
            set { _comision__id = value; curso__comision = value; NotifyPropertyChanged(nameof(comision__id)); }
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
        protected string? _disposicion__Label = null;

        public string? disposicion__Label
        {
            get { return _disposicion__Label; }
            set { _disposicion__Label = value; NotifyPropertyChanged(nameof(disposicion__Label)); }
        }

        protected string? _disposicion__id = null;

        public string? disposicion__id
        {
            get { return _disposicion__id; }
            set { _disposicion__id = value; curso__disposicion = value; NotifyPropertyChanged(nameof(disposicion__id)); }
        }
        protected string? _disposicion__asignatura = null;

        public string? disposicion__asignatura
        {
            get { return _disposicion__asignatura; }
            set { _disposicion__asignatura = value; NotifyPropertyChanged(nameof(disposicion__asignatura)); }
        }
        protected string? _disposicion__planificacion = null;

        public string? disposicion__planificacion
        {
            get { return _disposicion__planificacion; }
            set { _disposicion__planificacion = value; NotifyPropertyChanged(nameof(disposicion__planificacion)); }
        }
        protected int? _disposicion__orden_informe_coordinacion_distrital = null;

        public int? disposicion__orden_informe_coordinacion_distrital
        {
            get { return _disposicion__orden_informe_coordinacion_distrital; }
            set { _disposicion__orden_informe_coordinacion_distrital = value; NotifyPropertyChanged(nameof(disposicion__orden_informe_coordinacion_distrital)); }
        }
        protected string? _asignatura__Label = null;

        public string? asignatura__Label
        {
            get { return _asignatura__Label; }
            set { _asignatura__Label = value; NotifyPropertyChanged(nameof(asignatura__Label)); }
        }

        protected string? _asignatura__id = null;

        public string? asignatura__id
        {
            get { return _asignatura__id; }
            set { _asignatura__id = value; disposicion__asignatura = value; NotifyPropertyChanged(nameof(asignatura__id)); }
        }
        protected string? _asignatura__nombre = null;

        public string? asignatura__nombre
        {
            get { return _asignatura__nombre; }
            set { _asignatura__nombre = value; NotifyPropertyChanged(nameof(asignatura__nombre)); }
        }
        protected string? _asignatura__formacion = null;

        public string? asignatura__formacion
        {
            get { return _asignatura__formacion; }
            set { _asignatura__formacion = value; NotifyPropertyChanged(nameof(asignatura__formacion)); }
        }
        protected string? _asignatura__clasificacion = null;

        public string? asignatura__clasificacion
        {
            get { return _asignatura__clasificacion; }
            set { _asignatura__clasificacion = value; NotifyPropertyChanged(nameof(asignatura__clasificacion)); }
        }
        protected string? _asignatura__codigo = null;

        public string? asignatura__codigo
        {
            get { return _asignatura__codigo; }
            set { _asignatura__codigo = value; NotifyPropertyChanged(nameof(asignatura__codigo)); }
        }
        protected string? _asignatura__perfil = null;

        public string? asignatura__perfil
        {
            get { return _asignatura__perfil; }
            set { _asignatura__perfil = value; NotifyPropertyChanged(nameof(asignatura__perfil)); }
        }
        protected string? _planificacion_dis__Label = null;

        public string? planificacion_dis__Label
        {
            get { return _planificacion_dis__Label; }
            set { _planificacion_dis__Label = value; NotifyPropertyChanged(nameof(planificacion_dis__Label)); }
        }

        protected string? _planificacion_dis__id = null;

        public string? planificacion_dis__id
        {
            get { return _planificacion_dis__id; }
            set { _planificacion_dis__id = value; disposicion__planificacion = value; NotifyPropertyChanged(nameof(planificacion_dis__id)); }
        }
        protected string? _planificacion_dis__anio = null;

        public string? planificacion_dis__anio
        {
            get { return _planificacion_dis__anio; }
            set { _planificacion_dis__anio = value; NotifyPropertyChanged(nameof(planificacion_dis__anio)); }
        }
        protected string? _planificacion_dis__semestre = null;

        public string? planificacion_dis__semestre
        {
            get { return _planificacion_dis__semestre; }
            set { _planificacion_dis__semestre = value; NotifyPropertyChanged(nameof(planificacion_dis__semestre)); }
        }
        protected string? _planificacion_dis__plan = null;

        public string? planificacion_dis__plan
        {
            get { return _planificacion_dis__plan; }
            set { _planificacion_dis__plan = value; NotifyPropertyChanged(nameof(planificacion_dis__plan)); }
        }
        protected string? _planificacion_dis__pfid = null;

        public string? planificacion_dis__pfid
        {
            get { return _planificacion_dis__pfid; }
            set { _planificacion_dis__pfid = value; NotifyPropertyChanged(nameof(planificacion_dis__pfid)); }
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
            set { _plan_pla__id = value; planificacion_dis__plan = value; NotifyPropertyChanged(nameof(plan_pla__id)); }
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
        protected string? _docente__Label = null;

        public string? docente__Label
        {
            get { return _docente__Label; }
            set { _docente__Label = value; NotifyPropertyChanged(nameof(docente__Label)); }
        }

        protected string? _docente__id = null;

        public string? docente__id
        {
            get { return _docente__id; }
            set { _docente__id = value; docente = value; NotifyPropertyChanged(nameof(docente__id)); }
        }
        protected string? _docente__nombres = null;

        public string? docente__nombres
        {
            get { return _docente__nombres; }
            set { _docente__nombres = value; NotifyPropertyChanged(nameof(docente__nombres)); }
        }
        protected string? _docente__apellidos = null;

        public string? docente__apellidos
        {
            get { return _docente__apellidos; }
            set { _docente__apellidos = value; NotifyPropertyChanged(nameof(docente__apellidos)); }
        }
        protected DateTime? _docente__fecha_nacimiento = null;

        public DateTime? docente__fecha_nacimiento
        {
            get { return _docente__fecha_nacimiento; }
            set { _docente__fecha_nacimiento = value; NotifyPropertyChanged(nameof(docente__fecha_nacimiento)); }
        }
        protected string? _docente__numero_documento = null;

        public string? docente__numero_documento
        {
            get { return _docente__numero_documento; }
            set { _docente__numero_documento = value; NotifyPropertyChanged(nameof(docente__numero_documento)); }
        }
        protected string? _docente__cuil = null;

        public string? docente__cuil
        {
            get { return _docente__cuil; }
            set { _docente__cuil = value; NotifyPropertyChanged(nameof(docente__cuil)); }
        }
        protected string? _docente__genero = null;

        public string? docente__genero
        {
            get { return _docente__genero; }
            set { _docente__genero = value; NotifyPropertyChanged(nameof(docente__genero)); }
        }
        protected string? _docente__apodo = null;

        public string? docente__apodo
        {
            get { return _docente__apodo; }
            set { _docente__apodo = value; NotifyPropertyChanged(nameof(docente__apodo)); }
        }
        protected string? _docente__telefono = null;

        public string? docente__telefono
        {
            get { return _docente__telefono; }
            set { _docente__telefono = value; NotifyPropertyChanged(nameof(docente__telefono)); }
        }
        protected string? _docente__email = null;

        public string? docente__email
        {
            get { return _docente__email; }
            set { _docente__email = value; NotifyPropertyChanged(nameof(docente__email)); }
        }
        protected string? _docente__email_abc = null;

        public string? docente__email_abc
        {
            get { return _docente__email_abc; }
            set { _docente__email_abc = value; NotifyPropertyChanged(nameof(docente__email_abc)); }
        }
        protected DateTime? _docente__alta = null;

        public DateTime? docente__alta
        {
            get { return _docente__alta; }
            set { _docente__alta = value; NotifyPropertyChanged(nameof(docente__alta)); }
        }
        protected string? _docente__domicilio = null;

        public string? docente__domicilio
        {
            get { return _docente__domicilio; }
            set { _docente__domicilio = value; NotifyPropertyChanged(nameof(docente__domicilio)); }
        }
        protected string? _docente__lugar_nacimiento = null;

        public string? docente__lugar_nacimiento
        {
            get { return _docente__lugar_nacimiento; }
            set { _docente__lugar_nacimiento = value; NotifyPropertyChanged(nameof(docente__lugar_nacimiento)); }
        }
        protected bool? _docente__telefono_verificado = null;

        public bool? docente__telefono_verificado
        {
            get { return _docente__telefono_verificado; }
            set { _docente__telefono_verificado = value; NotifyPropertyChanged(nameof(docente__telefono_verificado)); }
        }
        protected bool? _docente__email_verificado = null;

        public bool? docente__email_verificado
        {
            get { return _docente__email_verificado; }
            set { _docente__email_verificado = value; NotifyPropertyChanged(nameof(docente__email_verificado)); }
        }
        protected bool? _docente__info_verificada = null;

        public bool? docente__info_verificada
        {
            get { return _docente__info_verificada; }
            set { _docente__info_verificada = value; NotifyPropertyChanged(nameof(docente__info_verificada)); }
        }
        protected string? _docente__descripcion_domicilio = null;

        public string? docente__descripcion_domicilio
        {
            get { return _docente__descripcion_domicilio; }
            set { _docente__descripcion_domicilio = value; NotifyPropertyChanged(nameof(docente__descripcion_domicilio)); }
        }
        protected byte? _docente__cuil1 = null;

        public byte? docente__cuil1
        {
            get { return _docente__cuil1; }
            set { _docente__cuil1 = value; NotifyPropertyChanged(nameof(docente__cuil1)); }
        }
        protected byte? _docente__cuil2 = null;

        public byte? docente__cuil2
        {
            get { return _docente__cuil2; }
            set { _docente__cuil2 = value; NotifyPropertyChanged(nameof(docente__cuil2)); }
        }
        protected string? _docente__departamento = null;

        public string? docente__departamento
        {
            get { return _docente__departamento; }
            set { _docente__departamento = value; NotifyPropertyChanged(nameof(docente__departamento)); }
        }
        protected string? _docente__localidad = null;

        public string? docente__localidad
        {
            get { return _docente__localidad; }
            set { _docente__localidad = value; NotifyPropertyChanged(nameof(docente__localidad)); }
        }
        protected string? _docente__partido = null;

        public string? docente__partido
        {
            get { return _docente__partido; }
            set { _docente__partido = value; NotifyPropertyChanged(nameof(docente__partido)); }
        }
        protected string? _docente__codigo_area = null;

        public string? docente__codigo_area
        {
            get { return _docente__codigo_area; }
            set { _docente__codigo_area = value; NotifyPropertyChanged(nameof(docente__codigo_area)); }
        }
        protected string? _docente__nacionalidad = null;

        public string? docente__nacionalidad
        {
            get { return _docente__nacionalidad; }
            set { _docente__nacionalidad = value; NotifyPropertyChanged(nameof(docente__nacionalidad)); }
        }
        protected byte? _docente__sexo = null;

        public byte? docente__sexo
        {
            get { return _docente__sexo; }
            set { _docente__sexo = value; NotifyPropertyChanged(nameof(docente__sexo)); }
        }
        protected byte? _docente__dia_nacimiento = null;

        public byte? docente__dia_nacimiento
        {
            get { return _docente__dia_nacimiento; }
            set { _docente__dia_nacimiento = value; NotifyPropertyChanged(nameof(docente__dia_nacimiento)); }
        }
        protected byte? _docente__mes_nacimiento = null;

        public byte? docente__mes_nacimiento
        {
            get { return _docente__mes_nacimiento; }
            set { _docente__mes_nacimiento = value; NotifyPropertyChanged(nameof(docente__mes_nacimiento)); }
        }
        protected ushort? _docente__anio_nacimiento = null;

        public ushort? docente__anio_nacimiento
        {
            get { return _docente__anio_nacimiento; }
            set { _docente__anio_nacimiento = value; NotifyPropertyChanged(nameof(docente__anio_nacimiento)); }
        }
        protected string? _domicilio_doc__Label = null;

        public string? domicilio_doc__Label
        {
            get { return _domicilio_doc__Label; }
            set { _domicilio_doc__Label = value; NotifyPropertyChanged(nameof(domicilio_doc__Label)); }
        }

        protected string? _domicilio_doc__id = null;

        public string? domicilio_doc__id
        {
            get { return _domicilio_doc__id; }
            set { _domicilio_doc__id = value; docente__domicilio = value; NotifyPropertyChanged(nameof(domicilio_doc__id)); }
        }
        protected string? _domicilio_doc__calle = null;

        public string? domicilio_doc__calle
        {
            get { return _domicilio_doc__calle; }
            set { _domicilio_doc__calle = value; NotifyPropertyChanged(nameof(domicilio_doc__calle)); }
        }
        protected string? _domicilio_doc__entre = null;

        public string? domicilio_doc__entre
        {
            get { return _domicilio_doc__entre; }
            set { _domicilio_doc__entre = value; NotifyPropertyChanged(nameof(domicilio_doc__entre)); }
        }
        protected string? _domicilio_doc__numero = null;

        public string? domicilio_doc__numero
        {
            get { return _domicilio_doc__numero; }
            set { _domicilio_doc__numero = value; NotifyPropertyChanged(nameof(domicilio_doc__numero)); }
        }
        protected string? _domicilio_doc__piso = null;

        public string? domicilio_doc__piso
        {
            get { return _domicilio_doc__piso; }
            set { _domicilio_doc__piso = value; NotifyPropertyChanged(nameof(domicilio_doc__piso)); }
        }
        protected string? _domicilio_doc__departamento = null;

        public string? domicilio_doc__departamento
        {
            get { return _domicilio_doc__departamento; }
            set { _domicilio_doc__departamento = value; NotifyPropertyChanged(nameof(domicilio_doc__departamento)); }
        }
        protected string? _domicilio_doc__barrio = null;

        public string? domicilio_doc__barrio
        {
            get { return _domicilio_doc__barrio; }
            set { _domicilio_doc__barrio = value; NotifyPropertyChanged(nameof(domicilio_doc__barrio)); }
        }
        protected string? _domicilio_doc__localidad = null;

        public string? domicilio_doc__localidad
        {
            get { return _domicilio_doc__localidad; }
            set { _domicilio_doc__localidad = value; NotifyPropertyChanged(nameof(domicilio_doc__localidad)); }
        }
        protected string? _reemplazo__Label = null;

        public string? reemplazo__Label
        {
            get { return _reemplazo__Label; }
            set { _reemplazo__Label = value; NotifyPropertyChanged(nameof(reemplazo__Label)); }
        }

        protected string? _reemplazo__id = null;

        public string? reemplazo__id
        {
            get { return _reemplazo__id; }
            set { _reemplazo__id = value; reemplazo = value; NotifyPropertyChanged(nameof(reemplazo__id)); }
        }
        protected string? _reemplazo__nombres = null;

        public string? reemplazo__nombres
        {
            get { return _reemplazo__nombres; }
            set { _reemplazo__nombres = value; NotifyPropertyChanged(nameof(reemplazo__nombres)); }
        }
        protected string? _reemplazo__apellidos = null;

        public string? reemplazo__apellidos
        {
            get { return _reemplazo__apellidos; }
            set { _reemplazo__apellidos = value; NotifyPropertyChanged(nameof(reemplazo__apellidos)); }
        }
        protected DateTime? _reemplazo__fecha_nacimiento = null;

        public DateTime? reemplazo__fecha_nacimiento
        {
            get { return _reemplazo__fecha_nacimiento; }
            set { _reemplazo__fecha_nacimiento = value; NotifyPropertyChanged(nameof(reemplazo__fecha_nacimiento)); }
        }
        protected string? _reemplazo__numero_documento = null;

        public string? reemplazo__numero_documento
        {
            get { return _reemplazo__numero_documento; }
            set { _reemplazo__numero_documento = value; NotifyPropertyChanged(nameof(reemplazo__numero_documento)); }
        }
        protected string? _reemplazo__cuil = null;

        public string? reemplazo__cuil
        {
            get { return _reemplazo__cuil; }
            set { _reemplazo__cuil = value; NotifyPropertyChanged(nameof(reemplazo__cuil)); }
        }
        protected string? _reemplazo__genero = null;

        public string? reemplazo__genero
        {
            get { return _reemplazo__genero; }
            set { _reemplazo__genero = value; NotifyPropertyChanged(nameof(reemplazo__genero)); }
        }
        protected string? _reemplazo__apodo = null;

        public string? reemplazo__apodo
        {
            get { return _reemplazo__apodo; }
            set { _reemplazo__apodo = value; NotifyPropertyChanged(nameof(reemplazo__apodo)); }
        }
        protected string? _reemplazo__telefono = null;

        public string? reemplazo__telefono
        {
            get { return _reemplazo__telefono; }
            set { _reemplazo__telefono = value; NotifyPropertyChanged(nameof(reemplazo__telefono)); }
        }
        protected string? _reemplazo__email = null;

        public string? reemplazo__email
        {
            get { return _reemplazo__email; }
            set { _reemplazo__email = value; NotifyPropertyChanged(nameof(reemplazo__email)); }
        }
        protected string? _reemplazo__email_abc = null;

        public string? reemplazo__email_abc
        {
            get { return _reemplazo__email_abc; }
            set { _reemplazo__email_abc = value; NotifyPropertyChanged(nameof(reemplazo__email_abc)); }
        }
        protected DateTime? _reemplazo__alta = null;

        public DateTime? reemplazo__alta
        {
            get { return _reemplazo__alta; }
            set { _reemplazo__alta = value; NotifyPropertyChanged(nameof(reemplazo__alta)); }
        }
        protected string? _reemplazo__domicilio = null;

        public string? reemplazo__domicilio
        {
            get { return _reemplazo__domicilio; }
            set { _reemplazo__domicilio = value; NotifyPropertyChanged(nameof(reemplazo__domicilio)); }
        }
        protected string? _reemplazo__lugar_nacimiento = null;

        public string? reemplazo__lugar_nacimiento
        {
            get { return _reemplazo__lugar_nacimiento; }
            set { _reemplazo__lugar_nacimiento = value; NotifyPropertyChanged(nameof(reemplazo__lugar_nacimiento)); }
        }
        protected bool? _reemplazo__telefono_verificado = null;

        public bool? reemplazo__telefono_verificado
        {
            get { return _reemplazo__telefono_verificado; }
            set { _reemplazo__telefono_verificado = value; NotifyPropertyChanged(nameof(reemplazo__telefono_verificado)); }
        }
        protected bool? _reemplazo__email_verificado = null;

        public bool? reemplazo__email_verificado
        {
            get { return _reemplazo__email_verificado; }
            set { _reemplazo__email_verificado = value; NotifyPropertyChanged(nameof(reemplazo__email_verificado)); }
        }
        protected bool? _reemplazo__info_verificada = null;

        public bool? reemplazo__info_verificada
        {
            get { return _reemplazo__info_verificada; }
            set { _reemplazo__info_verificada = value; NotifyPropertyChanged(nameof(reemplazo__info_verificada)); }
        }
        protected string? _reemplazo__descripcion_domicilio = null;

        public string? reemplazo__descripcion_domicilio
        {
            get { return _reemplazo__descripcion_domicilio; }
            set { _reemplazo__descripcion_domicilio = value; NotifyPropertyChanged(nameof(reemplazo__descripcion_domicilio)); }
        }
        protected byte? _reemplazo__cuil1 = null;

        public byte? reemplazo__cuil1
        {
            get { return _reemplazo__cuil1; }
            set { _reemplazo__cuil1 = value; NotifyPropertyChanged(nameof(reemplazo__cuil1)); }
        }
        protected byte? _reemplazo__cuil2 = null;

        public byte? reemplazo__cuil2
        {
            get { return _reemplazo__cuil2; }
            set { _reemplazo__cuil2 = value; NotifyPropertyChanged(nameof(reemplazo__cuil2)); }
        }
        protected string? _reemplazo__departamento = null;

        public string? reemplazo__departamento
        {
            get { return _reemplazo__departamento; }
            set { _reemplazo__departamento = value; NotifyPropertyChanged(nameof(reemplazo__departamento)); }
        }
        protected string? _reemplazo__localidad = null;

        public string? reemplazo__localidad
        {
            get { return _reemplazo__localidad; }
            set { _reemplazo__localidad = value; NotifyPropertyChanged(nameof(reemplazo__localidad)); }
        }
        protected string? _reemplazo__partido = null;

        public string? reemplazo__partido
        {
            get { return _reemplazo__partido; }
            set { _reemplazo__partido = value; NotifyPropertyChanged(nameof(reemplazo__partido)); }
        }
        protected string? _reemplazo__codigo_area = null;

        public string? reemplazo__codigo_area
        {
            get { return _reemplazo__codigo_area; }
            set { _reemplazo__codigo_area = value; NotifyPropertyChanged(nameof(reemplazo__codigo_area)); }
        }
        protected string? _reemplazo__nacionalidad = null;

        public string? reemplazo__nacionalidad
        {
            get { return _reemplazo__nacionalidad; }
            set { _reemplazo__nacionalidad = value; NotifyPropertyChanged(nameof(reemplazo__nacionalidad)); }
        }
        protected byte? _reemplazo__sexo = null;

        public byte? reemplazo__sexo
        {
            get { return _reemplazo__sexo; }
            set { _reemplazo__sexo = value; NotifyPropertyChanged(nameof(reemplazo__sexo)); }
        }
        protected byte? _reemplazo__dia_nacimiento = null;

        public byte? reemplazo__dia_nacimiento
        {
            get { return _reemplazo__dia_nacimiento; }
            set { _reemplazo__dia_nacimiento = value; NotifyPropertyChanged(nameof(reemplazo__dia_nacimiento)); }
        }
        protected byte? _reemplazo__mes_nacimiento = null;

        public byte? reemplazo__mes_nacimiento
        {
            get { return _reemplazo__mes_nacimiento; }
            set { _reemplazo__mes_nacimiento = value; NotifyPropertyChanged(nameof(reemplazo__mes_nacimiento)); }
        }
        protected ushort? _reemplazo__anio_nacimiento = null;

        public ushort? reemplazo__anio_nacimiento
        {
            get { return _reemplazo__anio_nacimiento; }
            set { _reemplazo__anio_nacimiento = value; NotifyPropertyChanged(nameof(reemplazo__anio_nacimiento)); }
        }
        protected string? _domicilio_ree__Label = null;

        public string? domicilio_ree__Label
        {
            get { return _domicilio_ree__Label; }
            set { _domicilio_ree__Label = value; NotifyPropertyChanged(nameof(domicilio_ree__Label)); }
        }

        protected string? _domicilio_ree__id = null;

        public string? domicilio_ree__id
        {
            get { return _domicilio_ree__id; }
            set { _domicilio_ree__id = value; reemplazo__domicilio = value; NotifyPropertyChanged(nameof(domicilio_ree__id)); }
        }
        protected string? _domicilio_ree__calle = null;

        public string? domicilio_ree__calle
        {
            get { return _domicilio_ree__calle; }
            set { _domicilio_ree__calle = value; NotifyPropertyChanged(nameof(domicilio_ree__calle)); }
        }
        protected string? _domicilio_ree__entre = null;

        public string? domicilio_ree__entre
        {
            get { return _domicilio_ree__entre; }
            set { _domicilio_ree__entre = value; NotifyPropertyChanged(nameof(domicilio_ree__entre)); }
        }
        protected string? _domicilio_ree__numero = null;

        public string? domicilio_ree__numero
        {
            get { return _domicilio_ree__numero; }
            set { _domicilio_ree__numero = value; NotifyPropertyChanged(nameof(domicilio_ree__numero)); }
        }
        protected string? _domicilio_ree__piso = null;

        public string? domicilio_ree__piso
        {
            get { return _domicilio_ree__piso; }
            set { _domicilio_ree__piso = value; NotifyPropertyChanged(nameof(domicilio_ree__piso)); }
        }
        protected string? _domicilio_ree__departamento = null;

        public string? domicilio_ree__departamento
        {
            get { return _domicilio_ree__departamento; }
            set { _domicilio_ree__departamento = value; NotifyPropertyChanged(nameof(domicilio_ree__departamento)); }
        }
        protected string? _domicilio_ree__barrio = null;

        public string? domicilio_ree__barrio
        {
            get { return _domicilio_ree__barrio; }
            set { _domicilio_ree__barrio = value; NotifyPropertyChanged(nameof(domicilio_ree__barrio)); }
        }
        protected string? _domicilio_ree__localidad = null;

        public string? domicilio_ree__localidad
        {
            get { return _domicilio_ree__localidad; }
            set { _domicilio_ree__localidad = value; NotifyPropertyChanged(nameof(domicilio_ree__localidad)); }
        }
        protected string? _planilla_docente__Label = null;

        public string? planilla_docente__Label
        {
            get { return _planilla_docente__Label; }
            set { _planilla_docente__Label = value; NotifyPropertyChanged(nameof(planilla_docente__Label)); }
        }

        protected string? _planilla_docente__id = null;

        public string? planilla_docente__id
        {
            get { return _planilla_docente__id; }
            set { _planilla_docente__id = value; planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente__id)); }
        }
        protected string? _planilla_docente__numero = null;

        public string? planilla_docente__numero
        {
            get { return _planilla_docente__numero; }
            set { _planilla_docente__numero = value; NotifyPropertyChanged(nameof(planilla_docente__numero)); }
        }
        protected DateTime? _planilla_docente__insertado = null;

        public DateTime? planilla_docente__insertado
        {
            get { return _planilla_docente__insertado; }
            set { _planilla_docente__insertado = value; NotifyPropertyChanged(nameof(planilla_docente__insertado)); }
        }
        protected DateTime? _planilla_docente__fecha_contralor = null;

        public DateTime? planilla_docente__fecha_contralor
        {
            get { return _planilla_docente__fecha_contralor; }
            set { _planilla_docente__fecha_contralor = value; NotifyPropertyChanged(nameof(planilla_docente__fecha_contralor)); }
        }
        protected DateTime? _planilla_docente__fecha_consejo = null;

        public DateTime? planilla_docente__fecha_consejo
        {
            get { return _planilla_docente__fecha_consejo; }
            set { _planilla_docente__fecha_consejo = value; NotifyPropertyChanged(nameof(planilla_docente__fecha_consejo)); }
        }
        protected string? _planilla_docente__observaciones = null;

        public string? planilla_docente__observaciones
        {
            get { return _planilla_docente__observaciones; }
            set { _planilla_docente__observaciones = value; NotifyPropertyChanged(nameof(planilla_docente__observaciones)); }
        }
    }
}
