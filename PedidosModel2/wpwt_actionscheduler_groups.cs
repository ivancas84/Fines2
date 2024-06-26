#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_actionscheduler_groups : SqlOrganize.Data
    {

        public Data_wpwt_actionscheduler_groups ()
        {
        }

        public Data_wpwt_actionscheduler_groups(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_actionscheduler_groups Default()
        {
            EntityValues val = db!.Values("wpwt_actionscheduler_groups");
            return this;
        }

        public string? Label { get; set; }

        protected ulong? _group_id = null;
        public ulong? group_id
        {
            get { return _group_id; }
            set { _group_id = value; NotifyPropertyChanged(nameof(group_id)); }
        }
        protected string? _slug = null;
        public string? slug
        {
            get { return _slug; }
            set { _slug = value; NotifyPropertyChanged(nameof(slug)); }
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
