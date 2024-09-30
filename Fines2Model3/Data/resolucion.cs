#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Resolucion : Entity
    {

        public Resolucion()
        {
            _entityName = "resolucion";
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

        #region numero
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
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

        #region tipo
        protected string? _tipo = null;
        public string? tipo
        {
            get { return _tipo; }
            set { if( _tipo != value) { _tipo = value; NotifyPropertyChanged(nameof(tipo)); } }
        }
        #endregion

        #region Alumno_ (ref alumno.resolucion_inscripcion _m:o resolucion.id)
        public ObservableCollection<Alumno> Alumno_ { get; set; } = new ();
        #endregion

    }
}
