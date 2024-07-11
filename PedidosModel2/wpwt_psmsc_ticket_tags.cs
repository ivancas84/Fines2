#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_ticket_tags : SqlOrganize.Sql.Data
    {

        public Data_wpwt_psmsc_ticket_tags ()
        {
        }

        public Data_wpwt_psmsc_ticket_tags(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_ticket_tags");
            _id = (int?)val.GetDefault("id");
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
        protected string? _description = null;
        public string? description
        {
            get { return _description; }
            set { _description = value; NotifyPropertyChanged(nameof(description)); }
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

                case "description":
                    if (_description == null)
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

            }

            return "";
        }
    }
}
