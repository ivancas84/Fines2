using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_plan : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_plan ()
        {
            Initialize();
        }

        public Data_plan (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("plan").Default("id").Get("id");
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

        protected string? _orientacion = null;
        public string? orientacion
        {
            get { return _orientacion; }
            set { _orientacion = value; NotifyPropertyChanged(); }
        }

        protected string? _resolucion = null;
        public string? resolucion
        {
            get { return _resolucion; }
            set { _resolucion = value; NotifyPropertyChanged(); }
        }

        protected string? _distribucion_horaria = null;
        public string? distribucion_horaria
        {
            get { return _distribucion_horaria; }
            set { _distribucion_horaria = value; NotifyPropertyChanged(); }
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

                case "orientacion":
                    if (_orientacion == null)
                        return "Debe completar valor.";
                    return "";

                case "resolucion":
                    return "";

                case "distribucion_horaria":
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
    }
}
