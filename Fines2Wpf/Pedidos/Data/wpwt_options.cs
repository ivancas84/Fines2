#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_options : SqlOrganize.Data
    {

        public Data_wpwt_options ()
        {
            Initialize();
        }

        public Data_wpwt_options(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _option_name = (string?)ContainerApp.db.Values("wpwt_options").Default("option_name").Get("option_name");
                    _autoload = (string?)ContainerApp.db.Values("wpwt_options").Default("autoload").Get("autoload");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _option_id = null;
        public ulong? option_id
        {
            get { return _option_id; }
            set { _option_id = value; NotifyPropertyChanged(); }
        }
        protected string? _option_name = null;
        public string? option_name
        {
            get { return _option_name; }
            set { _option_name = value; NotifyPropertyChanged(); }
        }
        protected string? _option_value = null;
        public string? option_value
        {
            get { return _option_value; }
            set { _option_value = value; NotifyPropertyChanged(); }
        }
        protected string? _autoload = null;
        public string? autoload
        {
            get { return _autoload; }
            set { _autoload = value; NotifyPropertyChanged(); }
        }
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
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
                    if (!_option_name.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Sql("wpwt_options").Where("$option_name = @0").Parameters(_option_name).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
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
