#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class DistribucionHoraria : EntityData
    {

        public DistribucionHoraria()
        {
            _entityName = "distribucion_horaria";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set { if( _horas_catedra != value) { _horas_catedra = value; NotifyPropertyChanged(nameof(horas_catedra)); } }
        }
        protected int? _dia = null;
        public int? dia
        {
            get { return _dia; }
            set { if( _dia != value) { _dia = value; NotifyPropertyChanged(nameof(dia)); } }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { if( _disposicion != value) { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); } }
        }
        //distribucion_horaria.disposicion _o:o disposicion.id
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set {
                _disposicion_ = value;
                disposicion = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(disposicion_));
            }
        }

    }
}
