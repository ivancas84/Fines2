#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_actionscheduler_claims : SqlOrganize.Data
    {

        public Data_wpwt_actionscheduler_claims ()
        {
            Initialize();
        }

        public Data_wpwt_actionscheduler_claims(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _date_created_gmt = (DateTime?)ContainerApp.db.Values("wpwt_actionscheduler_claims").Default("date_created_gmt").Get("date_created_gmt");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _claim_id = null;
        public ulong? claim_id
        {
            get { return _claim_id; }
            set { _claim_id = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _date_created_gmt = null;
        public DateTime? date_created_gmt
        {
            get { return _date_created_gmt; }
            set { _date_created_gmt = value; NotifyPropertyChanged(); }
        }
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
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
