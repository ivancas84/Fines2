#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Disposicion : Entity
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
                        if (_asignatura_ != null)
                        {
                            _asignatura_!.EnableSynchronization = true;
                            if (!_asignatura_!.Disposicion_.Contains(this))
                                _asignatura_!.Disposicion_.Add(this);
                        }

                        if (_planificacion_ != null)
                        {
                            _planificacion_!.EnableSynchronization = true;
                            if (!_planificacion_!.Disposicion_.Contains(this))
                                _planificacion_!.Disposicion_.Add(this);
                        }

                        foreach(var obj in Calificacion_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.disposicion_ != this)
                                 obj.disposicion_ = this;
                        }

                        foreach(var obj in Curso_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.disposicion_ != this)
                                 obj.disposicion_ = this;
                        }

                        foreach(var obj in DisposicionPendiente_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.disposicion_ != this)
                                 obj.disposicion_ = this;
                        }

                        foreach(var obj in DistribucionHoraria_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.disposicion_ != this)
                                 obj.disposicion_ = this;
                        }

                    }
                }
            }
        }

        public Disposicion()
        {
            _entityName = "disposicion";
            _db = Context.db;
            Default();
            Calificacion_.CollectionChanged += Calificacion_CollectionChanged;
            Curso_.CollectionChanged += Curso_CollectionChanged;
            DisposicionPendiente_.CollectionChanged += DisposicionPendiente_CollectionChanged;
            DistribucionHoraria_.CollectionChanged += DistribucionHoraria_CollectionChanged;
        }

        private void Calificacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Calificacion obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
                }
            }
        }
        private void Curso_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Curso obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
                }
            }
        }
        private void DisposicionPendiente_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (DisposicionPendiente obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
                }
            }
        }
        private void DistribucionHoraria_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (DistribucionHoraria obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.disposicion_ != this)
                        obj.disposicion_ = this;
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

        #region asignatura
        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set { if( _asignatura != value) { _asignatura = value; NotifyPropertyChanged(nameof(asignatura)); } }
        }
        #endregion

        #region planificacion
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set { if( _planificacion != value) { _planificacion = value; NotifyPropertyChanged(nameof(planificacion)); } }
        }
        #endregion

        #region orden_informe_coordinacion_distrital
        protected int? _orden_informe_coordinacion_distrital = null;
        public int? orden_informe_coordinacion_distrital
        {
            get { return _orden_informe_coordinacion_distrital; }
            set { if( _orden_informe_coordinacion_distrital != value) { _orden_informe_coordinacion_distrital = value; NotifyPropertyChanged(nameof(orden_informe_coordinacion_distrital)); } }
        }
        #endregion

        #region asignatura (fk disposicion.asignatura _m:o asignatura.id)
        protected Asignatura? _asignatura_ = null;
        public Asignatura? asignatura_
        {
            get { return _asignatura_; }
            set {
                if(  _asignatura_ != value )
                {
                    var old_asignatura = _asignatura;
                    _asignatura_ = value;

                    if( old_asignatura != null && EnableSynchronization)
                        _asignatura_!.Disposicion_.Remove(this);

                    if(value != null)
                    {
                        asignatura = value.id;
                        if(EnableSynchronization && !_asignatura_!.Disposicion_.Contains(this))
                        {
                            _asignatura_!.EnableSynchronization = true;
                            _asignatura_!.Disposicion_.Add(this);
                        }
                    }
                    else
                    {
                        asignatura = null;
                    }
                    NotifyPropertyChanged(nameof(asignatura_));
                }
            }
        }
        #endregion

        #region planificacion (fk disposicion.planificacion _m:o planificacion.id)
        protected Planificacion? _planificacion_ = null;
        public Planificacion? planificacion_
        {
            get { return _planificacion_; }
            set {
                if(  _planificacion_ != value )
                {
                    var old_planificacion = _planificacion;
                    _planificacion_ = value;

                    if( old_planificacion != null && EnableSynchronization)
                        _planificacion_!.Disposicion_.Remove(this);

                    if(value != null)
                    {
                        planificacion = value.id;
                        if(EnableSynchronization && !_planificacion_!.Disposicion_.Contains(this))
                        {
                            _planificacion_!.EnableSynchronization = true;
                            _planificacion_!.Disposicion_.Add(this);
                        }
                    }
                    else
                    {
                        planificacion = null;
                    }
                    NotifyPropertyChanged(nameof(planificacion_));
                }
            }
        }
        #endregion

        #region Calificacion_ (ref calificacion.disposicion _m:o disposicion.id)
        public ObservableCollection<Calificacion> Calificacion_ { get; set; } = new ();
        #endregion

        #region Curso_ (ref curso.disposicion _m:o disposicion.id)
        public ObservableCollection<Curso> Curso_ { get; set; } = new ();
        #endregion

        #region DisposicionPendiente_ (ref disposicion_pendiente.disposicion _m:o disposicion.id)
        public ObservableCollection<DisposicionPendiente> DisposicionPendiente_ { get; set; } = new ();
        #endregion

        #region DistribucionHoraria_ (ref distribucion_horaria.disposicion _m:o disposicion.id)
        public ObservableCollection<DistribucionHoraria> DistribucionHoraria_ { get; set; } = new ();
        #endregion

    }
}
