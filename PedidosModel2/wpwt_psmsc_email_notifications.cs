#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_psmsc_email_notifications : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_email_notifications ()
        {
        }

        public Data_wpwt_psmsc_email_notifications(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_psmsc_email_notifications Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_email_notifications");
            _id = (int?)val.GetDefault("id");
            _attempt = (int?)val.GetDefault("attempt");
            _priority = (int?)val.GetDefault("priority");
            return this;
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _from_name = null;
        public string? from_name
        {
            get { return _from_name; }
            set { _from_name = value; NotifyPropertyChanged(nameof(from_name)); }
        }
        protected string? _from_email = null;
        public string? from_email
        {
            get { return _from_email; }
            set { _from_email = value; NotifyPropertyChanged(nameof(from_email)); }
        }
        protected string? _reply_to = null;
        public string? reply_to
        {
            get { return _reply_to; }
            set { _reply_to = value; NotifyPropertyChanged(nameof(reply_to)); }
        }
        protected string? _subject = null;
        public string? subject
        {
            get { return _subject; }
            set { _subject = value; NotifyPropertyChanged(nameof(subject)); }
        }
        protected string? _body = null;
        public string? body
        {
            get { return _body; }
            set { _body = value; NotifyPropertyChanged(nameof(body)); }
        }
        protected string? _to_email = null;
        public string? to_email
        {
            get { return _to_email; }
            set { _to_email = value; NotifyPropertyChanged(nameof(to_email)); }
        }
        protected string? _cc_email = null;
        public string? cc_email
        {
            get { return _cc_email; }
            set { _cc_email = value; NotifyPropertyChanged(nameof(cc_email)); }
        }
        protected string? _bcc_email = null;
        public string? bcc_email
        {
            get { return _bcc_email; }
            set { _bcc_email = value; NotifyPropertyChanged(nameof(bcc_email)); }
        }
        protected string? _attachments = null;
        public string? attachments
        {
            get { return _attachments; }
            set { _attachments = value; NotifyPropertyChanged(nameof(attachments)); }
        }
        protected int? _attempt = null;
        public int? attempt
        {
            get { return _attempt; }
            set { _attempt = value; NotifyPropertyChanged(nameof(attempt)); }
        }
        protected int? _priority = null;
        public int? priority
        {
            get { return _priority; }
            set { _priority = value; NotifyPropertyChanged(nameof(priority)); }
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
