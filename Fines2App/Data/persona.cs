#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2App.Data
{
    public class Data_persona : SqlOrganize.Data
    {

        public Data_persona ()
        {
            Initialize();
        }

        public Data_persona(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("persona").Default("id").Get("id");
                    _alta = (DateTime?)ContainerApp.db.Values("persona").Default("alta").Get("alta");
                    _telefono_verificado = (bool?)ContainerApp.db.Values("persona").Default("telefono_verificado").Get("telefono_verificado");
                    _email_verificado = (bool?)ContainerApp.db.Values("persona").Default("email_verificado").Get("email_verificado");
                    _info_verificada = (bool?)ContainerApp.db.Values("persona").Default("info_verificada").Get("info_verificada");
                break;
            }
        }

        public string? Label { get; set; }

        protected string? _id = null;
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
        protected DateTime? _alta = null;
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
        protected bool? _telefono_verificado = null;
        public bool? telefono_verificado
        {
            get { return _telefono_verificado; }
            set { _telefono_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _email_verificado = null;
        public bool? email_verificado
        {
            get { return _email_verificado; }
            set { _email_verificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _info_verificada = null;
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
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "nombres":
                    if (_nombres == null)
                        return "Debe completar valor.";
                    return "";

                case "apellidos":
                    return "";

                case "fecha_nacimiento":
                    return "";

                case "numero_documento":
                    if (_numero_documento == null)
                        return "Debe completar valor.";
                    if (!_numero_documento.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("persona").Where("$numero_documento = @0").Parameters(_numero_documento).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "cuil":
                    if (!_cuil.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("persona").Where("$cuil = @0").Parameters(_cuil).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "genero":
                    return "";

                case "apodo":
                    return "";

                case "telefono":
                    return "";

                case "email":
                    return "";

                case "email_abc":
                    if (!_email_abc.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("persona").Where("$email_abc = @0").Parameters(_email_abc).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "domicilio":
                    return "";

                case "lugar_nacimiento":
                    return "";

                case "telefono_verificado":
                    if (_telefono_verificado == null)
                        return "Debe completar valor.";
                    return "";

                case "email_verificado":
                    if (_email_verificado == null)
                        return "Debe completar valor.";
                    return "";

                case "info_verificada":
                    if (_info_verificada == null)
                        return "Debe completar valor.";
                    return "";

                case "descripcion_domicilio":
                    return "";

            }

            return "";
        }
    }
}
