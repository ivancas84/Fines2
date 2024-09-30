#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Email : Entity
    {

        public override bool EnableSynchronization
        {
            get => _enableSynchronization;
            set
            {
                if(_enableSynchronization != value)
                {
                    _enableSynchronization = value;

                    if(_enableSynchronization)
                    {
                        if (_persona_ != null)
                        {
                            _persona_!.EnableSynchronization = true;
                            if (!_persona_!.Email_.Contains(this))
                                _persona_!.Email_.Add(this);
                        }

                    }
                }
            }
        }

        public Email()
        {
            _entityName = "email";
            _db = Context.db;
            Default();
        }

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region email
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { if( _email != value) { _email = value; NotifyPropertyChanged(nameof(email)); } }
        }
        #endregion

        #region verificado
        protected bool? _verificado = null;
        public bool? verificado
        {
            get { return _verificado; }
            set { if( _verificado != value) { _verificado = value; NotifyPropertyChanged(nameof(verificado)); } }
        }
        #endregion

        #region insertado
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        #endregion

        #region eliminado
        protected DateTime? _eliminado = null;
        public DateTime? eliminado
        {
            get { return _eliminado; }
            set { if( _eliminado != value) { _eliminado = value; NotifyPropertyChanged(nameof(eliminado)); } }
        }
        #endregion

        #region persona
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { if( _persona != value) { _persona = value; NotifyPropertyChanged(nameof(persona)); } }
        }
        #endregion

        #region persona (fk email.persona _m:o persona.id)
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                if(  _persona_ != value )
                {
                    var old_persona = _persona;
                    _persona_ = value;

                    if( old_persona != null && EnableSynchronization)
                        _persona_!.Email_.Remove(this);

                    if(value != null)
                    {
                        persona = value.id;
                        if(EnableSynchronization && !_persona_!.Email_.Contains(this))
                        {
                            _persona_!.EnableSynchronization = true;
                            _persona_!.Email_.Add(this);
                        }
                    }
                    else
                    {
                        persona = null;
                    }
                    NotifyPropertyChanged(nameof(persona_));
                }
            }
        }
        #endregion

    }
}
