#nullable enable
using SqlOrganize;
using System;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_psmsc_tickets_r : Data_wpwt_psmsc_tickets
    {

        public Data_wpwt_psmsc_tickets_r () : base()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_tickets_r (DataInitMode mode = DataInitMode.Default) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
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

        public string? customer__Label { get; set; }

        protected long? _customer__id = null;
        public long? customer__id
        {
            get { return _customer__id; }
            set { _customer__id = value; _customer = value; NotifyPropertyChanged(); }
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
            set { _status__id = value; _status = value; NotifyPropertyChanged(); }
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
            set { _priority__id = value; _priority = value; NotifyPropertyChanged(); }
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
            set { _category__id = value; _category = value; NotifyPropertyChanged(); }
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
