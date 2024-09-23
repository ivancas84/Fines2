#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AsignacionPlanillaDocente : EntityData
    {

        public AsignacionPlanillaDocente()
        {
            _entityName = "asignacion_planilla_docente";
            _db = Context.db;
            Default();
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { if( _planilla_docente != value) { _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente)); } }
        }
        protected string? _toma = null;
        public string? toma
        {
            get { return _toma; }
            set { if( _toma != value) { _toma = value; NotifyPropertyChanged(nameof(toma)); } }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { if( _comentario != value) { _comentario = value; NotifyPropertyChanged(nameof(comentario)); } }
        }
        protected bool? _reclamo = null;
        public bool? reclamo
        {
            get { return _reclamo; }
            set { if( _reclamo != value) { _reclamo = value; NotifyPropertyChanged(nameof(reclamo)); } }
        }
        //asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id
        protected PlanillaDocente? _planilla_docente_ = null;
        public PlanillaDocente? planilla_docente_
        {
            get { return _planilla_docente_; }
            set {
                _planilla_docente_ = value;
                planilla_docente = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(planilla_docente_));
            }
        }

        //asignacion_planilla_docente.toma _m:o toma.id
        protected Toma? _toma_ = null;
        public Toma? toma_
        {
            get { return _toma_; }
            set {
                _toma_ = value;
                toma = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(toma_));
            }
        }

    }
}
