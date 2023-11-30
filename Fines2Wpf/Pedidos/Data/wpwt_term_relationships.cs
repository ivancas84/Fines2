#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_term_relationships : SqlOrganize.Data
    {

        public Data_wpwt_term_relationships ()
        {
            Initialize();
        }

        public Data_wpwt_term_relationships(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _object_id = (ulong?)ContainerApp.db.Values("wpwt_term_relationships").Default("object_id").Get("object_id");
                    _term_taxonomy_id = (ulong?)ContainerApp.db.Values("wpwt_term_relationships").Default("term_taxonomy_id").Get("term_taxonomy_id");
                    _term_order = (int?)ContainerApp.db.Values("wpwt_term_relationships").Default("term_order").Get("term_order");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _object_id = null;
        public ulong? object_id
        {
            get { return _object_id; }
            set { _object_id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _term_taxonomy_id = null;
        public ulong? term_taxonomy_id
        {
            get { return _term_taxonomy_id; }
            set { _term_taxonomy_id = value; NotifyPropertyChanged(); }
        }
        protected int? _term_order = null;
        public int? term_order
        {
            get { return _term_order; }
            set { _term_order = value; NotifyPropertyChanged(); }
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
