#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_asignatura : SqlOrganize.Sql.Data
    {

        public override string entityName => "asignatura";

        public override void Default()
        {
            EntityValues val = db!.Values("asignatura");
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
        protected string? _formacion = null;
        public string? formacion
        {
            get { return _formacion; }
            set { _formacion = value; NotifyPropertyChanged(nameof(formacion)); }
        }
        protected string? _clasificacion = null;
        public string? clasificacion
        {
            get { return _clasificacion; }
            set { _clasificacion = value; NotifyPropertyChanged(nameof(clasificacion)); }
        }
        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set { _codigo = value; NotifyPropertyChanged(nameof(codigo)); }
        }
        protected string? _perfil = null;
        public string? perfil
        {
            get { return _perfil; }
            set { _perfil = value; NotifyPropertyChanged(nameof(perfil)); }
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
                    if (!db.IsNoE() && !_nombre.IsNoE()) {
                        var row = db.Sql("asignatura").Equal("$nombre", _nombre).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "formacion":
                    return "";

                case "clasificacion":
                    return "";

                case "codigo":
                    return "";

                case "perfil":
                    return "";

            }

            return "";
        }
    }
}
