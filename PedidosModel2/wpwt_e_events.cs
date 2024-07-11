#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_e_events : SqlOrganize.Sql.Data
    {

        public Data_wpwt_e_events ()
        {
        }

        public Data_wpwt_e_events(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_e_events");
            _id = (ulong?)val.GetDefault("id");
        }

        public string? Label { get; set; }

        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _event_data = null;
        public string? event_data
        {
            get { return _event_data; }
            set { _event_data = value; NotifyPropertyChanged(nameof(event_data)); }
        }
        protected DateTime? _created_at = null;
        public DateTime? created_at
        {
            get { return _created_at; }
            set { _created_at = value; NotifyPropertyChanged(nameof(created_at)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "event_data":
                    return "";

                case "created_at":
                    if (_created_at == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
