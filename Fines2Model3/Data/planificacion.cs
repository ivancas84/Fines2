#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_planificacion : SqlOrganize.Data
    {

        public Data_planificacion ()
        {
        }

        public Data_planificacion(Db db, bool init = true)
        {
            this.db = db;
            if(init)
                Init();
        }

        protected void Init()
        {
            EntityValues val = db!.Values("planificacion");
            _id = (string?)val.GetDefault("id");
        }

        public string? Label { get; set; }

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
    }
}
