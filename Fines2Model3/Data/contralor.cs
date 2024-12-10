#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;
using Dapper;
using System.Data;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Contralor : Entity
    {

        public Contralor()
        {
            _entityName = "contralor";
            _db = Context.db;
            Default();
        }

        #region CollectionChanged
        #endregion

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set {
                if( _id != value)
                {
                    _id = value; NotifyPropertyChanged(nameof(id));
                }
            }
        }
        #endregion

        #region fecha_contralor
        protected DateTime? _fecha_contralor = null;
        public DateTime? fecha_contralor
        {
            get { return _fecha_contralor; }
            set {
                if( _fecha_contralor != value)
                {
                    _fecha_contralor = value; NotifyPropertyChanged(nameof(fecha_contralor));
                }
            }
        }
        #endregion

        #region fecha_consejo
        protected DateTime? _fecha_consejo = null;
        public DateTime? fecha_consejo
        {
            get { return _fecha_consejo; }
            set {
                if( _fecha_consejo != value)
                {
                    _fecha_consejo = value; NotifyPropertyChanged(nameof(fecha_consejo));
                }
            }
        }
        #endregion

        #region insertado
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set {
                if( _insertado != value)
                {
                    _insertado = value; NotifyPropertyChanged(nameof(insertado));
                }
            }
        }
        #endregion

        #region planilla_docente
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set {
                if( _planilla_docente != value)
                {
                    _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente));
                    //desactivado hasta implementar cache
                    //if (_planilla_docente.HasValue && (planilla_docente_.IsNoE() || !planilla_docente_!.Get(db.config.id).ToString()!.Equals(_planilla_docente.Value.ToString())))
                    //    planilla_docente_ = CreateFromId<PlanillaDocente>(_planilla_docente);
                    //else if(_planilla_docente.IsNoE())
                    //    planilla_docente_ = null;
                }
            }
        }
        #endregion

        #region planilla_docente (fk contralor.planilla_docente _m:o planilla_docente.id)
        protected PlanillaDocente? _planilla_docente_ = null;
        public PlanillaDocente? planilla_docente_
        {
            get { return _planilla_docente_; }
            set {
                if ( _planilla_docente_ != value)
                {
                    _planilla_docente_ = value;
                    if(value != null)
                        planilla_docente = value.id;
                    else
                        planilla_docente = null;
                    NotifyPropertyChanged(nameof(planilla_docente_));
                }
            }
        }
        #endregion

        public static IEnumerable<Contralor> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Contralor, PlanillaDocente, Contralor>(
                sql,
                (main, planilla_docente) =>
                {
                    main.planilla_docente_ = planilla_docente;
                    return main;
                },
                parameters,
                splitOn:Context.db.Sql().SplitOn("contralor")
            );
        }
    }
}
