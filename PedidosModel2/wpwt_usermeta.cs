#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_usermeta : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_usermeta";

        public Data_wpwt_usermeta ()
        {
        }

        public Data_wpwt_usermeta(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_usermeta");
            _user_id = (ulong?)val.GetDefault("user_id");
        }

        public string? Label { get; set; }

        protected ulong? _umeta_id = null;
        public ulong? umeta_id
        {
            get { return _umeta_id; }
            set { _umeta_id = value; NotifyPropertyChanged(nameof(umeta_id)); }
        }
        protected ulong? _user_id = null;
        public ulong? user_id
        {
            get { return _user_id; }
            set { _user_id = value; NotifyPropertyChanged(nameof(user_id)); }
        }
        protected string? _meta_key = null;
        public string? meta_key
        {
            get { return _meta_key; }
            set { _meta_key = value; NotifyPropertyChanged(nameof(meta_key)); }
        }
        protected string? _meta_value = null;
        public string? meta_value
        {
            get { return _meta_value; }
            set { _meta_value = value; NotifyPropertyChanged(nameof(meta_value)); }
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

                case "umeta_id":
                    if (_umeta_id == null)
                        return "Debe completar valor.";
                    return "";

                case "user_id":
                    if (_user_id == null)
                        return "Debe completar valor.";
                    return "";

                case "meta_key":
                    return "";

                case "meta_value":
                    return "";

            }

            return "";
        }
    }
}
