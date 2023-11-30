#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_termmeta : SqlOrganize.Data
    {

        public Data_wpwt_termmeta ()
        {
            Initialize();
        }

        public Data_wpwt_termmeta(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _term_id = (ulong?)ContainerApp.db.Values("wpwt_termmeta").Default("term_id").Get("term_id");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _meta_id = null;
        public ulong? meta_id
        {
            get { return _meta_id; }
            set { _meta_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _term_id = null;
        public ulong? term_id
        {
            get { return _term_id; }
            set { _term_id = value; NotifyPropertyChanged(); }
        }
        protected string? _meta_key = null;
        public string? meta_key
        {
            get { return _meta_key; }
            set { _meta_key = value; NotifyPropertyChanged(); }
        }
        protected string? _meta_value = null;
        public string? meta_value
        {
            get { return _meta_value; }
            set { _meta_value = value; NotifyPropertyChanged(); }
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

                case "meta_id":
                    if (_meta_id == null)
                        return "Debe completar valor.";
                    return "";

                case "term_id":
                    if (_term_id == null)
                        return "Debe completar valor.";
                    return "";

                case "meta_key":
                    return "";

                case "meta_value":
                    return "";

            }

            return "";
        }
    }
}
