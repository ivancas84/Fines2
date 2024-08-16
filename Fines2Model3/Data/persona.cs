#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_persona : SqlOrganize.Sql.Data
    {

        public override string entityName => "persona";

        public override void Default()
        {
            EntityValues val = db!.Values("persona");
            _id = (string?)val.GetDefault("id");
            _alta = (DateTime?)val.GetDefault("alta");
            _telefono_verificado = (bool?)val.GetDefault("telefono_verificado");
            _email_verificado = (bool?)val.GetDefault("email_verificado");
            _info_verificada = (bool?)val.GetDefault("info_verificada");
        }


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
        protected byte? _cuil1 = null;
        public byte? cuil1
        {
            get { return _cuil1; }
            set { _cuil1 = value; NotifyPropertyChanged(nameof(cuil1)); }
        }
        protected byte? _cuil2 = null;
        public byte? cuil2
        {
            get { return _cuil2; }
            set { _cuil2 = value; NotifyPropertyChanged(nameof(cuil2)); }
        }
        protected string? _departamento = null;
        public string? departamento
        {
            get { return _departamento; }
            set { _departamento = value; NotifyPropertyChanged(nameof(departamento)); }
        }
        protected string? _localidad = null;
        public string? localidad
        {
            get { return _localidad; }
            set { _localidad = value; NotifyPropertyChanged(nameof(localidad)); }
        }
        protected string? _partido = null;
        public string? partido
        {
            get { return _partido; }
            set { _partido = value; NotifyPropertyChanged(nameof(partido)); }
        }
        protected string? _codigo_area = null;
        public string? codigo_area
        {
            get { return _codigo_area; }
            set { _codigo_area = value; NotifyPropertyChanged(nameof(codigo_area)); }
        }
        protected string? _nacionalidad = null;
        public string? nacionalidad
        {
            get { return _nacionalidad; }
            set { _nacionalidad = value; NotifyPropertyChanged(nameof(nacionalidad)); }
        }
        protected byte? _sexo = null;
        public byte? sexo
        {
            get { return _sexo; }
            set { _sexo = value; NotifyPropertyChanged(nameof(sexo)); }
        }
        protected byte? _dia_nacimiento = null;
        public byte? dia_nacimiento
        {
            get { return _dia_nacimiento; }
            set { _dia_nacimiento = value; NotifyPropertyChanged(nameof(dia_nacimiento)); }
        }
        protected byte? _mes_nacimiento = null;
        public byte? mes_nacimiento
        {
            get { return _mes_nacimiento; }
            set { _mes_nacimiento = value; NotifyPropertyChanged(nameof(mes_nacimiento)); }
        }
        protected ushort? _anio_nacimiento = null;
        public ushort? anio_nacimiento
        {
            get { return _anio_nacimiento; }
            set { _anio_nacimiento = value; NotifyPropertyChanged(nameof(anio_nacimiento)); }
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
                    if (!db.IsNoE() && !_numero_documento.IsNoE()) {
                        var row = db.Sql("persona").Where("$numero_documento = @0").Parameters(_numero_documento).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "cuil":
                    if (!db.IsNoE() && !_cuil.IsNoE()) {
                        var row = db.Sql("persona").Where("$cuil = @0").Parameters(_cuil).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
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
                    if (!db.IsNoE() && !_email_abc.IsNoE()) {
                        var row = db.Sql("persona").Where("$email_abc = @0").Parameters(_email_abc).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
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

                case "cuil1":
                    return "";

                case "cuil2":
                    return "";

                case "departamento":
                    return "";

                case "localidad":
                    return "";

                case "partido":
                    return "";

                case "codigo_area":
                    return "";

                case "nacionalidad":
                    return "";

                case "sexo":
                    return "";

                case "dia_nacimiento":
                    return "";

                case "mes_nacimiento":
                    return "";

                case "anio_nacimiento":
                    return "";

            }

            return "";
        }
    }
}
