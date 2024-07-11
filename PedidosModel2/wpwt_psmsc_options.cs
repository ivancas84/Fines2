#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_options : SqlOrganize.Sql.Data
    {

        public Data_wpwt_psmsc_options ()
        {
        }

        public Data_wpwt_psmsc_options(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_options");
            _id = (int?)val.GetDefault("id");
            _custom_field = (int?)val.GetDefault("custom_field");
            _load_order = (int?)val.GetDefault("load_order");
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(nameof(name)); }
        }
        protected int? _custom_field = null;
        public int? custom_field
        {
            get { return _custom_field; }
            set { _custom_field = value; NotifyPropertyChanged(nameof(custom_field)); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(nameof(date_created)); }
        }
        protected int? _load_order = null;
        public int? load_order
        {
            get { return _load_order; }
            set { _load_order = value; NotifyPropertyChanged(nameof(load_order)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "name":
                    if (_name == null)
                        return "Debe completar valor.";
                    return "";

                case "custom_field":
                    if (_custom_field == null)
                        return "Debe completar valor.";
                    return "";

                case "date_created":
                    if (_date_created == null)
                        return "Debe completar valor.";
                    return "";

                case "load_order":
                    if (_load_order == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
