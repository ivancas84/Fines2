#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_e_submissions_values : SqlOrganize.Data
    {

        public Data_wpwt_e_submissions_values ()
        {
            Initialize();
        }

        public Data_wpwt_e_submissions_values(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (ulong?)ContainerApp.db.Values("wpwt_e_submissions_values").Default("id").Get("id");
                    _submission_id = (ulong?)ContainerApp.db.Values("wpwt_e_submissions_values").Default("submission_id").Get("submission_id");
                break;
            }
        }

        public string? Label { get; set; }

        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected ulong? _submission_id = null;
        public ulong? submission_id
        {
            get { return _submission_id; }
            set { _submission_id = value; NotifyPropertyChanged(); }
        }
        protected string? _key = null;
        public string? key
        {
            get { return _key; }
            set { _key = value; NotifyPropertyChanged(); }
        }
        protected string? _value = null;
        public string? value
        {
            get { return _value; }
            set { _value = value; NotifyPropertyChanged(); }
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
