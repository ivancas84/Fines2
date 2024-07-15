#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_attachments_r : Data_wpwt_psmsc_attachments
    {

        public Data_wpwt_psmsc_attachments_r () : base()
        {
        }

        public Data_wpwt_psmsc_attachments_r (Db db) : base(db)
        {
        }

        public void DefaultRel(params string[] fieldIds)
        {
            EntityValues val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "ticket_id":
                        val = db!.Values("wpwt_psmsc_tickets");
                        ticket_id__id = (long?)val.GetDefault("id");
                        ticket_id__is_active = (bool?)val.GetDefault("is_active");
                        ticket_id__customer = (long?)val.GetDefault("customer");
                        ticket_id__status = (long?)val.GetDefault("status");
                        ticket_id__priority = (long?)val.GetDefault("priority");
                        ticket_id__category = (long?)val.GetDefault("category");
                        ticket_id__date_created = (DateTime?)val.GetDefault("date_created");
                        ticket_id__date_updated = (DateTime?)val.GetDefault("date_updated");
                        ticket_id__user_type = (string?)val.GetDefault("user_type");
                        ticket_id__last_reply_by = (long?)val.GetDefault("last_reply_by");
                        ticket_id__auth_code = (string?)val.GetDefault("auth_code");
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

        public string? ticket_id__Label { get; set; }

        protected long? _ticket_id__id = null;

        [JsonProperty("ticket_id-id")]
        public long? ticket_id__id
        {
            get { return _ticket_id__id; }
            set { _ticket_id__id = value; ticket_id = value; NotifyPropertyChanged(nameof(ticket_id__id)); }
        }
        protected bool? _ticket_id__is_active = null;

        [JsonProperty("ticket_id-is_active")]
        public bool? ticket_id__is_active
        {
            get { return _ticket_id__is_active; }
            set { _ticket_id__is_active = value; NotifyPropertyChanged(nameof(ticket_id__is_active)); }
        }
        protected long? _ticket_id__customer = null;

        [JsonProperty("ticket_id-customer")]
        public long? ticket_id__customer
        {
            get { return _ticket_id__customer; }
            set { _ticket_id__customer = value; NotifyPropertyChanged(nameof(ticket_id__customer)); }
        }
        protected string? _ticket_id__subject = null;

        [JsonProperty("ticket_id-subject")]
        public string? ticket_id__subject
        {
            get { return _ticket_id__subject; }
            set { _ticket_id__subject = value; NotifyPropertyChanged(nameof(ticket_id__subject)); }
        }
        protected long? _ticket_id__status = null;

        [JsonProperty("ticket_id-status")]
        public long? ticket_id__status
        {
            get { return _ticket_id__status; }
            set { _ticket_id__status = value; NotifyPropertyChanged(nameof(ticket_id__status)); }
        }
        protected long? _ticket_id__priority = null;

        [JsonProperty("ticket_id-priority")]
        public long? ticket_id__priority
        {
            get { return _ticket_id__priority; }
            set { _ticket_id__priority = value; NotifyPropertyChanged(nameof(ticket_id__priority)); }
        }
        protected long? _ticket_id__category = null;

        [JsonProperty("ticket_id-category")]
        public long? ticket_id__category
        {
            get { return _ticket_id__category; }
            set { _ticket_id__category = value; NotifyPropertyChanged(nameof(ticket_id__category)); }
        }
        protected string? _ticket_id__assigned_agent = null;

        [JsonProperty("ticket_id-assigned_agent")]
        public string? ticket_id__assigned_agent
        {
            get { return _ticket_id__assigned_agent; }
            set { _ticket_id__assigned_agent = value; NotifyPropertyChanged(nameof(ticket_id__assigned_agent)); }
        }
        protected DateTime? _ticket_id__date_created = null;

        [JsonProperty("ticket_id-date_created")]
        public DateTime? ticket_id__date_created
        {
            get { return _ticket_id__date_created; }
            set { _ticket_id__date_created = value; NotifyPropertyChanged(nameof(ticket_id__date_created)); }
        }
        protected DateTime? _ticket_id__date_updated = null;

        [JsonProperty("ticket_id-date_updated")]
        public DateTime? ticket_id__date_updated
        {
            get { return _ticket_id__date_updated; }
            set { _ticket_id__date_updated = value; NotifyPropertyChanged(nameof(ticket_id__date_updated)); }
        }
        protected int? _ticket_id__agent_created = null;

        [JsonProperty("ticket_id-agent_created")]
        public int? ticket_id__agent_created
        {
            get { return _ticket_id__agent_created; }
            set { _ticket_id__agent_created = value; NotifyPropertyChanged(nameof(ticket_id__agent_created)); }
        }
        protected string? _ticket_id__ip_address = null;

        [JsonProperty("ticket_id-ip_address")]
        public string? ticket_id__ip_address
        {
            get { return _ticket_id__ip_address; }
            set { _ticket_id__ip_address = value; NotifyPropertyChanged(nameof(ticket_id__ip_address)); }
        }
        protected string? _ticket_id__source = null;

        [JsonProperty("ticket_id-source")]
        public string? ticket_id__source
        {
            get { return _ticket_id__source; }
            set { _ticket_id__source = value; NotifyPropertyChanged(nameof(ticket_id__source)); }
        }
        protected string? _ticket_id__browser = null;

        [JsonProperty("ticket_id-browser")]
        public string? ticket_id__browser
        {
            get { return _ticket_id__browser; }
            set { _ticket_id__browser = value; NotifyPropertyChanged(nameof(ticket_id__browser)); }
        }
        protected string? _ticket_id__os = null;

        [JsonProperty("ticket_id-os")]
        public string? ticket_id__os
        {
            get { return _ticket_id__os; }
            set { _ticket_id__os = value; NotifyPropertyChanged(nameof(ticket_id__os)); }
        }
        protected string? _ticket_id__add_recipients = null;

        [JsonProperty("ticket_id-add_recipients")]
        public string? ticket_id__add_recipients
        {
            get { return _ticket_id__add_recipients; }
            set { _ticket_id__add_recipients = value; NotifyPropertyChanged(nameof(ticket_id__add_recipients)); }
        }
        protected string? _ticket_id__prev_assignee = null;

        [JsonProperty("ticket_id-prev_assignee")]
        public string? ticket_id__prev_assignee
        {
            get { return _ticket_id__prev_assignee; }
            set { _ticket_id__prev_assignee = value; NotifyPropertyChanged(nameof(ticket_id__prev_assignee)); }
        }
        protected DateTime? _ticket_id__date_closed = null;

        [JsonProperty("ticket_id-date_closed")]
        public DateTime? ticket_id__date_closed
        {
            get { return _ticket_id__date_closed; }
            set { _ticket_id__date_closed = value; NotifyPropertyChanged(nameof(ticket_id__date_closed)); }
        }
        protected string? _ticket_id__user_type = null;

        [JsonProperty("ticket_id-user_type")]
        public string? ticket_id__user_type
        {
            get { return _ticket_id__user_type; }
            set { _ticket_id__user_type = value; NotifyPropertyChanged(nameof(ticket_id__user_type)); }
        }
        protected DateTime? _ticket_id__last_reply_on = null;

        [JsonProperty("ticket_id-last_reply_on")]
        public DateTime? ticket_id__last_reply_on
        {
            get { return _ticket_id__last_reply_on; }
            set { _ticket_id__last_reply_on = value; NotifyPropertyChanged(nameof(ticket_id__last_reply_on)); }
        }
        protected long? _ticket_id__last_reply_by = null;

        [JsonProperty("ticket_id-last_reply_by")]
        public long? ticket_id__last_reply_by
        {
            get { return _ticket_id__last_reply_by; }
            set { _ticket_id__last_reply_by = value; NotifyPropertyChanged(nameof(ticket_id__last_reply_by)); }
        }
        protected string? _ticket_id__auth_code = null;

        [JsonProperty("ticket_id-auth_code")]
        public string? ticket_id__auth_code
        {
            get { return _ticket_id__auth_code; }
            set { _ticket_id__auth_code = value; NotifyPropertyChanged(nameof(ticket_id__auth_code)); }
        }
        protected string? _ticket_id__cust_24 = null;

        [JsonProperty("ticket_id-cust_24")]
        public string? ticket_id__cust_24
        {
            get { return _ticket_id__cust_24; }
            set { _ticket_id__cust_24 = value; NotifyPropertyChanged(nameof(ticket_id__cust_24)); }
        }
        protected string? _ticket_id__cust_25 = null;

        [JsonProperty("ticket_id-cust_25")]
        public string? ticket_id__cust_25
        {
            get { return _ticket_id__cust_25; }
            set { _ticket_id__cust_25 = value; NotifyPropertyChanged(nameof(ticket_id__cust_25)); }
        }
        protected string? _ticket_id__cust_26 = null;

        [JsonProperty("ticket_id-cust_26")]
        public string? ticket_id__cust_26
        {
            get { return _ticket_id__cust_26; }
            set { _ticket_id__cust_26 = value; NotifyPropertyChanged(nameof(ticket_id__cust_26)); }
        }
        protected string? _ticket_id__cust_27 = null;

        [JsonProperty("ticket_id-cust_27")]
        public string? ticket_id__cust_27
        {
            get { return _ticket_id__cust_27; }
            set { _ticket_id__cust_27 = value; NotifyPropertyChanged(nameof(ticket_id__cust_27)); }
        }
        protected string? _ticket_id__cust_28 = null;

        [JsonProperty("ticket_id-cust_28")]
        public string? ticket_id__cust_28
        {
            get { return _ticket_id__cust_28; }
            set { _ticket_id__cust_28 = value; NotifyPropertyChanged(nameof(ticket_id__cust_28)); }
        }
        protected string? _ticket_id__tags = null;

        [JsonProperty("ticket_id-tags")]
        public string? ticket_id__tags
        {
            get { return _ticket_id__tags; }
            set { _ticket_id__tags = value; NotifyPropertyChanged(nameof(ticket_id__tags)); }
        }
        protected string? _ticket_id__live_agents = null;

        [JsonProperty("ticket_id-live_agents")]
        public string? ticket_id__live_agents
        {
            get { return _ticket_id__live_agents; }
            set { _ticket_id__live_agents = value; NotifyPropertyChanged(nameof(ticket_id__live_agents)); }
        }
        protected string? _ticket_id__last_reply_source = null;

        [JsonProperty("ticket_id-last_reply_source")]
        public string? ticket_id__last_reply_source
        {
            get { return _ticket_id__last_reply_source; }
            set { _ticket_id__last_reply_source = value; NotifyPropertyChanged(nameof(ticket_id__last_reply_source)); }
        }
        protected string? _ticket_id__misc = null;

        [JsonProperty("ticket_id-misc")]
        public string? ticket_id__misc
        {
            get { return _ticket_id__misc; }
            set { _ticket_id__misc = value; NotifyPropertyChanged(nameof(ticket_id__misc)); }
        }

        public string? customer__Label { get; set; }

        protected long? _customer__id = null;

        [JsonProperty("customer-id")]
        public long? customer__id
        {
            get { return _customer__id; }
            set { _customer__id = value; ticket_id__customer = value; NotifyPropertyChanged(nameof(customer__id)); }
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
            set { _status__id = value; ticket_id__status = value; NotifyPropertyChanged(nameof(status__id)); }
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
            set { _priority__id = value; ticket_id__priority = value; NotifyPropertyChanged(nameof(priority__id)); }
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
            set { _category__id = value; ticket_id__category = value; NotifyPropertyChanged(nameof(category__id)); }
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
