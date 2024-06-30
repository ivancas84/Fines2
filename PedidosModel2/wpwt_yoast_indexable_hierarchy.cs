#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_yoast_indexable_hierarchy : SqlOrganize.Data
    {

        public Data_wpwt_yoast_indexable_hierarchy ()
        {
        }

        public Data_wpwt_yoast_indexable_hierarchy(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_yoast_indexable_hierarchy Default()
        {
            EntityValues val = db!.Values("wpwt_yoast_indexable_hierarchy");
            _blog_id = (long?)val.GetDefault("blog_id");
            return this;
        }

        public string? Label { get; set; }

        protected uint? _indexable_id = null;
        public uint? indexable_id
        {
            get { return _indexable_id; }
            set { _indexable_id = value; NotifyPropertyChanged(nameof(indexable_id)); }
        }
        protected uint? _ancestor_id = null;
        public uint? ancestor_id
        {
            get { return _ancestor_id; }
            set { _ancestor_id = value; NotifyPropertyChanged(nameof(ancestor_id)); }
        }
        protected uint? _depth = null;
        public uint? depth
        {
            get { return _depth; }
            set { _depth = value; NotifyPropertyChanged(nameof(depth)); }
        }
        protected long? _blog_id = null;
        public long? blog_id
        {
            get { return _blog_id; }
            set { _blog_id = value; NotifyPropertyChanged(nameof(blog_id)); }
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

                case "indexable_id":
                    if (_indexable_id == null)
                        return "Debe completar valor.";
                    return "";

                case "ancestor_id":
                    if (_ancestor_id == null)
                        return "Debe completar valor.";
                    return "";

                case "depth":
                    return "";

                case "blog_id":
                    if (_blog_id == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
