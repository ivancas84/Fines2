#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class ComisionRelacionada : Entity
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
                        if (_comision_ != null)
                        {
                            _comision_!.EnableSynchronization = true;
                            if (!_comision_!.ComisionRelacionada_.Contains(this))
                                _comision_!.ComisionRelacionada_.Add(this);
                        }

                        if (_relacion_ != null)
                        {
                            _relacion_!.EnableSynchronization = true;
                            if (!_relacion_!.ComisionRelacionada_relacion_.Contains(this))
                                _relacion_!.ComisionRelacionada_relacion_.Add(this);
                        }

                    }
                }
            }
        }

        public ComisionRelacionada()
        {
            _entityName = "comision_relacionada";
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

        #region comision
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { if( _comision != value) { _comision = value; NotifyPropertyChanged(nameof(comision)); } }
        }
        #endregion

        #region relacion
        protected string? _relacion = null;
        public string? relacion
        {
            get { return _relacion; }
            set { if( _relacion != value) { _relacion = value; NotifyPropertyChanged(nameof(relacion)); } }
        }
        #endregion

        #region comision (fk comision_relacionada.comision _m:o comision.id)
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                if(  _comision_ != value )
                {
                    var old_comision = _comision;
                    _comision_ = value;

                    if( old_comision != null && EnableSynchronization)
                        _comision_!.ComisionRelacionada_.Remove(this);

                    if(value != null)
                    {
                        comision = value.id;
                        if(EnableSynchronization && !_comision_!.ComisionRelacionada_.Contains(this))
                        {
                            _comision_!.EnableSynchronization = true;
                            _comision_!.ComisionRelacionada_.Add(this);
                        }
                    }
                    else
                    {
                        comision = null;
                    }
                    NotifyPropertyChanged(nameof(comision_));
                }
            }
        }
        #endregion

        #region relacion (fk comision_relacionada.relacion _m:o comision.id)
        protected Comision? _relacion_ = null;
        public Comision? relacion_
        {
            get { return _relacion_; }
            set {
                if(  _relacion_ != value )
                {
                    var old_relacion = _relacion;
                    _relacion_ = value;

                    if( old_relacion != null && EnableSynchronization)
                        _relacion_!.ComisionRelacionada_relacion_.Remove(this);

                    if(value != null)
                    {
                        relacion = value.id;
                        if(EnableSynchronization && !_relacion_!.ComisionRelacionada_relacion_.Contains(this))
                        {
                            _relacion_!.EnableSynchronization = true;
                            _relacion_!.ComisionRelacionada_relacion_.Add(this);
                        }
                    }
                    else
                    {
                        relacion = null;
                    }
                    NotifyPropertyChanged(nameof(relacion_));
                }
            }
        }
        #endregion

    }
}
