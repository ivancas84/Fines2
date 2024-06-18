#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_psmsc_customers : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_customers ()
        {
        }

        public Data_wpwt_psmsc_customers(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_psmsc_customers Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_customers");
            _id = (long?)val.GetDefault("id");
            _ticket_count = (int?)val.GetDefault("ticket_count");
            return this;
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
        protected int? _ticket_count = null;
        public int? ticket_count
        {
            get { return _ticket_count; }
            set { _ticket_count = value; NotifyPropertyChanged(nameof(ticket_count)); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(nameof(name)); }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { _email = value; NotifyPropertyChanged(nameof(email)); }
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
