#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class File : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "file";

        public override void Default()
        {
            EntityVal val = db!.Values("file");
            _id = (string?)val.GetDefault("id");
            _created = (DateTime?)val.GetDefault("created");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { _name = value; NotifyPropertyChanged(nameof(name)); }
        }
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { _type = value; NotifyPropertyChanged(nameof(type)); }
        }
        protected string? _content = null;
        public string? content
        {
            get { return _content; }
            set { _content = value; NotifyPropertyChanged(nameof(content)); }
        }
        protected uint? _size = null;
        public uint? size
        {
            get { return _size; }
            set { _size = value; NotifyPropertyChanged(nameof(size)); }
        }
        protected DateTime? _created = null;
        public DateTime? created
        {
            get { return _created; }
            set { _created = value; NotifyPropertyChanged(nameof(created)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "name":
                    if (_name == null)
                        return "Debe completar valor.";
                    return "";

                case "type":
                    if (_type == null)
                        return "Debe completar valor.";
                    return "";

                case "content":
                    if (_content == null)
                        return "Debe completar valor.";
                    return "";

                case "size":
                    if (_size == null)
                        return "Debe completar valor.";
                    return "";

                case "created":
                    if (_created == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
        //detalle_persona.archivo _m:o file.id
        protected ObservableCollection<DetallePersona> _DetallePersona_archivo_ { get; set; } = new ();

    }
}
