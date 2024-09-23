#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Designacion : EntityData
    {

        public Designacion()
        {
            _entityName = "designacion";
            _db = Context.db;
            Default();
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected DateTime? _desde = null;
        public DateTime? desde
        {
            get { return _desde; }
            set { if( _desde != value) { _desde = value; NotifyPropertyChanged(nameof(desde)); } }
        }
        protected DateTime? _hasta = null;
        public DateTime? hasta
        {
            get { return _hasta; }
            set { if( _hasta != value) { _hasta = value; NotifyPropertyChanged(nameof(hasta)); } }
        }
        protected string? _cargo = null;
        public string? cargo
        {
            get { return _cargo; }
            set { if( _cargo != value) { _cargo = value; NotifyPropertyChanged(nameof(cargo)); } }
        }
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { if( _sede != value) { _sede = value; NotifyPropertyChanged(nameof(sede)); } }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { if( _persona != value) { _persona = value; NotifyPropertyChanged(nameof(persona)); } }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        //designacion.cargo _m:o cargo.id
        protected Cargo? _cargo_ = null;
        public Cargo? cargo_
        {
            get { return _cargo_; }
            set {
                _cargo_ = value;
                cargo = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(cargo_));
            }
        }

        //designacion.sede _m:o sede.id
        protected Sede? _sede_ = null;
        public Sede? sede_
        {
            get { return _sede_; }
            set {
                _sede_ = value;
                sede = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(sede_));
            }
        }

        //designacion.persona _m:o persona.id
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
