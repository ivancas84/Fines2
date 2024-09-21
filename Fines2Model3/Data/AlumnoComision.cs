#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AlumnoComision : EntityData
    {

        public AlumnoComision()
        {
            _entityName = "alumno_comision";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { if( _creado != value) { _creado = value; NotifyPropertyChanged(nameof(creado)); } }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { if( _comision != value) { _comision = value; NotifyPropertyChanged(nameof(comision)); } }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { if( _alumno != value) { _alumno = value; NotifyPropertyChanged(nameof(alumno)); } }
        }
        protected string? _estado = null;
        public string? estado
        {
            get { return _estado; }
            set { if( _estado != value) { _estado = value; NotifyPropertyChanged(nameof(estado)); } }
        }
        protected uint? _pfid = null;
        public uint? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        //alumno_comision.comision _o:o comision.id
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                _comision_ = value;
                comision = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(comision_));
            }
        }

        //alumno_comision.alumno _o:o alumno.id
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
