#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_dia : SqlOrganize.Data
    {

        public Data_dia ()
        {
        }

        public Data_dia(Db db, bool init = true)
        {
            this.db = db;
            if(init)
                Init();
        }

        protected void Init()
        {
            EntityValues val = db!.Values("dia");
            _id = (string?)val.GetDefault("id");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected short? _numero = null;
        public short? numero
        {
            get { return _numero; }
            set { _numero = value; NotifyPropertyChanged(nameof(numero)); }
        }
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set { _dia = value; NotifyPropertyChanged(nameof(dia)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "numero":
                    if (_numero == null)
                        return "Debe completar valor.";
                    if (!db.IsNullOrEmpty() && !_numero.IsNullOrEmptyOrDbNull()) {
                        var row = db.Sql("dia").Where("$numero = @0").Parameters(_numero).Cache().Dict();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "dia":
                    if (_dia == null)
                        return "Debe completar valor.";
                    if (!db.IsNullOrEmpty() && !_dia.IsNullOrEmptyOrDbNull()) {
                        var row = db.Sql("dia").Where("$dia = @0").Parameters(_dia).Cache().Dict();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }

            return "";
        }
    }
}
