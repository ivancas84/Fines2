#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_actionscheduler_claims : SqlOrganize.Data
    {

        public Data_wpwt_actionscheduler_claims ()
        {
        }

        public Data_wpwt_actionscheduler_claims(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_actionscheduler_claims Default()
        {
            EntityValues val = db!.Values("wpwt_actionscheduler_claims");
            _date_created_gmt = (DateTime?)val.GetDefault("date_created_gmt");
            return this;
        }

        public string? Label { get; set; }

        protected ulong? _claim_id = null;
        public ulong? claim_id
        {
            get { return _claim_id; }
            set { _claim_id = value; NotifyPropertyChanged(nameof(claim_id)); }
        }
        protected DateTime? _date_created_gmt = null;
        public DateTime? date_created_gmt
        {
            get { return _date_created_gmt; }
            set { _date_created_gmt = value; NotifyPropertyChanged(nameof(date_created_gmt)); }
        }
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "claim_id":
                    if (_claim_id == null)
                        return "Debe completar valor.";
                    return "";

                case "date_created_gmt":
                    return "";

            }

            return "";
        }
    }
}
