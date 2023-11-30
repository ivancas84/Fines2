#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_e_submissions_actions_log : SqlOrganize.Data
    {

        public Data_wpwt_e_submissions_actions_log ()
        {
            Initialize();
        }

        public Data_wpwt_e_submissions_actions_log(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (ulong?)ContainerApp.db.Values("wpwt_e_submissions_actions_log").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _submission_id = null;
        public ulong? submission_id
        {
            get { return _submission_id; }
            set { _submission_id = value; NotifyPropertyChanged(); }
        }
        protected string? _action_name = null;
        public string? action_name
        {
            get { return _action_name; }
            set { _action_name = value; NotifyPropertyChanged(); }
        }
        protected string? _action_label = null;
        public string? action_label
        {
            get { return _action_label; }
            set { _action_label = value; NotifyPropertyChanged(); }
        }
        protected string? _status = null;
        public string? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(); }
        }
        protected string? _log = null;
        public string? log
        {
            get { return _log; }
            set { _log = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _created_at_gmt = null;
        public DateTime? created_at_gmt
        {
            get { return _created_at_gmt; }
            set { _created_at_gmt = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _updated_at_gmt = null;
        public DateTime? updated_at_gmt
        {
            get { return _updated_at_gmt; }
            set { _updated_at_gmt = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _created_at = null;
        public DateTime? created_at
        {
            get { return _created_at; }
            set { _created_at = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _updated_at = null;
        public DateTime? updated_at
        {
            get { return _updated_at; }
            set { _updated_at = value; NotifyPropertyChanged(); }
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
