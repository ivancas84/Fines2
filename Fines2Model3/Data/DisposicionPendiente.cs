#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class DisposicionPendiente : EntityData
    {

        public DisposicionPendiente()
        {
            _entityName = "disposicion_pendiente";
            _db = Context.db;
            Default();
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { if( _disposicion != value) { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); } }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { if( _alumno != value) { _alumno = value; NotifyPropertyChanged(nameof(alumno)); } }
        }
        protected string? _modo = null;
        public string? modo
        {
            get { return _modo; }
            set { if( _modo != value) { _modo = value; NotifyPropertyChanged(nameof(modo)); } }
        }
        //disposicion_pendiente.disposicion _m:o disposicion.id
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set {
                _disposicion_ = value;
                disposicion = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(disposicion_));
            }
        }

        //disposicion_pendiente.alumno _m:o alumno.id
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set {
                _alumno_ = value;
                alumno = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(alumno_));
            }
        }

    }
}
