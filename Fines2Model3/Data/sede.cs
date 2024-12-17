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
    public partial class Sede : Entity
    {

        public Sede()
        {
            _entityName = "sede";
            _db = Context.db;
            Default();
            Comision_.CollectionChanged += Comision_CollectionChanged;
            Designacion_.CollectionChanged += Designacion_CollectionChanged;
            Sede_organizacion_.CollectionChanged += Sede_organizacion_CollectionChanged;
        }

        #region CollectionChanged
        private void Comision_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Comision obj in e.NewItems)
                    if(obj.sede_ != this)
                        obj.sede_ = this;
        }
        private void Designacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Designacion obj in e.NewItems)
                    if(obj.sede_ != this)
                        obj.sede_ = this;
        }
        private void Sede_organizacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Sede obj in e.NewItems)
                    if(obj.organizacion_ != this)
                        obj.organizacion_ = this;
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

        #region numero
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set {
                if( _numero != value)
                {
                    _numero = value; NotifyPropertyChanged(nameof(numero));
                }
            }
        }
        #endregion

        #region nombre
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set {
                if( _nombre != value)
                {
                    _nombre = value; NotifyPropertyChanged(nameof(nombre));
                }
            }
        }
        #endregion

        #region observaciones
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set {
                if( _observaciones != value)
                {
                    _observaciones = value; NotifyPropertyChanged(nameof(observaciones));
                }
            }
        }
        #endregion

        #region alta
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set {
                if( _alta != value)
                {
                    _alta = value; NotifyPropertyChanged(nameof(alta));
                }
            }
        }
        #endregion

        #region baja
        protected DateTime? _baja = null;
        public DateTime? baja
        {
            get { return _baja; }
            set {
                if( _baja != value)
                {
                    _baja = value; NotifyPropertyChanged(nameof(baja));
                }
            }
        }
        #endregion

        #region domicilio
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set {
                if( _domicilio != value)
                {
                    _domicilio = value; NotifyPropertyChanged(nameof(domicilio));
                    if (!_domicilio.IsNoE() && (domicilio_.IsNoE() || !domicilio_!.Get(db.config.id).ToString()!.Equals(_domicilio.ToString())))
                        domicilio_ = CreateFromId<Domicilio>(_domicilio);
                    else if(_domicilio.IsNoE())
                        domicilio_ = null;
                }
            }
        }
        #endregion

        #region tipo_sede
        protected string? _tipo_sede = null;
        public string? tipo_sede
        {
            get { return _tipo_sede; }
            set {
                if( _tipo_sede != value)
                {
                    _tipo_sede = value; NotifyPropertyChanged(nameof(tipo_sede));
                    if (!_tipo_sede.IsNoE() && (tipo_sede_.IsNoE() || !tipo_sede_!.Get(db.config.id).ToString()!.Equals(_tipo_sede.ToString())))
                        tipo_sede_ = CreateFromId<TipoSede>(_tipo_sede);
                    else if(_tipo_sede.IsNoE())
                        tipo_sede_ = null;
                }
            }
        }
        #endregion

        #region centro_educativo
        protected string? _centro_educativo = null;
        public string? centro_educativo
        {
            get { return _centro_educativo; }
            set {
                if( _centro_educativo != value)
                {
                    _centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo));
                    if (!_centro_educativo.IsNoE() && (centro_educativo_.IsNoE() || !centro_educativo_!.Get(db.config.id).ToString()!.Equals(_centro_educativo.ToString())))
                        centro_educativo_ = CreateFromId<CentroEducativo>(_centro_educativo);
                    else if(_centro_educativo.IsNoE())
                        centro_educativo_ = null;
                }
            }
        }
        #endregion

        #region fecha_traspaso
        protected DateTime? _fecha_traspaso = null;
        public DateTime? fecha_traspaso
        {
            get { return _fecha_traspaso; }
            set {
                if( _fecha_traspaso != value)
                {
                    _fecha_traspaso = value; NotifyPropertyChanged(nameof(fecha_traspaso));
                }
            }
        }
        #endregion

        #region organizacion
        protected string? _organizacion = null;
        public string? organizacion
        {
            get { return _organizacion; }
            set {
                if( _organizacion != value)
                {
                    _organizacion = value; NotifyPropertyChanged(nameof(organizacion));
                    if (!_organizacion.IsNoE() && (organizacion_.IsNoE() || !organizacion_!.Get(db.config.id).ToString()!.Equals(_organizacion.ToString())))
                        organizacion_ = CreateFromId<Sede>(_organizacion);
                    else if(_organizacion.IsNoE())
                        organizacion_ = null;
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

        #region pfid_organizacion
        protected string? _pfid_organizacion = null;
        public string? pfid_organizacion
        {
            get { return _pfid_organizacion; }
            set {
                if( _pfid_organizacion != value)
                {
                    _pfid_organizacion = value; NotifyPropertyChanged(nameof(pfid_organizacion));
                }
            }
        }
        #endregion

        #region domicilio (fk sede.domicilio _m:o domicilio.id)
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

        #region tipo_sede (fk sede.tipo_sede _m:o tipo_sede.id)
        protected TipoSede? _tipo_sede_ = null;
        public TipoSede? tipo_sede_
        {
            get { return _tipo_sede_; }
            set {
                if ( _tipo_sede_ != value)
                {
                    _tipo_sede_ = value;
                    if(value != null)
                        tipo_sede = value.id;
                    else
                        tipo_sede = null;
                    NotifyPropertyChanged(nameof(tipo_sede_));
                }
            }
        }
        #endregion

        #region centro_educativo (fk sede.centro_educativo _m:o centro_educativo.id)
        protected CentroEducativo? _centro_educativo_ = null;
        public CentroEducativo? centro_educativo_
        {
            get { return _centro_educativo_; }
            set {
                if ( _centro_educativo_ != value)
                {
                    _centro_educativo_ = value;
                    if(value != null)
                        centro_educativo = value.id;
                    else
                        centro_educativo = null;
                    NotifyPropertyChanged(nameof(centro_educativo_));
                }
            }
        }
        #endregion

        #region organizacion (fk sede.organizacion _m:o sede.id)
        protected Sede? _organizacion_ = null;
        public Sede? organizacion_
        {
            get { return _organizacion_; }
            set {
                if ( _organizacion_ != value)
                {
                    _organizacion_ = value;
                    if(value != null)
                        organizacion = value.id;
                    else
                        organizacion = null;
                    NotifyPropertyChanged(nameof(organizacion_));
                }
            }
        }
        #endregion

        #region Comision_ (ref comision.sede _m:o sede.id)
        protected ObservableCollection<Comision> _Comision_ = new ();
        public ObservableCollection<Comision> Comision_
        {
            get { return _Comision_; }
            set { if( _Comision_ != value) { _Comision_ = value; NotifyPropertyChanged(nameof(Comision_)); } }
        }
        #endregion

        #region Designacion_ (ref designacion.sede _m:o sede.id)
        protected ObservableCollection<Designacion> _Designacion_ = new ();
        public ObservableCollection<Designacion> Designacion_
        {
            get { return _Designacion_; }
            set { if( _Designacion_ != value) { _Designacion_ = value; NotifyPropertyChanged(nameof(Designacion_)); } }
        }
        #endregion

        #region Sede_organizacion_ (ref sede.organizacion _m:o sede.id)
        protected ObservableCollection<Sede> _Sede_organizacion_ = new ();
        public ObservableCollection<Sede> Sede_organizacion_
        {
            get { return _Sede_organizacion_; }
            set { if( _Sede_organizacion_ != value) { _Sede_organizacion_ = value; NotifyPropertyChanged(nameof(Sede_organizacion_)); } }
        }
        #endregion

        public static IEnumerable<Sede> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Sede, Domicilio, TipoSede, CentroEducativo, Domicilio, Sede, Domicilio, Sede>(
                sql,
                (main, domicilio, tipo_sede, centro_educativo, domicilio_cen, organizacion, domicilio_org) =>
                {
                    main.domicilio_ = domicilio;
                    main.tipo_sede_ = tipo_sede;
                    main.centro_educativo_ = centro_educativo;
                    if(!domicilio_cen.IsNoE()) centro_educativo.domicilio_ = domicilio_cen;
                    main.organizacion_ = organizacion;
                    if(!domicilio_org.IsNoE()) organizacion.domicilio_ = domicilio_org;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
