#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_yoast_seo_links : SqlOrganize.Data
    {

        public Data_wpwt_yoast_seo_links ()
        {
            Initialize();
        }

        public Data_wpwt_yoast_seo_links(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (ulong?)ContainerApp.db.Values("wpwt_yoast_seo_links").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _url = null;
        public string? url
        {
            get { return _url; }
            set { _url = value; NotifyPropertyChanged(); }
        }
        protected ulong? _post_id = null;
        public ulong? post_id
        {
            get { return _post_id; }
            set { _post_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _target_post_id = null;
        public ulong? target_post_id
        {
            get { return _target_post_id; }
            set { _target_post_id = value; NotifyPropertyChanged(); }
        }
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(); }
        }
        protected uint? _indexable_id = null;
        public uint? indexable_id
        {
            get { return _indexable_id; }
            set { _indexable_id = value; NotifyPropertyChanged(); }
        }
        protected uint? _target_indexable_id = null;
        public uint? target_indexable_id
        {
            get { return _target_indexable_id; }
            set { _target_indexable_id = value; NotifyPropertyChanged(); }
        }
        protected uint? _height = null;
        public uint? height
        {
            get { return _height; }
            set { _height = value; NotifyPropertyChanged(); }
        }
        protected uint? _width = null;
        public uint? width
        {
            get { return _width; }
            set { _width = value; NotifyPropertyChanged(); }
        }
        protected uint? _size = null;
        public uint? size
        {
            get { return _size; }
            set { _size = value; NotifyPropertyChanged(); }
        }
        protected string? _language = null;
        public string? language
        {
            get { return _language; }
            set { _language = value; NotifyPropertyChanged(); }
        }
        protected string? _region = null;
        public string? region
        {
            get { return _region; }
            set { _region = value; NotifyPropertyChanged(); }
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
