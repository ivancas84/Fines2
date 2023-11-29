#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_tickets : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_tickets ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_tickets(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("id").Get("id");
                    _is_active = (bool?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("is_active").Get("is_active");
                    _customer = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("customer").Get("customer");
                    _status = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("status").Get("status");
                    _priority = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("priority").Get("priority");
                    _category = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("category").Get("category");
                    _date_created = (DateTime?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("date_created").Get("date_created");
                    _date_updated = (DateTime?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("date_updated").Get("date_updated");
                    _user_type = (string?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("user_type").Get("user_type");
                    _last_reply_by = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("last_reply_by").Get("last_reply_by");
                    _auth_code = (string?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("auth_code").Get("auth_code");
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
        protected bool? _is_active = null;
        public bool? is_active
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
        protected string? _subject = null;
        public string? subject
        {
            get { return _subject; }
            set { _subject = value; NotifyPropertyChanged(); }
        }
        protected long? _status = null;
        public long? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(); }
        }
        protected long? _priority = null;
        public long? priority
        {
            get { return _priority; }
            set { _priority = value; NotifyPropertyChanged(); }
        }
        protected long? _category = null;
        public long? category
        {
            get { return _category; }
            set { _category = value; NotifyPropertyChanged(); }
        }
        protected string? _assigned_agent = null;
        public string? assigned_agent
        {
            get { return _assigned_agent; }
            set { _assigned_agent = value; NotifyPropertyChanged(); }
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
        protected int? _agent_created = null;
        public int? agent_created
        {
            get { return _agent_created; }
            set { _agent_created = value; NotifyPropertyChanged(); }
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
        protected string? _browser = null;
        public string? browser
        {
            get { return _browser; }
            set { _browser = value; NotifyPropertyChanged(); }
        }
        protected string? _os = null;
        public string? os
        {
            get { return _os; }
            set { _os = value; NotifyPropertyChanged(); }
        }
        protected string? _add_recipients = null;
        public string? add_recipients
        {
            get { return _add_recipients; }
            set { _add_recipients = value; NotifyPropertyChanged(); }
        }
        protected string? _prev_assignee = null;
        public string? prev_assignee
        {
            get { return _prev_assignee; }
            set { _prev_assignee = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_closed = null;
        public DateTime? date_closed
        {
            get { return _date_closed; }
            set { _date_closed = value; NotifyPropertyChanged(); }
        }
        protected string? _user_type = null;
        public string? user_type
        {
            get { return _user_type; }
            set { _user_type = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _last_reply_on = null;
        public DateTime? last_reply_on
        {
            get { return _last_reply_on; }
            set { _last_reply_on = value; NotifyPropertyChanged(); }
        }
        protected long? _last_reply_by = null;
        public long? last_reply_by
        {
            get { return _last_reply_by; }
            set { _last_reply_by = value; NotifyPropertyChanged(); }
        }
        protected string? _auth_code = null;
        public string? auth_code
        {
            get { return _auth_code; }
            set { _auth_code = value; NotifyPropertyChanged(); }
        }
        protected string? _cust_24 = null;
        public string? cust_24
        {
            get { return _cust_24; }
            set { _cust_24 = value; NotifyPropertyChanged(); }
        }
        protected string? _cust_25 = null;
        public string? cust_25
        {
            get { return _cust_25; }
            set { _cust_25 = value; NotifyPropertyChanged(); }
        }
        protected string? _cust_26 = null;
        public string? cust_26
        {
            get { return _cust_26; }
            set { _cust_26 = value; NotifyPropertyChanged(); }
        }
        protected string? _cust_27 = null;
        public string? cust_27
        {
            get { return _cust_27; }
            set { _cust_27 = value; NotifyPropertyChanged(); }
        }
        protected string? _cust_28 = null;
        public string? cust_28
        {
            get { return _cust_28; }
            set { _cust_28 = value; NotifyPropertyChanged(); }
        }
        protected string? _tags = null;
        public string? tags
        {
            get { return _tags; }
            set { _tags = value; NotifyPropertyChanged(); }
        }
        protected string? _live_agents = null;
        public string? live_agents
        {
            get { return _live_agents; }
            set { _live_agents = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "is_active":
                    return "";

                case "customer":
                    if (_customer == null)
                        return "Debe completar valor.";
                    return "";

                case "subject":
                    if (_subject == null)
                        return "Debe completar valor.";
                    return "";

                case "status":
                    if (_status == null)
                        return "Debe completar valor.";
                    return "";

                case "priority":
                    if (_priority == null)
                        return "Debe completar valor.";
                    return "";

                case "category":
                    if (_category == null)
                        return "Debe completar valor.";
                    return "";

                case "assigned_agent":
                    return "";

                case "date_created":
                    if (_date_created == null)
                        return "Debe completar valor.";
                    return "";

                case "date_updated":
                    if (_date_updated == null)
                        return "Debe completar valor.";
                    return "";

                case "agent_created":
                    return "";

                case "ip_address":
                    return "";

                case "source":
                    return "";

                case "browser":
                    return "";

                case "os":
                    return "";

                case "add_recipients":
                    return "";

                case "prev_assignee":
                    return "";

                case "date_closed":
                    return "";

                case "user_type":
                    return "";

                case "last_reply_on":
                    return "";

                case "last_reply_by":
                    if (_last_reply_by == null)
                        return "Debe completar valor.";
                    return "";

                case "auth_code":
                    return "";

                case "cust_24":
                    return "";

                case "cust_25":
                    return "";

                case "cust_26":
                    return "";

                case "cust_27":
                    return "";

                case "cust_28":
                    return "";

                case "tags":
                    return "";

                case "live_agents":
                    return "";

            }

            return "";
        }
    }
}
