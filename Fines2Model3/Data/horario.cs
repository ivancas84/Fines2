#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Horario : EntityData
    {

        public Horario()
        {
            _entityName = "horario";
            _db = Context.db;
            Default();
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected DateTime? _hora_inicio = null;
        public DateTime? hora_inicio
        {
            get { return _hora_inicio; }
            set { if( _hora_inicio != value) { _hora_inicio = value; NotifyPropertyChanged(nameof(hora_inicio)); } }
        }
        protected DateTime? _hora_fin = null;
        public DateTime? hora_fin
        {
            get { return _hora_fin; }
            set { if( _hora_fin != value) { _hora_fin = value; NotifyPropertyChanged(nameof(hora_fin)); } }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { if( _curso != value) { _curso = value; NotifyPropertyChanged(nameof(curso)); } }
        }
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set { if( _dia != value) { _dia = value; NotifyPropertyChanged(nameof(dia)); } }
        }
        //horario.curso _m:o curso.id
        protected Curso? _curso_ = null;
        public Curso? curso_
        {
            get { return _curso_; }
            set {
                _curso_ = value;
                curso = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(curso_));
            }
        }

        //horario.dia _m:o dia.id
        protected Dia? _dia_ = null;
        public Dia? dia_
        {
            get { return _dia_; }
            set {
                _dia_ = value;
                dia = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(dia_));
            }
        }

    }
}
