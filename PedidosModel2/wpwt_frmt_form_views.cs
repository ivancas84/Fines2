#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_frmt_form_views : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_frmt_form_views";

        public Data_wpwt_frmt_form_views ()
        {
        }

        public Data_wpwt_frmt_form_views(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_frmt_form_views");
            _count = (uint?)val.GetDefault("count");
            _date_created = (DateTime?)val.GetDefault("date_created");
            _date_updated = (DateTime?)val.GetDefault("date_updated");
        }

        public string? Label { get; set; }

        protected ulong? _view_id = null;
        public ulong? view_id
        {
            get { return _view_id; }
            set { _view_id = value; NotifyPropertyChanged(nameof(view_id)); }
        }
        protected ulong? _form_id = null;
        public ulong? form_id
        {
            get { return _form_id; }
            set { _form_id = value; NotifyPropertyChanged(nameof(form_id)); }
        }
        protected ulong? _page_id = null;
        public ulong? page_id
        {
            get { return _page_id; }
            set { _page_id = value; NotifyPropertyChanged(nameof(page_id)); }
        }
        protected string? _ip = null;
        public string? ip
        {
            get { return _ip; }
            set { _ip = value; NotifyPropertyChanged(nameof(ip)); }
        }
        protected uint? _count = null;
        public uint? count
        {
            get { return _count; }
            set { _count = value; NotifyPropertyChanged(nameof(count)); }
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
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "view_id":
                    if (_view_id == null)
                        return "Debe completar valor.";
                    return "";

                case "form_id":
                    if (_form_id == null)
                        return "Debe completar valor.";
                    return "";

                case "page_id":
                    if (_page_id == null)
                        return "Debe completar valor.";
                    return "";

                case "ip":
                    return "";

                case "count":
                    if (_count == null)
                        return "Debe completar valor.";
                    return "";

                case "date_created":
                    if (_date_created == null)
                        return "Debe completar valor.";
                    return "";

                case "date_updated":
                    if (_date_updated == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
