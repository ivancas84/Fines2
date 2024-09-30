#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Calificacion : Entity
    {

        public Calificacion()
        {
            _entityName = "calificacion";
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

        #region nota1
        protected decimal? _nota1 = null;
        public decimal? nota1
        {
            get { return _nota1; }
            set { if( _nota1 != value) { _nota1 = value; NotifyPropertyChanged(nameof(nota1)); } }
        }
        #endregion

        #region nota2
        protected decimal? _nota2 = null;
        public decimal? nota2
        {
            get { return _nota2; }
            set { if( _nota2 != value) { _nota2 = value; NotifyPropertyChanged(nameof(nota2)); } }
        }
        #endregion

        #region nota3
        protected decimal? _nota3 = null;
        public decimal? nota3
        {
            get { return _nota3; }
            set { if( _nota3 != value) { _nota3 = value; NotifyPropertyChanged(nameof(nota3)); } }
        }
        #endregion

        #region nota_final
        protected decimal? _nota_final = null;
        public decimal? nota_final
        {
            get { return _nota_final; }
            set { if( _nota_final != value) { _nota_final = value; NotifyPropertyChanged(nameof(nota_final)); } }
        }
        #endregion

        #region crec
        protected decimal? _crec = null;
        public decimal? crec
        {
            get { return _crec; }
            set { if( _crec != value) { _crec = value; NotifyPropertyChanged(nameof(crec)); } }
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

        #region porcentaje_asistencia
        protected int? _porcentaje_asistencia = null;
        public int? porcentaje_asistencia
        {
            get { return _porcentaje_asistencia; }
            set { if( _porcentaje_asistencia != value) { _porcentaje_asistencia = value; NotifyPropertyChanged(nameof(porcentaje_asistencia)); } }
        }
        #endregion

        #region observaciones
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        #endregion

        #region division
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { if( _division != value) { _division = value; NotifyPropertyChanged(nameof(division)); } }
        }
        #endregion

        #region alumno
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { if( _alumno != value) { _alumno = value; NotifyPropertyChanged(nameof(alumno)); } }
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

        #region fecha
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { if( _fecha != value) { _fecha = value; NotifyPropertyChanged(nameof(fecha)); } }
        }
        #endregion

        #region archivado
        protected bool? _archivado = null;
        public bool? archivado
        {
            get { return _archivado; }
            set { if( _archivado != value) { _archivado = value; NotifyPropertyChanged(nameof(archivado)); } }
        }
        #endregion

        #region curso (fk calificacion.curso _m:o curso.id)
        protected Curso? _curso_ = null;
        public Curso? curso_
        {
            get { return _curso_; }
            set {
                if ( _curso_ != value)
                {
                    _curso_ = value;
                    if(value != null)
                        curso = value.id;
                    else
                        curso = null;
                    NotifyPropertyChanged(nameof(curso_));
                }
            }
        }
        #endregion

        #region alumno (fk calificacion.alumno _m:o alumno.id)
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set {
                if ( _alumno_ != value)
                {
                    _alumno_ = value;
                    if(value != null)
                        alumno = value.id;
                    else
                        alumno = null;
                    NotifyPropertyChanged(nameof(alumno_));
                }
            }
        }
        #endregion

        #region disposicion (fk calificacion.disposicion _m:o disposicion.id)
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set {
                if ( _disposicion_ != value)
                {
                    _disposicion_ = value;
                    if(value != null)
                        disposicion = value.id;
                    else
                        disposicion = null;
                    NotifyPropertyChanged(nameof(disposicion_));
                }
            }
        }
        #endregion

    }
}
