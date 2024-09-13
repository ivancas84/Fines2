#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtPosts : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_posts";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_posts");
            _post_author = (ulong?)val.GetDefault("post_author");
            _post_date = (DateTime?)val.GetDefault("post_date");
            _post_date_gmt = (DateTime?)val.GetDefault("post_date_gmt");
            _post_status = (string?)val.GetDefault("post_status");
            _comment_status = (string?)val.GetDefault("comment_status");
            _ping_status = (string?)val.GetDefault("ping_status");
            _post_password = (string?)val.GetDefault("post_password");
            _post_name = (string?)val.GetDefault("post_name");
            _post_modified = (DateTime?)val.GetDefault("post_modified");
            _post_modified_gmt = (DateTime?)val.GetDefault("post_modified_gmt");
            _post_parent = (ulong?)val.GetDefault("post_parent");
            _guid = (string?)val.GetDefault("guid");
            _menu_order = (int?)val.GetDefault("menu_order");
            _post_type = (string?)val.GetDefault("post_type");
            _post_mime_type = (string?)val.GetDefault("post_mime_type");
            _comment_count = (long?)val.GetDefault("comment_count");
        }


        protected ulong? _ID = null;
        public ulong? ID
        {
            get { return _ID; }
            set { _ID = value; NotifyPropertyChanged(nameof(ID)); }
        }
        protected ulong? _post_author = null;
        public ulong? post_author
        {
            get { return _post_author; }
            set { _post_author = value; NotifyPropertyChanged(nameof(post_author)); }
        }
        protected DateTime? _post_date = null;
        public DateTime? post_date
        {
            get { return _post_date; }
            set { _post_date = value; NotifyPropertyChanged(nameof(post_date)); }
        }
        protected DateTime? _post_date_gmt = null;
        public DateTime? post_date_gmt
        {
            get { return _post_date_gmt; }
            set { _post_date_gmt = value; NotifyPropertyChanged(nameof(post_date_gmt)); }
        }
        protected string? _post_content = null;
        public string? post_content
        {
            get { return _post_content; }
            set { _post_content = value; NotifyPropertyChanged(nameof(post_content)); }
        }
        protected string? _post_title = null;
        public string? post_title
        {
            get { return _post_title; }
            set { _post_title = value; NotifyPropertyChanged(nameof(post_title)); }
        }
        protected string? _post_excerpt = null;
        public string? post_excerpt
        {
            get { return _post_excerpt; }
            set { _post_excerpt = value; NotifyPropertyChanged(nameof(post_excerpt)); }
        }
        protected string? _post_status = null;
        public string? post_status
        {
            get { return _post_status; }
            set { _post_status = value; NotifyPropertyChanged(nameof(post_status)); }
        }
        protected string? _comment_status = null;
        public string? comment_status
        {
            get { return _comment_status; }
            set { _comment_status = value; NotifyPropertyChanged(nameof(comment_status)); }
        }
        protected string? _ping_status = null;
        public string? ping_status
        {
            get { return _ping_status; }
            set { _ping_status = value; NotifyPropertyChanged(nameof(ping_status)); }
        }
        protected string? _post_password = null;
        public string? post_password
        {
            get { return _post_password; }
            set { _post_password = value; NotifyPropertyChanged(nameof(post_password)); }
        }
        protected string? _post_name = null;
        public string? post_name
        {
            get { return _post_name; }
            set { _post_name = value; NotifyPropertyChanged(nameof(post_name)); }
        }
        protected string? _to_ping = null;
        public string? to_ping
        {
            get { return _to_ping; }
            set { _to_ping = value; NotifyPropertyChanged(nameof(to_ping)); }
        }
        protected string? _pinged = null;
        public string? pinged
        {
            get { return _pinged; }
            set { _pinged = value; NotifyPropertyChanged(nameof(pinged)); }
        }
        protected DateTime? _post_modified = null;
        public DateTime? post_modified
        {
            get { return _post_modified; }
            set { _post_modified = value; NotifyPropertyChanged(nameof(post_modified)); }
        }
        protected DateTime? _post_modified_gmt = null;
        public DateTime? post_modified_gmt
        {
            get { return _post_modified_gmt; }
            set { _post_modified_gmt = value; NotifyPropertyChanged(nameof(post_modified_gmt)); }
        }
        protected string? _post_content_filtered = null;
        public string? post_content_filtered
        {
            get { return _post_content_filtered; }
            set { _post_content_filtered = value; NotifyPropertyChanged(nameof(post_content_filtered)); }
        }
        protected ulong? _post_parent = null;
        public ulong? post_parent
        {
            get { return _post_parent; }
            set { _post_parent = value; NotifyPropertyChanged(nameof(post_parent)); }
        }
        protected string? _guid = null;
        public string? guid
        {
            get { return _guid; }
            set { _guid = value; NotifyPropertyChanged(nameof(guid)); }
        }
        protected int? _menu_order = null;
        public int? menu_order
        {
            get { return _menu_order; }
            set { _menu_order = value; NotifyPropertyChanged(nameof(menu_order)); }
        }
        protected string? _post_type = null;
        public string? post_type
        {
            get { return _post_type; }
            set { _post_type = value; NotifyPropertyChanged(nameof(post_type)); }
        }
        protected string? _post_mime_type = null;
        public string? post_mime_type
        {
            get { return _post_mime_type; }
            set { _post_mime_type = value; NotifyPropertyChanged(nameof(post_mime_type)); }
        }
        protected long? _comment_count = null;
        public long? comment_count
        {
            get { return _comment_count; }
            set { _comment_count = value; NotifyPropertyChanged(nameof(comment_count)); }
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
