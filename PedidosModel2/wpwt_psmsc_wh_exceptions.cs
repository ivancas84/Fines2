#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_wh_exceptions : SqlOrganize.Sql.Data
    {

        public Data_wpwt_psmsc_wh_exceptions ()
        {
        }

        public Data_wpwt_psmsc_wh_exceptions(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_wh_exceptions");
            _id = (int?)val.GetDefault("id");
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected long? _agent = null;
        public long? agent
        {
            get { return _agent; }
            set { _agent = value; NotifyPropertyChanged(nameof(agent)); }
        }
        protected string? _title = null;
        public string? title
        {
            get { return _title; }
            set { _title = value; NotifyPropertyChanged(nameof(title)); }
        }
        protected DateTime? _exception_date = null;
        public DateTime? exception_date
        {
            get { return _exception_date; }
            set { _exception_date = value; NotifyPropertyChanged(nameof(exception_date)); }
        }
        protected string? _start_time = null;
        public string? start_time
        {
            get { return _start_time; }
            set { _start_time = value; NotifyPropertyChanged(nameof(start_time)); }
        }
        protected string? _end_time = null;
        public string? end_time
        {
            get { return _end_time; }
            set { _end_time = value; NotifyPropertyChanged(nameof(end_time)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "agent":
                    if (_agent == null)
                        return "Debe completar valor.";
                    return "";

                case "title":
                    if (_title == null)
                        return "Debe completar valor.";
                    return "";

                case "exception_date":
                    if (_exception_date == null)
                        return "Debe completar valor.";
                    return "";

                case "start_time":
                    if (_start_time == null)
                        return "Debe completar valor.";
                    return "";

                case "end_time":
                    if (_end_time == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
