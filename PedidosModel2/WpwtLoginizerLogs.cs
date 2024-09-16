#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtLoginizerLogs : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_loginizer_logs";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_loginizer_logs");
            _username = (string?)val.GetDefault("username");
            _time = (int?)val.GetDefault("time");
            _count = (int?)val.GetDefault("count");
            _lockout = (int?)val.GetDefault("lockout");
            _ip = (string?)val.GetDefault("ip");
            _url = (string?)val.GetDefault("url");
        }


        protected string? _username = null;
        public string? username
        {
            get { return _username; }
            set { _username = value; NotifyPropertyChanged(nameof(username)); }
        }
        protected int? _time = null;
        public int? time
        {
            get { return _time; }
            set { _time = value; NotifyPropertyChanged(nameof(time)); }
        }
        protected int? _count = null;
        public int? count
        {
            get { return _count; }
            set { _count = value; NotifyPropertyChanged(nameof(count)); }
        }
        protected int? _lockout = null;
        public int? lockout
        {
            get { return _lockout; }
            set { _lockout = value; NotifyPropertyChanged(nameof(lockout)); }
        }
        protected string? _ip = null;
        public string? ip
        {
            get { return _ip; }
            set { _ip = value; NotifyPropertyChanged(nameof(ip)); }
        }
        protected string? _url = null;
        public string? url
        {
            get { return _url; }
            set { _url = value; NotifyPropertyChanged(nameof(url)); }
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
                    if (!db.IsNoE() && !_ip.IsNoE()) {
                        var row = db.Sql("wpwt_loginizer_logs").Equal("$ip", _ip).Cache().Dict();
                        if (!row.IsNoE() && !_Id.ToString().Equals(row!["id"]!.ToString()))
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
