#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_tickets_r : Data_wpwt_psmsc_tickets
    {

        public Data_wpwt_psmsc_tickets_r () : base()
        {
        }

        public Data_wpwt_psmsc_tickets_r (Db db) : base(db)
        {
        }

        public void DefaultRel(params string[] fieldIds)
        {
            EntityValues val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
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

        public string? customer__Label { get; set; }

        protected long? _customer__id = null;

        [JsonProperty("customer-id")]
        public long? customer__id
        {
            get { return _customer__id; }
            set { _customer__id = value; customer = value; NotifyPropertyChanged(nameof(customer__id)); }
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
            set { _status__id = value; status = value; NotifyPropertyChanged(nameof(status__id)); }
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
            set { _priority__id = value; priority = value; NotifyPropertyChanged(nameof(priority__id)); }
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
            set { _category__id = value; category = value; NotifyPropertyChanged(nameof(category__id)); }
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
