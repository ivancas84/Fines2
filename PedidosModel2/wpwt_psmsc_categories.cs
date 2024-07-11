#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_categories : SqlOrganize.Sql.Data
    {

        public Data_wpwt_psmsc_categories ()
        {
        }

        public Data_wpwt_psmsc_categories(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_categories");
            _id = (long?)val.GetDefault("id");
            _load_order = (int?)val.GetDefault("load_order");
        }

        public string? Label { get; set; }

        protected long? _id = null;
        public long? id
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

                case "load_order":
                    if (_load_order == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
