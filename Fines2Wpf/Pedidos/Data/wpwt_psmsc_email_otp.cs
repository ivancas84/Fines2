#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_psmsc_email_otp : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_email_otp ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_email_otp(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (int?)ContainerApp.db.Values("wpwt_psmsc_email_otp").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _email = null;
        public string? email
        {
            get { return _email; }
            set { _email = value; NotifyPropertyChanged(); }
        }
        protected int? _otp = null;
        public int? otp
        {
            get { return _otp; }
            set { _otp = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_expiry = null;
        public DateTime? date_expiry
        {
            get { return _date_expiry; }
            set { _date_expiry = value; NotifyPropertyChanged(); }
        }
        protected string? _data = null;
        public string? data
        {
            get { return _data; }
            set { _data = value; NotifyPropertyChanged(); }
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
