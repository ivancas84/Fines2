#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Alumno_ : Alumno
    {

        public void DefaultRel(params string[] fieldIds)
        {
            EntityVal val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
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
                    case "plan":
                        val = db!.Values("plan");
                        plan__id = (string?)val.GetDefault("id");
                    break;
                    case "resolucion_inscripcion":
                        val = db!.Values("resolucion");
                        resolucion_inscripcion__id = (string?)val.GetDefault("id");
                    break;
                }
            }
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
            set { _persona__id = value; persona = value; NotifyPropertyChanged(nameof(persona__id)); }
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
            set { _plan__id = value; plan = value; NotifyPropertyChanged(nameof(plan__id)); }
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
            set { _resolucion_inscripcion__id = value; resolucion_inscripcion = value; NotifyPropertyChanged(nameof(resolucion_inscripcion__id)); }
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
