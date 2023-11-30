#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_terms : SqlOrganize.Data
    {

        public Data_wpwt_terms ()
        {
            Initialize();
        }

        public Data_wpwt_terms(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _name = (string?)ContainerApp.db.Values("wpwt_terms").Default("name").Get("name");
                    _slug = (string?)ContainerApp.db.Values("wpwt_terms").Default("slug").Get("slug");
                    _term_group = (long?)ContainerApp.db.Values("wpwt_terms").Default("term_group").Get("term_group");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _term_id = null;
        public ulong? term_id
        {
            get { return _term_id; }
            set { _term_id = value; NotifyPropertyChanged(); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(); }
        }
        protected string? _slug = null;
        public string? slug
        {
            get { return _slug; }
            set { _slug = value; NotifyPropertyChanged(); }
        }
        protected long? _term_group = null;
        public long? term_group
        {
            get { return _term_group; }
            set { _term_group = value; NotifyPropertyChanged(); }
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
