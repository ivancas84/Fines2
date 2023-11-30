#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_psmsc_threads : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_threads ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_threads(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (long?)ContainerApp.db.Values("wpwt_psmsc_threads").Default("id").Get("id");
                    _is_active = (int?)ContainerApp.db.Values("wpwt_psmsc_threads").Default("is_active").Get("is_active");
                    _customer = (long?)ContainerApp.db.Values("wpwt_psmsc_threads").Default("customer").Get("customer");
                    _type = (string?)ContainerApp.db.Values("wpwt_psmsc_threads").Default("type").Get("type");
                    _date_created = (DateTime?)ContainerApp.db.Values("wpwt_psmsc_threads").Default("date_created").Get("date_created");
                    _date_updated = (DateTime?)ContainerApp.db.Values("wpwt_psmsc_threads").Default("date_updated").Get("date_updated");
                break;
            }
        }

        public string? Label { get; set; }

        protected long? _id = null;
        public long? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected long? _ticket = null;
        public long? ticket
        {
            get { return _ticket; }
            set { _ticket = value; NotifyPropertyChanged(); }
        }
        protected int? _is_active = null;
        public int? is_active
        {
            get { return _is_active; }
            set { _is_active = value; NotifyPropertyChanged(); }
        }
        protected long? _customer = null;
        public long? customer
        {
            get { return _customer; }
            set { _customer = value; NotifyPropertyChanged(); }
        }
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(); }
        }
        protected string? _body = null;
        public string? body
        {
            get { return _body; }
            set { _body = value; NotifyPropertyChanged(); }
        }
        protected string? _attachments = null;
        public string? attachments
        {
            get { return _attachments; }
            set { _attachments = value; NotifyPropertyChanged(); }
        }
        protected string? _ip_address = null;
        public string? ip_address
        {
            get { return _ip_address; }
            set { _ip_address = value; NotifyPropertyChanged(); }
        }
        protected string? _source = null;
        public string? source
        {
            get { return _source; }
            set { _source = value; NotifyPropertyChanged(); }
        }
        protected string? _os = null;
        public string? os
        {
            get { return _os; }
            set { _os = value; NotifyPropertyChanged(); }
        }
        protected string? _browser = null;
        public string? browser
        {
            get { return _browser; }
            set { _browser = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _seen = null;
        public DateTime? seen
        {
            get { return _seen; }
            set { _seen = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_updated = null;
        public DateTime? date_updated
        {
            get { return _date_updated; }
            set { _date_updated = value; NotifyPropertyChanged(); }
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
