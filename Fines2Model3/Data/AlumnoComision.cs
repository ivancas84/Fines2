#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AlumnoComision : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "alumno_comision";

        public override void Default()
        {
            EntityVal val = db!.Values("alumno_comision");
            _id = (string?)val.GetDefault("id");
            _creado = (DateTime?)val.GetDefault("creado");
            _estado = (string?)val.GetDefault("estado");
        }


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
        //alumno_comision.comision _o:o comision.id
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set { _comision_ = value; NotifyPropertyChanged(nameof(comision_)); }
        }

        //alumno_comision.alumno _o:o alumno.id
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set { _alumno_ = value; NotifyPropertyChanged(nameof(alumno_)); }
        }

    }
}
