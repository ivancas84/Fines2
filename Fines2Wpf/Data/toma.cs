#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_toma : SqlOrganize.Data
    {

        public Data_toma ()
        {
            Initialize();
        }

        public Data_toma(DataInitMode mode)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Null)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("toma").Default("id").Get("id");
                    _alta = (DateTime?)ContainerApp.db.Values("toma").Default("alta").Get("alta");
                    _calificacion = (bool?)ContainerApp.db.Values("toma").Default("calificacion").Get("calificacion");
                    _temas_tratados = (bool?)ContainerApp.db.Values("toma").Default("temas_tratados").Get("temas_tratados");
                    _asistencia = (bool?)ContainerApp.db.Values("toma").Default("asistencia").Get("asistencia");
                    _sin_planillas = (bool?)ContainerApp.db.Values("toma").Default("sin_planillas").Get("sin_planillas");
                    _confirmada = (bool?)ContainerApp.db.Values("toma").Default("confirmada").Get("confirmada");
                break;
            }
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _fecha_toma = null;
        public DateTime? fecha_toma
        {
            get { return _fecha_toma; }
            set { _fecha_toma = value; NotifyPropertyChanged(); }
        }
        protected string? _estado = null;
        public string? estado
        {
            get { return _estado; }
            set { _estado = value; NotifyPropertyChanged(); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(); }
        }
        protected string? _tipo_movimiento = null;
        public string? tipo_movimiento
        {
            get { return _tipo_movimiento; }
            set { _tipo_movimiento = value; NotifyPropertyChanged(); }
        }
        protected string? _estado_contralor = null;
        public string? estado_contralor
        {
            get { return _estado_contralor; }
            set { _estado_contralor = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(); }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { _curso = value; NotifyPropertyChanged(); }
        }
        protected string? _docente = null;
        public string? docente
        {
            get { return _docente; }
            set { _docente = value; NotifyPropertyChanged(); }
        }
        protected string? _reemplazo = null;
        public string? reemplazo
        {
            get { return _reemplazo; }
            set { _reemplazo = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { _planilla_docente = value; NotifyPropertyChanged(); }
        }
        protected bool? _calificacion = null;
        public bool? calificacion
        {
            get { return _calificacion; }
            set { _calificacion = value; NotifyPropertyChanged(); }
        }
        protected bool? _temas_tratados = null;
        public bool? temas_tratados
        {
            get { return _temas_tratados; }
            set { _temas_tratados = value; NotifyPropertyChanged(); }
        }
        protected bool? _asistencia = null;
        public bool? asistencia
        {
            get { return _asistencia; }
            set { _asistencia = value; NotifyPropertyChanged(); }
        }
        protected bool? _sin_planillas = null;
        public bool? sin_planillas
        {
            get { return _sin_planillas; }
            set { _sin_planillas = value; NotifyPropertyChanged(); }
        }
        protected bool? _confirmada = null;
        public bool? confirmada
        {
            get { return _confirmada; }
            set { _confirmada = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "fecha_toma":
                    return "";

                case "estado":
                    return "";

                case "observaciones":
                    return "";

                case "comentario":
                    return "";

                case "tipo_movimiento":
                    if (_tipo_movimiento == null)
                        return "Debe completar valor.";
                    return "";

                case "estado_contralor":
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "curso":
                    if (_curso == null)
                        return "Debe completar valor.";
                    return "";

                case "docente":
                    return "";

                case "reemplazo":
                    return "";

                case "planilla_docente":
                    return "";

                case "calificacion":
                    if (_calificacion == null)
                        return "Debe completar valor.";
                    return "";

                case "temas_tratados":
                    if (_temas_tratados == null)
                        return "Debe completar valor.";
                    return "";

                case "asistencia":
                    if (_asistencia == null)
                        return "Debe completar valor.";
                    return "";

                case "sin_planillas":
                    if (_sin_planillas == null)
                        return "Debe completar valor.";
                    return "";

                case "confirmada":
                    if (_confirmada == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
