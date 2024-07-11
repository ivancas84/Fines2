#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_psmsc_email_otp : SqlOrganize.Sql.Data
    {

        public Data_wpwt_psmsc_email_otp ()
        {
        }

        public Data_wpwt_psmsc_email_otp(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_psmsc_email_otp");
            _id = (int?)val.GetDefault("id");
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { _email = value; NotifyPropertyChanged(nameof(email)); }
        }
        protected int? _otp = null;
        public int? otp
        {
            get { return _otp; }
            set { _otp = value; NotifyPropertyChanged(nameof(otp)); }
        }
        protected DateTime? _date_expiry = null;
        public DateTime? date_expiry
        {
            get { return _date_expiry; }
            set { _date_expiry = value; NotifyPropertyChanged(nameof(date_expiry)); }
        }
        protected string? _data = null;
        public string? data
        {
            get { return _data; }
            set { _data = value; NotifyPropertyChanged(nameof(data)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "email":
                    if (_email == null)
                        return "Debe completar valor.";
                    return "";

                case "otp":
                    if (_otp == null)
                        return "Debe completar valor.";
                    return "";

                case "date_expiry":
                    if (_date_expiry == null)
                        return "Debe completar valor.";
                    return "";

                case "data":
                    return "";

            }

            return "";
        }
    }
}
