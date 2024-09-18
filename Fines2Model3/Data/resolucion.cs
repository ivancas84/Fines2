#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Resolucion : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "resolucion";

        public override void Default()
        {
            EntityVal val = db!.Values("resolucion");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { _numero = value; NotifyPropertyChanged(nameof(numero)); }
        }
        protected short? _anio = null;
        public short? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(nameof(anio)); }
        }
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { _tipo = value; NotifyPropertyChanged(nameof(tipo)); }
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
                    return "";

                case "anio":
                    return "";

                case "tipo":
                    return "";

            }

            return "";
        }
        //alumno.resolucion_inscripcion _m:o resolucion.id
        protected ObservableCollection<Alumno> _Alumno_resolucion_inscripcion_ { get; set; } = new ();

    }
}
