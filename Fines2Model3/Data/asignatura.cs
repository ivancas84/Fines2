#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Asignatura : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "asignatura";

        public override void Default()
        {
            EntityVal val = db!.Values("asignatura");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { if( _nombre != value) { _nombre = value; NotifyPropertyChanged(nameof(nombre)); } }
        }
        protected string? _formacion = null;
        public string? formacion
        {
            get { return _formacion; }
            set { if( _formacion != value) { _formacion = value; NotifyPropertyChanged(nameof(formacion)); } }
        }
        protected string? _clasificacion = null;
        public string? clasificacion
        {
            get { return _clasificacion; }
            set { if( _clasificacion != value) { _clasificacion = value; NotifyPropertyChanged(nameof(clasificacion)); } }
        }
        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set { if( _codigo != value) { _codigo = value; NotifyPropertyChanged(nameof(codigo)); } }
        }
        protected string? _perfil = null;
        public string? perfil
        {
            get { return _perfil; }
            set { if( _perfil != value) { _perfil = value; NotifyPropertyChanged(nameof(perfil)); } }
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
        //curso.asignatura _m:o asignatura.id
        public ObservableCollection<Curso> Curso_asignatura_ { get; set; } = new ();

        //disposicion.asignatura _m:o asignatura.id
        public ObservableCollection<Disposicion> Disposicion_asignatura_ { get; set; } = new ();

    }
}
