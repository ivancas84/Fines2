using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_asignacion_planilla_docente : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_asignacion_planilla_docente ()
        {
            Initialize();
        }

        public Data_asignacion_planilla_docente (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("asignacion_planilla_docente").Default("id").Get("id");
                    _insertado = (DateTime?)ContainerApp.db.Values("asignacion_planilla_docente").Default("insertado").Get("insertado");
                    _reclamo = (bool?)ContainerApp.db.Values("asignacion_planilla_docente").Default("reclamo").Get("reclamo");
                break;
            }

            Data_planilla_docente = new (mode);
            Data_toma = new (mode);
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }

        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { _planilla_docente = value; NotifyPropertyChanged(); }
        }

        protected string? _toma = null;
        public string? toma
        {
            get { return _toma; }
            set { _toma = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(); }
        }

        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(); }
        }

        protected bool? _reclamo = null;
        public bool? reclamo
        {
            get { return _reclamo; }
            set { _reclamo = value; NotifyPropertyChanged(); }
        }

        protected Data_planilla_docente? _Data_planilla_docente = null;
        public Data_planilla_docente? Data_planilla_docente
        {
            get { return _Data_planilla_docente; }
            set { _Data_planilla_docente = value; NotifyPropertyChanged(); }
        }

        protected Data_toma? _Data_toma = null;
        public Data_toma? Data_toma
        {
            get { return _Data_toma; }
            set { _Data_toma = value; NotifyPropertyChanged(); }
        }

        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
        public string Error
        {
            get
            {
                PropertyInfo[] properties = this.GetType().GetProperties();

                List<string> errors = new ();
                foreach (PropertyInfo property in properties)
                    if (this[property.Name] != "")
                    {
                        NotifyPropertyChanged(property.Name);
                        errors.Add(this[property.Name]);
                    }

                if(errors.Count > 0)
                    return String.Join(" - ", errors.ToArray());

                return "";
            }
        }

        public string this[string columnName]
        {
            get
            {
                if (!Validate)
                    return "";

                // If there's no error, empty string gets returned
                return ValidateField(columnName);
            }
        }

        protected virtual string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "planilla_docente":
                    if (_planilla_docente == null)
                        return "Debe completar valor.";
                    return "";

                case "toma":
                    if (_toma == null)
                        return "Debe completar valor.";
                    return "";

                case "insertado":
                    if (_insertado == null)
                        return "Debe completar valor.";
                    return "";

                case "comentario":
                    return "";

                case "reclamo":
                    if (_reclamo == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
