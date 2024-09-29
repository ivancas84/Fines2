#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.FinesApp.Data
{
    public partial class Calendario : Entity
    {

        public Calendario()
        {
            _entityName = "calendario";
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

        #region inicio
        protected DateTime? _inicio = null;
        public DateTime? inicio
        {
            get { return _inicio; }
            set { if( _inicio != value) { _inicio = value; NotifyPropertyChanged(nameof(inicio)); } }
        }
        #endregion

        #region fin
        protected DateTime? _fin = null;
        public DateTime? fin
        {
            get { return _fin; }
            set { if( _fin != value) { _fin = value; NotifyPropertyChanged(nameof(fin)); } }
        }
        #endregion

        #region anio
        protected short? _anio = null;
        public short? anio
        {
            get { return _anio; }
            set { if( _anio != value) { _anio = value; NotifyPropertyChanged(nameof(anio)); } }
        }
        #endregion

        #region semestre
        protected short? _semestre = null;
        public short? semestre
        {
            get { return _semestre; }
            set { if( _semestre != value) { _semestre = value; NotifyPropertyChanged(nameof(semestre)); } }
        }
        #endregion

        #region insertado
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { if( _insertado != value) { _insertado = value; NotifyPropertyChanged(nameof(insertado)); } }
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

        #region Comision_ (ref comision.calendario _m:o calendario.id)
        public ObservableCollection<Comision> Comision_ { get; set; } = new ();
        #endregion

    }
}
