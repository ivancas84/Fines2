#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_commentmeta : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_commentmeta";

        public Data_wpwt_commentmeta ()
        {
        }

        public Data_wpwt_commentmeta(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_commentmeta");
            _comment_id = (ulong?)val.GetDefault("comment_id");
        }

        public string? Label { get; set; }

        protected ulong? _meta_id = null;
        public ulong? meta_id
        {
            get { return _meta_id; }
            set { _meta_id = value; NotifyPropertyChanged(nameof(meta_id)); }
        }
        protected ulong? _comment_id = null;
        public ulong? comment_id
        {
            get { return _comment_id; }
            set { _comment_id = value; NotifyPropertyChanged(nameof(comment_id)); }
        }
        protected string? _meta_key = null;
        public string? meta_key
        {
            get { return _meta_key; }
            set { _meta_key = value; NotifyPropertyChanged(nameof(meta_key)); }
        }
        protected string? _meta_value = null;
        public string? meta_value
        {
            get { return _meta_value; }
            set { _meta_value = value; NotifyPropertyChanged(nameof(meta_value)); }
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

                case "meta_id":
                    if (_meta_id == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_id":
                    if (_comment_id == null)
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
