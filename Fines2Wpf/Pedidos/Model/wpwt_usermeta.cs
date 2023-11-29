#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_usermeta : SqlOrganize.Data
    {

        public Data_wpwt_usermeta ()
        {
            Initialize();
        }

        public Data_wpwt_usermeta(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _user_id = (ulong?)ContainerApp.db.Values("wpwt_usermeta").Default("user_id").Get("user_id");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _umeta_id = null;
        public ulong? umeta_id
        {
            get { return _umeta_id; }
            set { _umeta_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _user_id = null;
        public ulong? user_id
        {
            get { return _user_id; }
            set { _user_id = value; NotifyPropertyChanged(); }
        }
        protected string? _meta_key = null;
        public string? meta_key
        {
            get { return _meta_key; }
            set { _meta_key = value; NotifyPropertyChanged(); }
        }
        protected string? _meta_value = null;
        public string? meta_value
        {
            get { return _meta_value; }
            set { _meta_value = value; NotifyPropertyChanged(); }
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
