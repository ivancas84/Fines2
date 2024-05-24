#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_alumno_comision : SqlOrganize.Data
    {

        public Data_alumno_comision ()
        {
        }

        public Data_alumno_comision(Db db)
        {
            this.db = db;
            Init();
        }

        protected void Init()
        {
            _id = (string?)db!.Values("alumno_comision").GetDefault("id");
            _creado = (DateTime?)db!.Values("alumno_comision").GetDefault("creado");
            _estado = (string?)db!.Values("alumno_comision").GetDefault("estado");
            _programafines = (bool?)db!.Values("alumno_comision").GetDefault("programafines");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { _creado = value; NotifyPropertyChanged(nameof(creado)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { _comision = value; NotifyPropertyChanged(nameof(comision)); }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { _alumno = value; NotifyPropertyChanged(nameof(alumno)); }
        }
        protected string? _estado = null;
        public string? estado
        {
            get { return _estado; }
            set { _estado = value; NotifyPropertyChanged(nameof(estado)); }
        }
        protected bool? _programafines = null;
        public bool? programafines
        {
            get { return _programafines; }
            set { _programafines = value; NotifyPropertyChanged(nameof(programafines)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "creado":
                    if (_creado == null)
                        return "Debe completar valor.";
                    return "";

                case "observaciones":
                    return "";

                case "comision":
                    return "";

                case "alumno":
                    if (_alumno == null)
                        return "Debe completar valor.";
                    return "";

                case "estado":
                    return "";

                case "programafines":
                    if (_programafines == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
