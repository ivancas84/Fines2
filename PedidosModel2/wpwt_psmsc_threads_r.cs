#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_threads_r : Data_wpwt_psmsc_threads
    {

        public Data_wpwt_psmsc_threads_r () : base()
        {
        }

        public Data_wpwt_psmsc_threads_r (Db db) : base(db)
        {
        }

        public void DefaultRel(params string[] fieldIds)
        {
            EntityValues val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "ticket":
                        val = db!.Values("wpwt_psmsc_tickets");
                        ticket__id = (long?)val.GetDefault("id");
                        ticket__is_active = (bool?)val.GetDefault("is_active");
                        ticket__customer = (long?)val.GetDefault("customer");
                        ticket__status = (long?)val.GetDefault("status");
                        ticket__priority = (long?)val.GetDefault("priority");
                        ticket__category = (long?)val.GetDefault("category");
                        ticket__date_created = (DateTime?)val.GetDefault("date_created");
                        ticket__date_updated = (DateTime?)val.GetDefault("date_updated");
                        ticket__user_type = (string?)val.GetDefault("user_type");
                        ticket__last_reply_by = (long?)val.GetDefault("last_reply_by");
                        ticket__auth_code = (string?)val.GetDefault("auth_code");
                    break;
                    case "customer":
                        val = db!.Values("wpwt_psmsc_customers");
                        customer__id = (long?)val.GetDefault("id");
                        customer__ticket_count = (int?)val.GetDefault("ticket_count");
                    break;
                    case "status":
                        val = db!.Values("wpwt_psmsc_statuses");
                        status__id = (long?)val.GetDefault("id");
                        status__load_order = (int?)val.GetDefault("load_order");
                    break;
                    case "priority":
                        val = db!.Values("wpwt_psmsc_priorities");
                        priority__id = (long?)val.GetDefault("id");
                        priority__load_order = (int?)val.GetDefault("load_order");
                    break;
                    case "category":
                        val = db!.Values("wpwt_psmsc_categories");
                        category__id = (long?)val.GetDefault("id");
                        category__load_order = (int?)val.GetDefault("load_order");
                    break;
                }
            }
        }

        public string? ticket__Label { get; set; }

        protected long? _ticket__id = null;

        [JsonProperty("ticket-id")]
        public long? ticket__id
        {
            get { return _ticket__id; }
            set { _ticket__id = value; ticket = value; NotifyPropertyChanged(nameof(ticket__id)); }
        }
        protected bool? _ticket__is_active = null;

        [JsonProperty("ticket-is_active")]
        public bool? ticket__is_active
        {
            get { return _ticket__is_active; }
            set { _ticket__is_active = value; NotifyPropertyChanged(nameof(ticket__is_active)); }
        }
        protected long? _ticket__customer = null;

        [JsonProperty("ticket-customer")]
        public long? ticket__customer
        {
            get { return _ticket__customer; }
            set { _ticket__customer = value; NotifyPropertyChanged(nameof(ticket__customer)); }
        }
        protected string? _ticket__subject = null;

        [JsonProperty("ticket-subject")]
        public string? ticket__subject
        {
            get { return _ticket__subject; }
            set { _ticket__subject = value; NotifyPropertyChanged(nameof(ticket__subject)); }
        }
        protected long? _ticket__status = null;

        [JsonProperty("ticket-status")]
        public long? ticket__status
        {
            get { return _ticket__status; }
            set { _ticket__status = value; NotifyPropertyChanged(nameof(ticket__status)); }
        }
        protected long? _ticket__priority = null;

        [JsonProperty("ticket-priority")]
        public long? ticket__priority
        {
            get { return _ticket__priority; }
            set { _ticket__priority = value; NotifyPropertyChanged(nameof(ticket__priority)); }
        }
        protected long? _ticket__category = null;

        [JsonProperty("ticket-category")]
        public long? ticket__category
        {
            get { return _ticket__category; }
            set { _ticket__category = value; NotifyPropertyChanged(nameof(ticket__category)); }
        }
        protected string? _ticket__assigned_agent = null;

        [JsonProperty("ticket-assigned_agent")]
        public string? ticket__assigned_agent
        {
            get { return _ticket__assigned_agent; }
            set { _ticket__assigned_agent = value; NotifyPropertyChanged(nameof(ticket__assigned_agent)); }
        }
        protected DateTime? _ticket__date_created = null;

        [JsonProperty("ticket-date_created")]
        public DateTime? ticket__date_created
        {
            get { return _ticket__date_created; }
            set { _ticket__date_created = value; NotifyPropertyChanged(nameof(ticket__date_created)); }
        }
        protected DateTime? _ticket__date_updated = null;

        [JsonProperty("ticket-date_updated")]
        public DateTime? ticket__date_updated
        {
            get { return _ticket__date_updated; }
            set { _ticket__date_updated = value; NotifyPropertyChanged(nameof(ticket__date_updated)); }
        }
        protected int? _ticket__agent_created = null;

        [JsonProperty("ticket-agent_created")]
        public int? ticket__agent_created
        {
            get { return _ticket__agent_created; }
            set { _ticket__agent_created = value; NotifyPropertyChanged(nameof(ticket__agent_created)); }
        }
        protected string? _ticket__ip_address = null;

        [JsonProperty("ticket-ip_address")]
        public string? ticket__ip_address
        {
            get { return _ticket__ip_address; }
            set { _ticket__ip_address = value; NotifyPropertyChanged(nameof(ticket__ip_address)); }
        }
        protected string? _ticket__source = null;

        [JsonProperty("ticket-source")]
        public string? ticket__source
        {
            get { return _ticket__source; }
            set { _ticket__source = value; NotifyPropertyChanged(nameof(ticket__source)); }
        }
        protected string? _ticket__browser = null;

        [JsonProperty("ticket-browser")]
        public string? ticket__browser
        {
            get { return _ticket__browser; }
            set { _ticket__browser = value; NotifyPropertyChanged(nameof(ticket__browser)); }
        }
        protected string? _ticket__os = null;

        [JsonProperty("ticket-os")]
        public string? ticket__os
        {
            get { return _ticket__os; }
            set { _ticket__os = value; NotifyPropertyChanged(nameof(ticket__os)); }
        }
        protected string? _ticket__add_recipients = null;

        [JsonProperty("ticket-add_recipients")]
        public string? ticket__add_recipients
        {
            get { return _ticket__add_recipients; }
            set { _ticket__add_recipients = value; NotifyPropertyChanged(nameof(ticket__add_recipients)); }
        }
        protected string? _ticket__prev_assignee = null;

        [JsonProperty("ticket-prev_assignee")]
        public string? ticket__prev_assignee
        {
            get { return _ticket__prev_assignee; }
            set { _ticket__prev_assignee = value; NotifyPropertyChanged(nameof(ticket__prev_assignee)); }
        }
        protected DateTime? _ticket__date_closed = null;

        [JsonProperty("ticket-date_closed")]
        public DateTime? ticket__date_closed
        {
            get { return _ticket__date_closed; }
            set { _ticket__date_closed = value; NotifyPropertyChanged(nameof(ticket__date_closed)); }
        }
        protected string? _ticket__user_type = null;

        [JsonProperty("ticket-user_type")]
        public string? ticket__user_type
        {
            get { return _ticket__user_type; }
            set { _ticket__user_type = value; NotifyPropertyChanged(nameof(ticket__user_type)); }
        }
        protected DateTime? _ticket__last_reply_on = null;

        [JsonProperty("ticket-last_reply_on")]
        public DateTime? ticket__last_reply_on
        {
            get { return _ticket__last_reply_on; }
            set { _ticket__last_reply_on = value; NotifyPropertyChanged(nameof(ticket__last_reply_on)); }
        }
        protected long? _ticket__last_reply_by = null;

        [JsonProperty("ticket-last_reply_by")]
        public long? ticket__last_reply_by
        {
            get { return _ticket__last_reply_by; }
            set { _ticket__last_reply_by = value; NotifyPropertyChanged(nameof(ticket__last_reply_by)); }
        }
        protected string? _ticket__auth_code = null;

        [JsonProperty("ticket-auth_code")]
        public string? ticket__auth_code
        {
            get { return _ticket__auth_code; }
            set { _ticket__auth_code = value; NotifyPropertyChanged(nameof(ticket__auth_code)); }
        }
        protected string? _ticket__cust_24 = null;

        [JsonProperty("ticket-cust_24")]
        public string? ticket__cust_24
        {
            get { return _ticket__cust_24; }
            set { _ticket__cust_24 = value; NotifyPropertyChanged(nameof(ticket__cust_24)); }
        }
        protected string? _ticket__cust_25 = null;

        [JsonProperty("ticket-cust_25")]
        public string? ticket__cust_25
        {
            get { return _ticket__cust_25; }
            set { _ticket__cust_25 = value; NotifyPropertyChanged(nameof(ticket__cust_25)); }
        }
        protected string? _ticket__cust_26 = null;

        [JsonProperty("ticket-cust_26")]
        public string? ticket__cust_26
        {
            get { return _ticket__cust_26; }
            set { _ticket__cust_26 = value; NotifyPropertyChanged(nameof(ticket__cust_26)); }
        }
        protected string? _ticket__cust_27 = null;

        [JsonProperty("ticket-cust_27")]
        public string? ticket__cust_27
        {
            get { return _ticket__cust_27; }
            set { _ticket__cust_27 = value; NotifyPropertyChanged(nameof(ticket__cust_27)); }
        }
        protected string? _ticket__cust_28 = null;

        [JsonProperty("ticket-cust_28")]
        public string? ticket__cust_28
        {
            get { return _ticket__cust_28; }
            set { _ticket__cust_28 = value; NotifyPropertyChanged(nameof(ticket__cust_28)); }
        }
        protected string? _ticket__tags = null;

        [JsonProperty("ticket-tags")]
        public string? ticket__tags
        {
            get { return _ticket__tags; }
            set { _ticket__tags = value; NotifyPropertyChanged(nameof(ticket__tags)); }
        }
        protected string? _ticket__live_agents = null;

        [JsonProperty("ticket-live_agents")]
        public string? ticket__live_agents
        {
            get { return _ticket__live_agents; }
            set { _ticket__live_agents = value; NotifyPropertyChanged(nameof(ticket__live_agents)); }
        }
        protected string? _ticket__last_reply_source = null;

        [JsonProperty("ticket-last_reply_source")]
        public string? ticket__last_reply_source
        {
            get { return _ticket__last_reply_source; }
            set { _ticket__last_reply_source = value; NotifyPropertyChanged(nameof(ticket__last_reply_source)); }
        }
        protected string? _ticket__misc = null;

        [JsonProperty("ticket-misc")]
        public string? ticket__misc
        {
            get { return _ticket__misc; }
            set { _ticket__misc = value; NotifyPropertyChanged(nameof(ticket__misc)); }
        }

        public string? customer__Label { get; set; }

        protected long? _customer__id = null;

        [JsonProperty("customer-id")]
        public long? customer__id
        {
            get { return _customer__id; }
            set { _customer__id = value; ticket__customer = value; NotifyPropertyChanged(nameof(customer__id)); }
        }
        protected long? _customer__user = null;

        [JsonProperty("customer-user")]
        public long? customer__user
        {
            get { return _customer__user; }
            set { _customer__user = value; NotifyPropertyChanged(nameof(customer__user)); }
        }
        protected int? _customer__ticket_count = null;

        [JsonProperty("customer-ticket_count")]
        public int? customer__ticket_count
        {
            get { return _customer__ticket_count; }
            set { _customer__ticket_count = value; NotifyPropertyChanged(nameof(customer__ticket_count)); }
        }
        protected string? _customer__name = null;

        [JsonProperty("customer-name")]
        public string? customer__name
        {
            get { return _customer__name; }
            set { _customer__name = value; NotifyPropertyChanged(nameof(customer__name)); }
        }
        protected string? _customer__email = null;

        [JsonProperty("customer-email")]
        public string? customer__email
        {
            get { return _customer__email; }
            set { _customer__email = value; NotifyPropertyChanged(nameof(customer__email)); }
        }

        public string? status__Label { get; set; }

        protected long? _status__id = null;

        [JsonProperty("status-id")]
        public long? status__id
        {
            get { return _status__id; }
            set { _status__id = value; ticket__status = value; NotifyPropertyChanged(nameof(status__id)); }
        }
        protected string? _status__name = null;

        [JsonProperty("status-name")]
        public string? status__name
        {
            get { return _status__name; }
            set { _status__name = value; NotifyPropertyChanged(nameof(status__name)); }
        }
        protected string? _status__color = null;

        [JsonProperty("status-color")]
        public string? status__color
        {
            get { return _status__color; }
            set { _status__color = value; NotifyPropertyChanged(nameof(status__color)); }
        }
        protected string? _status__bg_color = null;

        [JsonProperty("status-bg_color")]
        public string? status__bg_color
        {
            get { return _status__bg_color; }
            set { _status__bg_color = value; NotifyPropertyChanged(nameof(status__bg_color)); }
        }
        protected int? _status__load_order = null;

        [JsonProperty("status-load_order")]
        public int? status__load_order
        {
            get { return _status__load_order; }
            set { _status__load_order = value; NotifyPropertyChanged(nameof(status__load_order)); }
        }

        public string? priority__Label { get; set; }

        protected long? _priority__id = null;

        [JsonProperty("priority-id")]
        public long? priority__id
        {
            get { return _priority__id; }
            set { _priority__id = value; ticket__priority = value; NotifyPropertyChanged(nameof(priority__id)); }
        }
        protected string? _priority__name = null;

        [JsonProperty("priority-name")]
        public string? priority__name
        {
            get { return _priority__name; }
            set { _priority__name = value; NotifyPropertyChanged(nameof(priority__name)); }
        }
        protected string? _priority__color = null;

        [JsonProperty("priority-color")]
        public string? priority__color
        {
            get { return _priority__color; }
            set { _priority__color = value; NotifyPropertyChanged(nameof(priority__color)); }
        }
        protected string? _priority__bg_color = null;

        [JsonProperty("priority-bg_color")]
        public string? priority__bg_color
        {
            get { return _priority__bg_color; }
            set { _priority__bg_color = value; NotifyPropertyChanged(nameof(priority__bg_color)); }
        }
        protected int? _priority__load_order = null;

        [JsonProperty("priority-load_order")]
        public int? priority__load_order
        {
            get { return _priority__load_order; }
            set { _priority__load_order = value; NotifyPropertyChanged(nameof(priority__load_order)); }
        }

        public string? category__Label { get; set; }

        protected long? _category__id = null;

        [JsonProperty("category-id")]
        public long? category__id
        {
            get { return _category__id; }
            set { _category__id = value; ticket__category = value; NotifyPropertyChanged(nameof(category__id)); }
        }
        protected string? _category__name = null;

        [JsonProperty("category-name")]
        public string? category__name
        {
            get { return _category__name; }
            set { _category__name = value; NotifyPropertyChanged(nameof(category__name)); }
        }
        protected int? _category__load_order = null;

        [JsonProperty("category-load_order")]
        public int? category__load_order
        {
            get { return _category__load_order; }
            set { _category__load_order = value; NotifyPropertyChanged(nameof(category__load_order)); }
        }
    }
}
