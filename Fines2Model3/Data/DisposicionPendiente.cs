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
                if( _disposicion_ != null && AutoAddToCollection)
                    _disposicion_!.DisposicionPendiente_.Remove(this);

                _disposicion_ = value;

                if(value != null)
                {
                    disposicion = value.id;
                    if(AutoAddToCollection && !_disposicion_!.DisposicionPendiente_.Contains(this))
                        _disposicion_!.DisposicionPendiente_.Add(this);
                }
                else
                {
                    disposicion = null;
                }
                NotifyPropertyChanged(nameof(disposicion_));
            }
        }
        #endregion

        #region alumno (fk disposicion_pendiente.alumno _m:o alumno.id)
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set {
                if( _alumno_ != null && AutoAddToCollection)
                    _alumno_!.DisposicionPendiente_.Remove(this);

                _alumno_ = value;

                if(value != null)
                {
                    alumno = value.id;
                    if(AutoAddToCollection && !_alumno_!.DisposicionPendiente_.Contains(this))
                        _alumno_!.DisposicionPendiente_.Add(this);
                }
                else
                {
                    alumno = null;
                }
                NotifyPropertyChanged(nameof(alumno_));
            }
        }
        #endregion

    }
}
