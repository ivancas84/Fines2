#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Curso : EntityData
    {

        public Curso()
        {
            _entityName = "curso";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set { if( _horas_catedra != value) { _horas_catedra = value; NotifyPropertyChanged(nameof(horas_catedra)); } }
        }
        protected string? _ige = null;
        public string? ige
        {
            get { return _ige; }
            set { if( _ige != value) { _ige = value; NotifyPropertyChanged(nameof(ige)); } }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { if( _comision != value) { _comision = value; NotifyPropertyChanged(nameof(comision)); } }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        protected string? _descripcion_horario = null;
        public string? descripcion_horario
        {
            get { return _descripcion_horario; }
            set { if( _descripcion_horario != value) { _descripcion_horario = value; NotifyPropertyChanged(nameof(descripcion_horario)); } }
        }
        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set { if( _codigo != value) { _codigo = value; NotifyPropertyChanged(nameof(codigo)); } }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { if( _disposicion != value) { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); } }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set { if( _asignatura != value) { _asignatura = value; NotifyPropertyChanged(nameof(asignatura)); } }
        }
        //curso.comision _o:o comision.id
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

        //curso.disposicion _o:o disposicion.id
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

        //curso.asignatura _o:o asignatura.id
        protected Asignatura? _asignatura_ = null;
        public Asignatura? asignatura_
        {
            get { return _asignatura_; }
            set {
                _asignatura_ = value;
                asignatura = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(asignatura_));
            }
        }

        //calificacion.curso _m:o curso.id
        public ObservableCollection<Calificacion> Calificacion_ { get; set; } = new ();

        //horario.curso _m:o curso.id
        public ObservableCollection<Horario> Horario_ { get; set; } = new ();

        //toma.curso _m:o curso.id
        public ObservableCollection<Toma> Toma_ { get; set; } = new ();

    }
}
