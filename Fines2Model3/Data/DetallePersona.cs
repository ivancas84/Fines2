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
    public partial class DetallePersona : Entity
    {

        public DetallePersona()
        {
            _entityName = "detalle_persona";
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

        #region descripcion
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set {
                if( _descripcion != value)
                {
                    _descripcion = value; NotifyPropertyChanged(nameof(descripcion));
                }
            }
        }
        #endregion

        #region archivo
        protected string? _archivo = null;
        public string? archivo
        {
            get { return _archivo; }
            set {
                if( _archivo != value)
                {
                    _archivo = value; NotifyPropertyChanged(nameof(archivo));
                    if (!_archivo.IsNoE() && (archivo_.IsNoE() || !archivo_!.Get(db.config.id).ToString()!.Equals(_archivo.ToString())))
                        archivo_ = CreateFromId<File>(_archivo);
                    else if(_archivo.IsNoE())
                        archivo_ = null;
                }
            }
        }
        #endregion

        #region creado
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set {
                if( _creado != value)
                {
                    _creado = value; NotifyPropertyChanged(nameof(creado));
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

        #region fecha
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set {
                if( _fecha != value)
                {
                    _fecha = value; NotifyPropertyChanged(nameof(fecha));
                }
            }
        }
        #endregion

        #region tipo
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set {
                if( _tipo != value)
                {
                    _tipo = value; NotifyPropertyChanged(nameof(tipo));
                }
            }
        }
        #endregion

        #region asunto
        protected string? _asunto = null;
        public string? asunto
        {
            get { return _asunto; }
            set {
                if( _asunto != value)
                {
                    _asunto = value; NotifyPropertyChanged(nameof(asunto));
                }
            }
        }
        #endregion

        #region archivo (fk detalle_persona.archivo _m:o file.id)
        protected File? _archivo_ = null;
        public File? archivo_
        {
            get { return _archivo_; }
            set {
                if ( _archivo_ != value)
                {
                    _archivo_ = value;
                    if(value != null)
                        archivo = value.id;
                    else
                        archivo = null;
                    NotifyPropertyChanged(nameof(archivo_));
                }
            }
        }
        #endregion

        #region persona (fk detalle_persona.persona _m:o persona.id)
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

        public static IEnumerable<DetallePersona> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<DetallePersona, File, Persona, Domicilio, DetallePersona>(
                sql,
                (main, archivo, persona, domicilio) =>
                {
                    main.archivo_ = archivo;
                    main.persona_ = persona;
                    if(!domicilio.IsNoE()) persona.domicilio_ = domicilio;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
