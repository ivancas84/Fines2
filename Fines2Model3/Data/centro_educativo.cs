#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_centro_educativo : SqlOrganize.Sql.Data
    {

        public Data_centro_educativo ()
        {
        }

        public Data_centro_educativo(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("centro_educativo");
            _id = (string?)val.GetDefault("id");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { _nombre = value; NotifyPropertyChanged(nameof(nombre)); }
        }
        protected string? _cue = null;
        public string? cue
        {
            get { return _cue; }
            set { _cue = value; NotifyPropertyChanged(nameof(cue)); }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { _domicilio = value; NotifyPropertyChanged(nameof(domicilio)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "nombre":
                    if (_nombre == null)
                        return "Debe completar valor.";
                    return "";

                case "cue":
                    if (!db.IsNoE() && !_cue.IsNoE()) {
                        var row = db.Sql("centro_educativo").Where("$cue = @0").Parameters(_cue).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "domicilio":
                    return "";

                case "observaciones":
                    return "";

            }

            return "";
        }
    }
}
