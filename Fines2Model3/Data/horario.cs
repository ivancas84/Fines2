#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Horario : Entity
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
                        if (_curso_ != null)
                        {
                            _curso_!.EnableSynchronization = true;
                            if (!_curso_!.Horario_.Contains(this))
                                _curso_!.Horario_.Add(this);
                        }

                        if (_dia_ != null)
                        {
                            _dia_!.EnableSynchronization = true;
                            if (!_dia_!.Horario_.Contains(this))
                                _dia_!.Horario_.Add(this);
                        }

                    }
                }
            }
        }

        public Horario()
        {
            _entityName = "horario";
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

        #region hora_inicio
        protected DateTime? _hora_inicio = null;
        public DateTime? hora_inicio
        {
            get { return _hora_inicio; }
            set { if( _hora_inicio != value) { _hora_inicio = value; NotifyPropertyChanged(nameof(hora_inicio)); } }
        }
        #endregion

        #region hora_fin
        protected DateTime? _hora_fin = null;
        public DateTime? hora_fin
        {
            get { return _hora_fin; }
            set { if( _hora_fin != value) { _hora_fin = value; NotifyPropertyChanged(nameof(hora_fin)); } }
        }
        #endregion

        #region curso
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { if( _curso != value) { _curso = value; NotifyPropertyChanged(nameof(curso)); } }
        }
        #endregion

        #region dia
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set { if( _dia != value) { _dia = value; NotifyPropertyChanged(nameof(dia)); } }
        }
        #endregion

        #region curso (fk horario.curso _m:o curso.id)
        protected Curso? _curso_ = null;
        public Curso? curso_
        {
            get { return _curso_; }
            set {
                if(  _curso_ != value )
                {
                    var old_curso = _curso;
                    _curso_ = value;

                    if( old_curso != null && EnableSynchronization)
                        _curso_!.Horario_.Remove(this);

                    if(value != null)
                    {
                        curso = value.id;
                        if(EnableSynchronization && !_curso_!.Horario_.Contains(this))
                        {
                            _curso_!.EnableSynchronization = true;
                            _curso_!.Horario_.Add(this);
                        }
                    }
                    else
                    {
                        curso = null;
                    }
                    NotifyPropertyChanged(nameof(curso_));
                }
            }
        }
        #endregion

        #region dia (fk horario.dia _m:o dia.id)
        protected Dia? _dia_ = null;
        public Dia? dia_
        {
            get { return _dia_; }
            set {
                if(  _dia_ != value )
                {
                    var old_dia = _dia;
                    _dia_ = value;

                    if( old_dia != null && EnableSynchronization)
                        _dia_!.Horario_.Remove(this);

                    if(value != null)
                    {
                        dia = value.id;
                        if(EnableSynchronization && !_dia_!.Horario_.Contains(this))
                        {
                            _dia_!.EnableSynchronization = true;
                            _dia_!.Horario_.Add(this);
                        }
                    }
                    else
                    {
                        dia = null;
                    }
                    NotifyPropertyChanged(nameof(dia_));
                }
            }
        }
        #endregion

    }
}
