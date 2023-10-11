using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_detalle_persona_r : Data_detalle_persona
    {

        public Data_detalle_persona_r () : base()
        {
            Initialize();
        }

        public Data_detalle_persona_r (DataInitMode mode = DataInitMode.Default) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
                    _archivo__id = (string?)ContainerApp.db.DefaultValue("file", "id");
                    _archivo__created = (DateTime?)ContainerApp.db.DefaultValue("file", "created");
                    _persona__id = (string?)ContainerApp.db.DefaultValue("persona", "id");
                    _persona__alta = (DateTime?)ContainerApp.db.DefaultValue("persona", "alta");
                    _persona__telefono_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "telefono_verificado");
                    _persona__email_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "email_verificado");
                    _persona__info_verificada = (bool?)ContainerApp.db.DefaultValue("persona", "info_verificada");
                    _domicilio__id = (string?)ContainerApp.db.DefaultValue("domicilio", "id");
                break;
            }
        }

        protected string? _archivo__id = null;
        public string? archivo__id
        {
            get { return _archivo__id; }
            set { _archivo__id = value; NotifyPropertyChanged(); }
        }
        protected string? _archivo__name = null;
        public string? archivo__name
        {
            get { return _archivo__name; }
            set { _archivo__name = value; NotifyPropertyChanged(); }
        }
        protected string? _archivo__type = null;
        public string? archivo__type
        {
            get { return _archivo__type; }
            set { _archivo__type = value; NotifyPropertyChanged(); }
        }
        protected string? _archivo__content = null;
        public string? archivo__content
        {
            get { return _archivo__content; }
            set { _archivo__content = value; NotifyPropertyChanged(); }
        }
        protected uint? _archivo__size = null;
        public uint? archivo__size
        {
            get { return _archivo__size; }
            set { _archivo__size = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _archivo__created = null;
        public DateTime? archivo__created
        {
            get { return _archivo__created; }
            set { _archivo__created = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__id = null;
        public string? persona__id
        {
            get { return _persona__id; }
            set { _persona__id = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__nombres = null;
        public string? persona__nombres
        {
            get { return _persona__nombres; }
            set { _persona__nombres = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__apellidos = null;
        public string? persona__apellidos
        {
            get { return _persona__apellidos; }
            set { _persona__apellidos = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _persona__fecha_nacimiento = null;
        public DateTime? persona__fecha_nacimiento
        {
            get { return _persona__fecha_nacimiento; }
            set { _persona__fecha_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__numero_documento = null;
        public string? persona__numero_documento
        {
            get { return _persona__numero_documento; }
            set { _persona__numero_documento = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__cuil = null;
        public string? persona__cuil
        {
            get { return _persona__cuil; }
            set { _persona__cuil = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__genero = null;
        public string? persona__genero
        {
            get { return _persona__genero; }
            set { _persona__genero = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__apodo = null;
        public string? persona__apodo
        {
            get { return _persona__apodo; }
            set { _persona__apodo = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__telefono = null;
        public string? persona__telefono
        {
            get { return _persona__telefono; }
            set { _persona__telefono = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__email = null;
        public string? persona__email
        {
            get { return _persona__email; }
            set { _persona__email = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__email_abc = null;
        public string? persona__email_abc
        {
            get { return _persona__email_abc; }
            set { _persona__email_abc = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _persona__alta = null;
        public DateTime? persona__alta
        {
            get { return _persona__alta; }
            set { _persona__alta = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__domicilio = null;
        public string? persona__domicilio
        {
            get { return _persona__domicilio; }
            set { _persona__domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__lugar_nacimiento = null;
        public string? persona__lugar_nacimiento
        {
            get { return _persona__lugar_nacimiento; }
            set { _persona__lugar_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected bool? _persona__telefono_verificado = null;
        public bool? persona__telefono_verificado
        {
            get { return _persona__telefono_verificado; }
            set { _persona__telefono_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _persona__email_verificado = null;
        public bool? persona__email_verificado
        {
            get { return _persona__email_verificado; }
            set { _persona__email_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _persona__info_verificada = null;
        public bool? persona__info_verificada
        {
            get { return _persona__info_verificada; }
            set { _persona__info_verificada = value; NotifyPropertyChanged(); }
        }
        protected string? _persona__descripcion_domicilio = null;
        public string? persona__descripcion_domicilio
        {
            get { return _persona__descripcion_domicilio; }
            set { _persona__descripcion_domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio__id = null;
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
    }
}
