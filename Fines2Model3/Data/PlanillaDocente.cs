#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class PlanillaDocente : Entity
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
                        foreach(var obj in AsignacionPlanillaDocente_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.planilla_docente_ != this)
                                 obj.planilla_docente_ = this;
                        }

                        foreach(var obj in Contralor_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.planilla_docente_ != this)
                                 obj.planilla_docente_ = this;
                        }

                        foreach(var obj in Toma_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.planilla_docente_ != this)
                                 obj.planilla_docente_ = this;
                        }

                    }
                }
            }
        }

        public PlanillaDocente()
        {
            _entityName = "planilla_docente";
            _db = Context.db;
            Default();
            AsignacionPlanillaDocente_.CollectionChanged += AsignacionPlanillaDocente_CollectionChanged;
            Contralor_.CollectionChanged += Contralor_CollectionChanged;
            Toma_.CollectionChanged += Toma_CollectionChanged;
        }

        private void AsignacionPlanillaDocente_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (AsignacionPlanillaDocente obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.planilla_docente_ != this)
                        obj.planilla_docente_ = this;
                }
            }
        }
        private void Contralor_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Contralor obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.planilla_docente_ != this)
                        obj.planilla_docente_ = this;
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
                    if(obj.planilla_docente_ != this)
                        obj.planilla_docente_ = this;
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

        #region numero
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
        }
        #endregion

        #region insertado
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        #endregion

        #region fecha_contralor
        protected DateTime? _fecha_contralor = null;
        public DateTime? fecha_contralor
        {
            get { return _fecha_contralor; }
            set { if( _fecha_contralor != value) { _fecha_contralor = value; NotifyPropertyChanged(nameof(fecha_contralor)); } }
        }
        #endregion

        #region fecha_consejo
        protected DateTime? _fecha_consejo = null;
        public DateTime? fecha_consejo
        {
            get { return _fecha_consejo; }
            set { if( _fecha_consejo != value) { _fecha_consejo = value; NotifyPropertyChanged(nameof(fecha_consejo)); } }
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

        #region AsignacionPlanillaDocente_ (ref asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id)
        public ObservableCollection<AsignacionPlanillaDocente> AsignacionPlanillaDocente_ { get; set; } = new ();
        #endregion

        #region Contralor_ (ref contralor.planilla_docente _m:o planilla_docente.id)
        public ObservableCollection<Contralor> Contralor_ { get; set; } = new ();
        #endregion

        #region Toma_ (ref toma.planilla_docente _m:o planilla_docente.id)
        public ObservableCollection<Toma> Toma_ { get; set; } = new ();
        #endregion

    }
}
