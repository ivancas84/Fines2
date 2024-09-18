#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Email : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "email";

        public override void Default()
        {
            EntityVal val = db!.Values("email");
            _id = (string?)val.GetDefault("id");
            _verificado = (bool?)val.GetDefault("verificado");
            _insertado = (DateTime?)val.GetDefault("insertado");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { _email = value; NotifyPropertyChanged(nameof(email)); }
        }
        protected bool? _verificado = null;
        public bool? verificado
        {
            get { return _verificado; }
            set { _verificado = value; NotifyPropertyChanged(nameof(verificado)); }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(nameof(insertado)); }
        }
        protected DateTime? _eliminado = null;
        public DateTime? eliminado
        {
            get { return _eliminado; }
            set { _eliminado = value; NotifyPropertyChanged(nameof(eliminado)); }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(nameof(persona)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "email":
                    if (_email == null)
                        return "Debe completar valor.";
                    return "";

                case "verificado":
                    if (_verificado == null)
                        return "Debe completar valor.";
                    return "";

                case "insertado":
                    if (_insertado == null)
                        return "Debe completar valor.";
                    return "";

                case "eliminado":
                    return "";

                case "persona":
                    if (_persona == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
        //email.persona _o:o persona.id
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set { _persona_ = value; NotifyPropertyChanged(nameof(persona_)); }
        }

    }
}
