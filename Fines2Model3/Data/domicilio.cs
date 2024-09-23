#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Domicilio : EntityData
    {

        public Domicilio()
        {
            _entityName = "domicilio";
            _db = Context.db;
            Default();
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _calle = null;
        public string? calle
        {
            get { return _calle; }
            set { if( _calle != value) { _calle = value; NotifyPropertyChanged(nameof(calle)); } }
        }
        protected string? _entre = null;
        public string? entre
        {
            get { return _entre; }
            set { if( _entre != value) { _entre = value; NotifyPropertyChanged(nameof(entre)); } }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
        }
        protected string? _piso = null;
        public string? piso
        {
            get { return _piso; }
            set { if( _piso != value) { _piso = value; NotifyPropertyChanged(nameof(piso)); } }
        }
        protected string? _departamento = null;
        public string? departamento
        {
            get { return _departamento; }
            set { if( _departamento != value) { _departamento = value; NotifyPropertyChanged(nameof(departamento)); } }
        }
        protected string? _barrio = null;
        public string? barrio
        {
            get { return _barrio; }
            set { if( _barrio != value) { _barrio = value; NotifyPropertyChanged(nameof(barrio)); } }
        }
        protected string? _localidad = null;
        public string? localidad
        {
            get { return _localidad; }
            set { if( _localidad != value) { _localidad = value; NotifyPropertyChanged(nameof(localidad)); } }
        }
        //centro_educativo.domicilio _m:o domicilio.id
        public ObservableCollection<CentroEducativo> CentroEducativo_ { get; set; } = new ();

        //persona.domicilio _m:o domicilio.id
        public ObservableCollection<Persona> Persona_ { get; set; } = new ();

        //sede.domicilio _m:o domicilio.id
        public ObservableCollection<Sede> Sede_ { get; set; } = new ();

    }
}
