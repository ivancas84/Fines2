#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class CentroEducativo : Entity
    {

        public CentroEducativo()
        {
            _entityName = "centro_educativo";
            _db = Context.db;
            Default();
            Sede_.CollectionChanged += Sede_CollectionChanged;
        }

        #region CollectionChanged
        private void Sede_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Sede obj in e.NewItems)
                    if(obj.centro_educativo_ != this)
                        obj.centro_educativo_ = this;
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

        #region nombre
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { if( _nombre != value) { _nombre = value; NotifyPropertyChanged(nameof(nombre)); } }
        }
        #endregion

        #region cue
        protected string? _cue = null;
        public string? cue
        {
            get { return _cue; }
            set { if( _cue != value) { _cue = value; NotifyPropertyChanged(nameof(cue)); } }
        }
        #endregion

        #region domicilio
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { if( _domicilio != value) { _domicilio = value; NotifyPropertyChanged(nameof(domicilio)); } }
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

        #region domicilio (fk centro_educativo.domicilio _m:o domicilio.id)
        protected Domicilio? _domicilio_ = null;
        public Domicilio? domicilio_
        {
            get { return _domicilio_; }
            set {
                if ( _domicilio_ != value)
                {
                    _domicilio_ = value;
                    if(value != null)
                        domicilio = value.id;
                    else
                        domicilio = null;
                    NotifyPropertyChanged(nameof(domicilio_));
                }
            }
        }
        #endregion

        #region Sede_ (ref sede.centro_educativo _m:o centro_educativo.id)
        public ObservableCollection<Sede> Sede_ { get; set; } = new ();
        #endregion

    }
}