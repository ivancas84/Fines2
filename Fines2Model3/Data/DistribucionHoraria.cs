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
    public partial class DistribucionHoraria : Entity
    {

        public DistribucionHoraria()
        {
            _entityName = "distribucion_horaria";
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

        #region horas_catedra
        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set {
                if( _horas_catedra != value)
                {
                    _horas_catedra = value; NotifyPropertyChanged(nameof(horas_catedra));
                }
            }
        }
        #endregion

        #region dia
        protected int? _dia = null;
        public int? dia
        {
            get { return _dia; }
            set {
                if( _dia != value)
                {
                    _dia = value; NotifyPropertyChanged(nameof(dia));
                }
            }
        }
        #endregion

        #region disposicion
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set {
                if( _disposicion != value)
                {
                    _disposicion = value; NotifyPropertyChanged(nameof(disposicion));
                    if (!_disposicion.IsNoE() && (disposicion_.IsNoE() || !disposicion_!.Get(db.config.id).ToString()!.Equals(_disposicion.ToString())))
                        disposicion_ = CreateFromId<Disposicion>(_disposicion);
                    else if(_disposicion.IsNoE())
                        disposicion_ = null;
                }
            }
        }
        #endregion

        #region disposicion (fk distribucion_horaria.disposicion _m:o disposicion.id)
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set {
                if ( _disposicion_ != value)
                {
                    _disposicion_ = value;
                    if(value != null)
                        disposicion = value.id;
                    else
                        disposicion = null;
                    NotifyPropertyChanged(nameof(disposicion_));
                }
            }
        }
        #endregion

        public static IEnumerable<DistribucionHoraria> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<DistribucionHoraria, Disposicion, Asignatura, Planificacion, Plan, DistribucionHoraria>(
                sql,
                (main, disposicion, asignatura, planificacion, plan) =>
                {
                    main.disposicion_ = disposicion;
                    if(!asignatura.IsNoE()) disposicion.asignatura_ = asignatura;
                    if(!planificacion.IsNoE()) disposicion.planificacion_ = planificacion;
                    if(!plan.IsNoE()) planificacion.plan_ = plan;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
