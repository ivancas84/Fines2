#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_agents : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_agents ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_agents(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (long?)ContainerApp.db.Values("wpwt_psmsc_agents").Default("id").Get("id");
                    _user = (long?)ContainerApp.db.Values("wpwt_psmsc_agents").Default("user").Get("user");
                    _customer = (long?)ContainerApp.db.Values("wpwt_psmsc_agents").Default("customer").Get("customer");
                    _role = (int?)ContainerApp.db.Values("wpwt_psmsc_agents").Default("role").Get("role");
                    _is_agentgroup = (int?)ContainerApp.db.Values("wpwt_psmsc_agents").Default("is_agentgroup").Get("is_agentgroup");
                    _is_active = (int?)ContainerApp.db.Values("wpwt_psmsc_agents").Default("is_active").Get("is_active");
                break;
            }
        }

        public string? Label { get; set; }

        protected long? _id = null;
        public long? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected long? _user = null;
        public long? user
        {
            get { return _user; }
            set { _user = value; NotifyPropertyChanged(); }
        }
        protected long? _customer = null;
        public long? customer
        {
            get { return _customer; }
            set { _customer = value; NotifyPropertyChanged(); }
        }
        protected int? _role = null;
        public int? role
        {
            get { return _role; }
            set { _role = value; NotifyPropertyChanged(); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(); }
        }
        protected int? _workload = null;
        public int? workload
        {
            get { return _workload; }
            set { _workload = value; NotifyPropertyChanged(); }
        }
        protected int? _unresolved_count = null;
        public int? unresolved_count
        {
            get { return _unresolved_count; }
            set { _unresolved_count = value; NotifyPropertyChanged(); }
        }
        protected int? _is_agentgroup = null;
        public int? is_agentgroup
        {
            get { return _is_agentgroup; }
            set { _is_agentgroup = value; NotifyPropertyChanged(); }
        }
        protected int? _is_active = null;
        public int? is_active
        {
            get { return _is_active; }
            set { _is_active = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "user":
                    return "";

                case "customer":
                    return "";

                case "role":
                    if (_role == null)
                        return "Debe completar valor.";
                    return "";

                case "name":
                    if (_name == null)
                        return "Debe completar valor.";
                    return "";

                case "workload":
                    return "";

                case "unresolved_count":
                    return "";

                case "is_agentgroup":
                    if (_is_agentgroup == null)
                        return "Debe completar valor.";
                    return "";

                case "is_active":
                    if (_is_active == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
