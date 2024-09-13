#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtPsmscLogs : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_psmsc_logs";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_psmsc_logs");
            _id = (long?)val.GetDefault("id");
        }


        protected long? _id = null;
        public long? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(nameof(type)); }
        }
        protected long? _ref_id = null;
        public long? ref_id
        {
            get { return _ref_id; }
            set { _ref_id = value; NotifyPropertyChanged(nameof(ref_id)); }
        }
        protected long? _modified_by = null;
        public long? modified_by
        {
            get { return _modified_by; }
            set { _modified_by = value; NotifyPropertyChanged(nameof(modified_by)); }
        }
        protected string? _body = null;
        public string? body
        {
            get { return _body; }
            set { _body = value; NotifyPropertyChanged(nameof(body)); }
        }
        protected DateTime? _date_created = null;
        public DateTime? date_created
        {
            get { return _date_created; }
            set { _date_created = value; NotifyPropertyChanged(nameof(date_created)); }
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
