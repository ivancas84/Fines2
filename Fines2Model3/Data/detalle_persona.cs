#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_detalle_persona : SqlOrganize.Data
    {

        public Data_detalle_persona ()
        {
        }

        public Data_detalle_persona(Db db, bool init = true)
        {
            this.db = db;
            if(init)
                Init();
        }

        protected void Init()
        {
            EntityValues val = db!.Values("detalle_persona");
            _id = (string?)val.GetDefault("id");
            _creado = (DateTime?)val.GetDefault("creado");
            _fecha = (DateTime?)val.GetDefault("fecha");
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
        protected string? _archivo = null;
        public string? archivo
        {
            get { return _archivo; }
            set { _archivo = value; NotifyPropertyChanged(nameof(archivo)); }
        }
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { _creado = value; NotifyPropertyChanged(nameof(creado)); }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(nameof(persona)); }
        }
        protected DateTime? _fecha = null;
        public DateTime? fecha
        {
            get { return _fecha; }
            set { _fecha = value; NotifyPropertyChanged(nameof(fecha)); }
        }
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { _tipo = value; NotifyPropertyChanged(nameof(tipo)); }
        }
        protected string? _asunto = null;
        public string? asunto
        {
            get { return _asunto; }
            set { _asunto = value; NotifyPropertyChanged(nameof(asunto)); }
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
                    return "";

                case "archivo":
                    return "";

                case "creado":
                    if (_creado == null)
                        return "Debe completar valor.";
                    return "";

                case "persona":
                    if (_persona == null)
                        return "Debe completar valor.";
                    return "";

                case "fecha":
                    return "";

                case "tipo":
                    return "";

                case "asunto":
                    return "";

            }

            return "";
        }
    }
}
