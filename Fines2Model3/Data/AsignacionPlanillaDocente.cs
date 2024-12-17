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
    public partial class AsignacionPlanillaDocente : Entity
    {

        public AsignacionPlanillaDocente()
        {
            _entityName = "asignacion_planilla_docente";
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

        #region planilla_docente
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set {
                if( _planilla_docente != value)
                {
                    _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente));
                    if (!_planilla_docente.IsNoE() && (planilla_docente_.IsNoE() || !planilla_docente_!.Get(db.config.id).ToString()!.Equals(_planilla_docente.ToString())))
                        planilla_docente_ = CreateFromId<PlanillaDocente>(_planilla_docente);
                    else if(_planilla_docente.IsNoE())
                        planilla_docente_ = null;
                }
            }
        }
        #endregion

        #region toma
        protected string? _toma = null;
        public string? toma
        {
            get { return _toma; }
            set {
                if( _toma != value)
                {
                    _toma = value; NotifyPropertyChanged(nameof(toma));
                    if (!_toma.IsNoE() && (toma_.IsNoE() || !toma_!.Get(db.config.id).ToString()!.Equals(_toma.ToString())))
                        toma_ = CreateFromId<Toma>(_toma);
                    else if(_toma.IsNoE())
                        toma_ = null;
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

        #region comentario
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set {
                if( _comentario != value)
                {
                    _comentario = value; NotifyPropertyChanged(nameof(comentario));
                }
            }
        }
        #endregion

        #region reclamo
        protected bool? _reclamo = null;
        public bool? reclamo
        {
            get { return _reclamo; }
            set {
                if( _reclamo != value)
                {
                    _reclamo = value; NotifyPropertyChanged(nameof(reclamo));
                }
            }
        }
        #endregion

        #region planilla_docente (fk asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id)
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

        #region toma (fk asignacion_planilla_docente.toma _m:o toma.id)
        protected Toma? _toma_ = null;
        public Toma? toma_
        {
            get { return _toma_; }
            set {
                if ( _toma_ != value)
                {
                    _toma_ = value;
                    if(value != null)
                        toma = value.id;
                    else
                        toma = null;
                    NotifyPropertyChanged(nameof(toma_));
                }
            }
        }
        #endregion

        public static IEnumerable<AsignacionPlanillaDocente> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<AsignacionPlanillaDocente, PlanillaDocente, Toma, Curso, Comision, Sede, Domicilio, AsignacionPlanillaDocente>(
                sql,
                (main, planilla_docente, toma, curso, comision, sede, domicilio) =>
                {
                    main.planilla_docente_ = planilla_docente;
                    main.toma_ = toma;
                    if(!curso.IsNoE()) toma.curso_ = curso;
                    if(!comision.IsNoE()) curso.comision_ = comision;
                    if(!sede.IsNoE()) comision.sede_ = sede;
                    if(!domicilio.IsNoE()) sede.domicilio_ = domicilio;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
