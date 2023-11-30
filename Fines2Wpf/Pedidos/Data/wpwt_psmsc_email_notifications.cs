#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_psmsc_email_notifications : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_email_notifications ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_email_notifications(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (int?)ContainerApp.db.Values("wpwt_psmsc_email_notifications").Default("id").Get("id");
                    _attempt = (int?)ContainerApp.db.Values("wpwt_psmsc_email_notifications").Default("attempt").Get("attempt");
                    _priority = (int?)ContainerApp.db.Values("wpwt_psmsc_email_notifications").Default("priority").Get("priority");
                break;
            }
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _from_name = null;
        public string? from_name
        {
            get { return _from_name; }
            set { _from_name = value; NotifyPropertyChanged(); }
        }
        protected string? _from_email = null;
        public string? from_email
        {
            get { return _from_email; }
            set { _from_email = value; NotifyPropertyChanged(); }
        }
        protected string? _reply_to = null;
        public string? reply_to
        {
            get { return _reply_to; }
            set { _reply_to = value; NotifyPropertyChanged(); }
        }
        protected string? _subject = null;
        public string? subject
        {
            get { return _subject; }
            set { _subject = value; NotifyPropertyChanged(); }
        }
        protected string? _body = null;
        public string? body
        {
            get { return _body; }
            set { _body = value; NotifyPropertyChanged(); }
        }
        protected string? _to_email = null;
        public string? to_email
        {
            get { return _to_email; }
            set { _to_email = value; NotifyPropertyChanged(); }
        }
        protected string? _cc_email = null;
        public string? cc_email
        {
            get { return _cc_email; }
            set { _cc_email = value; NotifyPropertyChanged(); }
        }
        protected string? _bcc_email = null;
        public string? bcc_email
        {
            get { return _bcc_email; }
            set { _bcc_email = value; NotifyPropertyChanged(); }
        }
        protected string? _attachments = null;
        public string? attachments
        {
            get { return _attachments; }
            set { _attachments = value; NotifyPropertyChanged(); }
        }
        protected int? _attempt = null;
        public int? attempt
        {
            get { return _attempt; }
            set { _attempt = value; NotifyPropertyChanged(); }
        }
        protected int? _priority = null;
        public int? priority
        {
            get { return _priority; }
            set { _priority = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "from_name":
                    return "";

                case "from_email":
                    return "";

                case "reply_to":
                    return "";

                case "subject":
                    return "";

                case "body":
                    return "";

                case "to_email":
                    return "";

                case "cc_email":
                    return "";

                case "bcc_email":
                    return "";

                case "attachments":
                    return "";

                case "attempt":
                    return "";

                case "priority":
                    if (_priority == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
