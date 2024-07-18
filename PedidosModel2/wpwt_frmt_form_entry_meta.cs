#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_frmt_form_entry_meta : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_frmt_form_entry_meta";

        public Data_wpwt_frmt_form_entry_meta ()
        {
        }

        public Data_wpwt_frmt_form_entry_meta(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_frmt_form_entry_meta");
            _date_created = (DateTime?)val.GetDefault("date_created");
            _date_updated = (DateTime?)val.GetDefault("date_updated");
        }

        public string? Label { get; set; }

        protected ulong? _meta_id = null;
        public ulong? meta_id
        {
            get { return _meta_id; }
            set { _meta_id = value; NotifyPropertyChanged(nameof(meta_id)); }
        }
        protected ulong? _entry_id = null;
        public ulong? entry_id
        {
            get { return _entry_id; }
            set { _entry_id = value; NotifyPropertyChanged(nameof(entry_id)); }
        }
        protected string? _meta_key = null;
        public string? meta_key
        {
            get { return _meta_key; }
            set { _meta_key = value; NotifyPropertyChanged(nameof(meta_key)); }
        }
        protected string? _meta_value = null;
        public string? meta_value
        {
            get { return _meta_value; }
            set { _meta_value = value; NotifyPropertyChanged(nameof(meta_value)); }
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

                case "meta_id":
                    if (_meta_id == null)
                        return "Debe completar valor.";
                    return "";

                case "entry_id":
                    if (_entry_id == null)
                        return "Debe completar valor.";
                    return "";

                case "meta_key":
                    return "";

                case "meta_value":
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
