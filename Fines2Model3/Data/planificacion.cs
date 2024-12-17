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
    public partial class Planificacion : Entity
    {

        public Planificacion()
        {
            _entityName = "planificacion";
            _db = Context.db;
            Default();
            Comision_.CollectionChanged += Comision_CollectionChanged;
            Disposicion_.CollectionChanged += Disposicion_CollectionChanged;
        }

        #region CollectionChanged
        private void Comision_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Comision obj in e.NewItems)
                    if(obj.planificacion_ != this)
                        obj.planificacion_ = this;
        }
        private void Disposicion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Disposicion obj in e.NewItems)
                    if(obj.planificacion_ != this)
                        obj.planificacion_ = this;
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

        #region anio
        protected string? _anio = null;
        public string? anio
        {
            get { return _anio; }
            set {
                if( _anio != value)
                {
                    _anio = value; NotifyPropertyChanged(nameof(anio));
                }
            }
        }
        #endregion

        #region semestre
        protected string? _semestre = null;
        public string? semestre
        {
            get { return _semestre; }
            set {
                if( _semestre != value)
                {
                    _semestre = value; NotifyPropertyChanged(nameof(semestre));
                }
            }
        }
        #endregion

        #region plan
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set {
                if( _plan != value)
                {
                    _plan = value; NotifyPropertyChanged(nameof(plan));
                    if (!_plan.IsNoE() && (plan_.IsNoE() || !plan_!.Get(db.config.id).ToString()!.Equals(_plan.ToString())))
                        plan_ = CreateFromId<Plan>(_plan);
                    else if(_plan.IsNoE())
                        plan_ = null;
                }
            }
        }
        #endregion

        #region pfid
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set {
                if( _pfid != value)
                {
                    _pfid = value; NotifyPropertyChanged(nameof(pfid));
                }
            }
        }
        #endregion

        #region plan (fk planificacion.plan _m:o plan.id)
        protected Plan? _plan_ = null;
        public Plan? plan_
        {
            get { return _plan_; }
            set {
                if ( _plan_ != value)
                {
                    _plan_ = value;
                    if(value != null)
                        plan = value.id;
                    else
                        plan = null;
                    NotifyPropertyChanged(nameof(plan_));
                }
            }
        }
        #endregion

        #region Comision_ (ref comision.planificacion _m:o planificacion.id)
        protected ObservableCollection<Comision> _Comision_ = new ();
        public ObservableCollection<Comision> Comision_
        {
            get { return _Comision_; }
            set { if( _Comision_ != value) { _Comision_ = value; NotifyPropertyChanged(nameof(Comision_)); } }
        }
        #endregion

        #region Disposicion_ (ref disposicion.planificacion _m:o planificacion.id)
        protected ObservableCollection<Disposicion> _Disposicion_ = new ();
        public ObservableCollection<Disposicion> Disposicion_
        {
            get { return _Disposicion_; }
            set { if( _Disposicion_ != value) { _Disposicion_ = value; NotifyPropertyChanged(nameof(Disposicion_)); } }
        }
        #endregion

        public static IEnumerable<Planificacion> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Planificacion, Plan, Planificacion>(
                sql,
                (main, plan) =>
                {
                    main.plan_ = plan;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
