using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_disposicion_pendiente : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_disposicion_pendiente ()
        {
            Initialize();
        }

        public Data_disposicion_pendiente (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("disposicion_pendiente").Default("id").Get("id");
                break;
            }

            Data_disposicion = new (mode);
            Data_alumno = new (mode);
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }

        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { _disposicion = value; NotifyPropertyChanged(); }
        }

        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { _alumno = value; NotifyPropertyChanged(); }
        }

        protected string? _modo = null;
        public string? modo
        {
            get { return _modo; }
            set { _modo = value; NotifyPropertyChanged(); }
        }

        protected Data_disposicion? _Data_disposicion = null;
        public Data_disposicion? Data_disposicion
        {
            get { return _Data_disposicion; }
            set { _Data_disposicion = value; NotifyPropertyChanged(); }
        }

        protected Data_alumno? _Data_alumno = null;
        public Data_alumno? Data_alumno
        {
            get { return _Data_alumno; }
            set { _Data_alumno = value; NotifyPropertyChanged(); }
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

                case "disposicion":
                    if (_disposicion == null)
                        return "Debe completar valor.";
                    return "";

                case "alumno":
                    if (_alumno == null)
                        return "Debe completar valor.";
                    return "";

                case "modo":
                    return "";

            }

            return "";
        }
    }
}
