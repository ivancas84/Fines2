#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_persona : SqlOrganize.Data
    {

        public Data_persona ()
        {
        }

        public Data_persona(Db db)
        {
            this.db = db;
            Init();
        }

        protected void Init()
        {
            _id = (string?)db!.Values("persona").GetDefault("id");
            _alta = (DateTime?)db!.Values("persona").GetDefault("alta");
            _telefono_verificado = (bool?)db!.Values("persona").GetDefault("telefono_verificado");
            _email_verificado = (bool?)db!.Values("persona").GetDefault("email_verificado");
            _info_verificada = (bool?)db!.Values("persona").GetDefault("info_verificada");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _nombres = null;
        public string? nombres
        {
            get { return _nombres; }
            set { _nombres = value; NotifyPropertyChanged(nameof(nombres)); }
        }
        protected string? _apellidos = null;
        public string? apellidos
        {
            get { return _apellidos; }
            set { _apellidos = value; NotifyPropertyChanged(nameof(apellidos)); }
        }
        protected DateTime? _fecha_nacimiento = null;
        public DateTime? fecha_nacimiento
        {
            get { return _fecha_nacimiento; }
            set { _fecha_nacimiento = value; NotifyPropertyChanged(nameof(fecha_nacimiento)); }
        }
        protected string? _numero_documento = null;
        public string? numero_documento
        {
            get { return _numero_documento; }
            set { _numero_documento = value; NotifyPropertyChanged(nameof(numero_documento)); }
        }
        protected string? _cuil = null;
        public string? cuil
        {
            get { return _cuil; }
            set { _cuil = value; NotifyPropertyChanged(nameof(cuil)); }
        }
        protected string? _genero = null;
        public string? genero
        {
            get { return _genero; }
            set { _genero = value; NotifyPropertyChanged(nameof(genero)); }
        }
        protected string? _apodo = null;
        public string? apodo
        {
            get { return _apodo; }
            set { _apodo = value; NotifyPropertyChanged(nameof(apodo)); }
        }
        protected string? _telefono = null;
        public string? telefono
        {
            get { return _telefono; }
            set { _telefono = value; NotifyPropertyChanged(nameof(telefono)); }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { _email = value; NotifyPropertyChanged(nameof(email)); }
        }
        protected string? _email_abc = null;
        public string? email_abc
        {
            get { return _email_abc; }
            set { _email_abc = value; NotifyPropertyChanged(nameof(email_abc)); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(nameof(alta)); }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { _domicilio = value; NotifyPropertyChanged(nameof(domicilio)); }
        }
        protected string? _lugar_nacimiento = null;
        public string? lugar_nacimiento
        {
            get { return _lugar_nacimiento; }
            set { _lugar_nacimiento = value; NotifyPropertyChanged(nameof(lugar_nacimiento)); }
        }
        protected bool? _telefono_verificado = null;
        public bool? telefono_verificado
        {
            get { return _telefono_verificado; }
            set { _telefono_verificado = value; NotifyPropertyChanged(nameof(telefono_verificado)); }
        }
        protected bool? _email_verificado = null;
        public bool? email_verificado
        {
            get { return _email_verificado; }
            set { _email_verificado = value; NotifyPropertyChanged(nameof(email_verificado)); }
        }
        protected bool? _info_verificada = null;
        public bool? info_verificada
        {
            get { return _info_verificada; }
            set { _info_verificada = value; NotifyPropertyChanged(nameof(info_verificada)); }
        }
        protected string? _descripcion_domicilio = null;
        public string? descripcion_domicilio
        {
            get { return _descripcion_domicilio; }
            set { _descripcion_domicilio = value; NotifyPropertyChanged(nameof(descripcion_domicilio)); }
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
                        var row = db.Sql("persona").Where("$numero_documento = @0").Parameters(_numero_documento).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "cuil":
                    if (!_cuil.IsNullOrEmptyOrDbNull()) {
                        var row = db.Sql("persona").Where("$cuil = @0").Parameters(_cuil).DictCache();
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
                        var row = db.Sql("persona").Where("$email_abc = @0").Parameters(_email_abc).DictCache();
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
