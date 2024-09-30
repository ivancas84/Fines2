#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class DisposicionPendiente : Entity
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
                        if (_disposicion_ != null)
                        {
                            _disposicion_!.EnableSynchronization = true;
                            if (!_disposicion_!.DisposicionPendiente_.Contains(this))
                                _disposicion_!.DisposicionPendiente_.Add(this);
                        }

                        if (_alumno_ != null)
                        {
                            _alumno_!.EnableSynchronization = true;
                            if (!_alumno_!.DisposicionPendiente_.Contains(this))
                                _alumno_!.DisposicionPendiente_.Add(this);
                        }

                    }
                }
            }
        }

        public DisposicionPendiente()
        {
            _entityName = "disposicion_pendiente";
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

        #region disposicion
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { if( _disposicion != value) { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); } }
        }
        #endregion

        #region alumno
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { if( _alumno != value) { _alumno = value; NotifyPropertyChanged(nameof(alumno)); } }
        }
        #endregion

        #region modo
        protected string? _modo = null;
        public string? modo
        {
            get { return _modo; }
            set { if( _modo != value) { _modo = value; NotifyPropertyChanged(nameof(modo)); } }
        }
        #endregion

        #region disposicion (fk disposicion_pendiente.disposicion _m:o disposicion.id)
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set {
                if(  _disposicion_ != value )
                {
                    var old_disposicion = _disposicion;
                    _disposicion_ = value;

                    if( old_disposicion != null && EnableSynchronization)
                        _disposicion_!.DisposicionPendiente_.Remove(this);

                    if(value != null)
                    {
                        disposicion = value.id;
                        if(EnableSynchronization && !_disposicion_!.DisposicionPendiente_.Contains(this))
                        {
                            _disposicion_!.EnableSynchronization = true;
                            _disposicion_!.DisposicionPendiente_.Add(this);
                        }
                    }
                    else
                    {
                        disposicion = null;
                    }
                    NotifyPropertyChanged(nameof(disposicion_));
                }
            }
        }
        #endregion

        #region alumno (fk disposicion_pendiente.alumno _m:o alumno.id)
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set {
                if(  _alumno_ != value )
                {
                    var old_alumno = _alumno;
                    _alumno_ = value;

                    if( old_alumno != null && EnableSynchronization)
                        _alumno_!.DisposicionPendiente_.Remove(this);

                    if(value != null)
                    {
                        alumno = value.id;
                        if(EnableSynchronization && !_alumno_!.DisposicionPendiente_.Contains(this))
                        {
                            _alumno_!.EnableSynchronization = true;
                            _alumno_!.DisposicionPendiente_.Add(this);
                        }
                    }
                    else
                    {
                        alumno = null;
                    }
                    NotifyPropertyChanged(nameof(alumno_));
                }
            }
        }
        #endregion

    }
}
