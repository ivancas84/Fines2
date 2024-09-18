#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Planificacion : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "planificacion";

        public override void Default()
        {
            EntityVal val = db!.Values("planificacion");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _anio = null;
        public string? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(nameof(anio)); }
        }
        protected string? _semestre = null;
        public string? semestre
        {
            get { return _semestre; }
            set { _semestre = value; NotifyPropertyChanged(nameof(semestre)); }
        }
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { _plan = value; NotifyPropertyChanged(nameof(plan)); }
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

                case "anio":
                    if (_anio == null)
                        return "Debe completar valor.";
                    return "";

                case "semestre":
                    if (_semestre == null)
                        return "Debe completar valor.";
                    return "";

                case "plan":
                    if (_plan == null)
                        return "Debe completar valor.";
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
        //planificacion.plan _o:o plan.id
        protected Plan? _plan_ = null;
        public Plan? plan_
        {
            get { return _plan_; }
            set { _plan_ = value; NotifyPropertyChanged(nameof(plan_)); }
        }

        //comision.planificacion _m:o planificacion.id
        protected ObservableCollection<Comision> _Comision_planificacion_ { get; set; } = new ();

        //disposicion.planificacion _m:o planificacion.id
        protected ObservableCollection<Disposicion> _Disposicion_planificacion_ { get; set; } = new ();

    }
}
