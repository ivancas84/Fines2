#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;
using Dapper;
using System.Data;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Cargo : Entity
    {

        public Cargo()
        {
            _entityName = "cargo";
            _db = Context.db;
            Default();
            Designacion_.CollectionChanged += Designacion_CollectionChanged;
        }

        #region CollectionChanged
        private void Designacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Designacion obj in e.NewItems)
                    if(obj.cargo_ != this)
                        obj.cargo_ = this;
        }
        #endregion

        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set {
                if( _id != value)
                {
                    _id = value; NotifyPropertyChanged(nameof(id));
                }
            }
        }
        #endregion

        #region descripcion
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set {
                if( _descripcion != value)
                {
                    _descripcion = value; NotifyPropertyChanged(nameof(descripcion));
                }
            }
        }
        #endregion

        #region Designacion_ (ref designacion.cargo _m:o cargo.id)
        protected ObservableCollection<Designacion> _Designacion_ = new ();
        public ObservableCollection<Designacion> Designacion_
        {
            get { return _Designacion_; }
            set { if( _Designacion_ != value) { _Designacion_ = value; NotifyPropertyChanged(nameof(Designacion_)); } }
        }
        #endregion

        public static IEnumerable<Cargo> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Cargo>(
                sql,
                parameters
            );
        }
    }
}
