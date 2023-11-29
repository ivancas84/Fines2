#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_yoast_primary_term : SqlOrganize.Data
    {

        public Data_wpwt_yoast_primary_term ()
        {
            Initialize();
        }

        public Data_wpwt_yoast_primary_term(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (uint?)ContainerApp.db.Values("wpwt_yoast_primary_term").Default("id").Get("id");
                    _updated_at = (DateTime?)ContainerApp.db.Values("wpwt_yoast_primary_term").Default("updated_at").Get("updated_at");
                    _blog_id = (long?)ContainerApp.db.Values("wpwt_yoast_primary_term").Default("blog_id").Get("blog_id");
                break;
            }
        }

        public string? Label { get; set; }

        protected uint? _id = null;
        public uint? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected long? _post_id = null;
        public long? post_id
        {
            get { return _post_id; }
            set { _post_id = value; NotifyPropertyChanged(); }
        }
        protected long? _term_id = null;
        public long? term_id
        {
            get { return _term_id; }
            set { _term_id = value; NotifyPropertyChanged(); }
        }
        protected string? _taxonomy = null;
        public string? taxonomy
        {
            get { return _taxonomy; }
            set { _taxonomy = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _created_at = null;
        public DateTime? created_at
        {
            get { return _created_at; }
            set { _created_at = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _updated_at = null;
        public DateTime? updated_at
        {
            get { return _updated_at; }
            set { _updated_at = value; NotifyPropertyChanged(); }
        }
        protected long? _blog_id = null;
        public long? blog_id
        {
            get { return _blog_id; }
            set { _blog_id = value; NotifyPropertyChanged(); }
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
