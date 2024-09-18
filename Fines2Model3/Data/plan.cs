#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Plan : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "plan";

        public override void Default()
        {
            EntityVal val = db!.Values("plan");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _orientacion = null;
        public string? orientacion
        {
            get { return _orientacion; }
            set { _orientacion = value; NotifyPropertyChanged(nameof(orientacion)); }
        }
        protected string? _resolucion = null;
        public string? resolucion
        {
            get { return _resolucion; }
            set { _resolucion = value; NotifyPropertyChanged(nameof(resolucion)); }
        }
        protected string? _distribucion_horaria = null;
        public string? distribucion_horaria
        {
            get { return _distribucion_horaria; }
            set { _distribucion_horaria = value; NotifyPropertyChanged(nameof(distribucion_horaria)); }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(nameof(pfid)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "orientacion":
                    if (_orientacion == null)
                        return "Debe completar valor.";
                    return "";

                case "resolucion":
                    return "";

                case "distribucion_horaria":
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
        //alumno.plan _m:o plan.id
        protected ObservableCollection<Alumno> _Alumno_plan_ { get; set; } = new ();

        //planificacion.plan _m:o plan.id
        protected ObservableCollection<Planificacion> _Planificacion_plan_ { get; set; } = new ();

    }
}
