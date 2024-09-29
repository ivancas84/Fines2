#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.FinesApp.Data
{
    public partial class TipoSede : Entity
    {

        public TipoSede()
        {
            _entityName = "tipo_sede";
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

        #region descripcion
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { if( _descripcion != value) { _descripcion = value; NotifyPropertyChanged(nameof(descripcion)); } }
        }
        #endregion

        #region Sede_ (ref sede.tipo_sede _m:o tipo_sede.id)
        public ObservableCollection<Sede> Sede_ { get; set; } = new ();
        #endregion

    }
}
