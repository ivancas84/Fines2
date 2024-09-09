#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_yoast_migrations : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_yoast_migrations";

        public Data_wpwt_yoast_migrations ()
        {
        }

        public Data_wpwt_yoast_migrations(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_yoast_migrations");
            _id = (uint?)val.GetDefault("id");
        }

        public string? Label { get; set; }

        protected uint? _id = null;
        public uint? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _version = null;
        public string? version
        {
            get { return _version; }
            set { _version = value; NotifyPropertyChanged(nameof(version)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "version":
                    if (!db.IsNoE() && !_version.IsNoE()) {
                        var row = db.Sql("wpwt_yoast_migrations").Where("$version = @0").Param("@0", _version).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }

            return "";
        }
    }
}
