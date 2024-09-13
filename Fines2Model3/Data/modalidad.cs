#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Modalidad : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "modalidad";

        public override void Default()
        {
            EntityVal val = db!.Values("modalidad");
            _id = (string?)val.GetDefault("id");
        }


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
                        var row = db.Sql("modalidad").Equal("$nombre", _nombre).Cache().Dict();
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
