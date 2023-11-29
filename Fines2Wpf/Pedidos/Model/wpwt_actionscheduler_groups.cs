#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_actionscheduler_groups : SqlOrganize.Data
    {

        public Data_wpwt_actionscheduler_groups ()
        {
            Initialize();
        }

        public Data_wpwt_actionscheduler_groups(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _group_id = null;
        public ulong? group_id
        {
            get { return _group_id; }
            set { _group_id = value; NotifyPropertyChanged(); }
        }
        protected string? _slug = null;
        public string? slug
        {
            get { return _slug; }
            set { _slug = value; NotifyPropertyChanged(); }
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

                case "group_id":
                    if (_group_id == null)
                        return "Debe completar valor.";
                    return "";

                case "slug":
                    if (_slug == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
