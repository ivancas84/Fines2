#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Telefono : EntityData
    {

        public Telefono()
        {
            _entityName = "telefono";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { if( _tipo != value) { _tipo = value; NotifyPropertyChanged(nameof(tipo)); } }
        }
        protected string? _prefijo = null;
        public string? prefijo
        {
            get { return _prefijo; }
            set { if( _prefijo != value) { _prefijo = value; NotifyPropertyChanged(nameof(prefijo)); } }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        protected DateTime? _eliminado = null;
        public DateTime? eliminado
        {
            get { return _eliminado; }
            set { if( _eliminado != value) { _eliminado = value; NotifyPropertyChanged(nameof(eliminado)); } }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { if( _persona != value) { _persona = value; NotifyPropertyChanged(nameof(persona)); } }
        }
        //telefono.persona _o:o persona.id
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
