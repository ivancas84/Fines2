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

        #region planificacion (fk disposicion.planificacion _m:o planificacion.id)
        protected Planificacion? _planificacion_ = null;
        public Planificacion? planificacion_
        {
            get { return _planificacion_; }
            set {
                if ( _planificacion_ != value)
                {
                    _planificacion_ = value;
                    if(value != null)
                        planificacion = value.id;
                    else
                        planificacion = null;
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
