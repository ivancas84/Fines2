#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Persona : EntityData
    {

        public Persona()
        {
            _entityName = "persona";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _nombres = null;
        public string? nombres
        {
            get { return _nombres; }
            set { if( _nombres != value) { _nombres = value; NotifyPropertyChanged(nameof(nombres)); } }
        }
        protected string? _apellidos = null;
        public string? apellidos
        {
            get { return _apellidos; }
            set { if( _apellidos != value) { _apellidos = value; NotifyPropertyChanged(nameof(apellidos)); } }
        }
        protected DateTime? _fecha_nacimiento = null;
        public DateTime? fecha_nacimiento
        {
            get { return _fecha_nacimiento; }
            set { if( _fecha_nacimiento != value) { _fecha_nacimiento = value; NotifyPropertyChanged(nameof(fecha_nacimiento)); } }
        }
        protected string? _numero_documento = null;
        public string? numero_documento
        {
            get { return _numero_documento; }
            set { if( _numero_documento != value) { _numero_documento = value; NotifyPropertyChanged(nameof(numero_documento)); } }
        }
        protected string? _cuil = null;
        public string? cuil
        {
            get { return _cuil; }
            set { if( _cuil != value) { _cuil = value; NotifyPropertyChanged(nameof(cuil)); } }
        }
        protected string? _genero = null;
        public string? genero
        {
            get { return _genero; }
            set { if( _genero != value) { _genero = value; NotifyPropertyChanged(nameof(genero)); } }
        }
        protected string? _apodo = null;
        public string? apodo
        {
            get { return _apodo; }
            set { if( _apodo != value) { _apodo = value; NotifyPropertyChanged(nameof(apodo)); } }
        }
        protected string? _telefono = null;
        public string? telefono
        {
            get { return _telefono; }
            set { if( _telefono != value) { _telefono = value; NotifyPropertyChanged(nameof(telefono)); } }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { if( _email != value) { _email = value; NotifyPropertyChanged(nameof(email)); } }
        }
        protected string? _email_abc = null;
        public string? email_abc
        {
            get { return _email_abc; }
            set { if( _email_abc != value) { _email_abc = value; NotifyPropertyChanged(nameof(email_abc)); } }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { if( _domicilio != value) { _domicilio = value; NotifyPropertyChanged(nameof(domicilio)); } }
        }
        protected string? _lugar_nacimiento = null;
        public string? lugar_nacimiento
        {
            get { return _lugar_nacimiento; }
            set { if( _lugar_nacimiento != value) { _lugar_nacimiento = value; NotifyPropertyChanged(nameof(lugar_nacimiento)); } }
        }
        protected bool? _telefono_verificado = null;
        public bool? telefono_verificado
        {
            get { return _telefono_verificado; }
            set { if( _telefono_verificado != value) { _telefono_verificado = value; NotifyPropertyChanged(nameof(telefono_verificado)); } }
        }
        protected bool? _email_verificado = null;
        public bool? email_verificado
        {
            get { return _email_verificado; }
            set { if( _email_verificado != value) { _email_verificado = value; NotifyPropertyChanged(nameof(email_verificado)); } }
        }
        protected bool? _info_verificada = null;
        public bool? info_verificada
        {
            get { return _info_verificada; }
            set { if( _info_verificada != value) { _info_verificada = value; NotifyPropertyChanged(nameof(info_verificada)); } }
        }
        protected string? _descripcion_domicilio = null;
        public string? descripcion_domicilio
        {
            get { return _descripcion_domicilio; }
            set { if( _descripcion_domicilio != value) { _descripcion_domicilio = value; NotifyPropertyChanged(nameof(descripcion_domicilio)); } }
        }
        protected byte? _cuil1 = null;
        public byte? cuil1
        {
            get { return _cuil1; }
            set { if( _cuil1 != value) { _cuil1 = value; NotifyPropertyChanged(nameof(cuil1)); } }
        }
        protected byte? _cuil2 = null;
        public byte? cuil2
        {
            get { return _cuil2; }
            set { if( _cuil2 != value) { _cuil2 = value; NotifyPropertyChanged(nameof(cuil2)); } }
        }
        protected string? _departamento = null;
        public string? departamento
        {
            get { return _departamento; }
            set { if( _departamento != value) { _departamento = value; NotifyPropertyChanged(nameof(departamento)); } }
        }
        protected string? _localidad = null;
        public string? localidad
        {
            get { return _localidad; }
            set { if( _localidad != value) { _localidad = value; NotifyPropertyChanged(nameof(localidad)); } }
        }
        protected string? _partido = null;
        public string? partido
        {
            get { return _partido; }
            set { if( _partido != value) { _partido = value; NotifyPropertyChanged(nameof(partido)); } }
        }
        protected string? _codigo_area = null;
        public string? codigo_area
        {
            get { return _codigo_area; }
            set { if( _codigo_area != value) { _codigo_area = value; NotifyPropertyChanged(nameof(codigo_area)); } }
        }
        protected string? _nacionalidad = null;
        public string? nacionalidad
        {
            get { return _nacionalidad; }
            set { if( _nacionalidad != value) { _nacionalidad = value; NotifyPropertyChanged(nameof(nacionalidad)); } }
        }
        protected byte? _sexo = null;
        public byte? sexo
        {
            get { return _sexo; }
            set { if( _sexo != value) { _sexo = value; NotifyPropertyChanged(nameof(sexo)); } }
        }
        protected byte? _dia_nacimiento = null;
        public byte? dia_nacimiento
        {
            get { return _dia_nacimiento; }
            set { if( _dia_nacimiento != value) { _dia_nacimiento = value; NotifyPropertyChanged(nameof(dia_nacimiento)); } }
        }
        protected byte? _mes_nacimiento = null;
        public byte? mes_nacimiento
        {
            get { return _mes_nacimiento; }
            set { if( _mes_nacimiento != value) { _mes_nacimiento = value; NotifyPropertyChanged(nameof(mes_nacimiento)); } }
        }
        protected ushort? _anio_nacimiento = null;
        public ushort? anio_nacimiento
        {
            get { return _anio_nacimiento; }
            set { if( _anio_nacimiento != value) { _anio_nacimiento = value; NotifyPropertyChanged(nameof(anio_nacimiento)); } }
        }
        //persona.domicilio _o:o domicilio.id
        protected Domicilio? _domicilio_ = null;
        public Domicilio? domicilio_
        {
            get { return _domicilio_; }
            set {
                _domicilio_ = value;
                domicilio = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(domicilio_));
            }
        }

        //alumno.persona _o:o persona.id
        protected Alumno? _Alumno_ = null;
        public Alumno? Alumno_
        {
            get { return _Alumno_; }
            set { _Alumno_ = value; NotifyPropertyChanged(nameof(Alumno_)); }
        }

        //designacion.persona _m:o persona.id
        public ObservableCollection<Designacion> Designacion_ { get; set; } = new ();

        //detalle_persona.persona _m:o persona.id
        public ObservableCollection<DetallePersona> DetallePersona_ { get; set; } = new ();

        //email.persona _m:o persona.id
        public ObservableCollection<Email> Email_ { get; set; } = new ();

        //telefono.persona _m:o persona.id
        public ObservableCollection<Telefono> Telefono_ { get; set; } = new ();

        //toma.docente _m:o persona.id
        public ObservableCollection<Toma> Toma_docente_ { get; set; } = new ();

        //toma.reemplazo _m:o persona.id
        public ObservableCollection<Toma> Toma_reemplazo_ { get; set; } = new ();

    }
}
