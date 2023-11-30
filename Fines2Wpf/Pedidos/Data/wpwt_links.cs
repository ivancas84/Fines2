#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_links : SqlOrganize.Data
    {

        public Data_wpwt_links ()
        {
            Initialize();
        }

        public Data_wpwt_links(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _link_url = (string?)ContainerApp.db.Values("wpwt_links").Default("link_url").Get("link_url");
                    _link_name = (string?)ContainerApp.db.Values("wpwt_links").Default("link_name").Get("link_name");
                    _link_image = (string?)ContainerApp.db.Values("wpwt_links").Default("link_image").Get("link_image");
                    _link_target = (string?)ContainerApp.db.Values("wpwt_links").Default("link_target").Get("link_target");
                    _link_description = (string?)ContainerApp.db.Values("wpwt_links").Default("link_description").Get("link_description");
                    _link_visible = (string?)ContainerApp.db.Values("wpwt_links").Default("link_visible").Get("link_visible");
                    _link_owner = (ulong?)ContainerApp.db.Values("wpwt_links").Default("link_owner").Get("link_owner");
                    _link_rating = (int?)ContainerApp.db.Values("wpwt_links").Default("link_rating").Get("link_rating");
                    _link_updated = (DateTime?)ContainerApp.db.Values("wpwt_links").Default("link_updated").Get("link_updated");
                    _link_rel = (string?)ContainerApp.db.Values("wpwt_links").Default("link_rel").Get("link_rel");
                    _link_rss = (string?)ContainerApp.db.Values("wpwt_links").Default("link_rss").Get("link_rss");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _link_id = null;
        public ulong? link_id
        {
            get { return _link_id; }
            set { _link_id = value; NotifyPropertyChanged(); }
        }
        protected string? _link_url = null;
        public string? link_url
        {
            get { return _link_url; }
            set { _link_url = value; NotifyPropertyChanged(); }
        }
        protected string? _link_name = null;
        public string? link_name
        {
            get { return _link_name; }
            set { _link_name = value; NotifyPropertyChanged(); }
        }
        protected string? _link_image = null;
        public string? link_image
        {
            get { return _link_image; }
            set { _link_image = value; NotifyPropertyChanged(); }
        }
        protected string? _link_target = null;
        public string? link_target
        {
            get { return _link_target; }
            set { _link_target = value; NotifyPropertyChanged(); }
        }
        protected string? _link_description = null;
        public string? link_description
        {
            get { return _link_description; }
            set { _link_description = value; NotifyPropertyChanged(); }
        }
        protected string? _link_visible = null;
        public string? link_visible
        {
            get { return _link_visible; }
            set { _link_visible = value; NotifyPropertyChanged(); }
        }
        protected ulong? _link_owner = null;
        public ulong? link_owner
        {
            get { return _link_owner; }
            set { _link_owner = value; NotifyPropertyChanged(); }
        }
        protected int? _link_rating = null;
        public int? link_rating
        {
            get { return _link_rating; }
            set { _link_rating = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _link_updated = null;
        public DateTime? link_updated
        {
            get { return _link_updated; }
            set { _link_updated = value; NotifyPropertyChanged(); }
        }
        protected string? _link_rel = null;
        public string? link_rel
        {
            get { return _link_rel; }
            set { _link_rel = value; NotifyPropertyChanged(); }
        }
        protected string? _link_notes = null;
        public string? link_notes
        {
            get { return _link_notes; }
            set { _link_notes = value; NotifyPropertyChanged(); }
        }
        protected string? _link_rss = null;
        public string? link_rss
        {
            get { return _link_rss; }
            set { _link_rss = value; NotifyPropertyChanged(); }
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

                case "link_id":
                    if (_link_id == null)
                        return "Debe completar valor.";
                    return "";

                case "link_url":
                    if (_link_url == null)
                        return "Debe completar valor.";
                    return "";

                case "link_name":
                    if (_link_name == null)
                        return "Debe completar valor.";
                    return "";

                case "link_image":
                    if (_link_image == null)
                        return "Debe completar valor.";
                    return "";

                case "link_target":
                    if (_link_target == null)
                        return "Debe completar valor.";
                    return "";

                case "link_description":
                    if (_link_description == null)
                        return "Debe completar valor.";
                    return "";

                case "link_visible":
                    if (_link_visible == null)
                        return "Debe completar valor.";
                    return "";

                case "link_owner":
                    if (_link_owner == null)
                        return "Debe completar valor.";
                    return "";

                case "link_rating":
                    if (_link_rating == null)
                        return "Debe completar valor.";
                    return "";

                case "link_updated":
                    if (_link_updated == null)
                        return "Debe completar valor.";
                    return "";

                case "link_rel":
                    if (_link_rel == null)
                        return "Debe completar valor.";
                    return "";

                case "link_notes":
                    if (_link_notes == null)
                        return "Debe completar valor.";
                    return "";

                case "link_rss":
                    if (_link_rss == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
