#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_asignacion_planilla_docente : SqlOrganize.Data
    {

        public Data_asignacion_planilla_docente ()
        {
            Initialize();
        }

        public Data_asignacion_planilla_docente(DataInitMode mode)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Null)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("asignacion_planilla_docente").Default("id").Get("id");
                    _insertado = (DateTime?)ContainerApp.db.Values("asignacion_planilla_docente").Default("insertado").Get("insertado");
                    _reclamo = (bool?)ContainerApp.db.Values("asignacion_planilla_docente").Default("reclamo").Get("reclamo");
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
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { _planilla_docente = value; NotifyPropertyChanged(); }
        }
        protected string? _toma = null;
        public string? toma
        {
            get { return _toma; }
            set { _toma = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(); }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(); }
        }
        protected bool? _reclamo = null;
        public bool? reclamo
        {
            get { return _reclamo; }
            set { _reclamo = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "planilla_docente":
                    if (_planilla_docente == null)
                        return "Debe completar valor.";
                    return "";

                case "toma":
                    if (_toma == null)
                        return "Debe completar valor.";
                    return "";

                case "insertado":
                    if (_insertado == null)
                        return "Debe completar valor.";
                    return "";

                case "comentario":
                    return "";

                case "reclamo":
                    if (_reclamo == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
