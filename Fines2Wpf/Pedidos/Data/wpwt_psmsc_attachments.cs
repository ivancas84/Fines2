#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_psmsc_attachments : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_attachments ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_attachments(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (long?)ContainerApp.db.Values("wpwt_psmsc_attachments").Default("id").Get("id");
                    _is_image = (int?)ContainerApp.db.Values("wpwt_psmsc_attachments").Default("is_image").Get("is_image");
                    _is_active = (int?)ContainerApp.db.Values("wpwt_psmsc_attachments").Default("is_active").Get("is_active");
                    _is_uploaded = (int?)ContainerApp.db.Values("wpwt_psmsc_attachments").Default("is_uploaded").Get("is_uploaded");
                    _source_id = (long?)ContainerApp.db.Values("wpwt_psmsc_attachments").Default("source_id").Get("source_id");
                    _ticket_id = (long?)ContainerApp.db.Values("wpwt_psmsc_attachments").Default("ticket_id").Get("ticket_id");
                    _customer_id = (long?)ContainerApp.db.Values("wpwt_psmsc_attachments").Default("customer_id").Get("customer_id");
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
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(); }
        }
        protected string? _file_path = null;
        public string? file_path
        {
            get { return _file_path; }
            set { _file_path = value; NotifyPropertyChanged(); }
        }
        protected int? _is_image = null;
        public int? is_image
        {
            get { return _is_image; }
            set { _is_image = value; NotifyPropertyChanged(); }
        }
        protected int? _is_active = null;
        public int? is_active
        {
            get { return _is_active; }
            set { _is_active = value; NotifyPropertyChanged(); }
        }
        protected int? _is_uploaded = null;
        public int? is_uploaded
        {
            get { return _is_uploaded; }
            set { _is_uploaded = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(); }
        }
        protected string? _source = null;
        public string? source
        {
            get { return _source; }
            set { _source = value; NotifyPropertyChanged(); }
        }
        protected long? _source_id = null;
        public long? source_id
        {
            get { return _source_id; }
            set { _source_id = value; NotifyPropertyChanged(); }
        }
        protected long? _ticket_id = null;
        public long? ticket_id
        {
            get { return _ticket_id; }
            set { _ticket_id = value; NotifyPropertyChanged(); }
        }
        protected long? _customer_id = null;
        public long? customer_id
        {
            get { return _customer_id; }
            set { _customer_id = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "name":
                    if (_name == null)
                        return "Debe completar valor.";
                    return "";

                case "file_path":
                    if (_file_path == null)
                        return "Debe completar valor.";
                    return "";

                case "is_image":
                    if (_is_image == null)
                        return "Debe completar valor.";
                    return "";

                case "is_active":
                    if (_is_active == null)
                        return "Debe completar valor.";
                    return "";

                case "is_uploaded":
                    if (_is_uploaded == null)
                        return "Debe completar valor.";
                    return "";

                case "date_created":
                    if (_date_created == null)
                        return "Debe completar valor.";
                    return "";

                case "source":
                    if (_source == null)
                        return "Debe completar valor.";
                    return "";

                case "source_id":
                    if (_source_id == null)
                        return "Debe completar valor.";
                    return "";

                case "ticket_id":
                    if (_ticket_id == null)
                        return "Debe completar valor.";
                    return "";

                case "customer_id":
                    if (_customer_id == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
