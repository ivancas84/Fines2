#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Disposicion : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "disposicion";

        public override void Default()
        {
            EntityVal val = db!.Values("disposicion");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set { _asignatura = value; NotifyPropertyChanged(nameof(asignatura)); }
        }
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set { _planificacion = value; NotifyPropertyChanged(nameof(planificacion)); }
        }
        protected int? _orden_informe_coordinacion_distrital = null;
        public int? orden_informe_coordinacion_distrital
        {
            get { return _orden_informe_coordinacion_distrital; }
            set { _orden_informe_coordinacion_distrital = value; NotifyPropertyChanged(nameof(orden_informe_coordinacion_distrital)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "asignatura":
                    if (_asignatura == null)
                        return "Debe completar valor.";
                    return "";

                case "planificacion":
                    if (_planificacion == null)
                        return "Debe completar valor.";
                    return "";

                case "orden_informe_coordinacion_distrital":
                    return "";

            }

            return "";
        }
        protected Asignatura? _asignatura_ = null;
        public Asignatura? asignatura_
        {
            get { return _asignatura_; }
            set { _asignatura_ = value; NotifyPropertyChanged(nameof(asignatura_)); }
        }

        protected Planificacion? _planificacion_ = null;
        public Planificacion? planificacion_
        {
            get { return _planificacion_; }
            set { _planificacion_ = value; NotifyPropertyChanged(nameof(planificacion_)); }
        }

    }
}
