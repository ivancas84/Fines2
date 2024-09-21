#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Comision : EntityData
    {

        public Comision()
        {
            _entityName = "comision";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _turno = null;
        public string? turno
        {
            get { return _turno; }
            set { if( _turno != value) { _turno = value; NotifyPropertyChanged(nameof(turno)); } }
        }
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { if( _division != value) { _division = value; NotifyPropertyChanged(nameof(division)); } }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { if( _comentario != value) { _comentario = value; NotifyPropertyChanged(nameof(comentario)); } }
        }
        protected bool? _autorizada = null;
        public bool? autorizada
        {
            get { return _autorizada; }
            set { if( _autorizada != value) { _autorizada = value; NotifyPropertyChanged(nameof(autorizada)); } }
        }
        protected bool? _apertura = null;
        public bool? apertura
        {
            get { return _apertura; }
            set { if( _apertura != value) { _apertura = value; NotifyPropertyChanged(nameof(apertura)); } }
        }
        protected bool? _publicada = null;
        public bool? publicada
        {
            get { return _publicada; }
            set { if( _publicada != value) { _publicada = value; NotifyPropertyChanged(nameof(publicada)); } }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { if( _sede != value) { _sede = value; NotifyPropertyChanged(nameof(sede)); } }
        }
        protected string? _modalidad = null;
        public string? modalidad
        {
            get { return _modalidad; }
            set { if( _modalidad != value) { _modalidad = value; NotifyPropertyChanged(nameof(modalidad)); } }
        }
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set { if( _planificacion != value) { _planificacion = value; NotifyPropertyChanged(nameof(planificacion)); } }
        }
        protected string? _comision_siguiente = null;
        public string? comision_siguiente
        {
            get { return _comision_siguiente; }
            set { if( _comision_siguiente != value) { _comision_siguiente = value; NotifyPropertyChanged(nameof(comision_siguiente)); } }
        }
        protected string? _calendario = null;
        public string? calendario
        {
            get { return _calendario; }
            set { if( _calendario != value) { _calendario = value; NotifyPropertyChanged(nameof(calendario)); } }
        }
        protected string? _identificacion = null;
        public string? identificacion
        {
            get { return _identificacion; }
            set { if( _identificacion != value) { _identificacion = value; NotifyPropertyChanged(nameof(identificacion)); } }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        //comision.sede _o:o sede.id
        protected Sede? _sede_ = null;
        public Sede? sede_
        {
            get { return _sede_; }
            set {
                _sede_ = value;
                sede = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(sede_));
            }
        }

        //comision.modalidad _o:o modalidad.id
        protected Modalidad? _modalidad_ = null;
        public Modalidad? modalidad_
        {
            get { return _modalidad_; }
            set {
                _modalidad_ = value;
                modalidad = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(modalidad_));
            }
        }

        //comision.planificacion _o:o planificacion.id
        protected Planificacion? _planificacion_ = null;
        public Planificacion? planificacion_
        {
            get { return _planificacion_; }
            set {
                _planificacion_ = value;
                planificacion = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(planificacion_));
            }
        }

        //comision.calendario _o:o calendario.id
        protected Calendario? _calendario_ = null;
        public Calendario? calendario_
        {
            get { return _calendario_; }
            set {
                _calendario_ = value;
                calendario = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(calendario_));
            }
        }

        //alumno_comision.comision _m:o comision.id
        public ObservableCollection<AlumnoComision> AlumnoComision_ { get; set; } = new ();

        //comision_relacionada.comision _m:o comision.id
        public ObservableCollection<ComisionRelacionada> ComisionRelacionada_ { get; set; } = new ();

        //comision_relacionada.relacion _m:o comision.id
        public ObservableCollection<ComisionRelacionada> ComisionRelacionada_relacion_ { get; set; } = new ();

        //curso.comision _m:o comision.id
        public ObservableCollection<Curso> Curso_ { get; set; } = new ();

    }
}
