#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtOptions : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_options";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_options");
            _option_name = (string?)val.GetDefault("option_name");
            _autoload = (string?)val.GetDefault("autoload");
        }


        protected ulong? _option_id = null;
        public ulong? option_id
        {
            get { return _option_id; }
            set { _option_id = value; NotifyPropertyChanged(nameof(option_id)); }
        }
        protected string? _option_name = null;
        public string? option_name
        {
            get { return _option_name; }
            set { _option_name = value; NotifyPropertyChanged(nameof(option_name)); }
        }
        protected string? _option_value = null;
        public string? option_value
        {
            get { return _option_value; }
            set { _option_value = value; NotifyPropertyChanged(nameof(option_value)); }
        }
        protected string? _autoload = null;
        public string? autoload
        {
            get { return _autoload; }
            set { _autoload = value; NotifyPropertyChanged(nameof(autoload)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "option_id":
                    if (_option_id == null)
                        return "Debe completar valor.";
                    return "";

                case "option_name":
                    if (_option_name == null)
                        return "Debe completar valor.";
                    if (!db.IsNoE() && !_option_name.IsNoE()) {
                        var row = db.Sql("wpwt_options").Equal("$option_name", _option_name).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "option_value":
                    if (_option_value == null)
                        return "Debe completar valor.";
                    return "";

                case "autoload":
                    if (_autoload == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
