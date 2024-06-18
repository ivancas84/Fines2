#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_psmsc_tickets : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_tickets ()
        {
        }

        public Data_wpwt_psmsc_tickets(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_psmsc_tickets Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_tickets");
            _id = (long?)val.GetDefault("id");
            _is_active = (bool?)val.GetDefault("is_active");
            _auth_code = (string?)val.GetDefault("auth_code");
            return this;
        }

        public string? Label { get; set; }

        protected long? _id = null;
        public long? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected bool? _is_active = null;
        public bool? is_active
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
        protected string? _subject = null;
        public string? subject
        {
            get { return _subject; }
            set { _subject = value; NotifyPropertyChanged(nameof(subject)); }
        }
        protected int? _status = null;
        public int? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(nameof(status)); }
        }
        protected int? _priority = null;
        public int? priority
        {
            get { return _priority; }
            set { _priority = value; NotifyPropertyChanged(nameof(priority)); }
        }
        protected int? _category = null;
        public int? category
        {
            get { return _category; }
            set { _category = value; NotifyPropertyChanged(nameof(category)); }
        }
        protected string? _assigned_agent = null;
        public string? assigned_agent
        {
            get { return _assigned_agent; }
            set { _assigned_agent = value; NotifyPropertyChanged(nameof(assigned_agent)); }
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
        protected int? _agent_created = null;
        public int? agent_created
        {
            get { return _agent_created; }
            set { _agent_created = value; NotifyPropertyChanged(nameof(agent_created)); }
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
        protected string? _browser = null;
        public string? browser
        {
            get { return _browser; }
            set { _browser = value; NotifyPropertyChanged(nameof(browser)); }
        }
        protected string? _os = null;
        public string? os
        {
            get { return _os; }
            set { _os = value; NotifyPropertyChanged(nameof(os)); }
        }
        protected string? _add_recipients = null;
        public string? add_recipients
        {
            get { return _add_recipients; }
            set { _add_recipients = value; NotifyPropertyChanged(nameof(add_recipients)); }
        }
        protected string? _prev_assignee = null;
        public string? prev_assignee
        {
            get { return _prev_assignee; }
            set { _prev_assignee = value; NotifyPropertyChanged(nameof(prev_assignee)); }
        }
        protected DateTime? _date_closed = null;
        public DateTime? date_closed
        {
            get { return _date_closed; }
            set { _date_closed = value; NotifyPropertyChanged(nameof(date_closed)); }
        }
        protected string? _user_type = null;
        public string? user_type
        {
            get { return _user_type; }
            set { _user_type = value; NotifyPropertyChanged(nameof(user_type)); }
        }
        protected DateTime? _last_reply_on = null;
        public DateTime? last_reply_on
        {
            get { return _last_reply_on; }
            set { _last_reply_on = value; NotifyPropertyChanged(nameof(last_reply_on)); }
        }
        protected long? _last_reply_by = null;
        public long? last_reply_by
        {
            get { return _last_reply_by; }
            set { _last_reply_by = value; NotifyPropertyChanged(nameof(last_reply_by)); }
        }
        protected string? _auth_code = null;
        public string? auth_code
        {
            get { return _auth_code; }
            set { _auth_code = value; NotifyPropertyChanged(nameof(auth_code)); }
        }
        protected string? _cust_24 = null;
        public string? cust_24
        {
            get { return _cust_24; }
            set { _cust_24 = value; NotifyPropertyChanged(nameof(cust_24)); }
        }
        protected string? _cust_25 = null;
        public string? cust_25
        {
            get { return _cust_25; }
            set { _cust_25 = value; NotifyPropertyChanged(nameof(cust_25)); }
        }
        protected string? _cust_26 = null;
        public string? cust_26
        {
            get { return _cust_26; }
            set { _cust_26 = value; NotifyPropertyChanged(nameof(cust_26)); }
        }
        protected string? _cust_27 = null;
        public string? cust_27
        {
            get { return _cust_27; }
            set { _cust_27 = value; NotifyPropertyChanged(nameof(cust_27)); }
        }
        protected string? _cust_28 = null;
        public string? cust_28
        {
            get { return _cust_28; }
            set { _cust_28 = value; NotifyPropertyChanged(nameof(cust_28)); }
        }
        protected string? _tags = null;
        public string? tags
        {
            get { return _tags; }
            set { _tags = value; NotifyPropertyChanged(nameof(tags)); }
        }
        protected string? _live_agents = null;
        public string? live_agents
        {
            get { return _live_agents; }
            set { _live_agents = value; NotifyPropertyChanged(nameof(live_agents)); }
        }
        protected string? _last_reply_source = null;
        public string? last_reply_source
        {
            get { return _last_reply_source; }
            set { _last_reply_source = value; NotifyPropertyChanged(nameof(last_reply_source)); }
        }
        protected string? _misc = null;
        public string? misc
        {
            get { return _misc; }
            set { _misc = value; NotifyPropertyChanged(nameof(misc)); }
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
                    if (_user_type == null)
                        return "Debe completar valor.";
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

                case "last_reply_source":
                    return "";

                case "misc":
                    return "";

            }

            return "";
        }
    }
}
