#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_term_taxonomy : SqlOrganize.Data
    {

        public Data_wpwt_term_taxonomy ()
        {
        }

        public Data_wpwt_term_taxonomy(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_term_taxonomy Default()
        {
            EntityValues val = db!.Values("wpwt_term_taxonomy");
            _term_id = (ulong?)val.GetDefault("term_id");
            _taxonomy = (string?)val.GetDefault("taxonomy");
            _parent = (ulong?)val.GetDefault("parent");
            _count = (long?)val.GetDefault("count");
            return this;
        }

        public string? Label { get; set; }

        protected ulong? _term_taxonomy_id = null;
        public ulong? term_taxonomy_id
        {
            get { return _term_taxonomy_id; }
            set { _term_taxonomy_id = value; NotifyPropertyChanged(nameof(term_taxonomy_id)); }
        }
        protected ulong? _term_id = null;
        public ulong? term_id
        {
            get { return _term_id; }
            set { _term_id = value; NotifyPropertyChanged(nameof(term_id)); }
        }
        protected string? _taxonomy = null;
        public string? taxonomy
        {
            get { return _taxonomy; }
            set { _taxonomy = value; NotifyPropertyChanged(nameof(taxonomy)); }
        }
        protected string? _description = null;
        public string? description
        {
            get { return _description; }
            set { _description = value; NotifyPropertyChanged(nameof(description)); }
        }
        protected ulong? _parent = null;
        public ulong? parent
        {
            get { return _parent; }
            set { _parent = value; NotifyPropertyChanged(nameof(parent)); }
        }
        protected long? _count = null;
        public long? count
        {
            get { return _count; }
            set { _count = value; NotifyPropertyChanged(nameof(count)); }
        }
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
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
