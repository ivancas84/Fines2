#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

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

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region comision
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { if( _comision != value) { _comision = value; NotifyPropertyChanged(nameof(comision)); } }
        }
        #endregion

        #region relacion
        protected string? _relacion = null;
        public string? relacion
        {
            get { return _relacion; }
            set { if( _relacion != value) { _relacion = value; NotifyPropertyChanged(nameof(relacion)); } }
        }
        #endregion

        #region comision (fk comision_relacionada.comision _ m:o comision.id)
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set {
                if(value != null && AutoAddRef)
                {
                    _comision_!.ComisionRelacionada_.Remove(this);
                }
                _comision_ = value;

                if(value != null)
                {
                    comision = value.id;
                    if(AutoAddRef && !_comision_!.ComisionRelacionada_.Contains(this))
                    {
                        _comision_!.ComisionRelacionada_.Add(this);
                    }
                }
                else
                {
                    comision = null;
                }
                NotifyPropertyChanged(nameof(comision_));
            }
        }
        #endregion

        #region relacion (fk comision_relacionada.relacion _ m:o comision.id)
        protected Comision? _relacion_ = null;
        public Comision? relacion_
        {
            get { return _relacion_; }
            set {
                if(value != null && AutoAddRef)
                {
                    _relacion_!.ComisionRelacionada_relacion_.Remove(this);
                }
                _relacion_ = value;

                if(value != null)
                {
                    relacion = value.id;
                    if(AutoAddRef && !_relacion_!.ComisionRelacionada_relacion_.Contains(this))
                    {
                        _relacion_!.ComisionRelacionada_relacion_.Add(this);
                    }
                }
                else
                {
                    relacion = null;
                }
                NotifyPropertyChanged(nameof(relacion_));
            }
        }
        #endregion

    }
}
