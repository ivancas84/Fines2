#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_threads : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_psmsc_threads";

        public Data_wpwt_psmsc_threads ()
        {
        }

        public Data_wpwt_psmsc_threads(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_threads");
            _id = (long?)val.GetDefault("id");
            _is_active = (int?)val.GetDefault("is_active");
            _customer = (long?)val.GetDefault("customer");
            _type = (string?)val.GetDefault("type");
            _date_created = (DateTime?)val.GetDefault("date_created");
            _date_updated = (DateTime?)val.GetDefault("date_updated");
        }

        public string? Label { get; set; }

        protected long? _id = null;
        public long? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected long? _ticket = null;
        public long? ticket
        {
            get { return _ticket; }
            set { _ticket = value; NotifyPropertyChanged(nameof(ticket)); }
        }
        protected int? _is_active = null;
        public int? is_active
        {
            get { return _is_active; }
            set { _is_active = value; NotifyPropertyChanged(nameof(is_active)); }
        }
        protected long? _customer = null;
        public long? customer
        {
            get { return _customer; }
            set { _customer = value; NotifyPropertyChanged(nameof(customer)); }
        }
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(nameof(type)); }
        }
        protected string? _body = null;
        public string? body
        {
            get { return _body; }
            set { _body = value; NotifyPropertyChanged(nameof(body)); }
        }
        protected string? _attachments = null;
        public string? attachments
        {
            get { return _attachments; }
            set { _attachments = value; NotifyPropertyChanged(nameof(attachments)); }
        }
        protected string? _ip_address = null;
        public string? ip_address
        {
            get { return _ip_address; }
            set { _ip_address = value; NotifyPropertyChanged(nameof(ip_address)); }
        }
        protected string? _source = null;
        public string? source
        {
            get { return _source; }
            set { _source = value; NotifyPropertyChanged(nameof(source)); }
        }
        protected string? _os = null;
        public string? os
        {
            get { return _os; }
            set { _os = value; NotifyPropertyChanged(nameof(os)); }
        }
        protected string? _browser = null;
        public string? browser
        {
            get { return _browser; }
            set { _browser = value; NotifyPropertyChanged(nameof(browser)); }
        }
        protected DateTime? _seen = null;
        public DateTime? seen
        {
            get { return _seen; }
            set { _seen = value; NotifyPropertyChanged(nameof(seen)); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(nameof(date_created)); }
        }
        protected DateTime? _date_updated = null;
        public DateTime? date_updated
        {
            get { return _date_updated; }
            set { _date_updated = value; NotifyPropertyChanged(nameof(date_updated)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "ticket":
                    if (_ticket == null)
                        return "Debe completar valor.";
                    return "";

                case "is_active":
                    if (_is_active == null)
                        return "Debe completar valor.";
                    return "";

                case "customer":
                    return "";

                case "type":
                    if (_type == null)
                        return "Debe completar valor.";
                    return "";

                case "body":
                    if (_body == null)
                        return "Debe completar valor.";
                    return "";

                case "attachments":
                    return "";

                case "ip_address":
                    return "";

                case "source":
                    return "";

                case "os":
                    return "";

                case "browser":
                    return "";

                case "seen":
                    return "";

                case "date_created":
                    if (_date_created == null)
                        return "Debe completar valor.";
                    return "";

                case "date_updated":
                    if (_date_updated == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
