#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_term_taxonomy : SqlOrganize.Data
    {

        public Data_wpwt_term_taxonomy ()
        {
            Initialize();
        }

        public Data_wpwt_term_taxonomy(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _term_id = (ulong?)ContainerApp.db.Values("wpwt_term_taxonomy").Default("term_id").Get("term_id");
                    _taxonomy = (string?)ContainerApp.db.Values("wpwt_term_taxonomy").Default("taxonomy").Get("taxonomy");
                    _parent = (ulong?)ContainerApp.db.Values("wpwt_term_taxonomy").Default("parent").Get("parent");
                    _count = (long?)ContainerApp.db.Values("wpwt_term_taxonomy").Default("count").Get("count");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _term_taxonomy_id = null;
        public ulong? term_taxonomy_id
        {
            get { return _term_taxonomy_id; }
            set { _term_taxonomy_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _term_id = null;
        public ulong? term_id
        {
            get { return _term_id; }
            set { _term_id = value; NotifyPropertyChanged(); }
        }
        protected string? _taxonomy = null;
        public string? taxonomy
        {
            get { return _taxonomy; }
            set { _taxonomy = value; NotifyPropertyChanged(); }
        }
        protected string? _description = null;
        public string? description
        {
            get { return _description; }
            set { _description = value; NotifyPropertyChanged(); }
        }
        protected ulong? _parent = null;
        public ulong? parent
        {
            get { return _parent; }
            set { _parent = value; NotifyPropertyChanged(); }
        }
        protected long? _count = null;
        public long? count
        {
            get { return _count; }
            set { _count = value; NotifyPropertyChanged(); }
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

                case "term_taxonomy_id":
                    if (_term_taxonomy_id == null)
                        return "Debe completar valor.";
                    return "";

                case "term_id":
                    if (_term_id == null)
                        return "Debe completar valor.";
                    return "";

                case "taxonomy":
                    if (_taxonomy == null)
                        return "Debe completar valor.";
                    return "";

                case "description":
                    if (_description == null)
                        return "Debe completar valor.";
                    return "";

                case "parent":
                    if (_parent == null)
                        return "Debe completar valor.";
                    return "";

                case "count":
                    if (_count == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
