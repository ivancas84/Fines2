#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AsignacionPlanillaDocente : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "asignacion_planilla_docente";

        public override void Default()
        {
            EntityVal val = db!.Values("asignacion_planilla_docente");
            _id = (string?)val.GetDefault("id");
            _insertado = (DateTime?)val.GetDefault("insertado");
            _reclamo = (bool?)val.GetDefault("reclamo");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente)); }
        }
        protected string? _toma = null;
        public string? toma
        {
            get { return _toma; }
            set { _toma = value; NotifyPropertyChanged(nameof(toma)); }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(nameof(insertado)); }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(nameof(comentario)); }
        }
        protected bool? _reclamo = null;
        public bool? reclamo
        {
            get { return _reclamo; }
            set { _reclamo = value; NotifyPropertyChanged(nameof(reclamo)); }
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
        //asignacion_planilla_docente.planilla_docente _o:o planilla_docente.id
        protected PlanillaDocente? _planilla_docente_ = null;
        public PlanillaDocente? planilla_docente_
        {
            get { return _planilla_docente_; }
            set { _planilla_docente_ = value; NotifyPropertyChanged(nameof(planilla_docente_)); }
        }

        //asignacion_planilla_docente.toma _o:o toma.id
        protected Toma? _toma_ = null;
        public Toma? toma_
        {
            get { return _toma_; }
            set { _toma_ = value; NotifyPropertyChanged(nameof(toma_)); }
        }

    }
}
