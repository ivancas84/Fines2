#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Calendario : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "calendario";

        public override void Default()
        {
            EntityVal val = db!.Values("calendario");
            _id = (string?)val.GetDefault("id");
            _anio = (short?)val.GetDefault("anio");
            _semestre = (short?)val.GetDefault("semestre");
            _insertado = (DateTime?)val.GetDefault("insertado");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected DateTime? _inicio = null;
        public DateTime? inicio
        {
            get { return _inicio; }
            set { _inicio = value; NotifyPropertyChanged(nameof(inicio)); }
        }
        protected DateTime? _fin = null;
        public DateTime? fin
        {
            get { return _fin; }
            set { _fin = value; NotifyPropertyChanged(nameof(fin)); }
        }
        protected short? _anio = null;
        public short? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(nameof(anio)); }
        }
        protected short? _semestre = null;
        public short? semestre
        {
            get { return _semestre; }
            set { _semestre = value; NotifyPropertyChanged(nameof(semestre)); }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(nameof(insertado)); }
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

                case "inicio":
                    return "";

                case "fin":
                    return "";

                case "anio":
                    return "";

                case "semestre":
                    return "";

                case "insertado":
                    if (_insertado == null)
                        return "Debe completar valor.";
                    return "";

                case "descripcion":
                    return "";

            }

            return "";
        }
    }
}
