#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class DisposicionPendiente_ : DisposicionPendiente
    {

        public void DefaultRel(params string[] fieldIds)
        {
            EntityVal val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "disposicion":
                        val = db!.Values("disposicion");
                        disposicion__id = (string?)val.GetDefault("id");
                    break;
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
                    case "domicilio":
                        val = db!.Values("domicilio");
                        domicilio__id = (string?)val.GetDefault("id");
                    break;
                    case "plan_alu":
                        val = db!.Values("plan");
                        plan_alu__id = (string?)val.GetDefault("id");
                    break;
                    case "resolucion_inscripcion":
                        val = db!.Values("resolucion");
                        resolucion_inscripcion__id = (string?)val.GetDefault("id");
                    break;
                }
            }
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
            set { _disposicion__id = value; disposicion = value; NotifyPropertyChanged(nameof(disposicion__id)); }
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
            set { _planificacion__id = value; disposicion__planificacion = value; NotifyPropertyChanged(nameof(planificacion__id)); }
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
        protected string? _alumno__Label = null;

        public string? alumno__Label
        {
            get { return _alumno__Label; }
            set { _alumno__Label = value; NotifyPropertyChanged(nameof(alumno__Label)); }
        }

        protected string? _alumno__id = null;

        public string? alumno__id
        {
            get { return _alumno__id; }
            set { _alumno__id = value; alumno = value; NotifyPropertyChanged(nameof(alumno__id)); }
        }
        protected string? _alumno__anio_ingreso = null;

        public string? alumno__anio_ingreso
        {
            get { return _alumno__anio_ingreso; }
            set { _alumno__anio_ingreso = value; NotifyPropertyChanged(nameof(alumno__anio_ingreso)); }
        }
        protected string? _alumno__observaciones = null;

        public string? alumno__observaciones
        {
            get { return _alumno__observaciones; }
            set { _alumno__observaciones = value; NotifyPropertyChanged(nameof(alumno__observaciones)); }
        }
        protected string? _alumno__persona = null;

        public string? alumno__persona
        {
            get { return _alumno__persona; }
            set { _alumno__persona = value; NotifyPropertyChanged(nameof(alumno__persona)); }
        }
        protected string? _alumno__estado_inscripcion = null;

        public string? alumno__estado_inscripcion
        {
            get { return _alumno__estado_inscripcion; }
            set { _alumno__estado_inscripcion = value; NotifyPropertyChanged(nameof(alumno__estado_inscripcion)); }
        }
        protected DateTime? _alumno__fecha_titulacion = null;

        public DateTime? alumno__fecha_titulacion
        {
            get { return _alumno__fecha_titulacion; }
            set { _alumno__fecha_titulacion = value; NotifyPropertyChanged(nameof(alumno__fecha_titulacion)); }
        }
        protected string? _alumno__plan = null;

        public string? alumno__plan
        {
            get { return _alumno__plan; }
            set { _alumno__plan = value; NotifyPropertyChanged(nameof(alumno__plan)); }
        }
        protected string? _alumno__resolucion_inscripcion = null;

        public string? alumno__resolucion_inscripcion
        {
            get { return _alumno__resolucion_inscripcion; }
            set { _alumno__resolucion_inscripcion = value; NotifyPropertyChanged(nameof(alumno__resolucion_inscripcion)); }
        }
        protected short? _alumno__anio_inscripcion = null;

        public short? alumno__anio_inscripcion
        {
            get { return _alumno__anio_inscripcion; }
            set { _alumno__anio_inscripcion = value; NotifyPropertyChanged(nameof(alumno__anio_inscripcion)); }
        }
        protected short? _alumno__semestre_inscripcion = null;

        public short? alumno__semestre_inscripcion
        {
            get { return _alumno__semestre_inscripcion; }
            set { _alumno__semestre_inscripcion = value; NotifyPropertyChanged(nameof(alumno__semestre_inscripcion)); }
        }
        protected short? _alumno__semestre_ingreso = null;

        public short? alumno__semestre_ingreso
        {
            get { return _alumno__semestre_ingreso; }
            set { _alumno__semestre_ingreso = value; NotifyPropertyChanged(nameof(alumno__semestre_ingreso)); }
        }
        protected string? _alumno__adeuda_legajo = null;

        public string? alumno__adeuda_legajo
        {
            get { return _alumno__adeuda_legajo; }
            set { _alumno__adeuda_legajo = value; NotifyPropertyChanged(nameof(alumno__adeuda_legajo)); }
        }
        protected string? _alumno__adeuda_deudores = null;

        public string? alumno__adeuda_deudores
        {
            get { return _alumno__adeuda_deudores; }
            set { _alumno__adeuda_deudores = value; NotifyPropertyChanged(nameof(alumno__adeuda_deudores)); }
        }
        protected string? _alumno__documentacion_inscripcion = null;

        public string? alumno__documentacion_inscripcion
        {
            get { return _alumno__documentacion_inscripcion; }
            set { _alumno__documentacion_inscripcion = value; NotifyPropertyChanged(nameof(alumno__documentacion_inscripcion)); }
        }
        protected bool? _alumno__anio_inscripcion_completo = null;

        public bool? alumno__anio_inscripcion_completo
        {
            get { return _alumno__anio_inscripcion_completo; }
            set { _alumno__anio_inscripcion_completo = value; NotifyPropertyChanged(nameof(alumno__anio_inscripcion_completo)); }
        }
        protected string? _alumno__establecimiento_inscripcion = null;

        public string? alumno__establecimiento_inscripcion
        {
            get { return _alumno__establecimiento_inscripcion; }
            set { _alumno__establecimiento_inscripcion = value; NotifyPropertyChanged(nameof(alumno__establecimiento_inscripcion)); }
        }
        protected string? _alumno__libro_folio = null;

        public string? alumno__libro_folio
        {
            get { return _alumno__libro_folio; }
            set { _alumno__libro_folio = value; NotifyPropertyChanged(nameof(alumno__libro_folio)); }
        }
        protected string? _alumno__libro = null;

        public string? alumno__libro
        {
            get { return _alumno__libro; }
            set { _alumno__libro = value; NotifyPropertyChanged(nameof(alumno__libro)); }
        }
        protected string? _alumno__folio = null;

        public string? alumno__folio
        {
            get { return _alumno__folio; }
            set { _alumno__folio = value; NotifyPropertyChanged(nameof(alumno__folio)); }
        }
        protected string? _alumno__comentarios = null;

        public string? alumno__comentarios
        {
            get { return _alumno__comentarios; }
            set { _alumno__comentarios = value; NotifyPropertyChanged(nameof(alumno__comentarios)); }
        }
        protected bool? _alumno__tiene_dni = null;

        public bool? alumno__tiene_dni
        {
            get { return _alumno__tiene_dni; }
            set { _alumno__tiene_dni = value; NotifyPropertyChanged(nameof(alumno__tiene_dni)); }
        }
        protected bool? _alumno__tiene_constancia = null;

        public bool? alumno__tiene_constancia
        {
            get { return _alumno__tiene_constancia; }
            set { _alumno__tiene_constancia = value; NotifyPropertyChanged(nameof(alumno__tiene_constancia)); }
        }
        protected bool? _alumno__tiene_certificado = null;

        public bool? alumno__tiene_certificado
        {
            get { return _alumno__tiene_certificado; }
            set { _alumno__tiene_certificado = value; NotifyPropertyChanged(nameof(alumno__tiene_certificado)); }
        }
        protected bool? _alumno__previas_completas = null;

        public bool? alumno__previas_completas
        {
            get { return _alumno__previas_completas; }
            set { _alumno__previas_completas = value; NotifyPropertyChanged(nameof(alumno__previas_completas)); }
        }
        protected bool? _alumno__tiene_partida = null;

        public bool? alumno__tiene_partida
        {
            get { return _alumno__tiene_partida; }
            set { _alumno__tiene_partida = value; NotifyPropertyChanged(nameof(alumno__tiene_partida)); }
        }
        protected DateTime? _alumno__creado = null;

        public DateTime? alumno__creado
        {
            get { return _alumno__creado; }
            set { _alumno__creado = value; NotifyPropertyChanged(nameof(alumno__creado)); }
        }
        protected bool? _alumno__confirmado_direccion = null;

        public bool? alumno__confirmado_direccion
        {
            get { return _alumno__confirmado_direccion; }
            set { _alumno__confirmado_direccion = value; NotifyPropertyChanged(nameof(alumno__confirmado_direccion)); }
        }
        protected string? _persona__Label = null;

        public string? persona__Label
        {
            get { return _persona__Label; }
            set { _persona__Label = value; NotifyPropertyChanged(nameof(persona__Label)); }
        }

        protected string? _persona__id = null;

        public string? persona__id
        {
            get { return _persona__id; }
            set { _persona__id = value; alumno__persona = value; NotifyPropertyChanged(nameof(persona__id)); }
        }
        protected string? _persona__nombres = null;

        public string? persona__nombres
        {
            get { return _persona__nombres; }
            set { _persona__nombres = value; NotifyPropertyChanged(nameof(persona__nombres)); }
        }
        protected string? _persona__apellidos = null;

        public string? persona__apellidos
        {
            get { return _persona__apellidos; }
            set { _persona__apellidos = value; NotifyPropertyChanged(nameof(persona__apellidos)); }
        }
        protected DateTime? _persona__fecha_nacimiento = null;

        public DateTime? persona__fecha_nacimiento
        {
            get { return _persona__fecha_nacimiento; }
            set { _persona__fecha_nacimiento = value; NotifyPropertyChanged(nameof(persona__fecha_nacimiento)); }
        }
        protected string? _persona__numero_documento = null;

        public string? persona__numero_documento
        {
            get { return _persona__numero_documento; }
            set { _persona__numero_documento = value; NotifyPropertyChanged(nameof(persona__numero_documento)); }
        }
        protected string? _persona__cuil = null;

        public string? persona__cuil
        {
            get { return _persona__cuil; }
            set { _persona__cuil = value; NotifyPropertyChanged(nameof(persona__cuil)); }
        }
        protected string? _persona__genero = null;

        public string? persona__genero
        {
            get { return _persona__genero; }
            set { _persona__genero = value; NotifyPropertyChanged(nameof(persona__genero)); }
        }
        protected string? _persona__apodo = null;

        public string? persona__apodo
        {
            get { return _persona__apodo; }
            set { _persona__apodo = value; NotifyPropertyChanged(nameof(persona__apodo)); }
        }
        protected string? _persona__telefono = null;

        public string? persona__telefono
        {
            get { return _persona__telefono; }
            set { _persona__telefono = value; NotifyPropertyChanged(nameof(persona__telefono)); }
        }
        protected string? _persona__email = null;

        public string? persona__email
        {
            get { return _persona__email; }
            set { _persona__email = value; NotifyPropertyChanged(nameof(persona__email)); }
        }
        protected string? _persona__email_abc = null;

        public string? persona__email_abc
        {
            get { return _persona__email_abc; }
            set { _persona__email_abc = value; NotifyPropertyChanged(nameof(persona__email_abc)); }
        }
        protected DateTime? _persona__alta = null;

        public DateTime? persona__alta
        {
            get { return _persona__alta; }
            set { _persona__alta = value; NotifyPropertyChanged(nameof(persona__alta)); }
        }
        protected string? _persona__domicilio = null;

        public string? persona__domicilio
        {
            get { return _persona__domicilio; }
            set { _persona__domicilio = value; NotifyPropertyChanged(nameof(persona__domicilio)); }
        }
        protected string? _persona__lugar_nacimiento = null;

        public string? persona__lugar_nacimiento
        {
            get { return _persona__lugar_nacimiento; }
            set { _persona__lugar_nacimiento = value; NotifyPropertyChanged(nameof(persona__lugar_nacimiento)); }
        }
        protected bool? _persona__telefono_verificado = null;

        public bool? persona__telefono_verificado
        {
            get { return _persona__telefono_verificado; }
            set { _persona__telefono_verificado = value; NotifyPropertyChanged(nameof(persona__telefono_verificado)); }
        }
        protected bool? _persona__email_verificado = null;

        public bool? persona__email_verificado
        {
            get { return _persona__email_verificado; }
            set { _persona__email_verificado = value; NotifyPropertyChanged(nameof(persona__email_verificado)); }
        }
        protected bool? _persona__info_verificada = null;

        public bool? persona__info_verificada
        {
            get { return _persona__info_verificada; }
            set { _persona__info_verificada = value; NotifyPropertyChanged(nameof(persona__info_verificada)); }
        }
        protected string? _persona__descripcion_domicilio = null;

        public string? persona__descripcion_domicilio
        {
            get { return _persona__descripcion_domicilio; }
            set { _persona__descripcion_domicilio = value; NotifyPropertyChanged(nameof(persona__descripcion_domicilio)); }
        }
        protected byte? _persona__cuil1 = null;

        public byte? persona__cuil1
        {
            get { return _persona__cuil1; }
            set { _persona__cuil1 = value; NotifyPropertyChanged(nameof(persona__cuil1)); }
        }
        protected byte? _persona__cuil2 = null;

        public byte? persona__cuil2
        {
            get { return _persona__cuil2; }
            set { _persona__cuil2 = value; NotifyPropertyChanged(nameof(persona__cuil2)); }
        }
        protected string? _persona__departamento = null;

        public string? persona__departamento
        {
            get { return _persona__departamento; }
            set { _persona__departamento = value; NotifyPropertyChanged(nameof(persona__departamento)); }
        }
        protected string? _persona__localidad = null;

        public string? persona__localidad
        {
            get { return _persona__localidad; }
            set { _persona__localidad = value; NotifyPropertyChanged(nameof(persona__localidad)); }
        }
        protected string? _persona__partido = null;

        public string? persona__partido
        {
            get { return _persona__partido; }
            set { _persona__partido = value; NotifyPropertyChanged(nameof(persona__partido)); }
        }
        protected string? _persona__codigo_area = null;

        public string? persona__codigo_area
        {
            get { return _persona__codigo_area; }
            set { _persona__codigo_area = value; NotifyPropertyChanged(nameof(persona__codigo_area)); }
        }
        protected string? _persona__nacionalidad = null;

        public string? persona__nacionalidad
        {
            get { return _persona__nacionalidad; }
            set { _persona__nacionalidad = value; NotifyPropertyChanged(nameof(persona__nacionalidad)); }
        }
        protected byte? _persona__sexo = null;

        public byte? persona__sexo
        {
            get { return _persona__sexo; }
            set { _persona__sexo = value; NotifyPropertyChanged(nameof(persona__sexo)); }
        }
        protected byte? _persona__dia_nacimiento = null;

        public byte? persona__dia_nacimiento
        {
            get { return _persona__dia_nacimiento; }
            set { _persona__dia_nacimiento = value; NotifyPropertyChanged(nameof(persona__dia_nacimiento)); }
        }
        protected byte? _persona__mes_nacimiento = null;

        public byte? persona__mes_nacimiento
        {
            get { return _persona__mes_nacimiento; }
            set { _persona__mes_nacimiento = value; NotifyPropertyChanged(nameof(persona__mes_nacimiento)); }
        }
        protected ushort? _persona__anio_nacimiento = null;

        public ushort? persona__anio_nacimiento
        {
            get { return _persona__anio_nacimiento; }
            set { _persona__anio_nacimiento = value; NotifyPropertyChanged(nameof(persona__anio_nacimiento)); }
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
            set { _domicilio__id = value; persona__domicilio = value; NotifyPropertyChanged(nameof(domicilio__id)); }
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
        protected string? _plan_alu__Label = null;

        public string? plan_alu__Label
        {
            get { return _plan_alu__Label; }
            set { _plan_alu__Label = value; NotifyPropertyChanged(nameof(plan_alu__Label)); }
        }

        protected string? _plan_alu__id = null;

        public string? plan_alu__id
        {
            get { return _plan_alu__id; }
            set { _plan_alu__id = value; alumno__plan = value; NotifyPropertyChanged(nameof(plan_alu__id)); }
        }
        protected string? _plan_alu__orientacion = null;

        public string? plan_alu__orientacion
        {
            get { return _plan_alu__orientacion; }
            set { _plan_alu__orientacion = value; NotifyPropertyChanged(nameof(plan_alu__orientacion)); }
        }
        protected string? _plan_alu__resolucion = null;

        public string? plan_alu__resolucion
        {
            get { return _plan_alu__resolucion; }
            set { _plan_alu__resolucion = value; NotifyPropertyChanged(nameof(plan_alu__resolucion)); }
        }
        protected string? _plan_alu__distribucion_horaria = null;

        public string? plan_alu__distribucion_horaria
        {
            get { return _plan_alu__distribucion_horaria; }
            set { _plan_alu__distribucion_horaria = value; NotifyPropertyChanged(nameof(plan_alu__distribucion_horaria)); }
        }
        protected string? _plan_alu__pfid = null;

        public string? plan_alu__pfid
        {
            get { return _plan_alu__pfid; }
            set { _plan_alu__pfid = value; NotifyPropertyChanged(nameof(plan_alu__pfid)); }
        }
        protected string? _resolucion_inscripcion__Label = null;

        public string? resolucion_inscripcion__Label
        {
            get { return _resolucion_inscripcion__Label; }
            set { _resolucion_inscripcion__Label = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__Label)); }
        }

        protected string? _resolucion_inscripcion__id = null;

        public string? resolucion_inscripcion__id
        {
            get { return _resolucion_inscripcion__id; }
            set { _resolucion_inscripcion__id = value; alumno__resolucion_inscripcion = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__id)); }
        }
        protected string? _resolucion_inscripcion__numero = null;

        public string? resolucion_inscripcion__numero
        {
            get { return _resolucion_inscripcion__numero; }
            set { _resolucion_inscripcion__numero = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__numero)); }
        }
        protected short? _resolucion_inscripcion__anio = null;

        public short? resolucion_inscripcion__anio
        {
            get { return _resolucion_inscripcion__anio; }
            set { _resolucion_inscripcion__anio = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__anio)); }
        }
        protected string? _resolucion_inscripcion__tipo = null;

        public string? resolucion_inscripcion__tipo
        {
            get { return _resolucion_inscripcion__tipo; }
            set { _resolucion_inscripcion__tipo = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__tipo)); }
        }
    }
}
