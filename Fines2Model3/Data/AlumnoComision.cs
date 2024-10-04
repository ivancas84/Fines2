#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AlumnoComision : Entity
    {

        public AlumnoComision()
        {
            _entityName = "alumno_comision";
            _db = Context.db;
            Default();
        }

        #region CollectionChanged
        #endregion

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region creado
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { if( _creado != value) { _creado = value; NotifyPropertyChanged(nameof(creado)); } }
        }
        #endregion

        #region activo
        protected bool? _activo = null;
        public bool? activo
        {
            get { return _activo; }
            set { if( _activo != value) { _activo = value; NotifyPropertyChanged(nameof(activo)); } }
        }
        #endregion

        #region observaciones
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
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

        #region alumno
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { if( _alumno != value) { _alumno = value; NotifyPropertyChanged(nameof(alumno)); } }
        }
        #endregion

        #region estado
        protected string? _estado = null;
        public string? estado
        {
            get { return _estado; }
            set { if( _estado != value) { _estado = value; NotifyPropertyChanged(nameof(estado)); } }
        }
        #endregion

        #region pfid
        protected uint? _pfid = null;
        public uint? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        #endregion

        #region comision (fk alumno_comision.comision _m:o comision.id)
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                if ( _comision_ != value)
                {
                    _comision_ = value;
                    if(value != null)
                        comision = value.id;
                    else
                        comision = null;
                    NotifyPropertyChanged(nameof(comision_));
                }
            }
        }
        #endregion

        #region alumno (fk alumno_comision.alumno _m:o alumno.id)
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set {
                if ( _alumno_ != value)
                {
                    _alumno_ = value;
                    if(value != null)
                        alumno = value.id;
                    else
                        alumno = null;
                    NotifyPropertyChanged(nameof(alumno_));
                }
            }
        }
        #endregion

    }
}
