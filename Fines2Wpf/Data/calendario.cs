using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_calendario : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_calendario ()
        {
            Initialize();
        }

        public Data_calendario (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("calendario").Default("id").Get("id");
                    _insertado = (DateTime?)ContainerApp.db.Values("calendario").Default("insertado").Get("insertado");
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

        protected DateTime? _inicio = null;
        public DateTime? inicio
        {
            get { return _inicio; }
            set { _inicio = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _fin = null;
        public DateTime? fin
        {
            get { return _fin; }
            set { _fin = value; NotifyPropertyChanged(); }
        }

        protected short? _anio = null;
        public short? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(); }
        }

        protected short? _semestre = null;
        public short? semestre
        {
            get { return _semestre; }
            set { _semestre = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(); }
        }

        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { _descripcion = value; NotifyPropertyChanged(); }
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

                case "inicio":
                    return "";

                case "fin":
                    return "";

                case "anio":
                    if (_anio == null)
                        return "Debe completar valor.";
                    return "";

                case "semestre":
                    if (_semestre == null)
                        return "Debe completar valor.";
                    return "";

                case "insertado":
                    if (_insertado == null)
                        return "Debe completar valor.";
                    return "";

                case "descripcion":
                    return "";

            }

            return "";
        }
    }
}
