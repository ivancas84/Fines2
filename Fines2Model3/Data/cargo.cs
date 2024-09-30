#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Cargo : Entity
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
                        foreach(var obj in Designacion_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.cargo_ != this)
                                 obj.cargo_ = this;
                        }

                    }
                }
            }
        }

        public Cargo()
        {
            _entityName = "cargo";
            _db = Context.db;
            Default();
            Designacion_.CollectionChanged += Designacion_CollectionChanged;
        }

        private void Designacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Designacion obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.cargo_ != this)
                        obj.cargo_ = this;
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

        #region descripcion
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { if( _descripcion != value) { _descripcion = value; NotifyPropertyChanged(nameof(descripcion)); } }
        }
        #endregion

        #region Designacion_ (ref designacion.cargo _m:o cargo.id)
        public ObservableCollection<Designacion> Designacion_ { get; set; } = new ();
        #endregion

    }
}
