#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Toma : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "toma";

        public override void Default()
        {
            EntityVal val = db!.Values("toma");
            _id = (string?)val.GetDefault("id");
            _alta = (DateTime?)val.GetDefault("alta");
            _calificacion = (bool?)val.GetDefault("calificacion");
            _temas_tratados = (bool?)val.GetDefault("temas_tratados");
            _asistencia = (bool?)val.GetDefault("asistencia");
            _sin_planillas = (bool?)val.GetDefault("sin_planillas");
            _confirmada = (bool?)val.GetDefault("confirmada");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected DateTime? _fecha_toma = null;
        public DateTime? fecha_toma
        {
            get { return _fecha_toma; }
            set { _fecha_toma = value; NotifyPropertyChanged(nameof(fecha_toma)); }
        }
        protected string? _estado = null;
        public string? estado
        {
            get { return _estado; }
            set { _estado = value; NotifyPropertyChanged(nameof(estado)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(nameof(comentario)); }
        }
        protected string? _tipo_movimiento = null;
        public string? tipo_movimiento
        {
            get { return _tipo_movimiento; }
            set { _tipo_movimiento = value; NotifyPropertyChanged(nameof(tipo_movimiento)); }
        }
        protected string? _estado_contralor = null;
        public string? estado_contralor
        {
            get { return _estado_contralor; }
            set { _estado_contralor = value; NotifyPropertyChanged(nameof(estado_contralor)); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(nameof(alta)); }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { _curso = value; NotifyPropertyChanged(nameof(curso)); }
        }
        protected string? _docente = null;
        public string? docente
        {
            get { return _docente; }
            set { _docente = value; NotifyPropertyChanged(nameof(docente)); }
        }
        protected string? _reemplazo = null;
        public string? reemplazo
        {
            get { return _reemplazo; }
            set { _reemplazo = value; NotifyPropertyChanged(nameof(reemplazo)); }
        }
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente)); }
        }
        protected bool? _calificacion = null;
        public bool? calificacion
        {
            get { return _calificacion; }
            set { _calificacion = value; NotifyPropertyChanged(nameof(calificacion)); }
        }
        protected bool? _temas_tratados = null;
        public bool? temas_tratados
        {
            get { return _temas_tratados; }
            set { _temas_tratados = value; NotifyPropertyChanged(nameof(temas_tratados)); }
        }
        protected bool? _asistencia = null;
        public bool? asistencia
        {
            get { return _asistencia; }
            set { _asistencia = value; NotifyPropertyChanged(nameof(asistencia)); }
        }
        protected bool? _sin_planillas = null;
        public bool? sin_planillas
        {
            get { return _sin_planillas; }
            set { _sin_planillas = value; NotifyPropertyChanged(nameof(sin_planillas)); }
        }
        protected bool? _confirmada = null;
        public bool? confirmada
        {
            get { return _confirmada; }
            set { _confirmada = value; NotifyPropertyChanged(nameof(confirmada)); }
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
