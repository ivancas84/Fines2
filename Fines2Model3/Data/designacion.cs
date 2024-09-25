#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

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

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region desde
        protected DateTime? _desde = null;
        public DateTime? desde
        {
            get { return _desde; }
            set { if( _desde != value) { _desde = value; NotifyPropertyChanged(nameof(desde)); } }
        }
        #endregion

        #region hasta
        protected DateTime? _hasta = null;
        public DateTime? hasta
        {
            get { return _hasta; }
            set { if( _hasta != value) { _hasta = value; NotifyPropertyChanged(nameof(hasta)); } }
        }
        #endregion

        #region cargo
        protected string? _cargo = null;
        public string? cargo
        {
            get { return _cargo; }
            set { if( _cargo != value) { _cargo = value; NotifyPropertyChanged(nameof(cargo)); } }
        }
        #endregion

        #region sede
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { if( _sede != value) { _sede = value; NotifyPropertyChanged(nameof(sede)); } }
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

        #region alta
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        #endregion

        #region pfid
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        #endregion

        #region cargo (fk designacion.cargo _ m:o cargo.id)
        protected Cargo? _cargo_ = null;
        public Cargo? cargo_
        {
            get { return _cargo_; }
            set {
                if(value != null && AutoAddRef)
                {
                    _cargo_!.Designacion_.Remove(this);
                }
                _cargo_ = value;

                if(value != null)
                {
                    cargo = value.id;
                    if(AutoAddRef && !_cargo_!.Designacion_.Contains(this))
                    {
                        _cargo_!.Designacion_.Add(this);
                    }
                }
                else
                {
                    cargo = null;
                }
                NotifyPropertyChanged(nameof(cargo_));
            }
        }
        #endregion

        #region sede (fk designacion.sede _ m:o sede.id)
        protected Sede? _sede_ = null;
        public Sede? sede_
        {
            get { return _sede_; }
            set {
                if(value != null && AutoAddRef)
                {
                    _sede_!.Designacion_.Remove(this);
                }
                _sede_ = value;

                if(value != null)
                {
                    sede = value.id;
                    if(AutoAddRef && !_sede_!.Designacion_.Contains(this))
                    {
                        _sede_!.Designacion_.Add(this);
                    }
                }
                else
                {
                    sede = null;
                }
                NotifyPropertyChanged(nameof(sede_));
            }
        }
        #endregion

        #region persona (fk designacion.persona _ m:o persona.id)
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                if(value != null && AutoAddRef)
                {
                    _persona_!.Designacion_.Remove(this);
                }
                _persona_ = value;

                if(value != null)
                {
                    persona = value.id;
                    if(AutoAddRef && !_persona_!.Designacion_.Contains(this))
                    {
                        _persona_!.Designacion_.Add(this);
                    }
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
