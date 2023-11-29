#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_users : SqlOrganize.Data
    {

        public Data_wpwt_users ()
        {
            Initialize();
        }

        public Data_wpwt_users(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _user_login = (string?)ContainerApp.db.Values("wpwt_users").Default("user_login").Get("user_login");
                    _user_pass = (string?)ContainerApp.db.Values("wpwt_users").Default("user_pass").Get("user_pass");
                    _user_nicename = (string?)ContainerApp.db.Values("wpwt_users").Default("user_nicename").Get("user_nicename");
                    _user_email = (string?)ContainerApp.db.Values("wpwt_users").Default("user_email").Get("user_email");
                    _user_url = (string?)ContainerApp.db.Values("wpwt_users").Default("user_url").Get("user_url");
                    _user_registered = (DateTime?)ContainerApp.db.Values("wpwt_users").Default("user_registered").Get("user_registered");
                    _user_activation_key = (string?)ContainerApp.db.Values("wpwt_users").Default("user_activation_key").Get("user_activation_key");
                    _user_status = (int?)ContainerApp.db.Values("wpwt_users").Default("user_status").Get("user_status");
                    _display_name = (string?)ContainerApp.db.Values("wpwt_users").Default("display_name").Get("display_name");
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
        protected string? _user_login = null;
        public string? user_login
        {
            get { return _user_login; }
            set { _user_login = value; NotifyPropertyChanged(); }
        }
        protected string? _user_pass = null;
        public string? user_pass
        {
            get { return _user_pass; }
            set { _user_pass = value; NotifyPropertyChanged(); }
        }
        protected string? _user_nicename = null;
        public string? user_nicename
        {
            get { return _user_nicename; }
            set { _user_nicename = value; NotifyPropertyChanged(); }
        }
        protected string? _user_email = null;
        public string? user_email
        {
            get { return _user_email; }
            set { _user_email = value; NotifyPropertyChanged(); }
        }
        protected string? _user_url = null;
        public string? user_url
        {
            get { return _user_url; }
            set { _user_url = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _user_registered = null;
        public DateTime? user_registered
        {
            get { return _user_registered; }
            set { _user_registered = value; NotifyPropertyChanged(); }
        }
        protected string? _user_activation_key = null;
        public string? user_activation_key
        {
            get { return _user_activation_key; }
            set { _user_activation_key = value; NotifyPropertyChanged(); }
        }
        protected int? _user_status = null;
        public int? user_status
        {
            get { return _user_status; }
            set { _user_status = value; NotifyPropertyChanged(); }
        }
        protected string? _display_name = null;
        public string? display_name
        {
            get { return _display_name; }
            set { _display_name = value; NotifyPropertyChanged(); }
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
