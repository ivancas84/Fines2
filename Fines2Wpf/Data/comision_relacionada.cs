using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_comision_relacionada : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_comision_relacionada ()
        {
            Initialize();
        }

        public Data_comision_relacionada (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("comision_relacionada").Default("id").Get("id");
                break;
            }

            Data_comision = new (mode);
            Data_relacion = new (mode);
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }

        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { _comision = value; NotifyPropertyChanged(); }
        }

        protected string? _relacion = null;
        public string? relacion
        {
            get { return _relacion; }
            set { _relacion = value; NotifyPropertyChanged(); }
        }

        protected Data_comision? _Data_comision = null;
        public Data_comision? Data_comision
        {
            get { return _Data_comision; }
            set { _Data_comision = value; NotifyPropertyChanged(); }
        }

        protected Data_comision? _Data_relacion = null;
        public Data_comision? Data_relacion
        {
            get { return _Data_relacion; }
            set { _Data_relacion = value; NotifyPropertyChanged(); }
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

                case "comision":
                    if (_comision == null)
                        return "Debe completar valor.";
                    return "";

                case "relacion":
                    if (_relacion == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
