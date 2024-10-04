#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class TipoSede : Entity
    {

        public TipoSede()
        {
            _entityName = "tipo_sede";
            _db = Context.db;
            Default();
            Sede_.CollectionChanged += Sede_CollectionChanged;
        }

        #region CollectionChanged
        private void Sede_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Sede obj in e.NewItems)
                    if(obj.tipo_sede_ != this)
                        obj.tipo_sede_ = this;
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

        #region descripcion
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { if( _descripcion != value) { _descripcion = value; NotifyPropertyChanged(nameof(descripcion)); } }
        }
        #endregion

        #region Sede_ (ref sede.tipo_sede _m:o tipo_sede.id)
        protected ObservableCollection<Sede> _Sede_ = new ();
        public ObservableCollection<Sede> Sede_
        {
            get { return _Sede_; }
            set { if( _Sede_ != value) { _Sede_ = value; NotifyPropertyChanged(nameof(Sede_)); } }
        }
        #endregion

    }
}
