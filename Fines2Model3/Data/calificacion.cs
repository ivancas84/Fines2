#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_calificacion : SqlOrganize.Data
    {

        public Data_calificacion ()
        {
        }

        public Data_calificacion(Db db)
        {
            this.db = db;
            Init();
        }

        protected void Init()
        {
            _id = (string?)db!.Values("calificacion").GetDefault("id");
            _archivado = (bool?)db!.Values("calificacion").GetDefault("archivado");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected decimal? _nota1 = null;
        public decimal? nota1
        {
            get { return _nota1; }
            set { _nota1 = value; NotifyPropertyChanged(nameof(nota1)); }
        }
        protected decimal? _nota2 = null;
        public decimal? nota2
        {
            get { return _nota2; }
            set { _nota2 = value; NotifyPropertyChanged(nameof(nota2)); }
        }
        protected decimal? _nota3 = null;
        public decimal? nota3
        {
            get { return _nota3; }
            set { _nota3 = value; NotifyPropertyChanged(nameof(nota3)); }
        }
        protected decimal? _nota_final = null;
        public decimal? nota_final
        {
            get { return _nota_final; }
            set { _nota_final = value; NotifyPropertyChanged(nameof(nota_final)); }
        }
        protected decimal? _crec = null;
        public decimal? crec
        {
            get { return _crec; }
            set { _crec = value; NotifyPropertyChanged(nameof(crec)); }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { _curso = value; NotifyPropertyChanged(nameof(curso)); }
        }
        protected int? _porcentaje_asistencia = null;
        public int? porcentaje_asistencia
        {
            get { return _porcentaje_asistencia; }
            set { _porcentaje_asistencia = value; NotifyPropertyChanged(nameof(porcentaje_asistencia)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { _division = value; NotifyPropertyChanged(nameof(division)); }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { _alumno = value; NotifyPropertyChanged(nameof(alumno)); }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); }
        }
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { _fecha = value; NotifyPropertyChanged(nameof(fecha)); }
        }
        protected bool? _archivado = null;
        public bool? archivado
        {
            get { return _archivado; }
            set { _archivado = value; NotifyPropertyChanged(nameof(archivado)); }
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
    }
}
