#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Planificacion : EntityData
    {

        public Planificacion()
        {
            _entityName = "planificacion";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _anio = null;
        public string? anio
        {
            get { return _anio; }
            set { if( _anio != value) { _anio = value; NotifyPropertyChanged(nameof(anio)); } }
        }
        protected string? _semestre = null;
        public string? semestre
        {
            get { return _semestre; }
            set { if( _semestre != value) { _semestre = value; NotifyPropertyChanged(nameof(semestre)); } }
        }
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { if( _plan != value) { _plan = value; NotifyPropertyChanged(nameof(plan)); } }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        //planificacion.plan _o:o plan.id
        protected Plan? _plan_ = null;
        public Plan? plan_
        {
            get { return _plan_; }
            set {
                _plan_ = value;
                plan = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(plan_));
            }
        }

        //comision.planificacion _m:o planificacion.id
        public ObservableCollection<Comision> Comision_ { get; set; } = new ();

        //disposicion.planificacion _m:o planificacion.id
        public ObservableCollection<Disposicion> Disposicion_ { get; set; } = new ();

    }
}
