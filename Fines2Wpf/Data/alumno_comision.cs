#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_alumno_comision : SqlOrganize.Data
    {

        public Data_alumno_comision ()
        {
            Initialize();
        }

        public Data_alumno_comision(DataInitMode mode)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Null)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("alumno_comision").Default("id").Get("id");
                    _creado = (DateTime?)ContainerApp.db.Values("alumno_comision").Default("creado").Get("creado");
                    _estado = (string?)ContainerApp.db.Values("alumno_comision").Default("estado").Get("estado");
                    _programafines = (bool?)ContainerApp.db.Values("alumno_comision").Default("programafines").Get("programafines");
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
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { _creado = value; NotifyPropertyChanged(); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { _comision = value; NotifyPropertyChanged(); }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { _alumno = value; NotifyPropertyChanged(); }
        }
        protected string? _estado = null;
        public string? estado
        {
            get { return _estado; }
            set { _estado = value; NotifyPropertyChanged(); }
        }
        protected bool? _programafines = null;
        public bool? programafines
        {
            get { return _programafines; }
            set { _programafines = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "creado":
                    if (_creado == null)
                        return "Debe completar valor.";
                    return "";

                case "observaciones":
                    return "";

                case "comision":
                    return "";

                case "alumno":
                    if (_alumno == null)
                        return "Debe completar valor.";
                    return "";

                case "estado":
                    return "";

                case "programafines":
                    if (_programafines == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
