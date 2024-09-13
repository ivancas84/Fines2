#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtPostmeta : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_postmeta";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_postmeta");
            _post_id = (ulong?)val.GetDefault("post_id");
        }


        protected ulong? _meta_id = null;
        public ulong? meta_id
        {
            get { return _meta_id; }
            set { _meta_id = value; NotifyPropertyChanged(nameof(meta_id)); }
        }
        protected ulong? _post_id = null;
        public ulong? post_id
        {
            get { return _post_id; }
            set { _post_id = value; NotifyPropertyChanged(nameof(post_id)); }
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
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "meta_id":
                    if (_meta_id == null)
                        return "Debe completar valor.";
                    return "";

                case "post_id":
                    if (_post_id == null)
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
