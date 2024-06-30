#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_login_redirects : SqlOrganize.Data
    {

        public Data_wpwt_login_redirects ()
        {
        }

        public Data_wpwt_login_redirects(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_login_redirects Default()
        {
            EntityValues val = db!.Values("wpwt_login_redirects");
            _id = (long?)val.GetDefault("id");
            _rul_order = (int?)val.GetDefault("rul_order");
            return this;
        }

        public string? Label { get; set; }

        protected long? _id = null;
        public long? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _rul_type = null;
        public string? rul_type
        {
            get { return _rul_type; }
            set { _rul_type = value; NotifyPropertyChanged(nameof(rul_type)); }
        }
        protected string? _rul_value = null;
        public string? rul_value
        {
            get { return _rul_value; }
            set { _rul_value = value; NotifyPropertyChanged(nameof(rul_value)); }
        }
        protected string? _rul_url = null;
        public string? rul_url
        {
            get { return _rul_url; }
            set { _rul_url = value; NotifyPropertyChanged(nameof(rul_url)); }
        }
        protected string? _rul_url_logout = null;
        public string? rul_url_logout
        {
            get { return _rul_url_logout; }
            set { _rul_url_logout = value; NotifyPropertyChanged(nameof(rul_url_logout)); }
        }
        protected int? _rul_order = null;
        public int? rul_order
        {
            get { return _rul_order; }
            set { _rul_order = value; NotifyPropertyChanged(nameof(rul_order)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "rul_type":
                    if (_rul_type == null)
                        return "Debe completar valor.";
                    return "";

                case "rul_value":
                    return "";

                case "rul_url":
                    return "";

                case "rul_url_logout":
                    return "";

                case "rul_order":
                    if (_rul_order == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
