using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Model
{
    public class Data_planificacion : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_planificacion ()
        {
            Initialize();
        }

        public Data_planificacion(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("planificacion").Default("id").Get("id");
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
        protected string? _anio = null;
        public string? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(); }
        }
        protected string? _semestre = null;
        public string? semestre
        {
            get { return _semestre; }
            set { _semestre = value; NotifyPropertyChanged(); }
        }
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { _plan = value; NotifyPropertyChanged(); }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(); }
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

                case "anio":
                    if (_anio == null)
                        return "Debe completar valor.";
                    return "";

                case "semestre":
                    if (_semestre == null)
                        return "Debe completar valor.";
                    return "";

                case "plan":
                    if (_plan == null)
                        return "Debe completar valor.";
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
    }
}
