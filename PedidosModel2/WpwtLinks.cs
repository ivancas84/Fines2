#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtLinks : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_links";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_links");
            _link_url = (string?)val.GetDefault("link_url");
            _link_name = (string?)val.GetDefault("link_name");
            _link_image = (string?)val.GetDefault("link_image");
            _link_target = (string?)val.GetDefault("link_target");
            _link_description = (string?)val.GetDefault("link_description");
            _link_visible = (string?)val.GetDefault("link_visible");
            _link_owner = (ulong?)val.GetDefault("link_owner");
            _link_rating = (int?)val.GetDefault("link_rating");
            _link_updated = (DateTime?)val.GetDefault("link_updated");
            _link_rel = (string?)val.GetDefault("link_rel");
            _link_rss = (string?)val.GetDefault("link_rss");
        }


        protected ulong? _link_id = null;
        public ulong? link_id
        {
            get { return _link_id; }
            set { _link_id = value; NotifyPropertyChanged(nameof(link_id)); }
        }
        protected string? _link_url = null;
        public string? link_url
        {
            get { return _link_url; }
            set { _link_url = value; NotifyPropertyChanged(nameof(link_url)); }
        }
        protected string? _link_name = null;
        public string? link_name
        {
            get { return _link_name; }
            set { _link_name = value; NotifyPropertyChanged(nameof(link_name)); }
        }
        protected string? _link_image = null;
        public string? link_image
        {
            get { return _link_image; }
            set { _link_image = value; NotifyPropertyChanged(nameof(link_image)); }
        }
        protected string? _link_target = null;
        public string? link_target
        {
            get { return _link_target; }
            set { _link_target = value; NotifyPropertyChanged(nameof(link_target)); }
        }
        protected string? _link_description = null;
        public string? link_description
        {
            get { return _link_description; }
            set { _link_description = value; NotifyPropertyChanged(nameof(link_description)); }
        }
        protected string? _link_visible = null;
        public string? link_visible
        {
            get { return _link_visible; }
            set { _link_visible = value; NotifyPropertyChanged(nameof(link_visible)); }
        }
        protected ulong? _link_owner = null;
        public ulong? link_owner
        {
            get { return _link_owner; }
            set { _link_owner = value; NotifyPropertyChanged(nameof(link_owner)); }
        }
        protected int? _link_rating = null;
        public int? link_rating
        {
            get { return _link_rating; }
            set { _link_rating = value; NotifyPropertyChanged(nameof(link_rating)); }
        }
        protected DateTime? _link_updated = null;
        public DateTime? link_updated
        {
            get { return _link_updated; }
            set { _link_updated = value; NotifyPropertyChanged(nameof(link_updated)); }
        }
        protected string? _link_rel = null;
        public string? link_rel
        {
            get { return _link_rel; }
            set { _link_rel = value; NotifyPropertyChanged(nameof(link_rel)); }
        }
        protected string? _link_notes = null;
        public string? link_notes
        {
            get { return _link_notes; }
            set { _link_notes = value; NotifyPropertyChanged(nameof(link_notes)); }
        }
        protected string? _link_rss = null;
        public string? link_rss
        {
            get { return _link_rss; }
            set { _link_rss = value; NotifyPropertyChanged(nameof(link_rss)); }
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
