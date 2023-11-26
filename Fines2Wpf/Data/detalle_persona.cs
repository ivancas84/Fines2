using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_detalle_persona : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_detalle_persona ()
        {
            Initialize();
        }

        public Data_detalle_persona (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("detalle_persona").Default("id").Get("id");
                    _creado = (DateTime?)ContainerApp.db.Values("detalle_persona").Default("creado").Get("creado");
                    _fecha = (DateTime?)ContainerApp.db.Values("detalle_persona").Default("fecha").Get("fecha");
                break;
            }

            Data_archivo = new (mode);
            Data_persona = new (mode);
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }

        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { _descripcion = value; NotifyPropertyChanged(); }
        }

        protected string? _archivo = null;
        public string? archivo
        {
            get { return _archivo; }
            set { _archivo = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { _creado = value; NotifyPropertyChanged(); }
        }

        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { _fecha = value; NotifyPropertyChanged(); }
        }

        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { _tipo = value; NotifyPropertyChanged(); }
        }

        protected string? _asunto = null;
        public string? asunto
        {
            get { return _asunto; }
            set { _asunto = value; NotifyPropertyChanged(); }
        }

        protected Data_file? _Data_archivo = null;
        public Data_file? Data_archivo
        {
            get { return _Data_archivo; }
            set { _Data_archivo = value; NotifyPropertyChanged(); }
        }

        protected Data_persona? _Data_persona = null;
        public Data_persona? Data_persona
        {
            get { return _Data_persona; }
            set { _Data_persona = value; NotifyPropertyChanged(); }
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

                case "descripcion":
                    if (_descripcion == null)
                        return "Debe completar valor.";
                    return "";

                case "archivo":
                    return "";

                case "creado":
                    if (_creado == null)
                        return "Debe completar valor.";
                    return "";

                case "persona":
                    if (_persona == null)
                        return "Debe completar valor.";
                    return "";

                case "fecha":
                    return "";

                case "tipo":
                    return "";

                case "asunto":
                    return "";

            }

            return "";
        }
    }
}
