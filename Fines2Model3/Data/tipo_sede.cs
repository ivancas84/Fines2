#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_tipo_sede : SqlOrganize.Data
    {

        public Data_tipo_sede ()
        {
        }

        public Data_tipo_sede(Db db)
        {
            this.db = db;
            Init();
        }

        protected void Init()
        {
            _id = (string?)db!.Values("tipo_sede").GetDefault("id");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { _descripcion = value; NotifyPropertyChanged(nameof(descripcion)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "descripcion":
                    if (_descripcion == null)
                        return "Debe completar valor.";
                    if (db != null && !_descripcion.IsNullOrEmptyOrDbNull()) {
                        var row = db.Sql("tipo_sede").Where("$descripcion = @0").Parameters(_descripcion).Dict();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }

            return "";
        }
    }
}
