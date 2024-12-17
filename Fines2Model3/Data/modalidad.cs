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
    public partial class Modalidad : Entity
    {

        public Modalidad()
        {
            _entityName = "modalidad";
            _db = Context.db;
            Default();
            Comision_.CollectionChanged += Comision_CollectionChanged;
        }

        #region CollectionChanged
        private void Comision_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Comision obj in e.NewItems)
                    if(obj.modalidad_ != this)
                        obj.modalidad_ = this;
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

        #region nombre
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set {
                if( _nombre != value)
                {
                    _nombre = value; NotifyPropertyChanged(nameof(nombre));
                }
            }
        }
        #endregion

        #region pfid
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set {
                if( _pfid != value)
                {
                    _pfid = value; NotifyPropertyChanged(nameof(pfid));
                }
            }
        }
        #endregion

        #region Comision_ (ref comision.modalidad _m:o modalidad.id)
        protected ObservableCollection<Comision> _Comision_ = new ();
        public ObservableCollection<Comision> Comision_
        {
            get { return _Comision_; }
            set { if( _Comision_ != value) { _Comision_ = value; NotifyPropertyChanged(nameof(Comision_)); } }
        }
        #endregion

        public static IEnumerable<Modalidad> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Modalidad>(
                sql,
                parameters
            );
        }

    }
}
