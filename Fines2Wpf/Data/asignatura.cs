using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_asignatura : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_asignatura ()
        {
            Initialize();
        }

        public Data_asignatura (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("asignatura").Default("id").Get("id");
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

        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { _nombre = value; NotifyPropertyChanged(); }
        }

        protected string? _formacion = null;
        public string? formacion
        {
            get { return _formacion; }
            set { _formacion = value; NotifyPropertyChanged(); }
        }

        protected string? _clasificacion = null;
        public string? clasificacion
        {
            get { return _clasificacion; }
            set { _clasificacion = value; NotifyPropertyChanged(); }
        }

        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set { _codigo = value; NotifyPropertyChanged(); }
        }

        protected string? _perfil = null;
        public string? perfil
        {
            get { return _perfil; }
            set { _perfil = value; NotifyPropertyChanged(); }
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
                    if (!_nombre.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("asignatura").Where("$nombre = @0").Parameters(_nombre).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "formacion":
                    return "";

                case "clasificacion":
                    return "";

                case "codigo":
                    return "";

                case "perfil":
                    return "";

            }

            return "";
        }
    }
}
