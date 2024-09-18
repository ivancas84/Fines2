#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class DisposicionPendiente : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "disposicion_pendiente";

        public override void Default()
        {
            EntityVal val = db!.Values("disposicion_pendiente");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { _alumno = value; NotifyPropertyChanged(nameof(alumno)); }
        }
        protected string? _modo = null;
        public string? modo
        {
            get { return _modo; }
            set { _modo = value; NotifyPropertyChanged(nameof(modo)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "disposicion":
                    if (_disposicion == null)
                        return "Debe completar valor.";
                    return "";

                case "alumno":
                    if (_alumno == null)
                        return "Debe completar valor.";
                    return "";

                case "modo":
                    return "";

            }

            return "";
        }
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set { _disposicion_ = value; NotifyPropertyChanged(nameof(disposicion_)); }
        }

        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set { _alumno_ = value; NotifyPropertyChanged(nameof(alumno_)); }
        }

    }
}
