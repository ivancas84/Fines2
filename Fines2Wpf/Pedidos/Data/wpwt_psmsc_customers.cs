#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_psmsc_customers : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_customers ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_customers(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (long?)ContainerApp.db.Values("wpwt_psmsc_customers").Default("id").Get("id");
                    _ticket_count = (int?)ContainerApp.db.Values("wpwt_psmsc_customers").Default("ticket_count").Get("ticket_count");
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
        protected int? _ticket_count = null;
        public int? ticket_count
        {
            get { return _ticket_count; }
            set { _ticket_count = value; NotifyPropertyChanged(); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(); }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { _email = value; NotifyPropertyChanged(); }
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
                    if (_user == null)
                        return "Debe completar valor.";
                    return "";

                case "ticket_count":
                    if (_ticket_count == null)
                        return "Debe completar valor.";
                    return "";

                case "name":
                    if (_name == null)
                        return "Debe completar valor.";
                    return "";

                case "email":
                    if (_email == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
