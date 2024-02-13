#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_calendario : SqlOrganize.Data
    {

        public Data_calendario ()
        {
            Initialize();
        }

        public Data_calendario(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("calendario").Default("id").Get("id");
                    _anio = (short?)ContainerApp.db.Values("calendario").Default("anio").Get("anio");
                    _semestre = (short?)ContainerApp.db.Values("calendario").Default("semestre").Get("semestre");
                    _insertado = (DateTime?)ContainerApp.db.Values("calendario").Default("insertado").Get("insertado");
                break;
            }
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _inicio = null;
        public DateTime? inicio
        {
            get { return _inicio; }
            set { _inicio = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _fin = null;
        public DateTime? fin
        {
            get { return _fin; }
            set { _fin = value; NotifyPropertyChanged(); }
        }
        protected short? _anio = null;
        public short? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(); }
        }
        protected short? _semestre = null;
        public short? semestre
        {
            get { return _semestre; }
            set { _semestre = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(); }
        }
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { _descripcion = value; NotifyPropertyChanged(); }
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
