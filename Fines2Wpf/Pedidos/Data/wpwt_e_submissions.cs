#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_e_submissions : SqlOrganize.Data
    {

        public Data_wpwt_e_submissions ()
        {
            Initialize();
        }

        public Data_wpwt_e_submissions(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (ulong?)ContainerApp.db.Values("wpwt_e_submissions").Default("id").Get("id");
                    _actions_count = (int?)ContainerApp.db.Values("wpwt_e_submissions").Default("actions_count").Get("actions_count");
                    _actions_succeeded_count = (int?)ContainerApp.db.Values("wpwt_e_submissions").Default("actions_succeeded_count").Get("actions_succeeded_count");
                    _is_read = (bool?)ContainerApp.db.Values("wpwt_e_submissions").Default("is_read").Get("is_read");
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
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(); }
        }
        protected string? _hash_id = null;
        public string? hash_id
        {
            get { return _hash_id; }
            set { _hash_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _main_meta_id = null;
        public ulong? main_meta_id
        {
            get { return _main_meta_id; }
            set { _main_meta_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _post_id = null;
        public ulong? post_id
        {
            get { return _post_id; }
            set { _post_id = value; NotifyPropertyChanged(); }
        }
        protected string? _referer = null;
        public string? referer
        {
            get { return _referer; }
            set { _referer = value; NotifyPropertyChanged(); }
        }
        protected string? _referer_title = null;
        public string? referer_title
        {
            get { return _referer_title; }
            set { _referer_title = value; NotifyPropertyChanged(); }
        }
        protected string? _element_id = null;
        public string? element_id
        {
            get { return _element_id; }
            set { _element_id = value; NotifyPropertyChanged(); }
        }
        protected string? _form_name = null;
        public string? form_name
        {
            get { return _form_name; }
            set { _form_name = value; NotifyPropertyChanged(); }
        }
        protected ulong? _campaign_id = null;
        public ulong? campaign_id
        {
            get { return _campaign_id; }
            set { _campaign_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _user_id = null;
        public ulong? user_id
        {
            get { return _user_id; }
            set { _user_id = value; NotifyPropertyChanged(); }
        }
        protected string? _user_ip = null;
        public string? user_ip
        {
            get { return _user_ip; }
            set { _user_ip = value; NotifyPropertyChanged(); }
        }
        protected string? _user_agent = null;
        public string? user_agent
        {
            get { return _user_agent; }
            set { _user_agent = value; NotifyPropertyChanged(); }
        }
        protected int? _actions_count = null;
        public int? actions_count
        {
            get { return _actions_count; }
            set { _actions_count = value; NotifyPropertyChanged(); }
        }
        protected int? _actions_succeeded_count = null;
        public int? actions_succeeded_count
        {
            get { return _actions_succeeded_count; }
            set { _actions_succeeded_count = value; NotifyPropertyChanged(); }
        }
        protected string? _status = null;
        public string? status
        {
            get { return _status; }
            set { _status = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_read = null;
        public bool? is_read
        {
            get { return _is_read; }
            set { _is_read = value; NotifyPropertyChanged(); }
        }
        protected string? _meta = null;
        public string? meta
        {
            get { return _meta; }
            set { _meta = value; NotifyPropertyChanged(); }
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

                case "type":
                    return "";

                case "hash_id":
                    if (_hash_id == null)
                        return "Debe completar valor.";
                    if (!_hash_id.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Sql("wpwt_e_submissions").Where("$hash_id = @0").Parameters(_hash_id).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "main_meta_id":
                    if (_main_meta_id == null)
                        return "Debe completar valor.";
                    return "";

                case "post_id":
                    if (_post_id == null)
                        return "Debe completar valor.";
                    return "";

                case "referer":
                    if (_referer == null)
                        return "Debe completar valor.";
                    return "";

                case "referer_title":
                    return "";

                case "element_id":
                    if (_element_id == null)
                        return "Debe completar valor.";
                    return "";

                case "form_name":
                    if (_form_name == null)
                        return "Debe completar valor.";
                    return "";

                case "campaign_id":
                    if (_campaign_id == null)
                        return "Debe completar valor.";
                    return "";

                case "user_id":
                    return "";

                case "user_ip":
                    if (_user_ip == null)
                        return "Debe completar valor.";
                    return "";

                case "user_agent":
                    if (_user_agent == null)
                        return "Debe completar valor.";
                    return "";

                case "actions_count":
                    return "";

                case "actions_succeeded_count":
                    return "";

                case "status":
                    if (_status == null)
                        return "Debe completar valor.";
                    return "";

                case "is_read":
                    if (_is_read == null)
                        return "Debe completar valor.";
                    return "";

                case "meta":
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
