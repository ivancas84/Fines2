#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.FinesApp.Data
{
    public partial class Disposicion : Entity
    {

        public Disposicion()
        {
            _entityName = "disposicion";
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
                if( _asignatura_ != null && AutoAddToCollection)
                    _asignatura_!.Disposicion_.Remove(this);

                _asignatura_ = value;

                if(value != null)
                {
                    asignatura = value.id;
                    if(AutoAddToCollection && !_asignatura_!.Disposicion_.Contains(this))
                        _asignatura_!.Disposicion_.Add(this);
                }
                else
                {
                    asignatura = null;
                }
                NotifyPropertyChanged(nameof(asignatura_));
            }
        }
        #endregion

        #region planificacion (fk disposicion.planificacion _m:o planificacion.id)
        protected Planificacion? _planificacion_ = null;
        public Planificacion? planificacion_
        {
            get { return _planificacion_; }
            set {
                if( _planificacion_ != null && AutoAddToCollection)
                    _planificacion_!.Disposicion_.Remove(this);

                _planificacion_ = value;

                if(value != null)
                {
                    planificacion = value.id;
                    if(AutoAddToCollection && !_planificacion_!.Disposicion_.Contains(this))
                        _planificacion_!.Disposicion_.Add(this);
                }
                else
                {
                    planificacion = null;
                }
                NotifyPropertyChanged(nameof(planificacion_));
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
