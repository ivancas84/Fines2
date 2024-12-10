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
    public partial class DisposicionPendiente : Entity
    {

        public DisposicionPendiente()
        {
            _entityName = "disposicion_pendiente";
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

        #region disposicion
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set {
                if( _disposicion != value)
                {
                    _disposicion = value; NotifyPropertyChanged(nameof(disposicion));
                    //desactivado hasta implementar cache
                    //if (_disposicion.HasValue && (disposicion_.IsNoE() || !disposicion_!.Get(db.config.id).ToString()!.Equals(_disposicion.Value.ToString())))
                    //    disposicion_ = CreateFromId<Disposicion>(_disposicion);
                    //else if(_disposicion.IsNoE())
                    //    disposicion_ = null;
                }
            }
        }
        #endregion

        #region alumno
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set {
                if( _alumno != value)
                {
                    _alumno = value; NotifyPropertyChanged(nameof(alumno));
                    //desactivado hasta implementar cache
                    //if (_alumno.HasValue && (alumno_.IsNoE() || !alumno_!.Get(db.config.id).ToString()!.Equals(_alumno.Value.ToString())))
                    //    alumno_ = CreateFromId<Alumno>(_alumno);
                    //else if(_alumno.IsNoE())
                    //    alumno_ = null;
                }
            }
        }
        #endregion

        #region modo
        protected string? _modo = null;
        public string? modo
        {
            get { return _modo; }
            set {
                if( _modo != value)
                {
                    _modo = value; NotifyPropertyChanged(nameof(modo));
                }
            }
        }
        #endregion

        #region disposicion (fk disposicion_pendiente.disposicion _m:o disposicion.id)
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

        #region alumno (fk disposicion_pendiente.alumno _m:o alumno.id)
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set {
                if ( _alumno_ != value)
                {
                    _alumno_ = value;
                    if(value != null)
                        alumno = value.id;
                    else
                        alumno = null;
                    NotifyPropertyChanged(nameof(alumno_));
                }
            }
        }
        #endregion

        public static IEnumerable<DisposicionPendiente> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<DisposicionPendiente, Disposicion, Asignatura, Planificacion, Plan, Alumno, Persona, DisposicionPendiente>(
                sql,
                (main, disposicion, asignatura, planificacion, plan, alumno, persona) =>
                {
                    main.disposicion_ = disposicion;
                    if(!asignatura.IsNoE()) disposicion.asignatura_ = asignatura;
                    if(!planificacion.IsNoE()) disposicion.planificacion_ = planificacion;
                    if(!plan.IsNoE()) planificacion.plan_ = plan;
                    main.alumno_ = alumno;
                    if(!persona.IsNoE()) alumno.persona_ = persona;
                    return main;
                },
                parameters,
                splitOn:Context.db.Sql().SplitOn("disposicion_pendiente")
            );
        }
    }
}
