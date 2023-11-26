using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_curso : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_curso ()
        {
            Initialize();
        }

        public Data_curso (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("curso").Default("id").Get("id");
                    _alta = (DateTime?)ContainerApp.db.Values("curso").Default("alta").Get("alta");
                break;
            }

            Data_comision = new (mode);
            Data_asignatura = new (mode);
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }

        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set { _horas_catedra = value; NotifyPropertyChanged(); }
        }

        protected string? _ige = null;
        public string? ige
        {
            get { return _ige; }
            set { _ige = value; NotifyPropertyChanged(); }
        }

        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { _comision = value; NotifyPropertyChanged(); }
        }

        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set { _asignatura = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(); }
        }

        protected string? _descripcion_horario = null;
        public string? descripcion_horario
        {
            get { return _descripcion_horario; }
            set { _descripcion_horario = value; NotifyPropertyChanged(); }
        }

        protected Data_comision? _Data_comision = null;
        public Data_comision? Data_comision
        {
            get { return _Data_comision; }
            set { _Data_comision = value; NotifyPropertyChanged(); }
        }

        protected Data_asignatura? _Data_asignatura = null;
        public Data_asignatura? Data_asignatura
        {
            get { return _Data_asignatura; }
            set { _Data_asignatura = value; NotifyPropertyChanged(); }
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

                case "horas_catedra":
                    if (_horas_catedra == null)
                        return "Debe completar valor.";
                    return "";

                case "ige":
                    return "";

                case "comision":
                    if (_comision == null)
                        return "Debe completar valor.";
                    return "";

                case "asignatura":
                    if (_asignatura == null)
                        return "Debe completar valor.";
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "descripcion_horario":
                    return "";

            }

            return "";
        }
    }
}
