#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_ticket_tags : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_ticket_tags ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_ticket_tags(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (int?)ContainerApp.db.Values("wpwt_psmsc_ticket_tags").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
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
        protected string? _description = null;
        public string? description
        {
            get { return _description; }
            set { _description = value; NotifyPropertyChanged(); }
        }
        protected string? _color = null;
        public string? color
        {
            get { return _color; }
            set { _color = value; NotifyPropertyChanged(); }
        }
        protected string? _bg_color = null;
        public string? bg_color
        {
            get { return _bg_color; }
            set { _bg_color = value; NotifyPropertyChanged(); }
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
