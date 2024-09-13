#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Sede_ : Sede
    {

        public void DefaultRel(params string[] fieldIds)
        {
            EntityVal val;
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
                    case "domicilio_cen":
                        val = db!.Values("domicilio");
                        domicilio_cen__id = (string?)val.GetDefault("id");
                    break;
                }
            }
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
            set { _domicilio__id = value; domicilio = value; NotifyPropertyChanged(nameof(domicilio__id)); }
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
            set { _centro_educativo__id = value; centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo__id)); }
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
    }
}
