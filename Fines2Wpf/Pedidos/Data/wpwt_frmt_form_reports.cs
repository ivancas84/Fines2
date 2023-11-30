#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_frmt_form_reports : SqlOrganize.Data
    {

        public Data_wpwt_frmt_form_reports ()
        {
            Initialize();
        }

        public Data_wpwt_frmt_form_reports(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _date_created = (DateTime?)ContainerApp.db.Values("wpwt_frmt_form_reports").Default("date_created").Get("date_created");
                    _date_updated = (DateTime?)ContainerApp.db.Values("wpwt_frmt_form_reports").Default("date_updated").Get("date_updated");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _report_id = null;
        public ulong? report_id
        {
            get { return _report_id; }
            set { _report_id = value; NotifyPropertyChanged(); }
        }
        protected string? _report_value = null;
        public string? report_value
        {
            get { return _report_value; }
            set { _report_value = value; NotifyPropertyChanged(); }
        }
        protected string? _status = null;
        public string? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(); }
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

                case "report_id":
                    if (_report_id == null)
                        return "Debe completar valor.";
                    return "";

                case "report_value":
                    if (_report_value == null)
                        return "Debe completar valor.";
                    return "";

                case "status":
                    if (_status == null)
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
