#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class CentroEducativo : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "centro_educativo";

        public override void Default()
        {
            EntityVal val = db!.Values("centro_educativo");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { _nombre = value; NotifyPropertyChanged(nameof(nombre)); }
        }
        protected string? _cue = null;
        public string? cue
        {
            get { return _cue; }
            set { _cue = value; NotifyPropertyChanged(nameof(cue)); }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { _domicilio = value; NotifyPropertyChanged(nameof(domicilio)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "nombre":
                    if (_nombre == null)
                        return "Debe completar valor.";
                    return "";

                case "cue":
                    if (!db.IsNoE() && !_cue.IsNoE()) {
                        var row = db.Sql("centro_educativo").Equal("$cue", _cue).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "domicilio":
                    return "";

                case "observaciones":
                    return "";

            }

            return "";
        }
        //centro_educativo.domicilio _o:o domicilio.id
        protected Domicilio? _domicilio_ = null;
        public Domicilio? domicilio_
        {
            get { return _domicilio_; }
            set { _domicilio_ = value; NotifyPropertyChanged(nameof(domicilio_)); }
        }

        //sede.centro_educativo _m:o centro_educativo.id
        protected ObservableCollection<Sede> _Sede_centro_educativo_ { get; set; } = new ();

    }
}
