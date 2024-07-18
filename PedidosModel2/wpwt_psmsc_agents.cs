#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_agents : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_psmsc_agents";

        public Data_wpwt_psmsc_agents ()
        {
        }

        public Data_wpwt_psmsc_agents(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_agents");
            _id = (long?)val.GetDefault("id");
            _user = (long?)val.GetDefault("user");
            _customer = (long?)val.GetDefault("customer");
            _role = (int?)val.GetDefault("role");
            _is_agentgroup = (int?)val.GetDefault("is_agentgroup");
            _is_active = (int?)val.GetDefault("is_active");
        }

        public string? Label { get; set; }

        protected long? _id = null;
        public long? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected long? _user = null;
        public long? user
        {
            get { return _user; }
            set { _user = value; NotifyPropertyChanged(nameof(user)); }
        }
        protected long? _customer = null;
        public long? customer
        {
            get { return _customer; }
            set { _customer = value; NotifyPropertyChanged(nameof(customer)); }
        }
        protected int? _role = null;
        public int? role
        {
            get { return _role; }
            set { _role = value; NotifyPropertyChanged(nameof(role)); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(nameof(name)); }
        }
        protected int? _workload = null;
        public int? workload
        {
            get { return _workload; }
            set { _workload = value; NotifyPropertyChanged(nameof(workload)); }
        }
        protected int? _unresolved_count = null;
        public int? unresolved_count
        {
            get { return _unresolved_count; }
            set { _unresolved_count = value; NotifyPropertyChanged(nameof(unresolved_count)); }
        }
        protected int? _is_agentgroup = null;
        public int? is_agentgroup
        {
            get { return _is_agentgroup; }
            set { _is_agentgroup = value; NotifyPropertyChanged(nameof(is_agentgroup)); }
        }
        protected int? _is_active = null;
        public int? is_active
        {
            get { return _is_active; }
            set { _is_active = value; NotifyPropertyChanged(nameof(is_active)); }
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
