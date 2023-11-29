#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_yoast_indexable_hierarchy : SqlOrganize.Data
    {

        public Data_wpwt_yoast_indexable_hierarchy ()
        {
            Initialize();
        }

        public Data_wpwt_yoast_indexable_hierarchy(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _blog_id = (long?)ContainerApp.db.Values("wpwt_yoast_indexable_hierarchy").Default("blog_id").Get("blog_id");
                break;
            }
        }

        public string? Label { get; set; }

        protected uint? _indexable_id = null;
        public uint? indexable_id
        {
            get { return _indexable_id; }
            set { _indexable_id = value; NotifyPropertyChanged(); }
        }
        protected uint? _ancestor_id = null;
        public uint? ancestor_id
        {
            get { return _ancestor_id; }
            set { _ancestor_id = value; NotifyPropertyChanged(); }
        }
        protected uint? _depth = null;
        public uint? depth
        {
            get { return _depth; }
            set { _depth = value; NotifyPropertyChanged(); }
        }
        protected long? _blog_id = null;
        public long? blog_id
        {
            get { return _blog_id; }
            set { _blog_id = value; NotifyPropertyChanged(); }
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
