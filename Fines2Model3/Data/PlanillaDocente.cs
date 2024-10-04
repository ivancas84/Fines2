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

        public PlanillaDocente()
        {
            _entityName = "planilla_docente";
            _db = Context.db;
            Default();
            AsignacionPlanillaDocente_.CollectionChanged += AsignacionPlanillaDocente_CollectionChanged;
            Contralor_.CollectionChanged += Contralor_CollectionChanged;
            Toma_.CollectionChanged += Toma_CollectionChanged;
        }

        #region CollectionChanged
        private void AsignacionPlanillaDocente_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (AsignacionPlanillaDocente obj in e.NewItems)
                    if(obj.planilla_docente_ != this)
                        obj.planilla_docente_ = this;
        }
        private void Contralor_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Contralor obj in e.NewItems)
                    if(obj.planilla_docente_ != this)
                        obj.planilla_docente_ = this;
        }
        private void Toma_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Toma obj in e.NewItems)
                    if(obj.planilla_docente_ != this)
                        obj.planilla_docente_ = this;
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
        protected ObservableCollection<AsignacionPlanillaDocente> _AsignacionPlanillaDocente_ = new ();
        public ObservableCollection<AsignacionPlanillaDocente> AsignacionPlanillaDocente_
        {
            get { return _AsignacionPlanillaDocente_; }
            set { if( _AsignacionPlanillaDocente_ != value) { _AsignacionPlanillaDocente_ = value; NotifyPropertyChanged(nameof(AsignacionPlanillaDocente_)); } }
        }
        #endregion

        #region Contralor_ (ref contralor.planilla_docente _m:o planilla_docente.id)
        protected ObservableCollection<Contralor> _Contralor_ = new ();
        public ObservableCollection<Contralor> Contralor_
        {
            get { return _Contralor_; }
            set { if( _Contralor_ != value) { _Contralor_ = value; NotifyPropertyChanged(nameof(Contralor_)); } }
        }
        #endregion

        #region Toma_ (ref toma.planilla_docente _m:o planilla_docente.id)
        protected ObservableCollection<Toma> _Toma_ = new ();
        public ObservableCollection<Toma> Toma_
        {
            get { return _Toma_; }
            set { if( _Toma_ != value) { _Toma_ = value; NotifyPropertyChanged(nameof(Toma_)); } }
        }
        #endregion

    }
}
