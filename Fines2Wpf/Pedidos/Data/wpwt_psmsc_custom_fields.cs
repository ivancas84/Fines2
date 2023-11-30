#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_psmsc_custom_fields : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_custom_fields ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_custom_fields(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (int?)ContainerApp.db.Values("wpwt_psmsc_custom_fields").Default("id").Get("id");
                    _extra_info = (string?)ContainerApp.db.Values("wpwt_psmsc_custom_fields").Default("extra_info").Get("extra_info");
                    _is_personal_info = (int?)ContainerApp.db.Values("wpwt_psmsc_custom_fields").Default("is_personal_info").Get("is_personal_info");
                    _allow_ticket_form = (int?)ContainerApp.db.Values("wpwt_psmsc_custom_fields").Default("allow_ticket_form").Get("allow_ticket_form");
                    _allow_my_profile = (int?)ContainerApp.db.Values("wpwt_psmsc_custom_fields").Default("allow_my_profile").Get("allow_my_profile");
                    _tl_width = (int?)ContainerApp.db.Values("wpwt_psmsc_custom_fields").Default("tl_width").Get("tl_width");
                    _load_order = (int?)ContainerApp.db.Values("wpwt_psmsc_custom_fields").Default("load_order").Get("load_order");
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
        protected string? _extra_info = null;
        public string? extra_info
        {
            get { return _extra_info; }
            set { _extra_info = value; NotifyPropertyChanged(); }
        }
        protected string? _slug = null;
        public string? slug
        {
            get { return _slug; }
            set { _slug = value; NotifyPropertyChanged(); }
        }
        protected string? _field = null;
        public string? field
        {
            get { return _field; }
            set { _field = value; NotifyPropertyChanged(); }
        }
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(); }
        }
        protected string? _default_value = null;
        public string? default_value
        {
            get { return _default_value; }
            set { _default_value = value; NotifyPropertyChanged(); }
        }
        protected string? _placeholder_text = null;
        public string? placeholder_text
        {
            get { return _placeholder_text; }
            set { _placeholder_text = value; NotifyPropertyChanged(); }
        }
        protected int? _char_limit = null;
        public int? char_limit
        {
            get { return _char_limit; }
            set { _char_limit = value; NotifyPropertyChanged(); }
        }
        protected string? _date_display_as = null;
        public string? date_display_as
        {
            get { return _date_display_as; }
            set { _date_display_as = value; NotifyPropertyChanged(); }
        }
        protected string? _date_format = null;
        public string? date_format
        {
            get { return _date_format; }
            set { _date_format = value; NotifyPropertyChanged(); }
        }
        protected string? _date_range = null;
        public string? date_range
        {
            get { return _date_range; }
            set { _date_range = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _start_range = null;
        public DateTime? start_range
        {
            get { return _start_range; }
            set { _start_range = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _end_range = null;
        public DateTime? end_range
        {
            get { return _end_range; }
            set { _end_range = value; NotifyPropertyChanged(); }
        }
        protected int? _time_format = null;
        public int? time_format
        {
            get { return _time_format; }
            set { _time_format = value; NotifyPropertyChanged(); }
        }
        protected int? _is_personal_info = null;
        public int? is_personal_info
        {
            get { return _is_personal_info; }
            set { _is_personal_info = value; NotifyPropertyChanged(); }
        }
        protected int? _is_auto_fill = null;
        public int? is_auto_fill
        {
            get { return _is_auto_fill; }
            set { _is_auto_fill = value; NotifyPropertyChanged(); }
        }
        protected int? _allow_ticket_form = null;
        public int? allow_ticket_form
        {
            get { return _allow_ticket_form; }
            set { _allow_ticket_form = value; NotifyPropertyChanged(); }
        }
        protected int? _allow_my_profile = null;
        public int? allow_my_profile
        {
            get { return _allow_my_profile; }
            set { _allow_my_profile = value; NotifyPropertyChanged(); }
        }
        protected int? _tl_width = null;
        public int? tl_width
        {
            get { return _tl_width; }
            set { _tl_width = value; NotifyPropertyChanged(); }
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

                case "extra_info":
                    if (_extra_info == null)
                        return "Debe completar valor.";
                    return "";

                case "slug":
                    return "";

                case "field":
                    return "";

                case "type":
                    return "";

                case "default_value":
                    return "";

                case "placeholder_text":
                    return "";

                case "char_limit":
                    return "";

                case "date_display_as":
                    return "";

                case "date_format":
                    return "";

                case "date_range":
                    return "";

                case "start_range":
                    return "";

                case "end_range":
                    return "";

                case "time_format":
                    return "";

                case "is_personal_info":
                    if (_is_personal_info == null)
                        return "Debe completar valor.";
                    return "";

                case "is_auto_fill":
                    return "";

                case "allow_ticket_form":
                    return "";

                case "allow_my_profile":
                    return "";

                case "tl_width":
                    if (_tl_width == null)
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
