#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_frmt_form_views : SqlOrganize.Data
    {

        public Data_wpwt_frmt_form_views ()
        {
            Initialize();
        }

        public Data_wpwt_frmt_form_views(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _count = (uint?)ContainerApp.db.Values("wpwt_frmt_form_views").Default("count").Get("count");
                    _date_created = (DateTime?)ContainerApp.db.Values("wpwt_frmt_form_views").Default("date_created").Get("date_created");
                    _date_updated = (DateTime?)ContainerApp.db.Values("wpwt_frmt_form_views").Default("date_updated").Get("date_updated");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _view_id = null;
        public ulong? view_id
        {
            get { return _view_id; }
            set { _view_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _form_id = null;
        public ulong? form_id
        {
            get { return _form_id; }
            set { _form_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _page_id = null;
        public ulong? page_id
        {
            get { return _page_id; }
            set { _page_id = value; NotifyPropertyChanged(); }
        }
        protected string? _ip = null;
        public string? ip
        {
            get { return _ip; }
            set { _ip = value; NotifyPropertyChanged(); }
        }
        protected uint? _count = null;
        public uint? count
        {
            get { return _count; }
            set { _count = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_updated = null;
        public DateTime? date_updated
        {
            get { return _date_updated; }
            set { _date_updated = value; NotifyPropertyChanged(); }
        }
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
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
