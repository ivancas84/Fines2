#nullable enable
using SqlOrganize;
using System;
using Newtonsoft.Json;

namespace Fines2Model3.Data
{
    public class Data_designacion_r : Data_designacion
    {

        public Data_designacion_r () : base()
        {
        }

        public Data_designacion_r (Db db, bool init = true) : base(db, init)
        {
        }

        public Data_designacion_r (Db db, bool init = true, params string[] fieldIds) : this(db, init)
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
                    case "cargo":
                        val = db!.Values("cargo");
                        cargo__id = (string?)val.GetDefault("id");
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
                }
            }
        }

        public string? cargo__Label { get; set; }

        protected string? _cargo__id = null;

        [JsonProperty("cargo-id")]
        public string? cargo__id
        {
            get { return _cargo__id; }
            set { _cargo__id = value; cargo = value; NotifyPropertyChanged(nameof(cargo__id)); }
        }
        protected string? _cargo__descripcion = null;

        [JsonProperty("cargo-descripcion")]
        public string? cargo__descripcion
        {
            get { return _cargo__descripcion; }
            set { _cargo__descripcion = value; NotifyPropertyChanged(nameof(cargo__descripcion)); }
        }

        public string? sede__Label { get; set; }

        protected string? _sede__id = null;

        [JsonProperty("sede-id")]
        public string? sede__id
        {
            get { return _sede__id; }
            set { _sede__id = value; sede = value; NotifyPropertyChanged(nameof(sede__id)); }
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
    }
}
