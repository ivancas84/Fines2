using System;

namespace Fines2Wpf.Data
{
    public class Data_asignacion_planilla_docente_r : Data_asignacion_planilla_docente
    {
        protected string? _planilla_docente__id = (string?)ContainerApp.db.DefaultValue("planilla_docente", "id");
        public string? planilla_docente__id
        {
            get { return _planilla_docente__id; }
            set { _planilla_docente__id = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente__numero = null;
        public string? planilla_docente__numero
        {
            get { return _planilla_docente__numero; }
            set { _planilla_docente__numero = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente__insertado = (DateTime?)ContainerApp.db.DefaultValue("planilla_docente", "insertado");
        public DateTime? planilla_docente__insertado
        {
            get { return _planilla_docente__insertado; }
            set { _planilla_docente__insertado = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente__fecha_contralor = null;
        public DateTime? planilla_docente__fecha_contralor
        {
            get { return _planilla_docente__fecha_contralor; }
            set { _planilla_docente__fecha_contralor = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente__fecha_consejo = null;
        public DateTime? planilla_docente__fecha_consejo
        {
            get { return _planilla_docente__fecha_consejo; }
            set { _planilla_docente__fecha_consejo = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente__observaciones = null;
        public string? planilla_docente__observaciones
        {
            get { return _planilla_docente__observaciones; }
            set { _planilla_docente__observaciones = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__id = (string?)ContainerApp.db.DefaultValue("toma", "id");
        public string? toma__id
        {
            get { return _toma__id; }
            set { _toma__id = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _toma__fecha_toma = null;
        public DateTime? toma__fecha_toma
        {
            get { return _toma__fecha_toma; }
            set { _toma__fecha_toma = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__estado = null;
        public string? toma__estado
        {
            get { return _toma__estado; }
            set { _toma__estado = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__observaciones = null;
        public string? toma__observaciones
        {
            get { return _toma__observaciones; }
            set { _toma__observaciones = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__comentario = null;
        public string? toma__comentario
        {
            get { return _toma__comentario; }
            set { _toma__comentario = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__tipo_movimiento = null;
        public string? toma__tipo_movimiento
        {
            get { return _toma__tipo_movimiento; }
            set { _toma__tipo_movimiento = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__estado_contralor = null;
        public string? toma__estado_contralor
        {
            get { return _toma__estado_contralor; }
            set { _toma__estado_contralor = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _toma__alta = (DateTime?)ContainerApp.db.DefaultValue("toma", "alta");
        public DateTime? toma__alta
        {
            get { return _toma__alta; }
            set { _toma__alta = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__curso = null;
        public string? toma__curso
        {
            get { return _toma__curso; }
            set { _toma__curso = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__docente = null;
        public string? toma__docente
        {
            get { return _toma__docente; }
            set { _toma__docente = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__reemplazo = null;
        public string? toma__reemplazo
        {
            get { return _toma__reemplazo; }
            set { _toma__reemplazo = value; NotifyPropertyChanged(); }
        }
        protected string? _toma__planilla_docente = null;
        public string? toma__planilla_docente
        {
            get { return _toma__planilla_docente; }
            set { _toma__planilla_docente = value; NotifyPropertyChanged(); }
        }
        protected bool? _toma__calificacion = (bool?)ContainerApp.db.DefaultValue("toma", "calificacion");
        public bool? toma__calificacion
        {
            get { return _toma__calificacion; }
            set { _toma__calificacion = value; NotifyPropertyChanged(); }
        }
        protected bool? _toma__temas_tratados = (bool?)ContainerApp.db.DefaultValue("toma", "temas_tratados");
        public bool? toma__temas_tratados
        {
            get { return _toma__temas_tratados; }
            set { _toma__temas_tratados = value; NotifyPropertyChanged(); }
        }
        protected bool? _toma__asistencia = (bool?)ContainerApp.db.DefaultValue("toma", "asistencia");
        public bool? toma__asistencia
        {
            get { return _toma__asistencia; }
            set { _toma__asistencia = value; NotifyPropertyChanged(); }
        }
        protected bool? _toma__sin_planillas = (bool?)ContainerApp.db.DefaultValue("toma", "sin_planillas");
        public bool? toma__sin_planillas
        {
            get { return _toma__sin_planillas; }
            set { _toma__sin_planillas = value; NotifyPropertyChanged(); }
        }
        protected bool? _toma__confirmada = (bool?)ContainerApp.db.DefaultValue("toma", "confirmada");
        public bool? toma__confirmada
        {
            get { return _toma__confirmada; }
            set { _toma__confirmada = value; NotifyPropertyChanged(); }
        }
        protected string? _curso__id = (string?)ContainerApp.db.DefaultValue("curso", "id");
        public string? curso__id
        {
            get { return _curso__id; }
            set { _curso__id = value; NotifyPropertyChanged(); }
        }
        protected int? _curso__horas_catedra = null;
        public int? curso__horas_catedra
        {
            get { return _curso__horas_catedra; }
            set { _curso__horas_catedra = value; NotifyPropertyChanged(); }
        }
        protected string? _curso__ige = null;
        public string? curso__ige
        {
            get { return _curso__ige; }
            set { _curso__ige = value; NotifyPropertyChanged(); }
        }
        protected string? _curso__comision = null;
        public string? curso__comision
        {
            get { return _curso__comision; }
            set { _curso__comision = value; NotifyPropertyChanged(); }
        }
        protected string? _curso__asignatura = null;
        public string? curso__asignatura
        {
            get { return _curso__asignatura; }
            set { _curso__asignatura = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _curso__alta = (DateTime?)ContainerApp.db.DefaultValue("curso", "alta");
        public DateTime? curso__alta
        {
            get { return _curso__alta; }
            set { _curso__alta = value; NotifyPropertyChanged(); }
        }
        protected string? _curso__descripcion_horario = null;
        public string? curso__descripcion_horario
        {
            get { return _curso__descripcion_horario; }
            set { _curso__descripcion_horario = value; NotifyPropertyChanged(); }
        }
        protected string? _comision__id = (string?)ContainerApp.db.DefaultValue("comision", "id");
        public string? comision__id
        {
            get { return _comision__id; }
            set { _comision__id = value; NotifyPropertyChanged(); }
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
        protected DateTime? _comision__alta = (DateTime?)ContainerApp.db.DefaultValue("comision", "alta");
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
        protected string? _sede__id = (string?)ContainerApp.db.DefaultValue("sede", "id");
        public string? sede__id
        {
            get { return _sede__id; }
            set { _sede__id = value; NotifyPropertyChanged(); }
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
        protected DateTime? _sede__alta = (DateTime?)ContainerApp.db.DefaultValue("sede", "alta");
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
        protected string? _domicilio__id = (string?)ContainerApp.db.DefaultValue("domicilio", "id");
        public string? domicilio__id
        {
            get { return _domicilio__id; }
            set { _domicilio__id = value; NotifyPropertyChanged(); }
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
        protected string? _centro_educativo__id = (string?)ContainerApp.db.DefaultValue("centro_educativo", "id");
        public string? centro_educativo__id
        {
            get { return _centro_educativo__id; }
            set { _centro_educativo__id = value; NotifyPropertyChanged(); }
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
        protected string? _domicilio_cen__id = (string?)ContainerApp.db.DefaultValue("domicilio", "id");
        public string? domicilio_cen__id
        {
            get { return _domicilio_cen__id; }
            set { _domicilio_cen__id = value; NotifyPropertyChanged(); }
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
        protected string? _modalidad__id = (string?)ContainerApp.db.DefaultValue("modalidad", "id");
        public string? modalidad__id
        {
            get { return _modalidad__id; }
            set { _modalidad__id = value; NotifyPropertyChanged(); }
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
        protected string? _planificacion__id = (string?)ContainerApp.db.DefaultValue("planificacion", "id");
        public string? planificacion__id
        {
            get { return _planificacion__id; }
            set { _planificacion__id = value; NotifyPropertyChanged(); }
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
        protected string? _plan__id = (string?)ContainerApp.db.DefaultValue("plan", "id");
        public string? plan__id
        {
            get { return _plan__id; }
            set { _plan__id = value; NotifyPropertyChanged(); }
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
        protected string? _calendario__id = (string?)ContainerApp.db.DefaultValue("calendario", "id");
        public string? calendario__id
        {
            get { return _calendario__id; }
            set { _calendario__id = value; NotifyPropertyChanged(); }
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
        protected DateTime? _calendario__insertado = (DateTime?)ContainerApp.db.DefaultValue("calendario", "insertado");
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
        protected string? _asignatura__id = (string?)ContainerApp.db.DefaultValue("asignatura", "id");
        public string? asignatura__id
        {
            get { return _asignatura__id; }
            set { _asignatura__id = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__nombre = null;
        public string? asignatura__nombre
        {
            get { return _asignatura__nombre; }
            set { _asignatura__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__formacion = null;
        public string? asignatura__formacion
        {
            get { return _asignatura__formacion; }
            set { _asignatura__formacion = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__clasificacion = null;
        public string? asignatura__clasificacion
        {
            get { return _asignatura__clasificacion; }
            set { _asignatura__clasificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__codigo = null;
        public string? asignatura__codigo
        {
            get { return _asignatura__codigo; }
            set { _asignatura__codigo = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__perfil = null;
        public string? asignatura__perfil
        {
            get { return _asignatura__perfil; }
            set { _asignatura__perfil = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__id = (string?)ContainerApp.db.DefaultValue("persona", "id");
        public string? docente__id
        {
            get { return _docente__id; }
            set { _docente__id = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__nombres = null;
        public string? docente__nombres
        {
            get { return _docente__nombres; }
            set { _docente__nombres = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__apellidos = null;
        public string? docente__apellidos
        {
            get { return _docente__apellidos; }
            set { _docente__apellidos = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _docente__fecha_nacimiento = null;
        public DateTime? docente__fecha_nacimiento
        {
            get { return _docente__fecha_nacimiento; }
            set { _docente__fecha_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__numero_documento = null;
        public string? docente__numero_documento
        {
            get { return _docente__numero_documento; }
            set { _docente__numero_documento = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__cuil = null;
        public string? docente__cuil
        {
            get { return _docente__cuil; }
            set { _docente__cuil = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__genero = null;
        public string? docente__genero
        {
            get { return _docente__genero; }
            set { _docente__genero = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__apodo = null;
        public string? docente__apodo
        {
            get { return _docente__apodo; }
            set { _docente__apodo = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__telefono = null;
        public string? docente__telefono
        {
            get { return _docente__telefono; }
            set { _docente__telefono = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__email = null;
        public string? docente__email
        {
            get { return _docente__email; }
            set { _docente__email = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__email_abc = null;
        public string? docente__email_abc
        {
            get { return _docente__email_abc; }
            set { _docente__email_abc = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _docente__alta = (DateTime?)ContainerApp.db.DefaultValue("persona", "alta");
        public DateTime? docente__alta
        {
            get { return _docente__alta; }
            set { _docente__alta = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__domicilio = null;
        public string? docente__domicilio
        {
            get { return _docente__domicilio; }
            set { _docente__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__lugar_nacimiento = null;
        public string? docente__lugar_nacimiento
        {
            get { return _docente__lugar_nacimiento; }
            set { _docente__lugar_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected bool? _docente__telefono_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "telefono_verificado");
        public bool? docente__telefono_verificado
        {
            get { return _docente__telefono_verificado; }
            set { _docente__telefono_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _docente__email_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "email_verificado");
        public bool? docente__email_verificado
        {
            get { return _docente__email_verificado; }
            set { _docente__email_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _docente__info_verificada = (bool?)ContainerApp.db.DefaultValue("persona", "info_verificada");
        public bool? docente__info_verificada
        {
            get { return _docente__info_verificada; }
            set { _docente__info_verificada = value; NotifyPropertyChanged(); }
        }
        protected string? _docente__descripcion_domicilio = null;
        public string? docente__descripcion_domicilio
        {
            get { return _docente__descripcion_domicilio; }
            set { _docente__descripcion_domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__id = (string?)ContainerApp.db.DefaultValue("domicilio", "id");
        public string? domicilio_doc__id
        {
            get { return _domicilio_doc__id; }
            set { _domicilio_doc__id = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__calle = null;
        public string? domicilio_doc__calle
        {
            get { return _domicilio_doc__calle; }
            set { _domicilio_doc__calle = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__entre = null;
        public string? domicilio_doc__entre
        {
            get { return _domicilio_doc__entre; }
            set { _domicilio_doc__entre = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__numero = null;
        public string? domicilio_doc__numero
        {
            get { return _domicilio_doc__numero; }
            set { _domicilio_doc__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__piso = null;
        public string? domicilio_doc__piso
        {
            get { return _domicilio_doc__piso; }
            set { _domicilio_doc__piso = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__departamento = null;
        public string? domicilio_doc__departamento
        {
            get { return _domicilio_doc__departamento; }
            set { _domicilio_doc__departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__barrio = null;
        public string? domicilio_doc__barrio
        {
            get { return _domicilio_doc__barrio; }
            set { _domicilio_doc__barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_doc__localidad = null;
        public string? domicilio_doc__localidad
        {
            get { return _domicilio_doc__localidad; }
            set { _domicilio_doc__localidad = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__id = (string?)ContainerApp.db.DefaultValue("persona", "id");
        public string? reemplazo__id
        {
            get { return _reemplazo__id; }
            set { _reemplazo__id = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__nombres = null;
        public string? reemplazo__nombres
        {
            get { return _reemplazo__nombres; }
            set { _reemplazo__nombres = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__apellidos = null;
        public string? reemplazo__apellidos
        {
            get { return _reemplazo__apellidos; }
            set { _reemplazo__apellidos = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _reemplazo__fecha_nacimiento = null;
        public DateTime? reemplazo__fecha_nacimiento
        {
            get { return _reemplazo__fecha_nacimiento; }
            set { _reemplazo__fecha_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__numero_documento = null;
        public string? reemplazo__numero_documento
        {
            get { return _reemplazo__numero_documento; }
            set { _reemplazo__numero_documento = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__cuil = null;
        public string? reemplazo__cuil
        {
            get { return _reemplazo__cuil; }
            set { _reemplazo__cuil = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__genero = null;
        public string? reemplazo__genero
        {
            get { return _reemplazo__genero; }
            set { _reemplazo__genero = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__apodo = null;
        public string? reemplazo__apodo
        {
            get { return _reemplazo__apodo; }
            set { _reemplazo__apodo = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__telefono = null;
        public string? reemplazo__telefono
        {
            get { return _reemplazo__telefono; }
            set { _reemplazo__telefono = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__email = null;
        public string? reemplazo__email
        {
            get { return _reemplazo__email; }
            set { _reemplazo__email = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__email_abc = null;
        public string? reemplazo__email_abc
        {
            get { return _reemplazo__email_abc; }
            set { _reemplazo__email_abc = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _reemplazo__alta = (DateTime?)ContainerApp.db.DefaultValue("persona", "alta");
        public DateTime? reemplazo__alta
        {
            get { return _reemplazo__alta; }
            set { _reemplazo__alta = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__domicilio = null;
        public string? reemplazo__domicilio
        {
            get { return _reemplazo__domicilio; }
            set { _reemplazo__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__lugar_nacimiento = null;
        public string? reemplazo__lugar_nacimiento
        {
            get { return _reemplazo__lugar_nacimiento; }
            set { _reemplazo__lugar_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected bool? _reemplazo__telefono_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "telefono_verificado");
        public bool? reemplazo__telefono_verificado
        {
            get { return _reemplazo__telefono_verificado; }
            set { _reemplazo__telefono_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _reemplazo__email_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "email_verificado");
        public bool? reemplazo__email_verificado
        {
            get { return _reemplazo__email_verificado; }
            set { _reemplazo__email_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _reemplazo__info_verificada = (bool?)ContainerApp.db.DefaultValue("persona", "info_verificada");
        public bool? reemplazo__info_verificada
        {
            get { return _reemplazo__info_verificada; }
            set { _reemplazo__info_verificada = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo__descripcion_domicilio = null;
        public string? reemplazo__descripcion_domicilio
        {
            get { return _reemplazo__descripcion_domicilio; }
            set { _reemplazo__descripcion_domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__id = (string?)ContainerApp.db.DefaultValue("domicilio", "id");
        public string? domicilio_ree__id
        {
            get { return _domicilio_ree__id; }
            set { _domicilio_ree__id = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__calle = null;
        public string? domicilio_ree__calle
        {
            get { return _domicilio_ree__calle; }
            set { _domicilio_ree__calle = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__entre = null;
        public string? domicilio_ree__entre
        {
            get { return _domicilio_ree__entre; }
            set { _domicilio_ree__entre = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__numero = null;
        public string? domicilio_ree__numero
        {
            get { return _domicilio_ree__numero; }
            set { _domicilio_ree__numero = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__piso = null;
        public string? domicilio_ree__piso
        {
            get { return _domicilio_ree__piso; }
            set { _domicilio_ree__piso = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__departamento = null;
        public string? domicilio_ree__departamento
        {
            get { return _domicilio_ree__departamento; }
            set { _domicilio_ree__departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__barrio = null;
        public string? domicilio_ree__barrio
        {
            get { return _domicilio_ree__barrio; }
            set { _domicilio_ree__barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio_ree__localidad = null;
        public string? domicilio_ree__localidad
        {
            get { return _domicilio_ree__localidad; }
            set { _domicilio_ree__localidad = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente_tom__id = (string?)ContainerApp.db.DefaultValue("planilla_docente", "id");
        public string? planilla_docente_tom__id
        {
            get { return _planilla_docente_tom__id; }
            set { _planilla_docente_tom__id = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente_tom__numero = null;
        public string? planilla_docente_tom__numero
        {
            get { return _planilla_docente_tom__numero; }
            set { _planilla_docente_tom__numero = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente_tom__insertado = (DateTime?)ContainerApp.db.DefaultValue("planilla_docente", "insertado");
        public DateTime? planilla_docente_tom__insertado
        {
            get { return _planilla_docente_tom__insertado; }
            set { _planilla_docente_tom__insertado = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente_tom__fecha_contralor = null;
        public DateTime? planilla_docente_tom__fecha_contralor
        {
            get { return _planilla_docente_tom__fecha_contralor; }
            set { _planilla_docente_tom__fecha_contralor = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente_tom__fecha_consejo = null;
        public DateTime? planilla_docente_tom__fecha_consejo
        {
            get { return _planilla_docente_tom__fecha_consejo; }
            set { _planilla_docente_tom__fecha_consejo = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente_tom__observaciones = null;
        public string? planilla_docente_tom__observaciones
        {
            get { return _planilla_docente_tom__observaciones; }
            set { _planilla_docente_tom__observaciones = value; NotifyPropertyChanged(); }
        }
    }
}
