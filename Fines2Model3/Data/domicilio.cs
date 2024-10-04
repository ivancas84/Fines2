#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Domicilio : Entity
    {

        public Domicilio()
        {
            _entityName = "domicilio";
            _db = Context.db;
            Default();
            CentroEducativo_.CollectionChanged += CentroEducativo_CollectionChanged;
            Persona_.CollectionChanged += Persona_CollectionChanged;
            Sede_.CollectionChanged += Sede_CollectionChanged;
        }

        #region CollectionChanged
        private void CentroEducativo_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (CentroEducativo obj in e.NewItems)
                    if(obj.domicilio_ != this)
                        obj.domicilio_ = this;
        }
        private void Persona_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Persona obj in e.NewItems)
                    if(obj.domicilio_ != this)
                        obj.domicilio_ = this;
        }
        private void Sede_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Sede obj in e.NewItems)
                    if(obj.domicilio_ != this)
                        obj.domicilio_ = this;
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

        #region calle
        protected string? _calle = null;
        public string? calle
        {
            get { return _calle; }
            set { if( _calle != value) { _calle = value; NotifyPropertyChanged(nameof(calle)); } }
        }
        #endregion

        #region entre
        protected string? _entre = null;
        public string? entre
        {
            get { return _entre; }
            set { if( _entre != value) { _entre = value; NotifyPropertyChanged(nameof(entre)); } }
        }
        #endregion

        #region numero
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
        }
        #endregion

        #region piso
        protected string? _piso = null;
        public string? piso
        {
            get { return _piso; }
            set { if( _piso != value) { _piso = value; NotifyPropertyChanged(nameof(piso)); } }
        }
        #endregion

        #region departamento
        protected string? _departamento = null;
        public string? departamento
        {
            get { return _departamento; }
            set { if( _departamento != value) { _departamento = value; NotifyPropertyChanged(nameof(departamento)); } }
        }
        #endregion

        #region barrio
        protected string? _barrio = null;
        public string? barrio
        {
            get { return _barrio; }
            set { if( _barrio != value) { _barrio = value; NotifyPropertyChanged(nameof(barrio)); } }
        }
        #endregion

        #region localidad
        protected string? _localidad = null;
        public string? localidad
        {
            get { return _localidad; }
            set { if( _localidad != value) { _localidad = value; NotifyPropertyChanged(nameof(localidad)); } }
        }
        #endregion

        #region CentroEducativo_ (ref centro_educativo.domicilio _m:o domicilio.id)
        protected ObservableCollection<CentroEducativo> _CentroEducativo_ = new ();
        public ObservableCollection<CentroEducativo> CentroEducativo_
        {
            get { return _CentroEducativo_; }
            set { if( _CentroEducativo_ != value) { _CentroEducativo_ = value; NotifyPropertyChanged(nameof(CentroEducativo_)); } }
        }
        #endregion

        #region Persona_ (ref persona.domicilio _m:o domicilio.id)
        protected ObservableCollection<Persona> _Persona_ = new ();
        public ObservableCollection<Persona> Persona_
        {
            get { return _Persona_; }
            set { if( _Persona_ != value) { _Persona_ = value; NotifyPropertyChanged(nameof(Persona_)); } }
        }
        #endregion

        #region Sede_ (ref sede.domicilio _m:o domicilio.id)
        protected ObservableCollection<Sede> _Sede_ = new ();
        public ObservableCollection<Sede> Sede_
        {
            get { return _Sede_; }
            set { if( _Sede_ != value) { _Sede_ = value; NotifyPropertyChanged(nameof(Sede_)); } }
        }
        #endregion

    }
}
