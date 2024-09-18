#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Cargo : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "cargo";

        public override void Default()
        {
            EntityVal val = db!.Values("cargo");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { _descripcion = value; NotifyPropertyChanged(nameof(descripcion)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "descripcion":
                    if (_descripcion == null)
                        return "Debe completar valor.";
                    if (!db.IsNoE() && !_descripcion.IsNoE()) {
                        var row = db.Sql("cargo").Equal("$descripcion", _descripcion).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }

            return "";
        }
        //designacion.cargo _m:o cargo.id
        protected ObservableCollection<Designacion> _Designacion_cargo_ { get; set; } = new ();

    }
}
