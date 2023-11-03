using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Model
{
    public class Data_calificacion : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_calificacion ()
        {
            Initialize();
        }

        public Data_calificacion(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("calificacion").Default("id").Get("id");
                    _archivado = (bool?)ContainerApp.db.Values("calificacion").Default("archivado").Get("archivado");
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
        protected decimal? _nota1 = null;
        public decimal? nota1
        {
            get { return _nota1; }
            set { _nota1 = value; NotifyPropertyChanged(); }
        }
        protected decimal? _nota2 = null;
        public decimal? nota2
        {
            get { return _nota2; }
            set { _nota2 = value; NotifyPropertyChanged(); }
        }
        protected decimal? _nota3 = null;
        public decimal? nota3
        {
            get { return _nota3; }
            set { _nota3 = value; NotifyPropertyChanged(); }
        }
        protected decimal? _nota_final = null;
        public decimal? nota_final
        {
            get { return _nota_final; }
            set { _nota_final = value; NotifyPropertyChanged(); }
        }
        protected decimal? _crec = null;
        public decimal? crec
        {
            get { return _crec; }
            set { _crec = value; NotifyPropertyChanged(); }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { _curso = value; NotifyPropertyChanged(); }
        }
        protected int? _porcentaje_asistencia = null;
        public int? porcentaje_asistencia
        {
            get { return _porcentaje_asistencia; }
            set { _porcentaje_asistencia = value; NotifyPropertyChanged(); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { _division = value; NotifyPropertyChanged(); }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { _alumno = value; NotifyPropertyChanged(); }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { _disposicion = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { _fecha = value; NotifyPropertyChanged(); }
        }
        protected bool? _archivado = null;
        public bool? archivado
        {
            get { return _archivado; }
            set { _archivado = value; NotifyPropertyChanged(); }
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

                case "nota1":
                    return "";

                case "nota2":
                    return "";

                case "nota3":
                    return "";

                case "nota_final":
                    return "";

                case "crec":
                    return "";

                case "curso":
                    return "";

                case "porcentaje_asistencia":
                    return "";

                case "observaciones":
                    return "";

                case "division":
                    return "";

                case "alumno":
                    if (_alumno == null)
                        return "Debe completar valor.";
                    return "";

                case "disposicion":
                    if (_disposicion == null)
                        return "Debe completar valor.";
                    return "";

                case "fecha":
                    return "";

                case "archivado":
                    if (_archivado == null)
                        return "Debe completar valor.";
                    return "";

            }
        }
    }
}
