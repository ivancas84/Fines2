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
    public partial class Curso : Entity
    {

        public Curso()
        {
            _entityName = "curso";
            _db = Context.db;
            Default();
            Calificacion_.CollectionChanged += Calificacion_CollectionChanged;
            Horario_.CollectionChanged += Horario_CollectionChanged;
            Toma_.CollectionChanged += Toma_CollectionChanged;
        }

        #region CollectionChanged
        private void Calificacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Calificacion obj in e.NewItems)
                    if(obj.curso_ != this)
                        obj.curso_ = this;
        }
        private void Horario_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Horario obj in e.NewItems)
                    if(obj.curso_ != this)
                        obj.curso_ = this;
        }
        private void Toma_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Toma obj in e.NewItems)
                    if(obj.curso_ != this)
                        obj.curso_ = this;
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

        #region horas_catedra
        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set {
                if( _horas_catedra != value)
                {
                    _horas_catedra = value; NotifyPropertyChanged(nameof(horas_catedra));
                }
            }
        }
        #endregion

        #region ige
        protected string? _ige = null;
        public string? ige
        {
            get { return _ige; }
            set {
                if( _ige != value)
                {
                    _ige = value; NotifyPropertyChanged(nameof(ige));
                }
            }
        }
        #endregion

        #region comision
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set {
                if( _comision != value)
                {
                    _comision = value; NotifyPropertyChanged(nameof(comision));
                    //desactivado hasta implementar cache
                    //if (_comision.HasValue && (comision_.IsNoE() || !comision_!.Get(db.config.id).ToString()!.Equals(_comision.Value.ToString())))
                    //    comision_ = CreateFromId<Comision>(_comision);
                    //else if(_comision.IsNoE())
                    //    comision_ = null;
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

        #region descripcion_horario
        protected string? _descripcion_horario = null;
        public string? descripcion_horario
        {
            get { return _descripcion_horario; }
            set {
                if( _descripcion_horario != value)
                {
                    _descripcion_horario = value; NotifyPropertyChanged(nameof(descripcion_horario));
                }
            }
        }
        #endregion

        #region codigo
        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set {
                if( _codigo != value)
                {
                    _codigo = value; NotifyPropertyChanged(nameof(codigo));
                }
            }
        }
        #endregion

        #region disposicion
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set {
                if( _disposicion != value)
                {
                    _disposicion = value; NotifyPropertyChanged(nameof(disposicion));
                    //desactivado hasta implementar cache
                    //if (_disposicion.HasValue && (disposicion_.IsNoE() || !disposicion_!.Get(db.config.id).ToString()!.Equals(_disposicion.Value.ToString())))
                    //    disposicion_ = CreateFromId<Disposicion>(_disposicion);
                    //else if(_disposicion.IsNoE())
                    //    disposicion_ = null;
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

        #region asignatura
        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set {
                if( _asignatura != value)
                {
                    _asignatura = value; NotifyPropertyChanged(nameof(asignatura));
                    //desactivado hasta implementar cache
                    //if (_asignatura.HasValue && (asignatura_.IsNoE() || !asignatura_!.Get(db.config.id).ToString()!.Equals(_asignatura.Value.ToString())))
                    //    asignatura_ = CreateFromId<Asignatura>(_asignatura);
                    //else if(_asignatura.IsNoE())
                    //    asignatura_ = null;
                }
            }
        }
        #endregion

        #region comision (fk curso.comision _m:o comision.id)
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                if ( _comision_ != value)
                {
                    _comision_ = value;
                    if(value != null)
                        comision = value.id;
                    else
                        comision = null;
                    NotifyPropertyChanged(nameof(comision_));
                }
            }
        }
        #endregion

        #region disposicion (fk curso.disposicion _m:o disposicion.id)
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set {
                if ( _disposicion_ != value)
                {
                    _disposicion_ = value;
                    if(value != null)
                        disposicion = value.id;
                    else
                        disposicion = null;
                    NotifyPropertyChanged(nameof(disposicion_));
                }
            }
        }
        #endregion

        #region asignatura (fk curso.asignatura _m:o asignatura.id)
        protected Asignatura? _asignatura_ = null;
        public Asignatura? asignatura_
        {
            get { return _asignatura_; }
            set {
                if ( _asignatura_ != value)
                {
                    _asignatura_ = value;
                    if(value != null)
                        asignatura = value.id;
                    else
                        asignatura = null;
                    NotifyPropertyChanged(nameof(asignatura_));
                }
            }
        }
        #endregion

        #region Calificacion_ (ref calificacion.curso _m:o curso.id)
        protected ObservableCollection<Calificacion> _Calificacion_ = new ();
        public ObservableCollection<Calificacion> Calificacion_
        {
            get { return _Calificacion_; }
            set { if( _Calificacion_ != value) { _Calificacion_ = value; NotifyPropertyChanged(nameof(Calificacion_)); } }
        }
        #endregion

        #region Horario_ (ref horario.curso _m:o curso.id)
        protected ObservableCollection<Horario> _Horario_ = new ();
        public ObservableCollection<Horario> Horario_
        {
            get { return _Horario_; }
            set { if( _Horario_ != value) { _Horario_ = value; NotifyPropertyChanged(nameof(Horario_)); } }
        }
        #endregion

        #region Toma_ (ref toma.curso _m:o curso.id)
        protected ObservableCollection<Toma> _Toma_ = new ();
        public ObservableCollection<Toma> Toma_
        {
            get { return _Toma_; }
            set { if( _Toma_ != value) { _Toma_ = value; NotifyPropertyChanged(nameof(Toma_)); } }
        }
        #endregion

        public static IEnumerable<Curso> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Curso, Comision, Sede, Domicilio, TipoSede, CentroEducativo, Domicilio, Curso>(
                sql,
                (main, comision, sede, domicilio, tipo_sede, centro_educativo, domicilio_cen) =>
                {
                    main.comision_ = comision;
                    if(!sede.IsNoE()) comision.sede_ = sede;
                    if(!domicilio.IsNoE()) sede.domicilio_ = domicilio;
                    if(!tipo_sede.IsNoE()) sede.tipo_sede_ = tipo_sede;
                    if(!centro_educativo.IsNoE()) sede.centro_educativo_ = centro_educativo;
                    if(!domicilio_cen.IsNoE()) centro_educativo.domicilio_ = domicilio_cen;
                    return main;
                },
                parameters,
                splitOn:Context.db.Sql().SplitOn("curso")
            );
        }
    }
}
