using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_centro_educativo : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_centro_educativo ()
        {
            Initialize();
        }

        public Data_centro_educativo (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("centro_educativo").Default("id").Get("id");
                break;
            }

            Data_domicilio = new (mode);
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }

        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { _nombre = value; NotifyPropertyChanged(); }
        }

        protected string? _cue = null;
        public string? cue
        {
            get { return _cue; }
            set { _cue = value; NotifyPropertyChanged(); }
        }

        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { _domicilio = value; NotifyPropertyChanged(); }
        }

        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }

        protected Data_domicilio? _Data_domicilio = null;
        public Data_domicilio? Data_domicilio
        {
            get { return _Data_domicilio; }
            set { _Data_domicilio = value; NotifyPropertyChanged(); }
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

                case "nombre":
                    if (_nombre == null)
                        return "Debe completar valor.";
                    return "";

                case "cue":
                    if (!_cue.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("centro_educativo").Where("$cue = @0").Parameters(_cue).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "domicilio":
                    return "";

                case "observaciones":
                    return "";

            }

            return "";
        }
    }
}
