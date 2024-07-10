#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_domicilio : SqlOrganize.Sql.Data
    {

        public Data_domicilio ()
        {
        }

        public Data_domicilio(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("domicilio");
            _id = (string?)val.GetDefault("id");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _calle = null;
        public string? calle
        {
            get { return _calle; }
            set { _calle = value; NotifyPropertyChanged(nameof(calle)); }
        }
        protected string? _entre = null;
        public string? entre
        {
            get { return _entre; }
            set { _entre = value; NotifyPropertyChanged(nameof(entre)); }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { _numero = value; NotifyPropertyChanged(nameof(numero)); }
        }
        protected string? _piso = null;
        public string? piso
        {
            get { return _piso; }
            set { _piso = value; NotifyPropertyChanged(nameof(piso)); }
        }
        protected string? _departamento = null;
        public string? departamento
        {
            get { return _departamento; }
            set { _departamento = value; NotifyPropertyChanged(nameof(departamento)); }
        }
        protected string? _barrio = null;
        public string? barrio
        {
            get { return _barrio; }
            set { _barrio = value; NotifyPropertyChanged(nameof(barrio)); }
        }
        protected string? _localidad = null;
        public string? localidad
        {
            get { return _localidad; }
            set { _localidad = value; NotifyPropertyChanged(nameof(localidad)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "calle":
                    if (_calle == null)
                        return "Debe completar valor.";
                    return "";

                case "entre":
                    return "";

                case "numero":
                    if (_numero == null)
                        return "Debe completar valor.";
                    return "";

                case "piso":
                    return "";

                case "departamento":
                    return "";

                case "barrio":
                    return "";

                case "localidad":
                    if (_localidad == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
