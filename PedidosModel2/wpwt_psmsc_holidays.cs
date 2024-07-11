#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_holidays : SqlOrganize.Sql.Data
    {

        public Data_wpwt_psmsc_holidays ()
        {
        }

        public Data_wpwt_psmsc_holidays(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_holidays");
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
        protected DateTime? _holiday = null;
        public DateTime? holiday
        {
            get { return _holiday; }
            set { _holiday = value; NotifyPropertyChanged(nameof(holiday)); }
        }
        protected byte? _is_recurring = null;
        public byte? is_recurring
        {
            get { return _is_recurring; }
            set { _is_recurring = value; NotifyPropertyChanged(nameof(is_recurring)); }
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

                case "holiday":
                    if (_holiday == null)
                        return "Debe completar valor.";
                    return "";

                case "is_recurring":
                    if (_is_recurring == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
