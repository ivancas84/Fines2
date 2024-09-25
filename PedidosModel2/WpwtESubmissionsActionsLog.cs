#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtESubmissionsActionsLog : SqlOrganize.Sql.Entity
    {

        public override string entityName => "wpwt_e_submissions_actions_log";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_e_submissions_actions_log");
            _id = (ulong?)val.GetDefault("id");
        }


        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected ulong? _submission_id = null;
        public ulong? submission_id
        {
            get { return _submission_id; }
            set { _submission_id = value; NotifyPropertyChanged(nameof(submission_id)); }
        }
        protected string? _action_name = null;
        public string? action_name
        {
            get { return _action_name; }
            set { _action_name = value; NotifyPropertyChanged(nameof(action_name)); }
        }
        protected string? _action_label = null;
        public string? action_label
        {
            get { return _action_label; }
            set { _action_label = value; NotifyPropertyChanged(nameof(action_label)); }
        }
        protected string? _status = null;
        public string? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(nameof(status)); }
        }
        protected string? _log = null;
        public string? log
        {
            get { return _log; }
            set { _log = value; NotifyPropertyChanged(nameof(log)); }
        }
        protected DateTime? _created_at_gmt = null;
        public DateTime? created_at_gmt
        {
            get { return _created_at_gmt; }
            set { _created_at_gmt = value; NotifyPropertyChanged(nameof(created_at_gmt)); }
        }
        protected DateTime? _updated_at_gmt = null;
        public DateTime? updated_at_gmt
        {
            get { return _updated_at_gmt; }
            set { _updated_at_gmt = value; NotifyPropertyChanged(nameof(updated_at_gmt)); }
        }
        protected DateTime? _created_at = null;
        public DateTime? created_at
        {
            get { return _created_at; }
            set { _created_at = value; NotifyPropertyChanged(nameof(created_at)); }
        }
        protected DateTime? _updated_at = null;
        public DateTime? updated_at
        {
            get { return _updated_at; }
            set { _updated_at = value; NotifyPropertyChanged(nameof(updated_at)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "submission_id":
                    if (_submission_id == null)
                        return "Debe completar valor.";
                    return "";

                case "action_name":
                    if (_action_name == null)
                        return "Debe completar valor.";
                    return "";

                case "action_label":
                    return "";

                case "status":
                    if (_status == null)
                        return "Debe completar valor.";
                    return "";

                case "log":
                    return "";

                case "created_at_gmt":
                    if (_created_at_gmt == null)
                        return "Debe completar valor.";
                    return "";

                case "updated_at_gmt":
                    if (_updated_at_gmt == null)
                        return "Debe completar valor.";
                    return "";

                case "created_at":
                    if (_created_at == null)
                        return "Debe completar valor.";
                    return "";

                case "updated_at":
                    if (_updated_at == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
