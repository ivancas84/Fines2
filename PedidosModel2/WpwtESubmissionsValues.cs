#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtESubmissionsValues : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_e_submissions_values";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_e_submissions_values");
            _id = (ulong?)val.GetDefault("id");
            _submission_id = (ulong?)val.GetDefault("submission_id");
        }


        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected ulong? _submission_id = null;
        public ulong? submission_id
        {
            get { return _submission_id; }
            set { _submission_id = value; NotifyPropertyChanged(nameof(submission_id)); }
        }
        protected string? _key = null;
        public string? key
        {
            get { return _key; }
            set { _key = value; NotifyPropertyChanged(nameof(key)); }
        }
        protected string? _value = null;
        public string? value
        {
            get { return _value; }
            set { _value = value; NotifyPropertyChanged(nameof(value)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "submission_id":
                    if (_submission_id == null)
                        return "Debe completar valor.";
                    return "";

                case "key":
                    return "";

                case "value":
                    return "";

            }

            return "";
        }
    }
}
