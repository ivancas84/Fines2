#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtYoastIndexableHierarchy : SqlOrganize.Sql.Entity
    {

        public override string entityName => "wpwt_yoast_indexable_hierarchy";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_yoast_indexable_hierarchy");
            _blog_id = (long?)val.GetDefault("blog_id");
        }


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
