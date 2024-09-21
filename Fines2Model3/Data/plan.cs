#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Plan : EntityData
    {

        public Plan()
        {
            _entityName = "plan";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _orientacion = null;
        public string? orientacion
        {
            get { return _orientacion; }
            set { if( _orientacion != value) { _orientacion = value; NotifyPropertyChanged(nameof(orientacion)); } }
        }
        protected string? _resolucion = null;
        public string? resolucion
        {
            get { return _resolucion; }
            set { if( _resolucion != value) { _resolucion = value; NotifyPropertyChanged(nameof(resolucion)); } }
        }
        protected string? _distribucion_horaria = null;
        public string? distribucion_horaria
        {
            get { return _distribucion_horaria; }
            set { if( _distribucion_horaria != value) { _distribucion_horaria = value; NotifyPropertyChanged(nameof(distribucion_horaria)); } }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        //alumno.plan _m:o plan.id
        public ObservableCollection<Alumno> Alumno_ { get; set; } = new ();

        //planificacion.plan _m:o plan.id
        public ObservableCollection<Planificacion> Planificacion_ { get; set; } = new ();

    }
}
