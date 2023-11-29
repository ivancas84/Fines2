#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_holidays : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_holidays ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_holidays(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (int?)ContainerApp.db.Values("wpwt_psmsc_holidays").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected int? _id = null;
        public int? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected long? _agent = null;
        public long? agent
        {
            get { return _agent; }
            set { _agent = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _holiday = null;
        public DateTime? holiday
        {
            get { return _holiday; }
            set { _holiday = value; NotifyPropertyChanged(); }
        }
        protected byte? _is_recurring = null;
        public byte? is_recurring
        {
            get { return _is_recurring; }
            set { _is_recurring = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "agent":
                    if (_agent == null)
                        return "Debe completar valor.";
                    return "";

                case "holiday":
                    if (_holiday == null)
                        return "Debe completar valor.";
                    return "";

                case "is_recurring":
                    if (_is_recurring == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
