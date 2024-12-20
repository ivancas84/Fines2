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
    public partial class Dia : Entity
    {

        public Dia()
        {
            _entityName = "dia";
            _db = Context.db;
            Default();
            Horario_.CollectionChanged += Horario_CollectionChanged;
        }

        #region CollectionChanged
        private void Horario_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Horario obj in e.NewItems)
                    if(obj.dia_ != this)
                        obj.dia_ = this;
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

        #region numero
        protected short? _numero = null;
        public short? numero
        {
            get { return _numero; }
            set {
                if( _numero != value)
                {
                    _numero = value; NotifyPropertyChanged(nameof(numero));
                }
            }
        }
        #endregion

        #region dia
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set {
                if( _dia != value)
                {
                    _dia = value; NotifyPropertyChanged(nameof(dia));
                }
            }
        }
        #endregion

        #region Horario_ (ref horario.dia _m:o dia.id)
        protected ObservableCollection<Horario> _Horario_ = new ();
        public ObservableCollection<Horario> Horario_
        {
            get { return _Horario_; }
            set { if( _Horario_ != value) { _Horario_ = value; NotifyPropertyChanged(nameof(Horario_)); } }
        }
        #endregion

        public static IEnumerable<Dia> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Dia>(
                sql,
                parameters
            );
        }

    }
}
