using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Model
{
    public class Data_horario : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_horario ()
        {
            Initialize();
        }

        public Data_horario(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("horario").Default("id").Get("id");
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
        protected DateTime? _hora_inicio = null;
        public DateTime? hora_inicio
        {
            get { return _hora_inicio; }
            set { _hora_inicio = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _hora_fin = null;
        public DateTime? hora_fin
        {
            get { return _hora_fin; }
            set { _hora_fin = value; NotifyPropertyChanged(); }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { _curso = value; NotifyPropertyChanged(); }
        }
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set { _dia = value; NotifyPropertyChanged(); }
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

                case "hora_inicio":
                    if (_hora_inicio == null)
                        return "Debe completar valor.";
                    return "";

                case "hora_fin":
                    if (_hora_fin == null)
                        return "Debe completar valor.";
                    return "";

                case "curso":
                    if (_curso == null)
                        return "Debe completar valor.";
                    return "";

                case "dia":
                    if (_dia == null)
                        return "Debe completar valor.";
                    return "";

            }
        }
    }
}
