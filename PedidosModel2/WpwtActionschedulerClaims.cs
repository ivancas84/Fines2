#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtActionschedulerClaims : SqlOrganize.Sql.Entity
    {

        public override string entityName => "wpwt_actionscheduler_claims";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_actionscheduler_claims");
            _date_created_gmt = (DateTime?)val.GetDefault("date_created_gmt");
        }


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
