#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Sede : Entity
    {

        public Sede()
        {
            _entityName = "sede";
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

        #region numero
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
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

        #region observaciones
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        #endregion

        #region alta
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        #endregion

        #region baja
        protected DateTime? _baja = null;
        public DateTime? baja
        {
            get { return _baja; }
            set { if( _baja != value) { _baja = value; NotifyPropertyChanged(nameof(baja)); } }
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

        #region tipo_sede
        protected string? _tipo_sede = null;
        public string? tipo_sede
        {
            get { return _tipo_sede; }
            set { if( _tipo_sede != value) { _tipo_sede = value; NotifyPropertyChanged(nameof(tipo_sede)); } }
        }
        #endregion

        #region centro_educativo
        protected string? _centro_educativo = null;
        public string? centro_educativo
        {
            get { return _centro_educativo; }
            set { if( _centro_educativo != value) { _centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo)); } }
        }
        #endregion

        #region fecha_traspaso
        protected DateTime? _fecha_traspaso = null;
        public DateTime? fecha_traspaso
        {
            get { return _fecha_traspaso; }
            set { if( _fecha_traspaso != value) { _fecha_traspaso = value; NotifyPropertyChanged(nameof(fecha_traspaso)); } }
        }
        #endregion

        #region organizacion
        protected string? _organizacion = null;
        public string? organizacion
        {
            get { return _organizacion; }
            set { if( _organizacion != value) { _organizacion = value; NotifyPropertyChanged(nameof(organizacion)); } }
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

        #region pfid_organizacion
        protected string? _pfid_organizacion = null;
        public string? pfid_organizacion
        {
            get { return _pfid_organizacion; }
            set { if( _pfid_organizacion != value) { _pfid_organizacion = value; NotifyPropertyChanged(nameof(pfid_organizacion)); } }
        }
        #endregion

        #region domicilio (fk sede.domicilio _m:o domicilio.id)
        protected Domicilio? _domicilio_ = null;
        public Domicilio? domicilio_
        {
            get { return _domicilio_; }
            set {
                if( _domicilio_ != null && AutoAddToCollection)
                    _domicilio_!.Sede_.Remove(this);

                _domicilio_ = value;

                if(value != null)
                {
                    domicilio = value.id;
                    if(AutoAddToCollection && !_domicilio_!.Sede_.Contains(this))
                        _domicilio_!.Sede_.Add(this);
                }
                else
                {
                    domicilio = null;
                }
                NotifyPropertyChanged(nameof(domicilio_));
            }
        }
        #endregion

        #region tipo_sede (fk sede.tipo_sede _m:o tipo_sede.id)
        protected TipoSede? _tipo_sede_ = null;
        public TipoSede? tipo_sede_
        {
            get { return _tipo_sede_; }
            set {
                if( _tipo_sede_ != null && AutoAddToCollection)
                    _tipo_sede_!.Sede_.Remove(this);

                _tipo_sede_ = value;

                if(value != null)
                {
                    tipo_sede = value.id;
                    if(AutoAddToCollection && !_tipo_sede_!.Sede_.Contains(this))
                        _tipo_sede_!.Sede_.Add(this);
                }
                else
                {
                    tipo_sede = null;
                }
                NotifyPropertyChanged(nameof(tipo_sede_));
            }
        }
        #endregion

        #region centro_educativo (fk sede.centro_educativo _m:o centro_educativo.id)
        protected CentroEducativo? _centro_educativo_ = null;
        public CentroEducativo? centro_educativo_
        {
            get { return _centro_educativo_; }
            set {
                if( _centro_educativo_ != null && AutoAddToCollection)
                    _centro_educativo_!.Sede_.Remove(this);

                _centro_educativo_ = value;

                if(value != null)
                {
                    centro_educativo = value.id;
                    if(AutoAddToCollection && !_centro_educativo_!.Sede_.Contains(this))
                        _centro_educativo_!.Sede_.Add(this);
                }
                else
                {
                    centro_educativo = null;
                }
                NotifyPropertyChanged(nameof(centro_educativo_));
            }
        }
        #endregion

        #region Comision_ (ref comision.sede _m:o sede.id)
        public ObservableCollection<Comision> Comision_ { get; set; } = new ();
        #endregion

        #region Designacion_ (ref designacion.sede _m:o sede.id)
        public ObservableCollection<Designacion> Designacion_ { get; set; } = new ();
        #endregion

    }
}
