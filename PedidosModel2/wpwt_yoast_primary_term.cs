#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class Data_wpwt_yoast_primary_term : SqlOrganize.Sql.Data
    {

        public Data_wpwt_yoast_primary_term ()
        {
        }

        public Data_wpwt_yoast_primary_term(Db db)
        {
            this.db = db;
        }

        public override void Default()
        {
            EntityValues val = db!.Values("wpwt_yoast_primary_term");
            _id = (uint?)val.GetDefault("id");
            _updated_at = (DateTime?)val.GetDefault("updated_at");
            _blog_id = (long?)val.GetDefault("blog_id");
        }

        public string? Label { get; set; }

        protected uint? _id = null;
        public uint? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected long? _post_id = null;
        public long? post_id
        {
            get { return _post_id; }
            set { _post_id = value; NotifyPropertyChanged(nameof(post_id)); }
        }
        protected long? _term_id = null;
        public long? term_id
        {
            get { return _term_id; }
            set { _term_id = value; NotifyPropertyChanged(nameof(term_id)); }
        }
        protected string? _taxonomy = null;
        public string? taxonomy
        {
            get { return _taxonomy; }
            set { _taxonomy = value; NotifyPropertyChanged(nameof(taxonomy)); }
        }
        protected DateTime? _created_at = null;
        public DateTime? created_at
        {
            get { return _created_at; }
            set { _created_at = value; NotifyPropertyChanged(nameof(created_at)); }
        }
        protected DateTime? _updated_at = null;
        public DateTime? updated_at
        {
            get { return _updated_at; }
            set { _updated_at = value; NotifyPropertyChanged(nameof(updated_at)); }
        }
        protected long? _blog_id = null;
        public long? blog_id
        {
            get { return _blog_id; }
            set { _blog_id = value; NotifyPropertyChanged(nameof(blog_id)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "post_id":
                    return "";

                case "term_id":
                    return "";

                case "taxonomy":
                    if (_taxonomy == null)
                        return "Debe completar valor.";
                    return "";

                case "created_at":
                    return "";

                case "updated_at":
                    if (_updated_at == null)
                        return "Debe completar valor.";
                    return "";

                case "blog_id":
                    if (_blog_id == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
