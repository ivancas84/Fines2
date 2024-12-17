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
    public partial class Designacion : Entity
    {

        public Designacion()
        {
            _entityName = "designacion";
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

        #region desde
        protected DateTime? _desde = null;
        public DateTime? desde
        {
            get { return _desde; }
            set {
                if( _desde != value)
                {
                    _desde = value; NotifyPropertyChanged(nameof(desde));
                }
            }
        }
        #endregion

        #region hasta
        protected DateTime? _hasta = null;
        public DateTime? hasta
        {
            get { return _hasta; }
            set {
                if( _hasta != value)
                {
                    _hasta = value; NotifyPropertyChanged(nameof(hasta));
                }
            }
        }
        #endregion

        #region cargo
        protected string? _cargo = null;
        public string? cargo
        {
            get { return _cargo; }
            set {
                if( _cargo != value)
                {
                    _cargo = value; NotifyPropertyChanged(nameof(cargo));
                    if (!_cargo.IsNoE() && (cargo_.IsNoE() || !cargo_!.Get(db.config.id).ToString()!.Equals(_cargo.ToString())))
                        cargo_ = CreateFromId<Cargo>(_cargo);
                    else if(_cargo.IsNoE())
                        cargo_ = null;
                }
            }
        }
        #endregion

        #region sede
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set {
                if( _sede != value)
                {
                    _sede = value; NotifyPropertyChanged(nameof(sede));
                    if (!_sede.IsNoE() && (sede_.IsNoE() || !sede_!.Get(db.config.id).ToString()!.Equals(_sede.ToString())))
                        sede_ = CreateFromId<Sede>(_sede);
                    else if(_sede.IsNoE())
                        sede_ = null;
                }
            }
        }
        #endregion

        #region persona
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set {
                if( _persona != value)
                {
                    _persona = value; NotifyPropertyChanged(nameof(persona));
                    if (!_persona.IsNoE() && (persona_.IsNoE() || !persona_!.Get(db.config.id).ToString()!.Equals(_persona.ToString())))
                        persona_ = CreateFromId<Persona>(_persona);
                    else if(_persona.IsNoE())
                        persona_ = null;
                }
            }
        }
        #endregion

        #region alta
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set {
                if( _alta != value)
                {
                    _alta = value; NotifyPropertyChanged(nameof(alta));
                }
            }
        }
        #endregion

        #region pfid
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set {
                if( _pfid != value)
                {
                    _pfid = value; NotifyPropertyChanged(nameof(pfid));
                }
            }
        }
        #endregion

        #region cargo (fk designacion.cargo _m:o cargo.id)
        protected Cargo? _cargo_ = null;
        public Cargo? cargo_
        {
            get { return _cargo_; }
            set {
                if ( _cargo_ != value)
                {
                    _cargo_ = value;
                    if(value != null)
                        cargo = value.id;
                    else
                        cargo = null;
                    NotifyPropertyChanged(nameof(cargo_));
                }
            }
        }
        #endregion

        #region sede (fk designacion.sede _m:o sede.id)
        protected Sede? _sede_ = null;
        public Sede? sede_
        {
            get { return _sede_; }
            set {
                if ( _sede_ != value)
                {
                    _sede_ = value;
                    if(value != null)
                        sede = value.id;
                    else
                        sede = null;
                    NotifyPropertyChanged(nameof(sede_));
                }
            }
        }
        #endregion

        #region persona (fk designacion.persona _m:o persona.id)
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                if ( _persona_ != value)
                {
                    _persona_ = value;
                    if(value != null)
                        persona = value.id;
                    else
                        persona = null;
                    NotifyPropertyChanged(nameof(persona_));
                }
            }
        }
        #endregion

        public static IEnumerable<Designacion> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Designacion, Cargo, Sede, Domicilio, TipoSede, CentroEducativo, Domicilio, Designacion>(
                sql,
                (main, cargo, sede, domicilio, tipo_sede, centro_educativo, domicilio_cen) =>
                {
                    main.cargo_ = cargo;
                    main.sede_ = sede;
                    if(!domicilio.IsNoE()) sede.domicilio_ = domicilio;
                    if(!tipo_sede.IsNoE()) sede.tipo_sede_ = tipo_sede;
                    if(!centro_educativo.IsNoE()) sede.centro_educativo_ = centro_educativo;
                    if(!domicilio_cen.IsNoE()) centro_educativo.domicilio_ = domicilio_cen;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
