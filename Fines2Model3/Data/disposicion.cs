#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Disposicion : EntityData
    {

        public Disposicion()
        {
            _entityName = "disposicion";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set { if( _asignatura != value) { _asignatura = value; NotifyPropertyChanged(nameof(asignatura)); } }
        }
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set { if( _planificacion != value) { _planificacion = value; NotifyPropertyChanged(nameof(planificacion)); } }
        }
        protected int? _orden_informe_coordinacion_distrital = null;
        public int? orden_informe_coordinacion_distrital
        {
            get { return _orden_informe_coordinacion_distrital; }
            set { if( _orden_informe_coordinacion_distrital != value) { _orden_informe_coordinacion_distrital = value; NotifyPropertyChanged(nameof(orden_informe_coordinacion_distrital)); } }
        }
        //disposicion.asignatura _o:o asignatura.id
        protected Asignatura? _asignatura_ = null;
        public Asignatura? asignatura_
        {
            get { return _asignatura_; }
            set {
                _asignatura_ = value;
                asignatura = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(asignatura_));
            }
        }

        //disposicion.planificacion _o:o planificacion.id
        protected Planificacion? _planificacion_ = null;
        public Planificacion? planificacion_
        {
            get { return _planificacion_; }
            set {
                _planificacion_ = value;
                planificacion = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(planificacion_));
            }
        }

        //calificacion.disposicion _m:o disposicion.id
        public ObservableCollection<Calificacion> Calificacion_ { get; set; } = new ();

        //curso.disposicion _m:o disposicion.id
        public ObservableCollection<Curso> Curso_ { get; set; } = new ();

        //disposicion_pendiente.disposicion _m:o disposicion.id
        public ObservableCollection<DisposicionPendiente> DisposicionPendiente_ { get; set; } = new ();

        //distribucion_horaria.disposicion _m:o disposicion.id
        public ObservableCollection<DistribucionHoraria> DistribucionHoraria_ { get; set; } = new ();

    }
}
