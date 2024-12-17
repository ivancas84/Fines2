#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;
using Dapper;
using System.Data;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Disposicion : Entity
    {

        public Disposicion()
        {
            _entityName = "disposicion";
            _db = Context.db;
            Default();
            Calificacion_.CollectionChanged += Calificacion_CollectionChanged;
            Curso_.CollectionChanged += Curso_CollectionChanged;
            DisposicionPendiente_.CollectionChanged += DisposicionPendiente_CollectionChanged;
            DistribucionHoraria_.CollectionChanged += DistribucionHoraria_CollectionChanged;
        }

        #region CollectionChanged
        private void Calificacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Calificacion obj in e.NewItems)
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
        }
        private void Curso_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Curso obj in e.NewItems)
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
        }
        private void DisposicionPendiente_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (DisposicionPendiente obj in e.NewItems)
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
        }
        private void DistribucionHoraria_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (DistribucionHoraria obj in e.NewItems)
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
        }
        #endregion

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set {
                if( _id != value)
                {
                    _id = value; NotifyPropertyChanged(nameof(id));
                }
            }
        }
        #endregion

        #region asignatura
        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set {
                if( _asignatura != value)
                {
                    _asignatura = value; NotifyPropertyChanged(nameof(asignatura));
                    if (!_asignatura.IsNoE() && (asignatura_.IsNoE() || !asignatura_!.Get(db.config.id).ToString()!.Equals(_asignatura.ToString())))
                        asignatura_ = CreateFromId<Asignatura>(_asignatura);
                    else if(_asignatura.IsNoE())
                        asignatura_ = null;
                }
            }
        }
        #endregion

        #region planificacion
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set {
                if( _planificacion != value)
                {
                    _planificacion = value; NotifyPropertyChanged(nameof(planificacion));
                    if (!_planificacion.IsNoE() && (planificacion_.IsNoE() || !planificacion_!.Get(db.config.id).ToString()!.Equals(_planificacion.ToString())))
                        planificacion_ = CreateFromId<Planificacion>(_planificacion);
                    else if(_planificacion.IsNoE())
                        planificacion_ = null;
                }
            }
        }
        #endregion

        #region orden_informe_coordinacion_distrital
        protected int? _orden_informe_coordinacion_distrital = null;
        public int? orden_informe_coordinacion_distrital
        {
            get { return _orden_informe_coordinacion_distrital; }
            set {
                if( _orden_informe_coordinacion_distrital != value)
                {
                    _orden_informe_coordinacion_distrital = value; NotifyPropertyChanged(nameof(orden_informe_coordinacion_distrital));
                }
            }
        }
        #endregion

        #region asignatura (fk disposicion.asignatura _m:o asignatura.id)
        protected Asignatura? _asignatura_ = null;
        public Asignatura? asignatura_
        {
            get { return _asignatura_; }
            set {
                if ( _asignatura_ != value)
                {
                    _asignatura_ = value;
                    if(value != null)
                        asignatura = value.id;
                    else
                        asignatura = null;
                    NotifyPropertyChanged(nameof(asignatura_));
                }
            }
        }
        #endregion

        #region planificacion (fk disposicion.planificacion _m:o planificacion.id)
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

        #region Calificacion_ (ref calificacion.disposicion _m:o disposicion.id)
        protected ObservableCollection<Calificacion> _Calificacion_ = new ();
        public ObservableCollection<Calificacion> Calificacion_
        {
            get { return _Calificacion_; }
            set { if( _Calificacion_ != value) { _Calificacion_ = value; NotifyPropertyChanged(nameof(Calificacion_)); } }
        }
        #endregion

        #region Curso_ (ref curso.disposicion _m:o disposicion.id)
        protected ObservableCollection<Curso> _Curso_ = new ();
        public ObservableCollection<Curso> Curso_
        {
            get { return _Curso_; }
            set { if( _Curso_ != value) { _Curso_ = value; NotifyPropertyChanged(nameof(Curso_)); } }
        }
        #endregion

        #region DisposicionPendiente_ (ref disposicion_pendiente.disposicion _m:o disposicion.id)
        protected ObservableCollection<DisposicionPendiente> _DisposicionPendiente_ = new ();
        public ObservableCollection<DisposicionPendiente> DisposicionPendiente_
        {
            get { return _DisposicionPendiente_; }
            set { if( _DisposicionPendiente_ != value) { _DisposicionPendiente_ = value; NotifyPropertyChanged(nameof(DisposicionPendiente_)); } }
        }
        #endregion

        #region DistribucionHoraria_ (ref distribucion_horaria.disposicion _m:o disposicion.id)
        protected ObservableCollection<DistribucionHoraria> _DistribucionHoraria_ = new ();
        public ObservableCollection<DistribucionHoraria> DistribucionHoraria_
        {
            get { return _DistribucionHoraria_; }
            set { if( _DistribucionHoraria_ != value) { _DistribucionHoraria_ = value; NotifyPropertyChanged(nameof(DistribucionHoraria_)); } }
        }
        #endregion

        public static IEnumerable<Disposicion> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Disposicion, Asignatura, Planificacion, Plan, Disposicion>(
                sql,
                (main, asignatura, planificacion, plan) =>
                {
                    main.asignatura_ = asignatura;
                    main.planificacion_ = planificacion;
                    if(!plan.IsNoE()) planificacion.plan_ = plan;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
