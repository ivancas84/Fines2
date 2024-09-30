#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Modalidad : Entity
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
                        foreach(var obj in Comision_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.modalidad_ != this)
                                 obj.modalidad_ = this;
                        }

                    }
                }
            }
        }

        public Modalidad()
        {
            _entityName = "modalidad";
            _db = Context.db;
            Default();
            Comision_.CollectionChanged += Comision_CollectionChanged;
        }

        private void Comision_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Comision obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.modalidad_ != this)
                        obj.modalidad_ = this;
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

        #region nombre
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { if( _nombre != value) { _nombre = value; NotifyPropertyChanged(nameof(nombre)); } }
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

        #region Comision_ (ref comision.modalidad _m:o modalidad.id)
        public ObservableCollection<Comision> Comision_ { get; set; } = new ();
        #endregion

    }
}
