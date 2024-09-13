#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Sede : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "sede";

        public override void Default()
        {
            EntityVal val = db!.Values("sede");
            _id = (string?)val.GetDefault("id");
            _alta = (DateTime?)val.GetDefault("alta");
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
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { _nombre = value; NotifyPropertyChanged(nameof(nombre)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(nameof(alta)); }
        }
        protected DateTime? _baja = null;
        public DateTime? baja
        {
            get { return _baja; }
            set { _baja = value; NotifyPropertyChanged(nameof(baja)); }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { _domicilio = value; NotifyPropertyChanged(nameof(domicilio)); }
        }
        protected string? _centro_educativo = null;
        public string? centro_educativo
        {
            get { return _centro_educativo; }
            set { _centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo)); }
        }
        protected DateTime? _fecha_traspaso = null;
        public DateTime? fecha_traspaso
        {
            get { return _fecha_traspaso; }
            set { _fecha_traspaso = value; NotifyPropertyChanged(nameof(fecha_traspaso)); }
        }
        protected string? _organizacion = null;
        public string? organizacion
        {
            get { return _organizacion; }
            set { _organizacion = value; NotifyPropertyChanged(nameof(organizacion)); }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(nameof(pfid)); }
        }
        protected string? _pfid_organizacion = null;
        public string? pfid_organizacion
        {
            get { return _pfid_organizacion; }
            set { _pfid_organizacion = value; NotifyPropertyChanged(nameof(pfid_organizacion)); }
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

                case "nombre":
                    if (_nombre == null)
                        return "Debe completar valor.";
                    return "";

                case "observaciones":
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "baja":
                    return "";

                case "domicilio":
                    return "";

                case "centro_educativo":
                    return "";

                case "fecha_traspaso":
                    return "";

                case "organizacion":
                    return "";

                case "pfid":
                    return "";

                case "pfid_organizacion":
                    return "";

            }

            return "";
        }
    }
}
