using System;
using System.ComponentModel;

namespace Fines2Wpf.Data
{
    public class Data_persona : INotifyPropertyChanged
    {

        public string? Label { get; set; }

        protected string? _id = (string?)ContainerApp.db.DefaultValue("persona", "id");
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _nombres = null;
        public string? nombres
        {
            get { return _nombres; }
            set { _nombres = value; NotifyPropertyChanged(); }
        }
        protected string? _apellidos = null;
        public string? apellidos
        {
            get { return _apellidos; }
            set { _apellidos = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _fecha_nacimiento = null;
        public DateTime? fecha_nacimiento
        {
            get { return _fecha_nacimiento; }
            set { _fecha_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected string? _numero_documento = null;
        public string? numero_documento
        {
            get { return _numero_documento; }
            set { _numero_documento = value; NotifyPropertyChanged(); }
        }
        protected string? _cuil = null;
        public string? cuil
        {
            get { return _cuil; }
            set { _cuil = value; NotifyPropertyChanged(); }
        }
        protected string? _genero = null;
        public string? genero
        {
            get { return _genero; }
            set { _genero = value; NotifyPropertyChanged(); }
        }
        protected string? _apodo = null;
        public string? apodo
        {
            get { return _apodo; }
            set { _apodo = value; NotifyPropertyChanged(); }
        }
        protected string? _telefono = null;
        public string? telefono
        {
            get { return _telefono; }
            set { _telefono = value; NotifyPropertyChanged(); }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { _email = value; NotifyPropertyChanged(); }
        }
        protected string? _email_abc = null;
        public string? email_abc
        {
            get { return _email_abc; }
            set { _email_abc = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _alta = (DateTime?)ContainerApp.db.DefaultValue("persona", "alta");
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { _domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _lugar_nacimiento = null;
        public string? lugar_nacimiento
        {
            get { return _lugar_nacimiento; }
            set { _lugar_nacimiento = value; NotifyPropertyChanged(); }
        }
        protected bool? _telefono_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "telefono_verificado");
        public bool? telefono_verificado
        {
            get { return _telefono_verificado; }
            set { _telefono_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _email_verificado = (bool?)ContainerApp.db.DefaultValue("persona", "email_verificado");
        public bool? email_verificado
        {
            get { return _email_verificado; }
            set { _email_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _info_verificada = (bool?)ContainerApp.db.DefaultValue("persona", "info_verificada");
        public bool? info_verificada
        {
            get { return _info_verificada; }
            set { _info_verificada = value; NotifyPropertyChanged(); }
        }
        protected string? _descripcion_domicilio = null;
        public string? descripcion_domicilio
        {
            get { return _descripcion_domicilio; }
            set { _descripcion_domicilio = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
