#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_yoast_migrations : SqlOrganize.Data
    {

        public Data_wpwt_yoast_migrations ()
        {
            Initialize();
        }

        public Data_wpwt_yoast_migrations(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (uint?)ContainerApp.db.Values("wpwt_yoast_migrations").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected uint? _id = null;
        public uint? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _version = null;
        public string? version
        {
            get { return _version; }
            set { _version = value; NotifyPropertyChanged(); }
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
                    if (!_version.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("wpwt_yoast_migrations").Where("$version = @0").Parameters(_version).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }

            return "";
        }
    }
}
