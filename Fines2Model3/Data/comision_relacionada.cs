#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_comision_relacionada : SqlOrganize.Data
    {

        public Data_comision_relacionada ()
        {
        }

        public Data_comision_relacionada(Db db)
        {
            this.db = db;
            Init();
        }

        protected void Init()
        {
            _id = (string?)db!.Values("comision_relacionada").GetDefault("id");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { _comision = value; NotifyPropertyChanged(nameof(comision)); }
        }
        protected string? _relacion = null;
        public string? relacion
        {
            get { return _relacion; }
            set { _relacion = value; NotifyPropertyChanged(nameof(relacion)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "comision":
                    if (_comision == null)
                        return "Debe completar valor.";
                    return "";

                case "relacion":
                    if (_relacion == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
