#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Telefono : Entity
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
                            if (!_persona_!.Telefono_.Contains(this))
                                _persona_!.Telefono_.Add(this);
                        }

                    }
                }
            }
        }

        public Telefono()
        {
            _entityName = "telefono";
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

        #region tipo
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { if( _tipo != value) { _tipo = value; NotifyPropertyChanged(nameof(tipo)); } }
        }
        #endregion

        #region prefijo
        protected string? _prefijo = null;
        public string? prefijo
        {
            get { return _prefijo; }
            set { if( _prefijo != value) { _prefijo = value; NotifyPropertyChanged(nameof(prefijo)); } }
        }
        #endregion

        #region numero
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
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

        #region persona (fk telefono.persona _m:o persona.id)
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
                        _persona_!.Telefono_.Remove(this);

                    if(value != null)
                    {
                        persona = value.id;
                        if(EnableSynchronization && !_persona_!.Telefono_.Contains(this))
                        {
                            _persona_!.EnableSynchronization = true;
                            _persona_!.Telefono_.Add(this);
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
