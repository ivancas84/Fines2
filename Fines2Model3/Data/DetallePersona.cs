#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class DetallePersona : EntityData
    {

        public DetallePersona()
        {
            _entityName = "detalle_persona";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { if( _descripcion != value) { _descripcion = value; NotifyPropertyChanged(nameof(descripcion)); } }
        }
        protected string? _archivo = null;
        public string? archivo
        {
            get { return _archivo; }
            set { if( _archivo != value) { _archivo = value; NotifyPropertyChanged(nameof(archivo)); } }
        }
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { if( _creado != value) { _creado = value; NotifyPropertyChanged(nameof(creado)); } }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { if( _persona != value) { _persona = value; NotifyPropertyChanged(nameof(persona)); } }
        }
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { if( _fecha != value) { _fecha = value; NotifyPropertyChanged(nameof(fecha)); } }
        }
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { if( _tipo != value) { _tipo = value; NotifyPropertyChanged(nameof(tipo)); } }
        }
        protected string? _asunto = null;
        public string? asunto
        {
            get { return _asunto; }
            set { if( _asunto != value) { _asunto = value; NotifyPropertyChanged(nameof(asunto)); } }
        }
        //detalle_persona.archivo _o:o file.id
        protected File? _archivo_ = null;
        public File? archivo_
        {
            get { return _archivo_; }
            set {
                _archivo_ = value;
                archivo = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(archivo_));
            }
        }

        //detalle_persona.persona _o:o persona.id
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                _persona_ = value;
                persona = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(persona_));
            }
        }

    }
}
