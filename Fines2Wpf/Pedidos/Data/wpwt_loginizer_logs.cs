#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_loginizer_logs : SqlOrganize.Data
    {

        public Data_wpwt_loginizer_logs ()
        {
            Initialize();
        }

        public Data_wpwt_loginizer_logs(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _username = (string?)ContainerApp.db.Values("wpwt_loginizer_logs").Default("username").Get("username");
                    _time = (int?)ContainerApp.db.Values("wpwt_loginizer_logs").Default("time").Get("time");
                    _count = (int?)ContainerApp.db.Values("wpwt_loginizer_logs").Default("count").Get("count");
                    _lockout = (int?)ContainerApp.db.Values("wpwt_loginizer_logs").Default("lockout").Get("lockout");
                    _ip = (string?)ContainerApp.db.Values("wpwt_loginizer_logs").Default("ip").Get("ip");
                    _url = (string?)ContainerApp.db.Values("wpwt_loginizer_logs").Default("url").Get("url");
                break;
            }
        }

        public string? Label { get; set; }

        protected string? _username = null;
        public string? username
        {
            get { return _username; }
            set { _username = value; NotifyPropertyChanged(); }
        }
        protected int? _time = null;
        public int? time
        {
            get { return _time; }
            set { _time = value; NotifyPropertyChanged(); }
        }
        protected int? _count = null;
        public int? count
        {
            get { return _count; }
            set { _count = value; NotifyPropertyChanged(); }
        }
        protected int? _lockout = null;
        public int? lockout
        {
            get { return _lockout; }
            set { _lockout = value; NotifyPropertyChanged(); }
        }
        protected string? _ip = null;
        public string? ip
        {
            get { return _ip; }
            set { _ip = value; NotifyPropertyChanged(); }
        }
        protected string? _url = null;
        public string? url
        {
            get { return _url; }
            set { _url = value; NotifyPropertyChanged(); }
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

                case "username":
                    if (_username == null)
                        return "Debe completar valor.";
                    return "";

                case "time":
                    if (_time == null)
                        return "Debe completar valor.";
                    return "";

                case "count":
                    if (_count == null)
                        return "Debe completar valor.";
                    return "";

                case "lockout":
                    if (_lockout == null)
                        return "Debe completar valor.";
                    return "";

                case "ip":
                    if (_ip == null)
                        return "Debe completar valor.";
                    if (!_ip.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("wpwt_loginizer_logs").Where("$ip = @0").Parameters(_ip).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "url":
                    if (_url == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
