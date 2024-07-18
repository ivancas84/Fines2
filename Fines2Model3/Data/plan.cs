#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_plan : SqlOrganize.Sql.Data
    {

        public override string entityName => "plan";

        public Data_plan ()
        {
        }

        public Data_plan(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("plan");
            _id = (string?)val.GetDefault("id");
        }

        public string? Label { get; set; }

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
    }
}
