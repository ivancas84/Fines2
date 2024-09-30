#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Designacion : Entity
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
                        if (_cargo_ != null)
                        {
                            _cargo_!.EnableSynchronization = true;
                            if (!_cargo_!.Designacion_.Contains(this))
                                _cargo_!.Designacion_.Add(this);
                        }

                        if (_sede_ != null)
                        {
                            _sede_!.EnableSynchronization = true;
                            if (!_sede_!.Designacion_.Contains(this))
                                _sede_!.Designacion_.Add(this);
                        }

                        if (_persona_ != null)
                        {
                            _persona_!.EnableSynchronization = true;
                            if (!_persona_!.Designacion_.Contains(this))
                                _persona_!.Designacion_.Add(this);
                        }

                    }
                }
            }
        }

        public Designacion()
        {
            _entityName = "designacion";
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

        #region desde
        protected DateTime? _desde = null;
        public DateTime? desde
        {
            get { return _desde; }
            set { if( _desde != value) { _desde = value; NotifyPropertyChanged(nameof(desde)); } }
        }
        #endregion

        #region hasta
        protected DateTime? _hasta = null;
        public DateTime? hasta
        {
            get { return _hasta; }
            set { if( _hasta != value) { _hasta = value; NotifyPropertyChanged(nameof(hasta)); } }
        }
        #endregion

        #region cargo
        protected string? _cargo = null;
        public string? cargo
        {
            get { return _cargo; }
            set { if( _cargo != value) { _cargo = value; NotifyPropertyChanged(nameof(cargo)); } }
        }
        #endregion

        #region sede
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { if( _sede != value) { _sede = value; NotifyPropertyChanged(nameof(sede)); } }
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

        #region alta
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        #endregion

        #region pfid
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        #endregion

        #region cargo (fk designacion.cargo _m:o cargo.id)
        protected Cargo? _cargo_ = null;
        public Cargo? cargo_
        {
            get { return _cargo_; }
            set {
                if(  _cargo_ != value )
                {
                    var old_cargo = _cargo;
                    _cargo_ = value;

                    if( old_cargo != null && EnableSynchronization)
                        _cargo_!.Designacion_.Remove(this);

                    if(value != null)
                    {
                        cargo = value.id;
                        if(EnableSynchronization && !_cargo_!.Designacion_.Contains(this))
                        {
                            _cargo_!.EnableSynchronization = true;
                            _cargo_!.Designacion_.Add(this);
                        }
                    }
                    else
                    {
                        cargo = null;
                    }
                    NotifyPropertyChanged(nameof(cargo_));
                }
            }
        }
        #endregion

        #region sede (fk designacion.sede _m:o sede.id)
        protected Sede? _sede_ = null;
        public Sede? sede_
        {
            get { return _sede_; }
            set {
                if(  _sede_ != value )
                {
                    var old_sede = _sede;
                    _sede_ = value;

                    if( old_sede != null && EnableSynchronization)
                        _sede_!.Designacion_.Remove(this);

                    if(value != null)
                    {
                        sede = value.id;
                        if(EnableSynchronization && !_sede_!.Designacion_.Contains(this))
                        {
                            _sede_!.EnableSynchronization = true;
                            _sede_!.Designacion_.Add(this);
                        }
                    }
                    else
                    {
                        sede = null;
                    }
                    NotifyPropertyChanged(nameof(sede_));
                }
            }
        }
        #endregion

        #region persona (fk designacion.persona _m:o persona.id)
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
                        _persona_!.Designacion_.Remove(this);

                    if(value != null)
                    {
                        persona = value.id;
                        if(EnableSynchronization && !_persona_!.Designacion_.Contains(this))
                        {
                            _persona_!.EnableSynchronization = true;
                            _persona_!.Designacion_.Add(this);
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
