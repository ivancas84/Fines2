#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtComments : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_comments";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_comments");
            _comment_post_ID = (ulong?)val.GetDefault("comment_post_ID");
            _comment_author_email = (string?)val.GetDefault("comment_author_email");
            _comment_author_url = (string?)val.GetDefault("comment_author_url");
            _comment_author_IP = (string?)val.GetDefault("comment_author_IP");
            _comment_date = (DateTime?)val.GetDefault("comment_date");
            _comment_date_gmt = (DateTime?)val.GetDefault("comment_date_gmt");
            _comment_karma = (int?)val.GetDefault("comment_karma");
            _comment_approved = (string?)val.GetDefault("comment_approved");
            _comment_agent = (string?)val.GetDefault("comment_agent");
            _comment_type = (string?)val.GetDefault("comment_type");
            _comment_parent = (ulong?)val.GetDefault("comment_parent");
            _user_id = (ulong?)val.GetDefault("user_id");
        }


        protected ulong? _comment_ID = null;
        public ulong? comment_ID
        {
            get { return _comment_ID; }
            set { _comment_ID = value; NotifyPropertyChanged(nameof(comment_ID)); }
        }
        protected ulong? _comment_post_ID = null;
        public ulong? comment_post_ID
        {
            get { return _comment_post_ID; }
            set { _comment_post_ID = value; NotifyPropertyChanged(nameof(comment_post_ID)); }
        }
        protected string? _comment_author = null;
        public string? comment_author
        {
            get { return _comment_author; }
            set { _comment_author = value; NotifyPropertyChanged(nameof(comment_author)); }
        }
        protected string? _comment_author_email = null;
        public string? comment_author_email
        {
            get { return _comment_author_email; }
            set { _comment_author_email = value; NotifyPropertyChanged(nameof(comment_author_email)); }
        }
        protected string? _comment_author_url = null;
        public string? comment_author_url
        {
            get { return _comment_author_url; }
            set { _comment_author_url = value; NotifyPropertyChanged(nameof(comment_author_url)); }
        }
        protected string? _comment_author_IP = null;
        public string? comment_author_IP
        {
            get { return _comment_author_IP; }
            set { _comment_author_IP = value; NotifyPropertyChanged(nameof(comment_author_IP)); }
        }
        protected DateTime? _comment_date = null;
        public DateTime? comment_date
        {
            get { return _comment_date; }
            set { _comment_date = value; NotifyPropertyChanged(nameof(comment_date)); }
        }
        protected DateTime? _comment_date_gmt = null;
        public DateTime? comment_date_gmt
        {
            get { return _comment_date_gmt; }
            set { _comment_date_gmt = value; NotifyPropertyChanged(nameof(comment_date_gmt)); }
        }
        protected string? _comment_content = null;
        public string? comment_content
        {
            get { return _comment_content; }
            set { _comment_content = value; NotifyPropertyChanged(nameof(comment_content)); }
        }
        protected int? _comment_karma = null;
        public int? comment_karma
        {
            get { return _comment_karma; }
            set { _comment_karma = value; NotifyPropertyChanged(nameof(comment_karma)); }
        }
        protected string? _comment_approved = null;
        public string? comment_approved
        {
            get { return _comment_approved; }
            set { _comment_approved = value; NotifyPropertyChanged(nameof(comment_approved)); }
        }
        protected string? _comment_agent = null;
        public string? comment_agent
        {
            get { return _comment_agent; }
            set { _comment_agent = value; NotifyPropertyChanged(nameof(comment_agent)); }
        }
        protected string? _comment_type = null;
        public string? comment_type
        {
            get { return _comment_type; }
            set { _comment_type = value; NotifyPropertyChanged(nameof(comment_type)); }
        }
        protected ulong? _comment_parent = null;
        public ulong? comment_parent
        {
            get { return _comment_parent; }
            set { _comment_parent = value; NotifyPropertyChanged(nameof(comment_parent)); }
        }
        protected ulong? _user_id = null;
        public ulong? user_id
        {
            get { return _user_id; }
            set { _user_id = value; NotifyPropertyChanged(nameof(user_id)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "comment_ID":
                    if (_comment_ID == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_post_ID":
                    if (_comment_post_ID == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_author":
                    if (_comment_author == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_author_email":
                    if (_comment_author_email == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_author_url":
                    if (_comment_author_url == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_author_IP":
                    if (_comment_author_IP == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_date":
                    if (_comment_date == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_date_gmt":
                    if (_comment_date_gmt == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_content":
                    if (_comment_content == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_karma":
                    if (_comment_karma == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_approved":
                    if (_comment_approved == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_agent":
                    if (_comment_agent == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_type":
                    if (_comment_type == null)
                        return "Debe completar valor.";
                    return "";

                case "comment_parent":
                    if (_comment_parent == null)
                        return "Debe completar valor.";
                    return "";

                case "user_id":
                    if (_user_id == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
