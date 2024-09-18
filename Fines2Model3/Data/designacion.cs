#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Designacion : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "designacion";

        public override void Default()
        {
            EntityVal val = db!.Values("designacion");
            _id = (string?)val.GetDefault("id");
            _alta = (DateTime?)val.GetDefault("alta");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected DateTime? _desde = null;
        public DateTime? desde
        {
            get { return _desde; }
            set { _desde = value; NotifyPropertyChanged(nameof(desde)); }
        }
        protected DateTime? _hasta = null;
        public DateTime? hasta
        {
            get { return _hasta; }
            set { _hasta = value; NotifyPropertyChanged(nameof(hasta)); }
        }
        protected string? _cargo = null;
        public string? cargo
        {
            get { return _cargo; }
            set { _cargo = value; NotifyPropertyChanged(nameof(cargo)); }
        }
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { _sede = value; NotifyPropertyChanged(nameof(sede)); }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(nameof(persona)); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(nameof(alta)); }
        }
        protected string? _pfid = null;
        public string? pfid
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

                case "desde":
                    return "";

                case "hasta":
                    return "";

                case "cargo":
                    if (_cargo == null)
                        return "Debe completar valor.";
                    return "";

                case "sede":
                    if (_sede == null)
                        return "Debe completar valor.";
                    return "";

                case "persona":
                    if (_persona == null)
                        return "Debe completar valor.";
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
        //designacion.cargo _o:o cargo.id
        protected Cargo? _cargo_ = null;
        public Cargo? cargo_
        {
            get { return _cargo_; }
            set { _cargo_ = value; NotifyPropertyChanged(nameof(cargo_)); }
        }

        //designacion.sede _o:o sede.id
        protected Sede? _sede_ = null;
        public Sede? sede_
        {
            get { return _sede_; }
            set { _sede_ = value; NotifyPropertyChanged(nameof(sede_)); }
        }

        //designacion.persona _o:o persona.id
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set { _persona_ = value; NotifyPropertyChanged(nameof(persona_)); }
        }

    }
}
