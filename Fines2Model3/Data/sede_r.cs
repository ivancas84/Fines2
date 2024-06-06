#nullable enable
using SqlOrganize;
using System;
using Newtonsoft.Json;

namespace Fines2Model3.Data
{
    public class Data_sede_r : Data_sede
    {

        public Data_sede_r () : base()
        {
        }

        public Data_sede_r (Db db, bool init = true) : base(db, init)
        {
        }

        public Data_sede_r (Db db, bool init = true, params string[] fieldIds) : this(db, init)
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
                }
            }
        }

        public string? domicilio__Label { get; set; }

        protected string? _domicilio__id = null;

        [JsonProperty("domicilio-id")]
        public string? domicilio__id
        {
            get { return _domicilio__id; }
            set { _domicilio__id = value; domicilio = value; NotifyPropertyChanged(nameof(domicilio__id)); }
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
            set { _centro_educativo__id = value; centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo__id)); }
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
    }
}
