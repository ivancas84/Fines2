#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Calificacion : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "calificacion";

        public override void Default()
        {
            EntityVal val = db!.Values("calificacion");
            _id = (string?)val.GetDefault("id");
            _archivado = (bool?)val.GetDefault("archivado");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected decimal? _nota1 = null;
        public decimal? nota1
        {
            get { return _nota1; }
            set { if( _nota1 != value) { _nota1 = value; NotifyPropertyChanged(nameof(nota1)); } }
        }
        protected decimal? _nota2 = null;
        public decimal? nota2
        {
            get { return _nota2; }
            set { if( _nota2 != value) { _nota2 = value; NotifyPropertyChanged(nameof(nota2)); } }
        }
        protected decimal? _nota3 = null;
        public decimal? nota3
        {
            get { return _nota3; }
            set { if( _nota3 != value) { _nota3 = value; NotifyPropertyChanged(nameof(nota3)); } }
        }
        protected decimal? _nota_final = null;
        public decimal? nota_final
        {
            get { return _nota_final; }
            set { if( _nota_final != value) { _nota_final = value; NotifyPropertyChanged(nameof(nota_final)); } }
        }
        protected decimal? _crec = null;
        public decimal? crec
        {
            get { return _crec; }
            set { if( _crec != value) { _crec = value; NotifyPropertyChanged(nameof(crec)); } }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { if( _curso != value) { _curso = value; NotifyPropertyChanged(nameof(curso)); } }
        }
        protected int? _porcentaje_asistencia = null;
        public int? porcentaje_asistencia
        {
            get { return _porcentaje_asistencia; }
            set { if( _porcentaje_asistencia != value) { _porcentaje_asistencia = value; NotifyPropertyChanged(nameof(porcentaje_asistencia)); } }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { if( _division != value) { _division = value; NotifyPropertyChanged(nameof(division)); } }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { if( _alumno != value) { _alumno = value; NotifyPropertyChanged(nameof(alumno)); } }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { if( _disposicion != value) { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); } }
        }
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { if( _fecha != value) { _fecha = value; NotifyPropertyChanged(nameof(fecha)); } }
        }
        protected bool? _archivado = null;
        public bool? archivado
        {
            get { return _archivado; }
            set { if( _archivado != value) { _archivado = value; NotifyPropertyChanged(nameof(archivado)); } }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "nota1":
                    return "";

                case "nota2":
                    return "";

                case "nota3":
                    return "";

                case "nota_final":
                    return "";

                case "crec":
                    return "";

                case "curso":
                    return "";

                case "porcentaje_asistencia":
                    return "";

                case "observaciones":
                    return "";

                case "division":
                    return "";

                case "alumno":
                    if (_alumno == null)
                        return "Debe completar valor.";
                    return "";

                case "disposicion":
                    if (_disposicion == null)
                        return "Debe completar valor.";
                    return "";

                case "fecha":
                    return "";

                case "archivado":
                    if (_archivado == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
        //calificacion.curso _o:o curso.id
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

        //calificacion.alumno _o:o alumno.id
        protected Alumno? _alumno_ = null;
        public Alumno? alumno_
        {
            get { return _alumno_; }
            set {
                _alumno_ = value;
                alumno = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(alumno_));
            }
        }

        //calificacion.disposicion _o:o disposicion.id
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
