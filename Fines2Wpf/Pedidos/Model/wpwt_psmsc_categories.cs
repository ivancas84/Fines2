#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_categories : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_categories ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_categories(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (long?)ContainerApp.db.Values("wpwt_psmsc_categories").Default("id").Get("id");
                    _load_order = (int?)ContainerApp.db.Values("wpwt_psmsc_categories").Default("load_order").Get("load_order");
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
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(); }
        }
        protected int? _load_order = null;
        public int? load_order
        {
            get { return _load_order; }
            set { _load_order = value; NotifyPropertyChanged(); }
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
