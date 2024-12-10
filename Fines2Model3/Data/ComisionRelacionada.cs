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
    public partial class ComisionRelacionada : Entity
    {

        public ComisionRelacionada()
        {
            _entityName = "comision_relacionada";
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

        #region comision
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set {
                if( _comision != value)
                {
                    _comision = value; NotifyPropertyChanged(nameof(comision));
                    //desactivado hasta implementar cache
                    //if (_comision.HasValue && (comision_.IsNoE() || !comision_!.Get(db.config.id).ToString()!.Equals(_comision.Value.ToString())))
                    //    comision_ = CreateFromId<Comision>(_comision);
                    //else if(_comision.IsNoE())
                    //    comision_ = null;
                }
            }
        }
        #endregion

        #region relacion
        protected string? _relacion = null;
        public string? relacion
        {
            get { return _relacion; }
            set {
                if( _relacion != value)
                {
                    _relacion = value; NotifyPropertyChanged(nameof(relacion));
                    //desactivado hasta implementar cache
                    //if (_relacion.HasValue && (relacion_.IsNoE() || !relacion_!.Get(db.config.id).ToString()!.Equals(_relacion.Value.ToString())))
                    //    relacion_ = CreateFromId<Comision>(_relacion);
                    //else if(_relacion.IsNoE())
                    //    relacion_ = null;
                }
            }
        }
        #endregion

        #region comision (fk comision_relacionada.comision _m:o comision.id)
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                if ( _comision_ != value)
                {
                    _comision_ = value;
                    if(value != null)
                        comision = value.id;
                    else
                        comision = null;
                    NotifyPropertyChanged(nameof(comision_));
                }
            }
        }
        #endregion

        #region relacion (fk comision_relacionada.relacion _m:o comision.id)
        protected Comision? _relacion_ = null;
        public Comision? relacion_
        {
            get { return _relacion_; }
            set {
                if ( _relacion_ != value)
                {
                    _relacion_ = value;
                    if(value != null)
                        relacion = value.id;
                    else
                        relacion = null;
                    NotifyPropertyChanged(nameof(relacion_));
                }
            }
        }
        #endregion

        public static IEnumerable<ComisionRelacionada> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<ComisionRelacionada, Comision, Sede, Domicilio, TipoSede, CentroEducativo, Domicilio, ComisionRelacionada>(
                sql,
                (main, comision, sede, domicilio, tipo_sede, centro_educativo, domicilio_cen) =>
                {
                    main.comision_ = comision;
                    if(!sede.IsNoE()) comision.sede_ = sede;
                    if(!domicilio.IsNoE()) sede.domicilio_ = domicilio;
                    if(!tipo_sede.IsNoE()) sede.tipo_sede_ = tipo_sede;
                    if(!centro_educativo.IsNoE()) sede.centro_educativo_ = centro_educativo;
                    if(!domicilio_cen.IsNoE()) centro_educativo.domicilio_ = domicilio_cen;
                    return main;
                },
                parameters,
                splitOn:Context.db.Sql().SplitOn("comision_relacionada")
            );
        }
    }
}
