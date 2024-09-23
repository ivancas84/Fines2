#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Email : EntityData
    {

        public Email()
        {
            _entityName = "email";
            _db = Context.db;
            Default();
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { if( _email != value) { _email = value; NotifyPropertyChanged(nameof(email)); } }
        }
        protected bool? _verificado = null;
        public bool? verificado
        {
            get { return _verificado; }
            set { if( _verificado != value) { _verificado = value; NotifyPropertyChanged(nameof(verificado)); } }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        protected DateTime? _eliminado = null;
        public DateTime? eliminado
        {
            get { return _eliminado; }
            set { if( _eliminado != value) { _eliminado = value; NotifyPropertyChanged(nameof(eliminado)); } }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { if( _persona != value) { _persona = value; NotifyPropertyChanged(nameof(persona)); } }
        }
        //email.persona _m:o persona.id
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                _persona_ = value;
                persona = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(persona_));
            }
        }

    }
}
