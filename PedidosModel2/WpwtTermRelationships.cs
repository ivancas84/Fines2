#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtTermRelationships : SqlOrganize.Sql.Entity
    {

        public override string entityName => "wpwt_term_relationships";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_term_relationships");
            _object_id = (ulong?)val.GetDefault("object_id");
            _term_taxonomy_id = (ulong?)val.GetDefault("term_taxonomy_id");
            _term_order = (int?)val.GetDefault("term_order");
        }


        protected ulong? _object_id = null;
        public ulong? object_id
        {
            get { return _object_id; }
            set { _object_id = value; NotifyPropertyChanged(nameof(object_id)); }
        }
        protected ulong? _term_taxonomy_id = null;
        public ulong? term_taxonomy_id
        {
            get { return _term_taxonomy_id; }
            set { _term_taxonomy_id = value; NotifyPropertyChanged(nameof(term_taxonomy_id)); }
        }
        protected int? _term_order = null;
        public int? term_order
        {
            get { return _term_order; }
            set { _term_order = value; NotifyPropertyChanged(nameof(term_order)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "object_id":
                    if (_object_id == null)
                        return "Debe completar valor.";
                    return "";

                case "term_taxonomy_id":
                    if (_term_taxonomy_id == null)
                        return "Debe completar valor.";
                    return "";

                case "term_order":
                    if (_term_order == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
