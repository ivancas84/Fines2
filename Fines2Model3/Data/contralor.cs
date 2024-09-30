#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Contralor : Entity
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
                        if (_planilla_docente_ != null)
                        {
                            _planilla_docente_!.EnableSynchronization = true;
                            if (!_planilla_docente_!.Contralor_.Contains(this))
                                _planilla_docente_!.Contralor_.Add(this);
                        }

                    }
                }
            }
        }

        public Contralor()
        {
            _entityName = "contralor";
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

        #region insertado
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        #endregion

        #region planilla_docente
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { if( _planilla_docente != value) { _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente)); } }
        }
        #endregion

        #region planilla_docente (fk contralor.planilla_docente _m:o planilla_docente.id)
        protected PlanillaDocente? _planilla_docente_ = null;
        public PlanillaDocente? planilla_docente_
        {
            get { return _planilla_docente_; }
            set {
                if(  _planilla_docente_ != value )
                {
                    var old_planilla_docente = _planilla_docente;
                    _planilla_docente_ = value;

                    if( old_planilla_docente != null && EnableSynchronization)
                        _planilla_docente_!.Contralor_.Remove(this);

                    if(value != null)
                    {
                        planilla_docente = value.id;
                        if(EnableSynchronization && !_planilla_docente_!.Contralor_.Contains(this))
                        {
                            _planilla_docente_!.EnableSynchronization = true;
                            _planilla_docente_!.Contralor_.Add(this);
                        }
                    }
                    else
                    {
                        planilla_docente = null;
                    }
                    NotifyPropertyChanged(nameof(planilla_docente_));
                }
            }
        }
        #endregion

    }
}
