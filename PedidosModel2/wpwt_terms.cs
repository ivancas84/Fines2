#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_terms : SqlOrganize.Sql.Data
    {

        public override string entityName => "wpwt_terms";

        public Data_wpwt_terms ()
        {
        }

        public Data_wpwt_terms(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_terms");
            _name = (string?)val.GetDefault("name");
            _slug = (string?)val.GetDefault("slug");
            _term_group = (long?)val.GetDefault("term_group");
        }

        public string? Label { get; set; }

        protected ulong? _term_id = null;
        public ulong? term_id
        {
            get { return _term_id; }
            set { _term_id = value; NotifyPropertyChanged(nameof(term_id)); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(nameof(name)); }
        }
        protected string? _slug = null;
        public string? slug
        {
            get { return _slug; }
            set { _slug = value; NotifyPropertyChanged(nameof(slug)); }
        }
        protected long? _term_group = null;
        public long? term_group
        {
            get { return _term_group; }
            set { _term_group = value; NotifyPropertyChanged(nameof(term_group)); }
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

                case "term_id":
                    if (_term_id == null)
                        return "Debe completar valor.";
                    return "";

                case "name":
                    if (_name == null)
                        return "Debe completar valor.";
                    return "";

                case "slug":
                    if (_slug == null)
                        return "Debe completar valor.";
                    return "";

                case "term_group":
                    if (_term_group == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
