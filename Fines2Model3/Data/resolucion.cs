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
    public partial class Resolucion : Entity
    {

        public Resolucion()
        {
            _entityName = "resolucion";
            _db = Context.db;
            Default();
            Alumno_.CollectionChanged += Alumno_CollectionChanged;
        }

        #region CollectionChanged
        private void Alumno_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Alumno obj in e.NewItems)
                    if(obj.resolucion_inscripcion_ != this)
                        obj.resolucion_inscripcion_ = this;
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
        protected string? _numero = null;
        public string? numero
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

        #region anio
        protected short? _anio = null;
        public short? anio
        {
            get { return _anio; }
            set {
                if( _anio != value)
                {
                    _anio = value; NotifyPropertyChanged(nameof(anio));
                }
            }
        }
        #endregion

        #region tipo
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set {
                if( _tipo != value)
                {
                    _tipo = value; NotifyPropertyChanged(nameof(tipo));
                }
            }
        }
        #endregion

        #region Alumno_ (ref alumno.resolucion_inscripcion _m:o resolucion.id)
        protected ObservableCollection<Alumno> _Alumno_ = new ();
        public ObservableCollection<Alumno> Alumno_
        {
            get { return _Alumno_; }
            set { if( _Alumno_ != value) { _Alumno_ = value; NotifyPropertyChanged(nameof(Alumno_)); } }
        }
        #endregion

        public static IEnumerable<Resolucion> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Resolucion>(
                sql,
                parameters
            );
        }
    }
}
