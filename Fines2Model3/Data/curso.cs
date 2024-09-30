#nullable enable
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Curso : Entity
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
                        if (_comision_ != null)
                        {
                            _comision_!.EnableSynchronization = true;
                            if (!_comision_!.Curso_.Contains(this))
                                _comision_!.Curso_.Add(this);
                        }

                        if (_disposicion_ != null)
                        {
                            _disposicion_!.EnableSynchronization = true;
                            if (!_disposicion_!.Curso_.Contains(this))
                                _disposicion_!.Curso_.Add(this);
                        }

                        foreach(var obj in Calificacion_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.curso_ != this)
                                 obj.curso_ = this;
                        }

                        foreach(var obj in Horario_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.curso_ != this)
                                 obj.curso_ = this;
                        }

                        foreach(var obj in Toma_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.curso_ != this)
                                 obj.curso_ = this;
                        }

                    }
                }
            }
        }

        public Curso()
        {
            _entityName = "curso";
            _db = Context.db;
            Default();
            Calificacion_.CollectionChanged += Calificacion_CollectionChanged;
            Horario_.CollectionChanged += Horario_CollectionChanged;
            Toma_.CollectionChanged += Toma_CollectionChanged;
        }

        private void Calificacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Calificacion obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.curso_ != this)
                        obj.curso_ = this;
                }
            }
        }
        private void Horario_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Horario obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.curso_ != this)
                        obj.curso_ = this;
                }
            }
        }
        private void Toma_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Toma obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.curso_ != this)
                        obj.curso_ = this;
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

        #region horas_catedra
        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set { if( _horas_catedra != value) { _horas_catedra = value; NotifyPropertyChanged(nameof(horas_catedra)); } }
        }
        #endregion

        #region ige
        protected string? _ige = null;
        public string? ige
        {
            get { return _ige; }
            set { if( _ige != value) { _ige = value; NotifyPropertyChanged(nameof(ige)); } }
        }
        #endregion

        #region comision
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { if( _comision != value) { _comision = value; NotifyPropertyChanged(nameof(comision)); } }
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

        #region descripcion_horario
        protected string? _descripcion_horario = null;
        public string? descripcion_horario
        {
            get { return _descripcion_horario; }
            set { if( _descripcion_horario != value) { _descripcion_horario = value; NotifyPropertyChanged(nameof(descripcion_horario)); } }
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

        #region disposicion
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { if( _disposicion != value) { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); } }
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

        #region comision (fk curso.comision _m:o comision.id)
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                if(  _comision_ != value )
                {
                    var old_comision = _comision;
                    _comision_ = value;

                    if( old_comision != null && EnableSynchronization)
                        _comision_!.Curso_.Remove(this);

                    if(value != null)
                    {
                        comision = value.id;
                        if(EnableSynchronization && !_comision_!.Curso_.Contains(this))
                        {
                            _comision_!.EnableSynchronization = true;
                            _comision_!.Curso_.Add(this);
                        }
                    }
                    else
                    {
                        comision = null;
                    }
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
                if(  _disposicion_ != value )
                {
                    var old_disposicion = _disposicion;
                    _disposicion_ = value;

                    if( old_disposicion != null && EnableSynchronization)
                        _disposicion_!.Curso_.Remove(this);

                    if(value != null)
                    {
                        disposicion = value.id;
                        if(EnableSynchronization && !_disposicion_!.Curso_.Contains(this))
                        {
                            _disposicion_!.EnableSynchronization = true;
                            _disposicion_!.Curso_.Add(this);
                        }
                    }
                    else
                    {
                        disposicion = null;
                    }
                    NotifyPropertyChanged(nameof(disposicion_));
                }
            }
        }
        #endregion

        #region Calificacion_ (ref calificacion.curso _m:o curso.id)
        public ObservableCollection<Calificacion> Calificacion_ { get; set; } = new ();
        #endregion

        #region Horario_ (ref horario.curso _m:o curso.id)
        public ObservableCollection<Horario> Horario_ { get; set; } = new ();
        #endregion

        #region Toma_ (ref toma.curso _m:o curso.id)
        public ObservableCollection<Toma> Toma_ { get; set; } = new ();
        #endregion

    }
}
