#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

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
