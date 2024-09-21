#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Alumno : EntityData
    {

        public Alumno()
        {
            _entityName = "alumno";
            _db = Context.db;
        }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _anio_ingreso = null;
        public string? anio_ingreso
        {
            get { return _anio_ingreso; }
            set { if( _anio_ingreso != value) { _anio_ingreso = value; NotifyPropertyChanged(nameof(anio_ingreso)); } }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { if( _persona != value) { _persona = value; NotifyPropertyChanged(nameof(persona)); } }
        }
        protected string? _estado_inscripcion = null;
        public string? estado_inscripcion
        {
            get { return _estado_inscripcion; }
            set { if( _estado_inscripcion != value) { _estado_inscripcion = value; NotifyPropertyChanged(nameof(estado_inscripcion)); } }
        }
        protected DateTime? _fecha_titulacion = null;
        public DateTime? fecha_titulacion
        {
            get { return _fecha_titulacion; }
            set { if( _fecha_titulacion != value) { _fecha_titulacion = value; NotifyPropertyChanged(nameof(fecha_titulacion)); } }
        }
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { if( _plan != value) { _plan = value; NotifyPropertyChanged(nameof(plan)); } }
        }
        protected string? _resolucion_inscripcion = null;
        public string? resolucion_inscripcion
        {
            get { return _resolucion_inscripcion; }
            set { if( _resolucion_inscripcion != value) { _resolucion_inscripcion = value; NotifyPropertyChanged(nameof(resolucion_inscripcion)); } }
        }
        protected short? _anio_inscripcion = null;
        public short? anio_inscripcion
        {
            get { return _anio_inscripcion; }
            set { if( _anio_inscripcion != value) { _anio_inscripcion = value; NotifyPropertyChanged(nameof(anio_inscripcion)); } }
        }
        protected short? _semestre_inscripcion = null;
        public short? semestre_inscripcion
        {
            get { return _semestre_inscripcion; }
            set { if( _semestre_inscripcion != value) { _semestre_inscripcion = value; NotifyPropertyChanged(nameof(semestre_inscripcion)); } }
        }
        protected short? _semestre_ingreso = null;
        public short? semestre_ingreso
        {
            get { return _semestre_ingreso; }
            set { if( _semestre_ingreso != value) { _semestre_ingreso = value; NotifyPropertyChanged(nameof(semestre_ingreso)); } }
        }
        protected string? _adeuda_legajo = null;
        public string? adeuda_legajo
        {
            get { return _adeuda_legajo; }
            set { if( _adeuda_legajo != value) { _adeuda_legajo = value; NotifyPropertyChanged(nameof(adeuda_legajo)); } }
        }
        protected string? _adeuda_deudores = null;
        public string? adeuda_deudores
        {
            get { return _adeuda_deudores; }
            set { if( _adeuda_deudores != value) { _adeuda_deudores = value; NotifyPropertyChanged(nameof(adeuda_deudores)); } }
        }
        protected string? _documentacion_inscripcion = null;
        public string? documentacion_inscripcion
        {
            get { return _documentacion_inscripcion; }
            set { if( _documentacion_inscripcion != value) { _documentacion_inscripcion = value; NotifyPropertyChanged(nameof(documentacion_inscripcion)); } }
        }
        protected bool? _anio_inscripcion_completo = null;
        public bool? anio_inscripcion_completo
        {
            get { return _anio_inscripcion_completo; }
            set { if( _anio_inscripcion_completo != value) { _anio_inscripcion_completo = value; NotifyPropertyChanged(nameof(anio_inscripcion_completo)); } }
        }
        protected string? _establecimiento_inscripcion = null;
        public string? establecimiento_inscripcion
        {
            get { return _establecimiento_inscripcion; }
            set { if( _establecimiento_inscripcion != value) { _establecimiento_inscripcion = value; NotifyPropertyChanged(nameof(establecimiento_inscripcion)); } }
        }
        protected string? _libro_folio = null;
        public string? libro_folio
        {
            get { return _libro_folio; }
            set { if( _libro_folio != value) { _libro_folio = value; NotifyPropertyChanged(nameof(libro_folio)); } }
        }
        protected string? _libro = null;
        public string? libro
        {
            get { return _libro; }
            set { if( _libro != value) { _libro = value; NotifyPropertyChanged(nameof(libro)); } }
        }
        protected string? _folio = null;
        public string? folio
        {
            get { return _folio; }
            set { if( _folio != value) { _folio = value; NotifyPropertyChanged(nameof(folio)); } }
        }
        protected string? _comentarios = null;
        public string? comentarios
        {
            get { return _comentarios; }
            set { if( _comentarios != value) { _comentarios = value; NotifyPropertyChanged(nameof(comentarios)); } }
        }
        protected bool? _tiene_dni = null;
        public bool? tiene_dni
        {
            get { return _tiene_dni; }
            set { if( _tiene_dni != value) { _tiene_dni = value; NotifyPropertyChanged(nameof(tiene_dni)); } }
        }
        protected bool? _tiene_constancia = null;
        public bool? tiene_constancia
        {
            get { return _tiene_constancia; }
            set { if( _tiene_constancia != value) { _tiene_constancia = value; NotifyPropertyChanged(nameof(tiene_constancia)); } }
        }
        protected bool? _tiene_certificado = null;
        public bool? tiene_certificado
        {
            get { return _tiene_certificado; }
            set { if( _tiene_certificado != value) { _tiene_certificado = value; NotifyPropertyChanged(nameof(tiene_certificado)); } }
        }
        protected bool? _previas_completas = null;
        public bool? previas_completas
        {
            get { return _previas_completas; }
            set { if( _previas_completas != value) { _previas_completas = value; NotifyPropertyChanged(nameof(previas_completas)); } }
        }
        protected bool? _tiene_partida = null;
        public bool? tiene_partida
        {
            get { return _tiene_partida; }
            set { if( _tiene_partida != value) { _tiene_partida = value; NotifyPropertyChanged(nameof(tiene_partida)); } }
        }
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { if( _creado != value) { _creado = value; NotifyPropertyChanged(nameof(creado)); } }
        }
        protected bool? _confirmado_direccion = null;
        public bool? confirmado_direccion
        {
            get { return _confirmado_direccion; }
            set { if( _confirmado_direccion != value) { _confirmado_direccion = value; NotifyPropertyChanged(nameof(confirmado_direccion)); } }
        }
        //alumno.persona _o:o persona.id
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                _persona_ = value;
                persona = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(persona_));
            }
        }

        //alumno.plan _o:o plan.id
        protected Plan? _plan_ = null;
        public Plan? plan_
        {
            get { return _plan_; }
            set {
                _plan_ = value;
                plan = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(plan_));
            }
        }

        //alumno.resolucion_inscripcion _o:o resolucion.id
        protected Resolucion? _resolucion_inscripcion_ = null;
        public Resolucion? resolucion_inscripcion_
        {
            get { return _resolucion_inscripcion_; }
            set {
                _resolucion_inscripcion_ = value;
                resolucion_inscripcion = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(resolucion_inscripcion_));
            }
        }

        //alumno_comision.alumno _m:o alumno.id
        public ObservableCollection<AlumnoComision> AlumnoComision_ { get; set; } = new ();

        //calificacion.alumno _m:o alumno.id
        public ObservableCollection<Calificacion> Calificacion_ { get; set; } = new ();

        //disposicion_pendiente.alumno _m:o alumno.id
        public ObservableCollection<DisposicionPendiente> DisposicionPendiente_ { get; set; } = new ();

    }
}
