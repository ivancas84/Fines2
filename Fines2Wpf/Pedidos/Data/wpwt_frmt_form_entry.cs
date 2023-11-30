#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_frmt_form_entry : SqlOrganize.Data
    {

        public Data_wpwt_frmt_form_entry ()
        {
            Initialize();
        }

        public Data_wpwt_frmt_form_entry(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _is_spam = (bool?)ContainerApp.db.Values("wpwt_frmt_form_entry").Default("is_spam").Get("is_spam");
                    _date_created = (DateTime?)ContainerApp.db.Values("wpwt_frmt_form_entry").Default("date_created").Get("date_created");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _entry_id = null;
        public ulong? entry_id
        {
            get { return _entry_id; }
            set { _entry_id = value; NotifyPropertyChanged(); }
        }
        protected string? _entry_type = null;
        public string? entry_type
        {
            get { return _entry_type; }
            set { _entry_type = value; NotifyPropertyChanged(); }
        }
        protected string? _draft_id = null;
        public string? draft_id
        {
            get { return _draft_id; }
            set { _draft_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _form_id = null;
        public ulong? form_id
        {
            get { return _form_id; }
            set { _form_id = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_spam = null;
        public bool? is_spam
        {
            get { return _is_spam; }
            set { _is_spam = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(); }
        }
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "entry_id":
                    if (_entry_id == null)
                        return "Debe completar valor.";
                    return "";

                case "entry_type":
                    if (_entry_type == null)
                        return "Debe completar valor.";
                    return "";

                case "draft_id":
                    return "";

                case "form_id":
                    if (_form_id == null)
                        return "Debe completar valor.";
                    return "";

                case "is_spam":
                    if (_is_spam == null)
                        return "Debe completar valor.";
                    return "";

                case "date_created":
                    if (_date_created == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
