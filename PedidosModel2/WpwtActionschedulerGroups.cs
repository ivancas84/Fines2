#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtActionschedulerGroups : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_actionscheduler_groups";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_actionscheduler_groups");
        }


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
