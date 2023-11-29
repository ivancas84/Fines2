#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Model
{
    public class Data_wpwt_psmsc_working_hrs : SqlOrganize.Data
    {

        public Data_wpwt_psmsc_working_hrs ()
        {
            Initialize();
        }

        public Data_wpwt_psmsc_working_hrs(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (int?)ContainerApp.db.Values("wpwt_psmsc_working_hrs").Default("id").Get("id");
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
        protected byte? _day = null;
        public byte? day
        {
            get { return _day; }
            set { _day = value; NotifyPropertyChanged(); }
        }
        protected string? _start_time = null;
        public string? start_time
        {
            get { return _start_time; }
            set { _start_time = value; NotifyPropertyChanged(); }
        }
        protected string? _end_time = null;
        public string? end_time
        {
            get { return _end_time; }
            set { _end_time = value; NotifyPropertyChanged(); }
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

                case "day":
                    if (_day == null)
                        return "Debe completar valor.";
                    return "";

                case "start_time":
                    if (_start_time == null)
                        return "Debe completar valor.";
                    return "";

                case "end_time":
                    if (_end_time == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
