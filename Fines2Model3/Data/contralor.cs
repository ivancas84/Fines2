#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Contralor : EntityData
    {

        public Contralor()
        {
            _entityName = "contralor";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected DateTime? _fecha_contralor = null;
        public DateTime? fecha_contralor
        {
            get { return _fecha_contralor; }
            set { if( _fecha_contralor != value) { _fecha_contralor = value; NotifyPropertyChanged(nameof(fecha_contralor)); } }
        }
        protected DateTime? _fecha_consejo = null;
        public DateTime? fecha_consejo
        {
            get { return _fecha_consejo; }
            set { if( _fecha_consejo != value) { _fecha_consejo = value; NotifyPropertyChanged(nameof(fecha_consejo)); } }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { if( _planilla_docente != value) { _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente)); } }
        }
        //contralor.planilla_docente _o:o planilla_docente.id
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

    }
}
