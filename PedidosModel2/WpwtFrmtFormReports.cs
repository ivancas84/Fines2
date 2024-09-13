#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtFrmtFormReports : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_frmt_form_reports";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_frmt_form_reports");
            _date_created = (DateTime?)val.GetDefault("date_created");
            _date_updated = (DateTime?)val.GetDefault("date_updated");
        }


        protected ulong? _report_id = null;
        public ulong? report_id
        {
            get { return _report_id; }
            set { _report_id = value; NotifyPropertyChanged(nameof(report_id)); }
        }
        protected string? _report_value = null;
        public string? report_value
        {
            get { return _report_value; }
            set { _report_value = value; NotifyPropertyChanged(nameof(report_value)); }
        }
        protected string? _status = null;
        public string? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(nameof(status)); }
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
