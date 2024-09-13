#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.PedidosModel2
{
    public class WpwtBitFmLogs : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "wpwt_bit_fm_logs";

        public override void Default()
        {
            EntityVal val = db!.Values("wpwt_bit_fm_logs");
            _id = (ulong?)val.GetDefault("id");
        }


        protected ulong? _id = null;
        public ulong? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected int? _user_id = null;
        public int? user_id
        {
            get { return _user_id; }
            set { _user_id = value; NotifyPropertyChanged(nameof(user_id)); }
        }
        protected string? _command = null;
        public string? command
        {
            get { return _command; }
            set { _command = value; NotifyPropertyChanged(nameof(command)); }
        }
        protected string? _details = null;
        public string? details
        {
            get { return _details; }
            set { _details = value; NotifyPropertyChanged(nameof(details)); }
        }
        protected DateTime? _created_at = null;
        public DateTime? created_at
        {
            get { return _created_at; }
            set { _created_at = value; NotifyPropertyChanged(nameof(created_at)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "user_id":
                    if (_user_id == null)
                        return "Debe completar valor.";
                    return "";

                case "command":
                    if (_command == null)
                        return "Debe completar valor.";
                    return "";

                case "details":
                    if (_details == null)
                        return "Debe completar valor.";
                    return "";

                case "created_at":
                    if (_created_at == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
