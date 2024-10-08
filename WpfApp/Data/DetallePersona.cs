#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.FinesApp.Data
{
    public partial class DetallePersona : Entity
    {

        public DetallePersona()
        {
            _entityName = "detalle_persona";
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

        #region descripcion
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { if( _descripcion != value) { _descripcion = value; NotifyPropertyChanged(nameof(descripcion)); } }
        }
        #endregion

        #region archivo
        protected string? _archivo = null;
        public string? archivo
        {
            get { return _archivo; }
            set { if( _archivo != value) { _archivo = value; NotifyPropertyChanged(nameof(archivo)); } }
        }
        #endregion

        #region creado
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { if( _creado != value) { _creado = value; NotifyPropertyChanged(nameof(creado)); } }
        }
        #endregion

        #region persona
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { if( _persona != value) { _persona = value; NotifyPropertyChanged(nameof(persona)); } }
        }
        #endregion

        #region fecha
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { if( _fecha != value) { _fecha = value; NotifyPropertyChanged(nameof(fecha)); } }
        }
        #endregion

        #region tipo
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { if( _tipo != value) { _tipo = value; NotifyPropertyChanged(nameof(tipo)); } }
        }
        #endregion

        #region asunto
        protected string? _asunto = null;
        public string? asunto
        {
            get { return _asunto; }
            set { if( _asunto != value) { _asunto = value; NotifyPropertyChanged(nameof(asunto)); } }
        }
        #endregion

        #region archivo (fk detalle_persona.archivo _m:o file.id)
        protected File? _archivo_ = null;
        public File? archivo_
        {
            get { return _archivo_; }
            set {
                if( _archivo_ != null && AutoAddToCollection)
                    _archivo_!.DetallePersona_archivo_.Remove(this);

                _archivo_ = value;

                if(value != null)
                {
                    archivo = value.id;
                    if(AutoAddToCollection && !_archivo_!.DetallePersona_archivo_.Contains(this))
                        _archivo_!.DetallePersona_archivo_.Add(this);
                }
                else
                {
                    archivo = null;
                }
                NotifyPropertyChanged(nameof(archivo_));
            }
        }
        #endregion

        #region persona (fk detalle_persona.persona _m:o persona.id)
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                if( _persona_ != null && AutoAddToCollection)
                    _persona_!.DetallePersona_.Remove(this);

                _persona_ = value;

                if(value != null)
                {
                    persona = value.id;
                    if(AutoAddToCollection && !_persona_!.DetallePersona_.Contains(this))
                        _persona_!.DetallePersona_.Add(this);
                }
                else
                {
                    persona = null;
                }
                NotifyPropertyChanged(nameof(persona_));
            }
        }
        #endregion

    }
}
