#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class DetallePersona : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "detalle_persona";

        public override void Default()
        {
            EntityVal val = db!.Values("detalle_persona");
            _id = (string?)val.GetDefault("id");
            _creado = (DateTime?)val.GetDefault("creado");
            _fecha = (DateTime?)val.GetDefault("fecha");
        }


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
        //detalle_persona.archivo _o:o file.id
        protected File? _archivo_ = null;
        public File? archivo_
        {
            get { return _archivo_; }
            set { _archivo_ = value; NotifyPropertyChanged(nameof(archivo_)); }
        }

        //detalle_persona.persona _o:o persona.id
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set { _persona_ = value; NotifyPropertyChanged(nameof(persona_)); }
        }

    }
}
