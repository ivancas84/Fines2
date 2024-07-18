#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_users : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_users";

        public Data_wpwt_users ()
        {
        }

        public Data_wpwt_users(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_users");
            _user_login = (string?)val.GetDefault("user_login");
            _user_pass = (string?)val.GetDefault("user_pass");
            _user_nicename = (string?)val.GetDefault("user_nicename");
            _user_email = (string?)val.GetDefault("user_email");
            _user_url = (string?)val.GetDefault("user_url");
            _user_registered = (DateTime?)val.GetDefault("user_registered");
            _user_activation_key = (string?)val.GetDefault("user_activation_key");
            _user_status = (int?)val.GetDefault("user_status");
            _display_name = (string?)val.GetDefault("display_name");
        }

        public string? Label { get; set; }

        protected ulong? _ID = null;
        public ulong? ID
        {
            get { return _ID; }
            set { _ID = value; NotifyPropertyChanged(nameof(ID)); }
        }
        protected string? _user_login = null;
        public string? user_login
        {
            get { return _user_login; }
            set { _user_login = value; NotifyPropertyChanged(nameof(user_login)); }
        }
        protected string? _user_pass = null;
        public string? user_pass
        {
            get { return _user_pass; }
            set { _user_pass = value; NotifyPropertyChanged(nameof(user_pass)); }
        }
        protected string? _user_nicename = null;
        public string? user_nicename
        {
            get { return _user_nicename; }
            set { _user_nicename = value; NotifyPropertyChanged(nameof(user_nicename)); }
        }
        protected string? _user_email = null;
        public string? user_email
        {
            get { return _user_email; }
            set { _user_email = value; NotifyPropertyChanged(nameof(user_email)); }
        }
        protected string? _user_url = null;
        public string? user_url
        {
            get { return _user_url; }
            set { _user_url = value; NotifyPropertyChanged(nameof(user_url)); }
        }
        protected DateTime? _user_registered = null;
        public DateTime? user_registered
        {
            get { return _user_registered; }
            set { _user_registered = value; NotifyPropertyChanged(nameof(user_registered)); }
        }
        protected string? _user_activation_key = null;
        public string? user_activation_key
        {
            get { return _user_activation_key; }
            set { _user_activation_key = value; NotifyPropertyChanged(nameof(user_activation_key)); }
        }
        protected int? _user_status = null;
        public int? user_status
        {
            get { return _user_status; }
            set { _user_status = value; NotifyPropertyChanged(nameof(user_status)); }
        }
        protected string? _display_name = null;
        public string? display_name
        {
            get { return _display_name; }
            set { _display_name = value; NotifyPropertyChanged(nameof(display_name)); }
        }
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "ID":
                    if (_ID == null)
                        return "Debe completar valor.";
                    return "";

                case "user_login":
                    if (_user_login == null)
                        return "Debe completar valor.";
                    return "";

                case "user_pass":
                    if (_user_pass == null)
                        return "Debe completar valor.";
                    return "";

                case "user_nicename":
                    if (_user_nicename == null)
                        return "Debe completar valor.";
                    return "";

                case "user_email":
                    if (_user_email == null)
                        return "Debe completar valor.";
                    return "";

                case "user_url":
                    if (_user_url == null)
                        return "Debe completar valor.";
                    return "";

                case "user_registered":
                    if (_user_registered == null)
                        return "Debe completar valor.";
                    return "";

                case "user_activation_key":
                    if (_user_activation_key == null)
                        return "Debe completar valor.";
                    return "";

                case "user_status":
                    if (_user_status == null)
                        return "Debe completar valor.";
                    return "";

                case "display_name":
                    if (_display_name == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
