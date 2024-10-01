#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Toma : Entity
    {

        public Toma()
        {
            _entityName = "toma";
            _db = Context.db;
            Default();
            AsignacionPlanillaDocente_.CollectionChanged += AsignacionPlanillaDocente_CollectionChanged;
        }

        #region CollectionChanged
        private void AsignacionPlanillaDocente_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (AsignacionPlanillaDocente obj in e.NewItems)
                    if(obj.toma_ != this)
                        obj.toma_ = this;
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

        #region fecha_toma
        protected DateTime? _fecha_toma = null;
        public DateTime? fecha_toma
        {
            get { return _fecha_toma; }
            set { if( _fecha_toma != value) { _fecha_toma = value; NotifyPropertyChanged(nameof(fecha_toma)); } }
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

        #region observaciones
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
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

        #region tipo_movimiento
        protected string? _tipo_movimiento = null;
        public string? tipo_movimiento
        {
            get { return _tipo_movimiento; }
            set { if( _tipo_movimiento != value) { _tipo_movimiento = value; NotifyPropertyChanged(nameof(tipo_movimiento)); } }
        }
        #endregion

        #region estado_contralor
        protected string? _estado_contralor = null;
        public string? estado_contralor
        {
            get { return _estado_contralor; }
            set { if( _estado_contralor != value) { _estado_contralor = value; NotifyPropertyChanged(nameof(estado_contralor)); } }
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

        #region curso
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { if( _curso != value) { _curso = value; NotifyPropertyChanged(nameof(curso)); } }
        }
        #endregion

        #region docente
        protected string? _docente = null;
        public string? docente
        {
            get { return _docente; }
            set { if( _docente != value) { _docente = value; NotifyPropertyChanged(nameof(docente)); } }
        }
        #endregion

        #region reemplazo
        protected string? _reemplazo = null;
        public string? reemplazo
        {
            get { return _reemplazo; }
            set { if( _reemplazo != value) { _reemplazo = value; NotifyPropertyChanged(nameof(reemplazo)); } }
        }
        #endregion

        #region planilla_docente
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { if( _planilla_docente != value) { _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente)); } }
        }
        #endregion

        #region calificacion
        protected bool? _calificacion = null;
        public bool? calificacion
        {
            get { return _calificacion; }
            set { if( _calificacion != value) { _calificacion = value; NotifyPropertyChanged(nameof(calificacion)); } }
        }
        #endregion

        #region temas_tratados
        protected bool? _temas_tratados = null;
        public bool? temas_tratados
        {
            get { return _temas_tratados; }
            set { if( _temas_tratados != value) { _temas_tratados = value; NotifyPropertyChanged(nameof(temas_tratados)); } }
        }
        #endregion

        #region asistencia
        protected bool? _asistencia = null;
        public bool? asistencia
        {
            get { return _asistencia; }
            set { if( _asistencia != value) { _asistencia = value; NotifyPropertyChanged(nameof(asistencia)); } }
        }
        #endregion

        #region sin_planillas
        protected bool? _sin_planillas = null;
        public bool? sin_planillas
        {
            get { return _sin_planillas; }
            set { if( _sin_planillas != value) { _sin_planillas = value; NotifyPropertyChanged(nameof(sin_planillas)); } }
        }
        #endregion

        #region confirmada
        protected bool? _confirmada = null;
        public bool? confirmada
        {
            get { return _confirmada; }
            set { if( _confirmada != value) { _confirmada = value; NotifyPropertyChanged(nameof(confirmada)); } }
        }
        #endregion

        #region curso (fk toma.curso _m:o curso.id)
        protected Curso? _curso_ = null;
        public Curso? curso_
        {
            get { return _curso_; }
            set {
                if ( _curso_ != value)
                {
                    _curso_ = value;
                    if(value != null)
                        curso = value.id;


                    else
                        curso = null;
                    NotifyPropertyChanged(nameof(curso_));
                }
            }
        }
        #endregion

        #region docente (fk toma.docente _m:o persona.id)
        protected Persona? _docente_ = null;
        public Persona? docente_
        {
            get { return _docente_; }
            set {
                if ( _docente_ != value)
                {
                    _docente_ = value;
                    if(value != null)
                        docente = value.id;
                    else
                        docente = null;
                    NotifyPropertyChanged(nameof(docente_));
                }
            }
        }
        #endregion

        #region reemplazo (fk toma.reemplazo _m:o persona.id)
        protected Persona? _reemplazo_ = null;
        public Persona? reemplazo_
        {
            get { return _reemplazo_; }
            set {
                if ( _reemplazo_ != value)
                {
                    _reemplazo_ = value;
                    if(value != null)
                        reemplazo = value.id;
                    else
                        reemplazo = null;
                    NotifyPropertyChanged(nameof(reemplazo_));
                }
            }
        }
        #endregion

        #region planilla_docente (fk toma.planilla_docente _m:o planilla_docente.id)
        protected PlanillaDocente? _planilla_docente_ = null;
        public PlanillaDocente? planilla_docente_
        {
            get { return _planilla_docente_; }
            set {
                if ( _planilla_docente_ != value)
                {
                    _planilla_docente_ = value;
                    if(value != null)
                        planilla_docente = value.id;
                    else
                        planilla_docente = null;
                    NotifyPropertyChanged(nameof(planilla_docente_));
                }
            }
        }
        #endregion

        #region AsignacionPlanillaDocente_ (ref asignacion_planilla_docente.toma _m:o toma.id)
        public ObservableCollection<AsignacionPlanillaDocente> AsignacionPlanillaDocente_ { get; set; } = new ();
        #endregion

    }
}
