#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class File : Entity
    {

        public File()
        {
            _entityName = "file";
            _db = Context.db;
            Default();
        }

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region name
        protected string? _name = null;
        public string? name
        {
            get { return _name; }
            set { if( _name != value) { _name = value; NotifyPropertyChanged(nameof(name)); } }
        }
        #endregion

        #region type
        protected string? _type = null;
        public string? type
        {
            get { return _type; }
            set { if( _type != value) { _type = value; NotifyPropertyChanged(nameof(type)); } }
        }
        #endregion

        #region content
        protected string? _content = null;
        public string? content
        {
            get { return _content; }
            set { if( _content != value) { _content = value; NotifyPropertyChanged(nameof(content)); } }
        }
        #endregion

        #region size
        protected uint? _size = null;
        public uint? size
        {
            get { return _size; }
            set { if( _size != value) { _size = value; NotifyPropertyChanged(nameof(size)); } }
        }
        #endregion

        #region created
        protected DateTime? _created = null;
        public DateTime? created
        {
            get { return _created; }
            set { if( _created != value) { _created = value; NotifyPropertyChanged(nameof(created)); } }
        }
        #endregion

        #region DetallePersona_archivo_ (ref detalle_persona.archivo _m:o file.id)
        public ObservableCollection<DetallePersona> DetallePersona_archivo_ { get; set; } = new ();
        #endregion

    }
}
