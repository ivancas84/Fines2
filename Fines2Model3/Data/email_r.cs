#nullable enable
using SqlOrganize;
using System;
using Newtonsoft.Json;

namespace Fines2Model3.Data
{
    public class Data_email_r : Data_email
    {

        public Data_email_r () : base()
        {
        }

        public Data_email_r (Db db) : base(db)
        {
        }

        public Data_email_r (Db db, params string[] fieldIds) : this(db)
        {
            Init(fieldIds);
        }

        protected void Init(params string[] fieldIds)
        {
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "persona":
                        persona__id = (string?)db!.Values("persona").GetDefault("id");
                        persona__alta = (DateTime?)db!.Values("persona").GetDefault("alta");
                        persona__telefono_verificado = (bool?)db!.Values("persona").GetDefault("telefono_verificado");
                        persona__email_verificado = (bool?)db!.Values("persona").GetDefault("email_verificado");
                        persona__info_verificada = (bool?)db!.Values("persona").GetDefault("info_verificada");
                    break;
                    case "domicilio":
                        domicilio__id = (string?)db!.Values("domicilio").GetDefault("id");
                    break;
                }
            }
        }

        public string? persona__Label { get; set; }

        protected string? _persona__id = null;

        [JsonProperty("persona-id")]
        public string? persona__id
        {
            get { return _persona__id; }
            set { _persona__id = value; persona = value; NotifyPropertyChanged(nameof(persona__id)); }
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

        public string? domicilio__Label { get; set; }

        protected string? _domicilio__id = null;

        [JsonProperty("domicilio-id")]
        public string? domicilio__id
        {
            get { return _domicilio__id; }
            set { _domicilio__id = value; persona__domicilio = value; NotifyPropertyChanged(nameof(domicilio__id)); }
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
    }
}
