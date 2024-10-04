#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Asignatura : Entity
    {

        public Asignatura()
        {
            _entityName = "asignatura";
            _db = Context.db;
            Default();
            Disposicion_.CollectionChanged += Disposicion_CollectionChanged;
        }

        #region CollectionChanged
        private void Disposicion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Disposicion obj in e.NewItems)
                    if(obj.asignatura_ != this)
                        obj.asignatura_ = this;
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

        #region formacion
        protected string? _formacion = null;
        public string? formacion
        {
            get { return _formacion; }
            set { if( _formacion != value) { _formacion = value; NotifyPropertyChanged(nameof(formacion)); } }
        }
        #endregion

        #region clasificacion
        protected string? _clasificacion = null;
        public string? clasificacion
        {
            get { return _clasificacion; }
            set { if( _clasificacion != value) { _clasificacion = value; NotifyPropertyChanged(nameof(clasificacion)); } }
        }
        #endregion

        #region codigo
        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set { if( _codigo != value) { _codigo = value; NotifyPropertyChanged(nameof(codigo)); } }
        }
        #endregion

        #region perfil
        protected string? _perfil = null;
        public string? perfil
        {
            get { return _perfil; }
            set { if( _perfil != value) { _perfil = value; NotifyPropertyChanged(nameof(perfil)); } }
        }
        #endregion

        #region Disposicion_ (ref disposicion.asignatura _m:o asignatura.id)
        protected ObservableCollection<Disposicion> _Disposicion_ = new ();
        public ObservableCollection<Disposicion> Disposicion_
        {
            get { return _Disposicion_; }
            set { if( _Disposicion_ != value) { _Disposicion_ = value; NotifyPropertyChanged(nameof(Disposicion_)); } }
        }
        #endregion

    }
}
