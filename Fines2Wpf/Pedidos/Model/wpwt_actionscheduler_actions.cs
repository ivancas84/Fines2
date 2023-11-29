#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_actionscheduler_actions : SqlOrganize.Data
    {

        public Data_wpwt_actionscheduler_actions ()
        {
            Initialize();
        }

        public Data_wpwt_actionscheduler_actions(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _scheduled_date_gmt = (DateTime?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("scheduled_date_gmt").Get("scheduled_date_gmt");
                    _scheduled_date_local = (DateTime?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("scheduled_date_local").Get("scheduled_date_local");
                    _priority = (byte?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("priority").Get("priority");
                    _group_id = (ulong?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("group_id").Get("group_id");
                    _attempts = (int?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("attempts").Get("attempts");
                    _last_attempt_gmt = (DateTime?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("last_attempt_gmt").Get("last_attempt_gmt");
                    _last_attempt_local = (DateTime?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("last_attempt_local").Get("last_attempt_local");
                    _claim_id = (ulong?)ContainerApp.db.Values("wpwt_actionscheduler_actions").Default("claim_id").Get("claim_id");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _action_id = null;
        public ulong? action_id
        {
            get { return _action_id; }
            set { _action_id = value; NotifyPropertyChanged(); }
        }
        protected string? _hook = null;
        public string? hook
        {
            get { return _hook; }
            set { _hook = value; NotifyPropertyChanged(); }
        }
        protected string? _status = null;
        public string? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _scheduled_date_gmt = null;
        public DateTime? scheduled_date_gmt
        {
            get { return _scheduled_date_gmt; }
            set { _scheduled_date_gmt = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _scheduled_date_local = null;
        public DateTime? scheduled_date_local
        {
            get { return _scheduled_date_local; }
            set { _scheduled_date_local = value; NotifyPropertyChanged(); }
        }
        protected byte? _priority = null;
        public byte? priority
        {
            get { return _priority; }
            set { _priority = value; NotifyPropertyChanged(); }
        }
        protected string? _args = null;
        public string? args
        {
            get { return _args; }
            set { _args = value; NotifyPropertyChanged(); }
        }
        protected string? _schedule = null;
        public string? schedule
        {
            get { return _schedule; }
            set { _schedule = value; NotifyPropertyChanged(); }
        }
        protected ulong? _group_id = null;
        public ulong? group_id
        {
            get { return _group_id; }
            set { _group_id = value; NotifyPropertyChanged(); }
        }
        protected int? _attempts = null;
        public int? attempts
        {
            get { return _attempts; }
            set { _attempts = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _last_attempt_gmt = null;
        public DateTime? last_attempt_gmt
        {
            get { return _last_attempt_gmt; }
            set { _last_attempt_gmt = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _last_attempt_local = null;
        public DateTime? last_attempt_local
        {
            get { return _last_attempt_local; }
            set { _last_attempt_local = value; NotifyPropertyChanged(); }
        }
        protected ulong? _claim_id = null;
        public ulong? claim_id
        {
            get { return _claim_id; }
            set { _claim_id = value; NotifyPropertyChanged(); }
        }
        protected string? _extended_args = null;
        public string? extended_args
        {
            get { return _extended_args; }
            set { _extended_args = value; NotifyPropertyChanged(); }
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

                case "action_id":
                    if (_action_id == null)
                        return "Debe completar valor.";
                    return "";

                case "hook":
                    if (_hook == null)
                        return "Debe completar valor.";
                    return "";

                case "status":
                    if (_status == null)
                        return "Debe completar valor.";
                    return "";

                case "scheduled_date_gmt":
                    return "";

                case "scheduled_date_local":
                    return "";

                case "priority":
                    if (_priority == null)
                        return "Debe completar valor.";
                    return "";

                case "args":
                    return "";

                case "schedule":
                    return "";

                case "group_id":
                    if (_group_id == null)
                        return "Debe completar valor.";
                    return "";

                case "attempts":
                    if (_attempts == null)
                        return "Debe completar valor.";
                    return "";

                case "last_attempt_gmt":
                    return "";

                case "last_attempt_local":
                    return "";

                case "claim_id":
                    if (_claim_id == null)
                        return "Debe completar valor.";
                    return "";

                case "extended_args":
                    return "";

            }

            return "";
        }
    }
}
