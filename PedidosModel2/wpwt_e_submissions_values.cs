#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace PedidosModel2.Data
{
    public class Data_wpwt_e_submissions_values : SqlOrganize.Data
    {

        public Data_wpwt_e_submissions_values ()
        {
        }

        public Data_wpwt_e_submissions_values(Db db)
        {
            this.db = db;
        }

        public Data_wpwt_e_submissions_values Default()
        {
            EntityValues val = db!.Values("wpwt_e_submissions_values");
            _id = (ulong?)val.GetDefault("id");
            _submission_id = (ulong?)val.GetDefault("submission_id");
            return this;
        }

        public string? Label { get; set; }

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
