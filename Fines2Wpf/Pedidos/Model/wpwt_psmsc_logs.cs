#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_logs : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_logs ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_logs(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (long?)ContainerApp.db.Values("wpwt_psmsc_logs").Default("id").Get("id");
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
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(); }
        }
        protected long? _ref_id = null;
        public long? ref_id
        {
            get { return _ref_id; }
            set { _ref_id = value; NotifyPropertyChanged(); }
        }
        protected long? _modified_by = null;
        public long? modified_by
        {
            get { return _modified_by; }
            set { _modified_by = value; NotifyPropertyChanged(); }
        }
        protected string? _body = null;
        public string? body
        {
            get { return _body; }
            set { _body = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "type":
                    if (_type == null)
                        return "Debe completar valor.";
                    return "";

                case "ref_id":
                    if (_ref_id == null)
                        return "Debe completar valor.";
                    return "";

                case "modified_by":
                    if (_modified_by == null)
                        return "Debe completar valor.";
                    return "";

                case "body":
                    if (_body == null)
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
