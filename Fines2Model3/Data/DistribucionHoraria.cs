#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class DistribucionHoraria : Entity
    {

        public override bool EnableSynchronization
        {
            get => _enableSynchronization;
            set
            {
                if(_enableSynchronization != value)
                {
                    _enableSynchronization = value;

                    if(_enableSynchronization)
                    {
                        if (_disposicion_ != null)
                        {
                            _disposicion_!.EnableSynchronization = true;
                            if (!_disposicion_!.DistribucionHoraria_.Contains(this))
                                _disposicion_!.DistribucionHoraria_.Add(this);
                        }

                    }
                }
            }
        }

        public DistribucionHoraria()
        {
            _entityName = "distribucion_horaria";
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

        #region horas_catedra
        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set { if( _horas_catedra != value) { _horas_catedra = value; NotifyPropertyChanged(nameof(horas_catedra)); } }
        }
        #endregion

        #region dia
        protected int? _dia = null;
        public int? dia
        {
            get { return _dia; }
            set { if( _dia != value) { _dia = value; NotifyPropertyChanged(nameof(dia)); } }
        }
        #endregion

        #region disposicion
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { if( _disposicion != value) { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); } }
        }
        #endregion

        #region disposicion (fk distribucion_horaria.disposicion _m:o disposicion.id)
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set {
                if(  _disposicion_ != value )
                {
                    var old_disposicion = _disposicion;
                    _disposicion_ = value;

                    if( old_disposicion != null && EnableSynchronization)
                        _disposicion_!.DistribucionHoraria_.Remove(this);

                    if(value != null)
                    {
                        disposicion = value.id;
                        if(EnableSynchronization && !_disposicion_!.DistribucionHoraria_.Contains(this))
                        {
                            _disposicion_!.EnableSynchronization = true;
                            _disposicion_!.DistribucionHoraria_.Add(this);
                        }
                    }
                    else
                    {
                        disposicion = null;
                    }
                    NotifyPropertyChanged(nameof(disposicion_));
                }
            }
        }
        #endregion

    }
}
