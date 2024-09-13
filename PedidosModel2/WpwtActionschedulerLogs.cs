#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtActionschedulerLogs : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_actionscheduler_logs";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_actionscheduler_logs");
            _log_date_gmt = (DateTime?)val.GetDefault("log_date_gmt");
            _log_date_local = (DateTime?)val.GetDefault("log_date_local");
        }


        protected ulong? _log_id = null;
        public ulong? log_id
        {
            get { return _log_id; }
            set { _log_id = value; NotifyPropertyChanged(nameof(log_id)); }
        }
        protected ulong? _action_id = null;
        public ulong? action_id
        {
            get { return _action_id; }
            set { _action_id = value; NotifyPropertyChanged(nameof(action_id)); }
        }
        protected string? _message = null;
        public string? message
        {
            get { return _message; }
            set { _message = value; NotifyPropertyChanged(nameof(message)); }
        }
        protected DateTime? _log_date_gmt = null;
        public DateTime? log_date_gmt
        {
            get { return _log_date_gmt; }
            set { _log_date_gmt = value; NotifyPropertyChanged(nameof(log_date_gmt)); }
        }
        protected DateTime? _log_date_local = null;
        public DateTime? log_date_local
        {
            get { return _log_date_local; }
            set { _log_date_local = value; NotifyPropertyChanged(nameof(log_date_local)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "log_id":
                    if (_log_id == null)
                        return "Debe completar valor.";
                    return "";

                case "action_id":
                    if (_action_id == null)
                        return "Debe completar valor.";
                    return "";

                case "message":
                    if (_message == null)
                        return "Debe completar valor.";
                    return "";

                case "log_date_gmt":
                    return "";

                case "log_date_local":
                    return "";

            }

            return "";
        }
    }
}
