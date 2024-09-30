#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Planificacion : Entity
    {

        public override bool EnableSynchronization
        {
            get => _enableSynchronization;
            set
            {
                if(_enableSynchronization != value)
                {
                    _enableSynchronization = value;

                    if(_enableSynchronization)
                    {
                        if (_plan_ != null)
                        {
                            _plan_!.EnableSynchronization = true;
                            if (!_plan_!.Planificacion_.Contains(this))
                                _plan_!.Planificacion_.Add(this);
                        }

                        foreach(var obj in Comision_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.planificacion_ != this)
                                 obj.planificacion_ = this;
                        }

                        foreach(var obj in Disposicion_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.planificacion_ != this)
                                 obj.planificacion_ = this;
                        }

                    }
                }
            }
        }

        public Planificacion()
        {
            _entityName = "planificacion";
            _db = Context.db;
            Default();
            Comision_.CollectionChanged += Comision_CollectionChanged;
            Disposicion_.CollectionChanged += Disposicion_CollectionChanged;
        }

        private void Comision_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Comision obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.planificacion_ != this)
                        obj.planificacion_ = this;
                }
            }
        }
        private void Disposicion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Disposicion obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.planificacion_ != this)
                        obj.planificacion_ = this;
                }
            }
        }
        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region anio
        protected string? _anio = null;
        public string? anio
        {
            get { return _anio; }
            set { if( _anio != value) { _anio = value; NotifyPropertyChanged(nameof(anio)); } }
        }
        #endregion

        #region semestre
        protected string? _semestre = null;
        public string? semestre
        {
            get { return _semestre; }
            set { if( _semestre != value) { _semestre = value; NotifyPropertyChanged(nameof(semestre)); } }
        }
        #endregion

        #region plan
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { if( _plan != value) { _plan = value; NotifyPropertyChanged(nameof(plan)); } }
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

        #region plan (fk planificacion.plan _m:o plan.id)
        protected Plan? _plan_ = null;
        public Plan? plan_
        {
            get { return _plan_; }
            set {
                if(  _plan_ != value )
                {
                    var old_plan = _plan;
                    _plan_ = value;

                    if( old_plan != null && EnableSynchronization)
                        _plan_!.Planificacion_.Remove(this);

                    if(value != null)
                    {
                        plan = value.id;
                        if(EnableSynchronization && !_plan_!.Planificacion_.Contains(this))
                        {
                            _plan_!.EnableSynchronization = true;
                            _plan_!.Planificacion_.Add(this);
                        }
                    }
                    else
                    {
                        plan = null;
                    }
                    NotifyPropertyChanged(nameof(plan_));
                }
            }
        }
        #endregion

        #region Comision_ (ref comision.planificacion _m:o planificacion.id)
        public ObservableCollection<Comision> Comision_ { get; set; } = new ();
        #endregion

        #region Disposicion_ (ref disposicion.planificacion _m:o planificacion.id)
        public ObservableCollection<Disposicion> Disposicion_ { get; set; } = new ();
        #endregion

    }
}
