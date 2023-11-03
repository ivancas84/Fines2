using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Model
{
    public class Data_sede : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_sede ()
        {
            Initialize();
        }

        public Data_sede(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("sede").Default("id").Get("id");
                    _alta = (DateTime?)ContainerApp.db.Values("sede").Default("alta").Get("alta");
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
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { _numero = value; NotifyPropertyChanged(); }
        }
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { _nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _baja = null;
        public DateTime? baja
        {
            get { return _baja; }
            set { _baja = value; NotifyPropertyChanged(); }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { _domicilio = value; NotifyPropertyChanged(); }
        }
        protected string? _centro_educativo = null;
        public string? centro_educativo
        {
            get { return _centro_educativo; }
            set { _centro_educativo = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _fecha_traspaso = null;
        public DateTime? fecha_traspaso
        {
            get { return _fecha_traspaso; }
            set { _fecha_traspaso = value; NotifyPropertyChanged(); }
        }
        protected string? _organizacion = null;
        public string? organizacion
        {
            get { return _organizacion; }
            set { _organizacion = value; NotifyPropertyChanged(); }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(); }
        }
        protected string? _pfid_organizacion = null;
        public string? pfid_organizacion
        {
            get { return _pfid_organizacion; }
            set { _pfid_organizacion = value; NotifyPropertyChanged(); }
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

                case "numero":
                    if (_numero == null)
                        return "Debe completar valor.";
                    return "";

                case "nombre":
                    if (_nombre == null)
                        return "Debe completar valor.";
                    return "";

                case "observaciones":
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "baja":
                    return "";

                case "domicilio":
                    return "";

                case "centro_educativo":
                    return "";

                case "fecha_traspaso":
                    return "";

                case "organizacion":
                    return "";

                case "pfid":
                    return "";

                case "pfid_organizacion":
                    return "";

            }

            return "";
        }
    }
}
