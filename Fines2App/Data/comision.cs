#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2App.Data
{
    public class Data_comision : SqlOrganize.Data
    {

        public Data_comision ()
        {
            Initialize();
        }

        public Data_comision(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("comision").Default("id").Get("id");
                    _alta = (DateTime?)ContainerApp.db.Values("comision").Default("alta").Get("alta");
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
        protected string? _turno = null;
        public string? turno
        {
            get { return _turno; }
            set { _turno = value; NotifyPropertyChanged(); }
        }
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { _division = value; NotifyPropertyChanged(); }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(); }
        }
        protected bool? _autorizada = null;
        public bool? autorizada
        {
            get { return _autorizada; }
            set { _autorizada = value; NotifyPropertyChanged(); }
        }
        protected bool? _apertura = null;
        public bool? apertura
        {
            get { return _apertura; }
            set { _apertura = value; NotifyPropertyChanged(); }
        }
        protected bool? _publicada = null;
        public bool? publicada
        {
            get { return _publicada; }
            set { _publicada = value; NotifyPropertyChanged(); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(); }
        }
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { _sede = value; NotifyPropertyChanged(); }
        }
        protected string? _modalidad = null;
        public string? modalidad
        {
            get { return _modalidad; }
            set { _modalidad = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set { _planificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _comision_siguiente = null;
        public string? comision_siguiente
        {
            get { return _comision_siguiente; }
            set { _comision_siguiente = value; NotifyPropertyChanged(); }
        }
        protected string? _calendario = null;
        public string? calendario
        {
            get { return _calendario; }
            set { _calendario = value; NotifyPropertyChanged(); }
        }
        protected string? _identificacion = null;
        public string? identificacion
        {
            get { return _identificacion; }
            set { _identificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "turno":
                    return "";

                case "division":
                    if (_division == null)
                        return "Debe completar valor.";
                    return "";

                case "comentario":
                    return "";

                case "autorizada":
                    if (_autorizada == null)
                        return "Debe completar valor.";
                    return "";

                case "apertura":
                    if (_apertura == null)
                        return "Debe completar valor.";
                    return "";

                case "publicada":
                    if (_publicada == null)
                        return "Debe completar valor.";
                    return "";

                case "observaciones":
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "sede":
                    if (_sede == null)
                        return "Debe completar valor.";
                    return "";

                case "modalidad":
                    if (_modalidad == null)
                        return "Debe completar valor.";
                    return "";

                case "planificacion":
                    return "";

                case "comision_siguiente":
                    return "";

                case "calendario":
                    if (_calendario == null)
                        return "Debe completar valor.";
                    return "";

                case "identificacion":
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
    }
}
