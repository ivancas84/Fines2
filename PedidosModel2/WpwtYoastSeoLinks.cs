#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtYoastSeoLinks : SqlOrganize.Sql.Entity
    {

        public override string entityName => "wpwt_yoast_seo_links";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_yoast_seo_links");
            _id = (ulong?)val.GetDefault("id");
        }


        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _url = null;
        public string? url
        {
            get { return _url; }
            set { _url = value; NotifyPropertyChanged(nameof(url)); }
        }
        protected ulong? _post_id = null;
        public ulong? post_id
        {
            get { return _post_id; }
            set { _post_id = value; NotifyPropertyChanged(nameof(post_id)); }
        }
        protected ulong? _target_post_id = null;
        public ulong? target_post_id
        {
            get { return _target_post_id; }
            set { _target_post_id = value; NotifyPropertyChanged(nameof(target_post_id)); }
        }
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(nameof(type)); }
        }
        protected uint? _indexable_id = null;
        public uint? indexable_id
        {
            get { return _indexable_id; }
            set { _indexable_id = value; NotifyPropertyChanged(nameof(indexable_id)); }
        }
        protected uint? _target_indexable_id = null;
        public uint? target_indexable_id
        {
            get { return _target_indexable_id; }
            set { _target_indexable_id = value; NotifyPropertyChanged(nameof(target_indexable_id)); }
        }
        protected uint? _height = null;
        public uint? height
        {
            get { return _height; }
            set { _height = value; NotifyPropertyChanged(nameof(height)); }
        }
        protected uint? _width = null;
        public uint? width
        {
            get { return _width; }
            set { _width = value; NotifyPropertyChanged(nameof(width)); }
        }
        protected uint? _size = null;
        public uint? size
        {
            get { return _size; }
            set { _size = value; NotifyPropertyChanged(nameof(size)); }
        }
        protected string? _language = null;
        public string? language
        {
            get { return _language; }
            set { _language = value; NotifyPropertyChanged(nameof(language)); }
        }
        protected string? _region = null;
        public string? region
        {
            get { return _region; }
            set { _region = value; NotifyPropertyChanged(nameof(region)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "url":
                    return "";

                case "post_id":
                    return "";

                case "target_post_id":
                    return "";

                case "type":
                    return "";

                case "indexable_id":
                    return "";

                case "target_indexable_id":
                    return "";

                case "height":
                    return "";

                case "width":
                    return "";

                case "size":
                    return "";

                case "language":
                    return "";

                case "region":
                    return "";

            }

            return "";
        }
    }
}
