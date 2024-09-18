#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Dia : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "dia";

        public override void Default()
        {
            EntityVal val = db!.Values("dia");
            _id = (string?)val.GetDefault("id");
        }


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
                    if (!db.IsNoE() && !_numero.IsNoE()) {
                        var row = db.Sql("dia").Equal("$numero", _numero).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "dia":
                    if (_dia == null)
                        return "Debe completar valor.";
                    if (!db.IsNoE() && !_dia.IsNoE()) {
                        var row = db.Sql("dia").Equal("$dia", _dia).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }

            return "";
        }
        //horario.dia _m:o dia.id
        protected ObservableCollection<Horario> _Horario_dia_ { get; set; } = new ();

    }
}
