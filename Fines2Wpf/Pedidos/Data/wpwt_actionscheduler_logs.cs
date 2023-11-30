#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_actionscheduler_logs : SqlOrganize.Data
    {

        public Data_wpwt_actionscheduler_logs ()
        {
            Initialize();
        }

        public Data_wpwt_actionscheduler_logs(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _log_date_gmt = (DateTime?)ContainerApp.db.Values("wpwt_actionscheduler_logs").Default("log_date_gmt").Get("log_date_gmt");
                    _log_date_local = (DateTime?)ContainerApp.db.Values("wpwt_actionscheduler_logs").Default("log_date_local").Get("log_date_local");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _log_id = null;
        public ulong? log_id
        {
            get { return _log_id; }
            set { _log_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _action_id = null;
        public ulong? action_id
        {
            get { return _action_id; }
            set { _action_id = value; NotifyPropertyChanged(); }
        }
        protected string? _message = null;
        public string? message
        {
            get { return _message; }
            set { _message = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _log_date_gmt = null;
        public DateTime? log_date_gmt
        {
            get { return _log_date_gmt; }
            set { _log_date_gmt = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _log_date_local = null;
        public DateTime? log_date_local
        {
            get { return _log_date_local; }
            set { _log_date_local = value; NotifyPropertyChanged(); }
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
