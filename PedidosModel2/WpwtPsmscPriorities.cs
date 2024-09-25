#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtPsmscPriorities : SqlOrganize.Sql.Entity
    {

        public override string entityName => "wpwt_psmsc_priorities";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_psmsc_priorities");
            _id = (long?)val.GetDefault("id");
            _load_order = (int?)val.GetDefault("load_order");
        }


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
        protected string? _color = null;
        public string? color
        {
            get { return _color; }
            set { _color = value; NotifyPropertyChanged(nameof(color)); }
        }
        protected string? _bg_color = null;
        public string? bg_color
        {
            get { return _bg_color; }
            set { _bg_color = value; NotifyPropertyChanged(nameof(bg_color)); }
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

                case "color":
                    if (_color == null)
                        return "Debe completar valor.";
                    return "";

                case "bg_color":
                    if (_bg_color == null)
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
