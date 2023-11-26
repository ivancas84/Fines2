#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Model
{
    public class Data_domicilio : SqlOrganize.Data
    {

        public Data_domicilio ()
        {
            Initialize();
        }

        public Data_domicilio(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("domicilio").Default("id").Get("id");
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
        protected string? _calle = null;
        public string? calle
        {
            get { return _calle; }
            set { _calle = value; NotifyPropertyChanged(); }
        }
        protected string? _entre = null;
        public string? entre
        {
            get { return _entre; }
            set { _entre = value; NotifyPropertyChanged(); }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { _numero = value; NotifyPropertyChanged(); }
        }
        protected string? _piso = null;
        public string? piso
        {
            get { return _piso; }
            set { _piso = value; NotifyPropertyChanged(); }
        }
        protected string? _departamento = null;
        public string? departamento
        {
            get { return _departamento; }
            set { _departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _barrio = null;
        public string? barrio
        {
            get { return _barrio; }
            set { _barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _localidad = null;
        public string? localidad
        {
            get { return _localidad; }
            set { _localidad = value; NotifyPropertyChanged(); }
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
