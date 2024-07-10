#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_modalidad : SqlOrganize.Sql.Data
    {

        public Data_modalidad ()
        {
        }

        public Data_modalidad(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("modalidad");
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

                case "nombre":
                    if (_nombre == null)
                        return "Debe completar valor.";
                    if (!db.IsNoE() && !_nombre.IsNoE()) {
                        var row = db.Sql("modalidad").Where("$nombre = @0").Parameters(_nombre).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
    }
}
