#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class ComisionRelacionada : EntityData
    {

        public ComisionRelacionada()
        {
            _entityName = "comision_relacionada";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { if( _comision != value) { _comision = value; NotifyPropertyChanged(nameof(comision)); } }
        }
        protected string? _relacion = null;
        public string? relacion
        {
            get { return _relacion; }
            set { if( _relacion != value) { _relacion = value; NotifyPropertyChanged(nameof(relacion)); } }
        }
        //comision_relacionada.comision _o:o comision.id
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                _comision_ = value;
                comision = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(comision_));
            }
        }

        //comision_relacionada.relacion _o:o comision.id
        protected Comision? _relacion_ = null;
        public Comision? relacion_
        {
            get { return _relacion_; }
            set {
                _relacion_ = value;
                relacion = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(relacion_));
            }
        }

    }
}
