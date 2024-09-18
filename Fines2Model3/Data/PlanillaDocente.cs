#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class PlanillaDocente : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "planilla_docente";

        public override void Default()
        {
            EntityVal val = db!.Values("planilla_docente");
            _id = (string?)val.GetDefault("id");
            _insertado = (DateTime?)val.GetDefault("insertado");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
        }
        protected DateTime? _fecha_contralor = null;
        public DateTime? fecha_contralor
        {
            get { return _fecha_contralor; }
            set { if( _fecha_contralor != value) { _fecha_contralor = value; NotifyPropertyChanged(nameof(fecha_contralor)); } }
        }
        protected DateTime? _fecha_consejo = null;
        public DateTime? fecha_consejo
        {
            get { return _fecha_consejo; }
            set { if( _fecha_consejo != value) { _fecha_consejo = value; NotifyPropertyChanged(nameof(fecha_consejo)); } }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "numero":
                    if (_numero == null)
                        return "Debe completar valor.";
                    return "";

                case "insertado":
                    if (_insertado == null)
                        return "Debe completar valor.";
                    return "";

                case "fecha_contralor":
                    return "";

                case "fecha_consejo":
                    return "";

                case "observaciones":
                    return "";

            }

            return "";
        }
        //asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id
        public ObservableCollection<AsignacionPlanillaDocente> AsignacionPlanillaDocente_planilla_docente_ { get; set; } = new ();

        //contralor.planilla_docente _m:o planilla_docente.id
        public ObservableCollection<Contralor> Contralor_planilla_docente_ { get; set; } = new ();

        //toma.planilla_docente _m:o planilla_docente.id
        public ObservableCollection<Toma> Toma_planilla_docente_ { get; set; } = new ();

    }
}
