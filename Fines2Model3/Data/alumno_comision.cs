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

        public Data_alumno_comision(Db db, bool init = true)
        {
            this.db = db;
            if(init)
                Init();
        }

        protected void Init()
        {
            EntityValues val = db!.Values("alumno_comision");
            _id = (string?)val.GetDefault("id");
            _creado = (DateTime?)val.GetDefault("creado");
            _estado = (string?)val.GetDefault("estado");
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
        protected uint? _pfid = null;
        public uint? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(nameof(pfid)); }
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

                case "pfid":
                    return "";

            }

            return "";
        }
    }
}
