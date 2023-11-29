#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_posts : SqlOrganize.Data
    {

        public Data_wpwt_posts ()
        {
            Initialize();
        }

        public Data_wpwt_posts(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _post_author = (ulong?)ContainerApp.db.Values("wpwt_posts").Default("post_author").Get("post_author");
                    _post_date = (DateTime?)ContainerApp.db.Values("wpwt_posts").Default("post_date").Get("post_date");
                    _post_date_gmt = (DateTime?)ContainerApp.db.Values("wpwt_posts").Default("post_date_gmt").Get("post_date_gmt");
                    _post_status = (string?)ContainerApp.db.Values("wpwt_posts").Default("post_status").Get("post_status");
                    _comment_status = (string?)ContainerApp.db.Values("wpwt_posts").Default("comment_status").Get("comment_status");
                    _ping_status = (string?)ContainerApp.db.Values("wpwt_posts").Default("ping_status").Get("ping_status");
                    _post_password = (string?)ContainerApp.db.Values("wpwt_posts").Default("post_password").Get("post_password");
                    _post_name = (string?)ContainerApp.db.Values("wpwt_posts").Default("post_name").Get("post_name");
                    _post_modified = (DateTime?)ContainerApp.db.Values("wpwt_posts").Default("post_modified").Get("post_modified");
                    _post_modified_gmt = (DateTime?)ContainerApp.db.Values("wpwt_posts").Default("post_modified_gmt").Get("post_modified_gmt");
                    _post_parent = (ulong?)ContainerApp.db.Values("wpwt_posts").Default("post_parent").Get("post_parent");
                    _guid = (string?)ContainerApp.db.Values("wpwt_posts").Default("guid").Get("guid");
                    _menu_order = (int?)ContainerApp.db.Values("wpwt_posts").Default("menu_order").Get("menu_order");
                    _post_type = (string?)ContainerApp.db.Values("wpwt_posts").Default("post_type").Get("post_type");
                    _post_mime_type = (string?)ContainerApp.db.Values("wpwt_posts").Default("post_mime_type").Get("post_mime_type");
                    _comment_count = (long?)ContainerApp.db.Values("wpwt_posts").Default("comment_count").Get("comment_count");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _ID = null;
        public ulong? ID
        {
            get { return _ID; }
            set { _ID = value; NotifyPropertyChanged(); }
        }
        protected ulong? _post_author = null;
        public ulong? post_author
        {
            get { return _post_author; }
            set { _post_author = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _post_date = null;
        public DateTime? post_date
        {
            get { return _post_date; }
            set { _post_date = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _post_date_gmt = null;
        public DateTime? post_date_gmt
        {
            get { return _post_date_gmt; }
            set { _post_date_gmt = value; NotifyPropertyChanged(); }
        }
        protected string? _post_content = null;
        public string? post_content
        {
            get { return _post_content; }
            set { _post_content = value; NotifyPropertyChanged(); }
        }
        protected string? _post_title = null;
        public string? post_title
        {
            get { return _post_title; }
            set { _post_title = value; NotifyPropertyChanged(); }
        }
        protected string? _post_excerpt = null;
        public string? post_excerpt
        {
            get { return _post_excerpt; }
            set { _post_excerpt = value; NotifyPropertyChanged(); }
        }
        protected string? _post_status = null;
        public string? post_status
        {
            get { return _post_status; }
            set { _post_status = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_status = null;
        public string? comment_status
        {
            get { return _comment_status; }
            set { _comment_status = value; NotifyPropertyChanged(); }
        }
        protected string? _ping_status = null;
        public string? ping_status
        {
            get { return _ping_status; }
            set { _ping_status = value; NotifyPropertyChanged(); }
        }
        protected string? _post_password = null;
        public string? post_password
        {
            get { return _post_password; }
            set { _post_password = value; NotifyPropertyChanged(); }
        }
        protected string? _post_name = null;
        public string? post_name
        {
            get { return _post_name; }
            set { _post_name = value; NotifyPropertyChanged(); }
        }
        protected string? _to_ping = null;
        public string? to_ping
        {
            get { return _to_ping; }
            set { _to_ping = value; NotifyPropertyChanged(); }
        }
        protected string? _pinged = null;
        public string? pinged
        {
            get { return _pinged; }
            set { _pinged = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _post_modified = null;
        public DateTime? post_modified
        {
            get { return _post_modified; }
            set { _post_modified = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _post_modified_gmt = null;
        public DateTime? post_modified_gmt
        {
            get { return _post_modified_gmt; }
            set { _post_modified_gmt = value; NotifyPropertyChanged(); }
        }
        protected string? _post_content_filtered = null;
        public string? post_content_filtered
        {
            get { return _post_content_filtered; }
            set { _post_content_filtered = value; NotifyPropertyChanged(); }
        }
        protected ulong? _post_parent = null;
        public ulong? post_parent
        {
            get { return _post_parent; }
            set { _post_parent = value; NotifyPropertyChanged(); }
        }
        protected string? _guid = null;
        public string? guid
        {
            get { return _guid; }
            set { _guid = value; NotifyPropertyChanged(); }
        }
        protected int? _menu_order = null;
        public int? menu_order
        {
            get { return _menu_order; }
            set { _menu_order = value; NotifyPropertyChanged(); }
        }
        protected string? _post_type = null;
        public string? post_type
        {
            get { return _post_type; }
            set { _post_type = value; NotifyPropertyChanged(); }
        }
        protected string? _post_mime_type = null;
        public string? post_mime_type
        {
            get { return _post_mime_type; }
            set { _post_mime_type = value; NotifyPropertyChanged(); }
        }
        protected long? _comment_count = null;
        public long? comment_count
        {
            get { return _comment_count; }
            set { _comment_count = value; NotifyPropertyChanged(); }
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

                case "ID":
                    if (_ID == null)
                        return "Debe completar valor.";
                    return "";

                case "post_author":
                    if (_post_author == null)
                        return "Debe completar valor.";
                    return "";

                case "post_date":
                    if (_post_date == null)
                        return "Debe completar valor.";
                    return "";

                case "post_date_gmt":
                    if (_post_date_gmt == null)
                        return "Debe completar valor.";
                    return "";

                case "post_content":
                    if (_post_content == null)
                        return "Debe completar valor.";
                    return "";

                case "post_title":
                    if (_post_title == null)
                        return "Debe completar valor.";
                    return "";

                case "post_excerpt":
                    if (_post_excerpt == null)
                        return "Debe completar valor.";
                    return "";

                case "post_status":
                    if (_post_status == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_status":
                    if (_comment_status == null)
                        return "Debe completar valor.";
                    return "";

                case "ping_status":
                    if (_ping_status == null)
                        return "Debe completar valor.";
                    return "";

                case "post_password":
                    if (_post_password == null)
                        return "Debe completar valor.";
                    return "";

                case "post_name":
                    if (_post_name == null)
                        return "Debe completar valor.";
                    return "";

                case "to_ping":
                    if (_to_ping == null)
                        return "Debe completar valor.";
                    return "";

                case "pinged":
                    if (_pinged == null)
                        return "Debe completar valor.";
                    return "";

                case "post_modified":
                    if (_post_modified == null)
                        return "Debe completar valor.";
                    return "";

                case "post_modified_gmt":
                    if (_post_modified_gmt == null)
                        return "Debe completar valor.";
                    return "";

                case "post_content_filtered":
                    if (_post_content_filtered == null)
                        return "Debe completar valor.";
                    return "";

                case "post_parent":
                    if (_post_parent == null)
                        return "Debe completar valor.";
                    return "";

                case "guid":
                    if (_guid == null)
                        return "Debe completar valor.";
                    return "";

                case "menu_order":
                    if (_menu_order == null)
                        return "Debe completar valor.";
                    return "";

                case "post_type":
                    if (_post_type == null)
                        return "Debe completar valor.";
                    return "";

                case "post_mime_type":
                    if (_post_mime_type == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_count":
                    if (_comment_count == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
