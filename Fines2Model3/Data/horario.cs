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
    public partial class Horario : Entity
    {

        public Horario()
        {
            _entityName = "horario";
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

        #region hora_inicio
        protected DateTime? _hora_inicio = null;
        public DateTime? hora_inicio
        {
            get { return _hora_inicio; }
            set {
                if( _hora_inicio != value)
                {
                    _hora_inicio = value; NotifyPropertyChanged(nameof(hora_inicio));
                }
            }
        }
        #endregion

        #region hora_fin
        protected DateTime? _hora_fin = null;
        public DateTime? hora_fin
        {
            get { return _hora_fin; }
            set {
                if( _hora_fin != value)
                {
                    _hora_fin = value; NotifyPropertyChanged(nameof(hora_fin));
                }
            }
        }
        #endregion

        #region curso
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set {
                if( _curso != value)
                {
                    _curso = value; NotifyPropertyChanged(nameof(curso));
                    //desactivado hasta implementar cache
                    //if (_curso.HasValue && (curso_.IsNoE() || !curso_!.Get(db.config.id).ToString()!.Equals(_curso.Value.ToString())))
                    //    curso_ = CreateFromId<Curso>(_curso);
                    //else if(_curso.IsNoE())
                    //    curso_ = null;
                }
            }
        }
        #endregion

        #region dia
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set {
                if( _dia != value)
                {
                    _dia = value; NotifyPropertyChanged(nameof(dia));
                    //desactivado hasta implementar cache
                    //if (_dia.HasValue && (dia_.IsNoE() || !dia_!.Get(db.config.id).ToString()!.Equals(_dia.Value.ToString())))
                    //    dia_ = CreateFromId<Dia>(_dia);
                    //else if(_dia.IsNoE())
                    //    dia_ = null;
                }
            }
        }
        #endregion

        #region curso (fk horario.curso _m:o curso.id)
        protected Curso? _curso_ = null;
        public Curso? curso_
        {
            get { return _curso_; }
            set {
                if ( _curso_ != value)
                {
                    _curso_ = value;
                    if(value != null)
                        curso = value.id;
                    else
                        curso = null;
                    NotifyPropertyChanged(nameof(curso_));
                }
            }
        }
        #endregion

        #region dia (fk horario.dia _m:o dia.id)
        protected Dia? _dia_ = null;
        public Dia? dia_
        {
            get { return _dia_; }
            set {
                if ( _dia_ != value)
                {
                    _dia_ = value;
                    if(value != null)
                        dia = value.id;
                    else
                        dia = null;
                    NotifyPropertyChanged(nameof(dia_));
                }
            }
        }
        #endregion

        public static IEnumerable<Horario> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Horario, Curso, Comision, Sede, Domicilio, TipoSede, CentroEducativo, Horario>(
                sql,
                (main, curso, comision, sede, domicilio, tipo_sede, centro_educativo) =>
                {
                    main.curso_ = curso;
                    if(!comision.IsNoE()) curso.comision_ = comision;
                    if(!sede.IsNoE()) comision.sede_ = sede;
                    if(!domicilio.IsNoE()) sede.domicilio_ = domicilio;
                    if(!tipo_sede.IsNoE()) sede.tipo_sede_ = tipo_sede;
                    if(!centro_educativo.IsNoE()) sede.centro_educativo_ = centro_educativo;
                    return main;
                },
                parameters,
                splitOn:Context.db.Sql().SplitOn("horario")
            );
        }
    }
}
