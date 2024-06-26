#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_yoast_migrations : SqlOrganize.Data
    {

        public Data_wpwt_yoast_migrations ()
        {
        }

        public Data_wpwt_yoast_migrations(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_yoast_migrations Default()
        {
            EntityValues val = db!.Values("wpwt_yoast_migrations");
            _id = (uint?)val.GetDefault("id");
            return this;
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
                    if (!db.IsNullOrEmpty() && !_version.IsNullOrEmptyOrDbNull()) {
                        var row = db.Sql("wpwt_yoast_migrations").Where("$version = @0").Parameters(_version).Cache().Dict();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }

            return "";
        }
    }
}
