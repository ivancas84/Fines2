#nullable enable
using SqlOrganize;
using System;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_attachments_r : Data_wpwt_psmsc_attachments
    {

        public Data_wpwt_psmsc_attachments_r () : base()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_attachments_r (DataInitMode mode = DataInitMode.Default) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
                    ticket_id__id = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("id").Get("id");
                    ticket_id__is_active = (bool?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("is_active").Get("is_active");
                    ticket_id__customer = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("customer").Get("customer");
                    ticket_id__status = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("status").Get("status");
                    ticket_id__priority = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("priority").Get("priority");
                    ticket_id__category = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("category").Get("category");
                    ticket_id__date_created = (DateTime?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("date_created").Get("date_created");
                    ticket_id__date_updated = (DateTime?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("date_updated").Get("date_updated");
                    ticket_id__user_type = (string?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("user_type").Get("user_type");
                    ticket_id__last_reply_by = (long?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("last_reply_by").Get("last_reply_by");
                    ticket_id__auth_code = (string?)ContainerApp.db.Values("wpwt_psmsc_tickets").Default("auth_code").Get("auth_code");
                    customer__id = (long?)ContainerApp.db.Values("wpwt_psmsc_customers").Default("id").Get("id");
                    customer__ticket_count = (int?)ContainerApp.db.Values("wpwt_psmsc_customers").Default("ticket_count").Get("ticket_count");
                    status__id = (long?)ContainerApp.db.Values("wpwt_psmsc_statuses").Default("id").Get("id");
                    status__load_order = (int?)ContainerApp.db.Values("wpwt_psmsc_statuses").Default("load_order").Get("load_order");
                    priority__id = (long?)ContainerApp.db.Values("wpwt_psmsc_priorities").Default("id").Get("id");
                    priority__load_order = (int?)ContainerApp.db.Values("wpwt_psmsc_priorities").Default("load_order").Get("load_order");
                    category__id = (long?)ContainerApp.db.Values("wpwt_psmsc_categories").Default("id").Get("id");
                    category__load_order = (int?)ContainerApp.db.Values("wpwt_psmsc_categories").Default("load_order").Get("load_order");
                break;
            }
        }

        public string? ticket_id__Label { get; set; }

        protected long? _ticket_id__id = null;
        public long? ticket_id__id
        {
            get { return _ticket_id__id; }
            set { _ticket_id__id = value; _ticket_id = value; NotifyPropertyChanged(); }
        }
        protected bool? _ticket_id__is_active = null;
        public bool? ticket_id__is_active
        {
            get { return _ticket_id__is_active; }
            set { _ticket_id__is_active = value; NotifyPropertyChanged(); }
        }
        protected long? _ticket_id__customer = null;
        public long? ticket_id__customer
        {
            get { return _ticket_id__customer; }
            set { _ticket_id__customer = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__subject = null;
        public string? ticket_id__subject
        {
            get { return _ticket_id__subject; }
            set { _ticket_id__subject = value; NotifyPropertyChanged(); }
        }
        protected long? _ticket_id__status = null;
        public long? ticket_id__status
        {
            get { return _ticket_id__status; }
            set { _ticket_id__status = value; NotifyPropertyChanged(); }
        }
        protected long? _ticket_id__priority = null;
        public long? ticket_id__priority
        {
            get { return _ticket_id__priority; }
            set { _ticket_id__priority = value; NotifyPropertyChanged(); }
        }
        protected long? _ticket_id__category = null;
        public long? ticket_id__category
        {
            get { return _ticket_id__category; }
            set { _ticket_id__category = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__assigned_agent = null;
        public string? ticket_id__assigned_agent
        {
            get { return _ticket_id__assigned_agent; }
            set { _ticket_id__assigned_agent = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _ticket_id__date_created = null;
        public DateTime? ticket_id__date_created
        {
            get { return _ticket_id__date_created; }
            set { _ticket_id__date_created = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _ticket_id__date_updated = null;
        public DateTime? ticket_id__date_updated
        {
            get { return _ticket_id__date_updated; }
            set { _ticket_id__date_updated = value; NotifyPropertyChanged(); }
        }
        protected int? _ticket_id__agent_created = null;
        public int? ticket_id__agent_created
        {
            get { return _ticket_id__agent_created; }
            set { _ticket_id__agent_created = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__ip_address = null;
        public string? ticket_id__ip_address
        {
            get { return _ticket_id__ip_address; }
            set { _ticket_id__ip_address = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__source = null;
        public string? ticket_id__source
        {
            get { return _ticket_id__source; }
            set { _ticket_id__source = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__browser = null;
        public string? ticket_id__browser
        {
            get { return _ticket_id__browser; }
            set { _ticket_id__browser = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__os = null;
        public string? ticket_id__os
        {
            get { return _ticket_id__os; }
            set { _ticket_id__os = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__add_recipients = null;
        public string? ticket_id__add_recipients
        {
            get { return _ticket_id__add_recipients; }
            set { _ticket_id__add_recipients = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__prev_assignee = null;
        public string? ticket_id__prev_assignee
        {
            get { return _ticket_id__prev_assignee; }
            set { _ticket_id__prev_assignee = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _ticket_id__date_closed = null;
        public DateTime? ticket_id__date_closed
        {
            get { return _ticket_id__date_closed; }
            set { _ticket_id__date_closed = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__user_type = null;
        public string? ticket_id__user_type
        {
            get { return _ticket_id__user_type; }
            set { _ticket_id__user_type = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _ticket_id__last_reply_on = null;
        public DateTime? ticket_id__last_reply_on
        {
            get { return _ticket_id__last_reply_on; }
            set { _ticket_id__last_reply_on = value; NotifyPropertyChanged(); }
        }
        protected long? _ticket_id__last_reply_by = null;
        public long? ticket_id__last_reply_by
        {
            get { return _ticket_id__last_reply_by; }
            set { _ticket_id__last_reply_by = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__auth_code = null;
        public string? ticket_id__auth_code
        {
            get { return _ticket_id__auth_code; }
            set { _ticket_id__auth_code = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__cust_24 = null;
        public string? ticket_id__cust_24
        {
            get { return _ticket_id__cust_24; }
            set { _ticket_id__cust_24 = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__cust_25 = null;
        public string? ticket_id__cust_25
        {
            get { return _ticket_id__cust_25; }
            set { _ticket_id__cust_25 = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__cust_26 = null;
        public string? ticket_id__cust_26
        {
            get { return _ticket_id__cust_26; }
            set { _ticket_id__cust_26 = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__cust_27 = null;
        public string? ticket_id__cust_27
        {
            get { return _ticket_id__cust_27; }
            set { _ticket_id__cust_27 = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__cust_28 = null;
        public string? ticket_id__cust_28
        {
            get { return _ticket_id__cust_28; }
            set { _ticket_id__cust_28 = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__tags = null;
        public string? ticket_id__tags
        {
            get { return _ticket_id__tags; }
            set { _ticket_id__tags = value; NotifyPropertyChanged(); }
        }
        protected string? _ticket_id__live_agents = null;
        public string? ticket_id__live_agents
        {
            get { return _ticket_id__live_agents; }
            set { _ticket_id__live_agents = value; NotifyPropertyChanged(); }
        }

        public string? customer__Label { get; set; }

        protected long? _customer__id = null;
        public long? customer__id
        {
            get { return _customer__id; }
            set { _customer__id = value; _ticket_id__customer = value; NotifyPropertyChanged(); }
        }
        protected long? _customer__user = null;
        public long? customer__user
        {
            get { return _customer__user; }
            set { _customer__user = value; NotifyPropertyChanged(); }
        }
        protected int? _customer__ticket_count = null;
        public int? customer__ticket_count
        {
            get { return _customer__ticket_count; }
            set { _customer__ticket_count = value; NotifyPropertyChanged(); }
        }
        protected string? _customer__name = null;
        public string? customer__name
        {
            get { return _customer__name; }
            set { _customer__name = value; NotifyPropertyChanged(); }
        }
        protected string? _customer__email = null;
        public string? customer__email
        {
            get { return _customer__email; }
            set { _customer__email = value; NotifyPropertyChanged(); }
        }

        public string? status__Label { get; set; }

        protected long? _status__id = null;
        public long? status__id
        {
            get { return _status__id; }
            set { _status__id = value; _ticket_id__status = value; NotifyPropertyChanged(); }
        }
        protected string? _status__name = null;
        public string? status__name
        {
            get { return _status__name; }
            set { _status__name = value; NotifyPropertyChanged(); }
        }
        protected string? _status__color = null;
        public string? status__color
        {
            get { return _status__color; }
            set { _status__color = value; NotifyPropertyChanged(); }
        }
        protected string? _status__bg_color = null;
        public string? status__bg_color
        {
            get { return _status__bg_color; }
            set { _status__bg_color = value; NotifyPropertyChanged(); }
        }
        protected int? _status__load_order = null;
        public int? status__load_order
        {
            get { return _status__load_order; }
            set { _status__load_order = value; NotifyPropertyChanged(); }
        }

        public string? priority__Label { get; set; }

        protected long? _priority__id = null;
        public long? priority__id
        {
            get { return _priority__id; }
            set { _priority__id = value; _ticket_id__priority = value; NotifyPropertyChanged(); }
        }
        protected string? _priority__name = null;
        public string? priority__name
        {
            get { return _priority__name; }
            set { _priority__name = value; NotifyPropertyChanged(); }
        }
        protected string? _priority__color = null;
        public string? priority__color
        {
            get { return _priority__color; }
            set { _priority__color = value; NotifyPropertyChanged(); }
        }
        protected string? _priority__bg_color = null;
        public string? priority__bg_color
        {
            get { return _priority__bg_color; }
            set { _priority__bg_color = value; NotifyPropertyChanged(); }
        }
        protected int? _priority__load_order = null;
        public int? priority__load_order
        {
            get { return _priority__load_order; }
            set { _priority__load_order = value; NotifyPropertyChanged(); }
        }

        public string? category__Label { get; set; }

        protected long? _category__id = null;
        public long? category__id
        {
            get { return _category__id; }
            set { _category__id = value; _ticket_id__category = value; NotifyPropertyChanged(); }
        }
        protected string? _category__name = null;
        public string? category__name
        {
            get { return _category__name; }
            set { _category__name = value; NotifyPropertyChanged(); }
        }
        protected int? _category__load_order = null;
        public int? category__load_order
        {
            get { return _category__load_order; }
            set { _category__load_order = value; NotifyPropertyChanged(); }
        }
    }
}
