#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class ComisionRelacionada : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "comision_relacionada";

        public override void Default()
        {
            EntityVal val = db!.Values("comision_relacionada");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { _comision = value; NotifyPropertyChanged(nameof(comision)); }
        }
        protected string? _relacion = null;
        public string? relacion
        {
            get { return _relacion; }
            set { _relacion = value; NotifyPropertyChanged(nameof(relacion)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "comision":
                    if (_comision == null)
                        return "Debe completar valor.";
                    return "";

                case "relacion":
                    if (_relacion == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set { _comision_ = value; NotifyPropertyChanged(nameof(comision_)); }
        }

        protected Comision? _relacion_ = null;
        public Comision? relacion_
        {
            get { return _relacion_; }
            set { _relacion_ = value; NotifyPropertyChanged(nameof(relacion_)); }
        }

    }
}
