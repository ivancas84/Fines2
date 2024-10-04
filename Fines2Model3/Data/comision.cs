#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Comision : Entity
    {

        public Comision()
        {
            _entityName = "comision";
            _db = Context.db;
            Default();
            AlumnoComision_.CollectionChanged += AlumnoComision_CollectionChanged;
            ComisionRelacionada_.CollectionChanged += ComisionRelacionada_CollectionChanged;
            ComisionRelacionada_relacion_.CollectionChanged += ComisionRelacionada_relacion_CollectionChanged;
            Curso_.CollectionChanged += Curso_CollectionChanged;
        }

        #region CollectionChanged
        private void AlumnoComision_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (AlumnoComision obj in e.NewItems)
                    if(obj.comision_ != this)
                        obj.comision_ = this;
        }
        private void ComisionRelacionada_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (ComisionRelacionada obj in e.NewItems)
                    if(obj.comision_ != this)
                        obj.comision_ = this;
        }
        private void ComisionRelacionada_relacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (ComisionRelacionada obj in e.NewItems)
                    if(obj.relacion_ != this)
                        obj.relacion_ = this;
        }
        private void Curso_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Curso obj in e.NewItems)
                    if(obj.comision_ != this)
                        obj.comision_ = this;
        }
        #endregion

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region turno
        protected string? _turno = null;
        public string? turno
        {
            get { return _turno; }
            set { if( _turno != value) { _turno = value; NotifyPropertyChanged(nameof(turno)); } }
        }
        #endregion

        #region division
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { if( _division != value) { _division = value; NotifyPropertyChanged(nameof(division)); } }
        }
        #endregion

        #region comentario
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { if( _comentario != value) { _comentario = value; NotifyPropertyChanged(nameof(comentario)); } }
        }
        #endregion

        #region autorizada
        protected bool? _autorizada = null;
        public bool? autorizada
        {
            get { return _autorizada; }
            set { if( _autorizada != value) { _autorizada = value; NotifyPropertyChanged(nameof(autorizada)); } }
        }
        #endregion

        #region apertura
        protected bool? _apertura = null;
        public bool? apertura
        {
            get { return _apertura; }
            set { if( _apertura != value) { _apertura = value; NotifyPropertyChanged(nameof(apertura)); } }
        }
        #endregion

        #region publicada
        protected bool? _publicada = null;
        public bool? publicada
        {
            get { return _publicada; }
            set { if( _publicada != value) { _publicada = value; NotifyPropertyChanged(nameof(publicada)); } }
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

        #region alta
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
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

        #region modalidad
        protected string? _modalidad = null;
        public string? modalidad
        {
            get { return _modalidad; }
            set { if( _modalidad != value) { _modalidad = value; NotifyPropertyChanged(nameof(modalidad)); } }
        }
        #endregion

        #region planificacion
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set { if( _planificacion != value) { _planificacion = value; NotifyPropertyChanged(nameof(planificacion)); } }
        }
        #endregion

        #region comision_siguiente
        protected string? _comision_siguiente = null;
        public string? comision_siguiente
        {
            get { return _comision_siguiente; }
            set { if( _comision_siguiente != value) { _comision_siguiente = value; NotifyPropertyChanged(nameof(comision_siguiente)); } }
        }
        #endregion

        #region calendario
        protected string? _calendario = null;
        public string? calendario
        {
            get { return _calendario; }
            set { if( _calendario != value) { _calendario = value; NotifyPropertyChanged(nameof(calendario)); } }
        }
        #endregion

        #region identificacion
        protected string? _identificacion = null;
        public string? identificacion
        {
            get { return _identificacion; }
            set { if( _identificacion != value) { _identificacion = value; NotifyPropertyChanged(nameof(identificacion)); } }
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

        #region configuracion
        protected string? _configuracion = null;
        public string? configuracion
        {
            get { return _configuracion; }
            set { if( _configuracion != value) { _configuracion = value; NotifyPropertyChanged(nameof(configuracion)); } }
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

        #region sede (fk comision.sede _m:o sede.id)
        protected Sede? _sede_ = null;
        public Sede? sede_
        {
            get { return _sede_; }
            set {
                if ( _sede_ != value)
                {
                    _sede_ = value;
                    if(value != null)
                        sede = value.id;
                    else
                        sede = null;
                    NotifyPropertyChanged(nameof(sede_));
                }
            }
        }
        #endregion

        #region modalidad (fk comision.modalidad _m:o modalidad.id)
        protected Modalidad? _modalidad_ = null;
        public Modalidad? modalidad_
        {
            get { return _modalidad_; }
            set {
                if ( _modalidad_ != value)
                {
                    _modalidad_ = value;
                    if(value != null)
                        modalidad = value.id;
                    else
                        modalidad = null;
                    NotifyPropertyChanged(nameof(modalidad_));
                }
            }
        }
        #endregion

        #region planificacion (fk comision.planificacion _m:o planificacion.id)
        protected Planificacion? _planificacion_ = null;
        public Planificacion? planificacion_
        {
            get { return _planificacion_; }
            set {
                if ( _planificacion_ != value)
                {
                    _planificacion_ = value;
                    if(value != null)
                        planificacion = value.id;
                    else
                        planificacion = null;
                    NotifyPropertyChanged(nameof(planificacion_));
                }
            }
        }
        #endregion

        #region calendario (fk comision.calendario _m:o calendario.id)
        protected Calendario? _calendario_ = null;
        public Calendario? calendario_
        {
            get { return _calendario_; }
            set {
                if ( _calendario_ != value)
                {
                    _calendario_ = value;
                    if(value != null)
                        calendario = value.id;
                    else
                        calendario = null;
                    NotifyPropertyChanged(nameof(calendario_));
                }
            }
        }
        #endregion

        #region AlumnoComision_ (ref alumno_comision.comision _m:o comision.id)
        protected ObservableCollection<AlumnoComision> _AlumnoComision_ = new ();
        public ObservableCollection<AlumnoComision> AlumnoComision_
        {
            get { return _AlumnoComision_; }
            set { if( _AlumnoComision_ != value) { _AlumnoComision_ = value; NotifyPropertyChanged(nameof(AlumnoComision_)); } }
        }
        #endregion

        #region ComisionRelacionada_ (ref comision_relacionada.comision _m:o comision.id)
        protected ObservableCollection<ComisionRelacionada> _ComisionRelacionada_ = new ();
        public ObservableCollection<ComisionRelacionada> ComisionRelacionada_
        {
            get { return _ComisionRelacionada_; }
            set { if( _ComisionRelacionada_ != value) { _ComisionRelacionada_ = value; NotifyPropertyChanged(nameof(ComisionRelacionada_)); } }
        }
        #endregion

        #region ComisionRelacionada_relacion_ (ref comision_relacionada.relacion _m:o comision.id)
        protected ObservableCollection<ComisionRelacionada> _ComisionRelacionada_relacion_ = new ();
        public ObservableCollection<ComisionRelacionada> ComisionRelacionada_relacion_
        {
            get { return _ComisionRelacionada_relacion_; }
            set { if( _ComisionRelacionada_relacion_ != value) { _ComisionRelacionada_relacion_ = value; NotifyPropertyChanged(nameof(ComisionRelacionada_relacion_)); } }
        }
        #endregion

        #region Curso_ (ref curso.comision _m:o comision.id)
        protected ObservableCollection<Curso> _Curso_ = new ();
        public ObservableCollection<Curso> Curso_
        {
            get { return _Curso_; }
            set { if( _Curso_ != value) { _Curso_ = value; NotifyPropertyChanged(nameof(Curso_)); } }
        }
        #endregion

    }
}
