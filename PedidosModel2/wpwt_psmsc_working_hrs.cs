#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_working_hrs : SqlOrganize.Sql.Data
    {

        public Data_wpwt_psmsc_working_hrs ()
        {
        }

        public Data_wpwt_psmsc_working_hrs(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_working_hrs");
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
        protected byte? _day = null;
        public byte? day
        {
            get { return _day; }
            set { _day = value; NotifyPropertyChanged(nameof(day)); }
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

                case "day":
                    if (_day == null)
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
