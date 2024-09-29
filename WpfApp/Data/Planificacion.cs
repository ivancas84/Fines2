#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.FinesApp.Data
{
    public partial class Planificacion : Entity
    {

        public Planificacion()
        {
            _entityName = "planificacion";
            _db = Context.db;
            Default();
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
                if( _plan_ != null && AutoAddToCollection)
                    _plan_!.Planificacion_.Remove(this);

                _plan_ = value;

                if(value != null)
                {
                    plan = value.id;
                    if(AutoAddToCollection && !_plan_!.Planificacion_.Contains(this))
                        _plan_!.Planificacion_.Add(this);
                }
                else
                {
                    plan = null;
                }
                NotifyPropertyChanged(nameof(plan_));
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
