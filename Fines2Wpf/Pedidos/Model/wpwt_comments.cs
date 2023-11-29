#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_comments : SqlOrganize.Data
    {

        public Data_wpwt_comments ()
        {
            Initialize();
        }

        public Data_wpwt_comments(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _comment_post_ID = (ulong?)ContainerApp.db.Values("wpwt_comments").Default("comment_post_ID").Get("comment_post_ID");
                    _comment_author_email = (string?)ContainerApp.db.Values("wpwt_comments").Default("comment_author_email").Get("comment_author_email");
                    _comment_author_url = (string?)ContainerApp.db.Values("wpwt_comments").Default("comment_author_url").Get("comment_author_url");
                    _comment_author_IP = (string?)ContainerApp.db.Values("wpwt_comments").Default("comment_author_IP").Get("comment_author_IP");
                    _comment_date = (DateTime?)ContainerApp.db.Values("wpwt_comments").Default("comment_date").Get("comment_date");
                    _comment_date_gmt = (DateTime?)ContainerApp.db.Values("wpwt_comments").Default("comment_date_gmt").Get("comment_date_gmt");
                    _comment_karma = (int?)ContainerApp.db.Values("wpwt_comments").Default("comment_karma").Get("comment_karma");
                    _comment_approved = (string?)ContainerApp.db.Values("wpwt_comments").Default("comment_approved").Get("comment_approved");
                    _comment_agent = (string?)ContainerApp.db.Values("wpwt_comments").Default("comment_agent").Get("comment_agent");
                    _comment_type = (string?)ContainerApp.db.Values("wpwt_comments").Default("comment_type").Get("comment_type");
                    _comment_parent = (ulong?)ContainerApp.db.Values("wpwt_comments").Default("comment_parent").Get("comment_parent");
                    _user_id = (ulong?)ContainerApp.db.Values("wpwt_comments").Default("user_id").Get("user_id");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _comment_ID = null;
        public ulong? comment_ID
        {
            get { return _comment_ID; }
            set { _comment_ID = value; NotifyPropertyChanged(); }
        }
        protected ulong? _comment_post_ID = null;
        public ulong? comment_post_ID
        {
            get { return _comment_post_ID; }
            set { _comment_post_ID = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_author = null;
        public string? comment_author
        {
            get { return _comment_author; }
            set { _comment_author = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_author_email = null;
        public string? comment_author_email
        {
            get { return _comment_author_email; }
            set { _comment_author_email = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_author_url = null;
        public string? comment_author_url
        {
            get { return _comment_author_url; }
            set { _comment_author_url = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_author_IP = null;
        public string? comment_author_IP
        {
            get { return _comment_author_IP; }
            set { _comment_author_IP = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _comment_date = null;
        public DateTime? comment_date
        {
            get { return _comment_date; }
            set { _comment_date = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _comment_date_gmt = null;
        public DateTime? comment_date_gmt
        {
            get { return _comment_date_gmt; }
            set { _comment_date_gmt = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_content = null;
        public string? comment_content
        {
            get { return _comment_content; }
            set { _comment_content = value; NotifyPropertyChanged(); }
        }
        protected int? _comment_karma = null;
        public int? comment_karma
        {
            get { return _comment_karma; }
            set { _comment_karma = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_approved = null;
        public string? comment_approved
        {
            get { return _comment_approved; }
            set { _comment_approved = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_agent = null;
        public string? comment_agent
        {
            get { return _comment_agent; }
            set { _comment_agent = value; NotifyPropertyChanged(); }
        }
        protected string? _comment_type = null;
        public string? comment_type
        {
            get { return _comment_type; }
            set { _comment_type = value; NotifyPropertyChanged(); }
        }
        protected ulong? _comment_parent = null;
        public ulong? comment_parent
        {
            get { return _comment_parent; }
            set { _comment_parent = value; NotifyPropertyChanged(); }
        }
        protected ulong? _user_id = null;
        public ulong? user_id
        {
            get { return _user_id; }
            set { _user_id = value; NotifyPropertyChanged(); }
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
