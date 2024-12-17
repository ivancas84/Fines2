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
    public partial class Alumno : Entity
    {

        public Alumno()
        {
            _entityName = "alumno";
            _db = Context.db;
            Default();
            AlumnoComision_.CollectionChanged += AlumnoComision_CollectionChanged;
            Calificacion_.CollectionChanged += Calificacion_CollectionChanged;
            DisposicionPendiente_.CollectionChanged += DisposicionPendiente_CollectionChanged;
        }

        #region CollectionChanged
        private void AlumnoComision_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (AlumnoComision obj in e.NewItems)
                    if(obj.alumno_ != this)
                        obj.alumno_ = this;
        }
        private void Calificacion_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (Calificacion obj in e.NewItems)
                    if(obj.alumno_ != this)
                        obj.alumno_ = this;
        }
        private void DisposicionPendiente_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if ( e.NewItems != null )
                foreach (DisposicionPendiente obj in e.NewItems)
                    if(obj.alumno_ != this)
                        obj.alumno_ = this;
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

        #region anio_ingreso
        protected string? _anio_ingreso = null;
        public string? anio_ingreso
        {
            get { return _anio_ingreso; }
            set {
                if( _anio_ingreso != value)
                {
                    _anio_ingreso = value; NotifyPropertyChanged(nameof(anio_ingreso));
                }
            }
        }
        #endregion

        #region observaciones
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set {
                if( _observaciones != value)
                {
                    _observaciones = value; NotifyPropertyChanged(nameof(observaciones));
                }
            }
        }
        #endregion

        #region persona
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set {
                if( _persona != value)
                {
                    _persona = value; NotifyPropertyChanged(nameof(persona));
                    if (!_persona.IsNoE() && (persona_.IsNoE() || !persona_!.Get(db.config.id).ToString()!.Equals(_persona.ToString())))
                        persona_ = CreateFromId<Persona>(_persona);
                    else if(_persona.IsNoE())
                        persona_ = null;
                }
            }
        }
        #endregion

        #region estado_inscripcion
        protected string? _estado_inscripcion = null;
        public string? estado_inscripcion
        {
            get { return _estado_inscripcion; }
            set {
                if( _estado_inscripcion != value)
                {
                    _estado_inscripcion = value; NotifyPropertyChanged(nameof(estado_inscripcion));
                }
            }
        }
        #endregion

        #region fecha_titulacion
        protected DateTime? _fecha_titulacion = null;
        public DateTime? fecha_titulacion
        {
            get { return _fecha_titulacion; }
            set {
                if( _fecha_titulacion != value)
                {
                    _fecha_titulacion = value; NotifyPropertyChanged(nameof(fecha_titulacion));
                }
            }
        }
        #endregion

        #region plan
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set {
                if( _plan != value)
                {
                    _plan = value; NotifyPropertyChanged(nameof(plan));
                    if (!_plan.IsNoE() && (plan_.IsNoE() || !plan_!.Get(db.config.id).ToString()!.Equals(_plan.ToString())))
                        plan_ = CreateFromId<Plan>(_plan);
                    else if(_plan.IsNoE())
                        plan_ = null;
                }
            }
        }
        #endregion

        #region resolucion_inscripcion
        protected string? _resolucion_inscripcion = null;
        public string? resolucion_inscripcion
        {
            get { return _resolucion_inscripcion; }
            set {
                if( _resolucion_inscripcion != value)
                {
                    _resolucion_inscripcion = value; NotifyPropertyChanged(nameof(resolucion_inscripcion));
                    if (!_resolucion_inscripcion.IsNoE() && (resolucion_inscripcion_.IsNoE() || !resolucion_inscripcion_!.Get(db.config.id).ToString()!.Equals(_resolucion_inscripcion.ToString())))
                        resolucion_inscripcion_ = CreateFromId<Resolucion>(_resolucion_inscripcion);
                    else if(_resolucion_inscripcion.IsNoE())
                        resolucion_inscripcion_ = null;
                }
            }
        }
        #endregion

        #region anio_inscripcion
        protected short? _anio_inscripcion = null;
        public short? anio_inscripcion
        {
            get { return _anio_inscripcion; }
            set {
                if( _anio_inscripcion != value)
                {
                    _anio_inscripcion = value; NotifyPropertyChanged(nameof(anio_inscripcion));
                }
            }
        }
        #endregion

        #region semestre_inscripcion
        protected short? _semestre_inscripcion = null;
        public short? semestre_inscripcion
        {
            get { return _semestre_inscripcion; }
            set {
                if( _semestre_inscripcion != value)
                {
                    _semestre_inscripcion = value; NotifyPropertyChanged(nameof(semestre_inscripcion));
                }
            }
        }
        #endregion

        #region semestre_ingreso
        protected short? _semestre_ingreso = null;
        public short? semestre_ingreso
        {
            get { return _semestre_ingreso; }
            set {
                if( _semestre_ingreso != value)
                {
                    _semestre_ingreso = value; NotifyPropertyChanged(nameof(semestre_ingreso));
                }
            }
        }
        #endregion

        #region adeuda_legajo
        protected string? _adeuda_legajo = null;
        public string? adeuda_legajo
        {
            get { return _adeuda_legajo; }
            set {
                if( _adeuda_legajo != value)
                {
                    _adeuda_legajo = value; NotifyPropertyChanged(nameof(adeuda_legajo));
                }
            }
        }
        #endregion

        #region adeuda_deudores
        protected string? _adeuda_deudores = null;
        public string? adeuda_deudores
        {
            get { return _adeuda_deudores; }
            set {
                if( _adeuda_deudores != value)
                {
                    _adeuda_deudores = value; NotifyPropertyChanged(nameof(adeuda_deudores));
                }
            }
        }
        #endregion

        #region documentacion_inscripcion
        protected string? _documentacion_inscripcion = null;
        public string? documentacion_inscripcion
        {
            get { return _documentacion_inscripcion; }
            set {
                if( _documentacion_inscripcion != value)
                {
                    _documentacion_inscripcion = value; NotifyPropertyChanged(nameof(documentacion_inscripcion));
                }
            }
        }
        #endregion

        #region anio_inscripcion_completo
        protected bool? _anio_inscripcion_completo = null;
        public bool? anio_inscripcion_completo
        {
            get { return _anio_inscripcion_completo; }
            set {
                if( _anio_inscripcion_completo != value)
                {
                    _anio_inscripcion_completo = value; NotifyPropertyChanged(nameof(anio_inscripcion_completo));
                }
            }
        }
        #endregion

        #region establecimiento_inscripcion
        protected string? _establecimiento_inscripcion = null;
        public string? establecimiento_inscripcion
        {
            get { return _establecimiento_inscripcion; }
            set {
                if( _establecimiento_inscripcion != value)
                {
                    _establecimiento_inscripcion = value; NotifyPropertyChanged(nameof(establecimiento_inscripcion));
                }
            }
        }
        #endregion

        #region libro_folio
        protected string? _libro_folio = null;
        public string? libro_folio
        {
            get { return _libro_folio; }
            set {
                if( _libro_folio != value)
                {
                    _libro_folio = value; NotifyPropertyChanged(nameof(libro_folio));
                }
            }
        }
        #endregion

        #region libro
        protected string? _libro = null;
        public string? libro
        {
            get { return _libro; }
            set {
                if( _libro != value)
                {
                    _libro = value; NotifyPropertyChanged(nameof(libro));
                }
            }
        }
        #endregion

        #region folio
        protected string? _folio = null;
        public string? folio
        {
            get { return _folio; }
            set {
                if( _folio != value)
                {
                    _folio = value; NotifyPropertyChanged(nameof(folio));
                }
            }
        }
        #endregion

        #region comentarios
        protected string? _comentarios = null;
        public string? comentarios
        {
            get { return _comentarios; }
            set {
                if( _comentarios != value)
                {
                    _comentarios = value; NotifyPropertyChanged(nameof(comentarios));
                }
            }
        }
        #endregion

        #region tiene_dni
        protected bool? _tiene_dni = null;
        public bool? tiene_dni
        {
            get { return _tiene_dni; }
            set {
                if( _tiene_dni != value)
                {
                    _tiene_dni = value; NotifyPropertyChanged(nameof(tiene_dni));
                }
            }
        }
        #endregion

        #region tiene_constancia
        protected bool? _tiene_constancia = null;
        public bool? tiene_constancia
        {
            get { return _tiene_constancia; }
            set {
                if( _tiene_constancia != value)
                {
                    _tiene_constancia = value; NotifyPropertyChanged(nameof(tiene_constancia));
                }
            }
        }
        #endregion

        #region tiene_certificado
        protected bool? _tiene_certificado = null;
        public bool? tiene_certificado
        {
            get { return _tiene_certificado; }
            set {
                if( _tiene_certificado != value)
                {
                    _tiene_certificado = value; NotifyPropertyChanged(nameof(tiene_certificado));
                }
            }
        }
        #endregion

        #region previas_completas
        protected bool? _previas_completas = null;
        public bool? previas_completas
        {
            get { return _previas_completas; }
            set {
                if( _previas_completas != value)
                {
                    _previas_completas = value; NotifyPropertyChanged(nameof(previas_completas));
                }
            }
        }
        #endregion

        #region tiene_partida
        protected bool? _tiene_partida = null;
        public bool? tiene_partida
        {
            get { return _tiene_partida; }
            set {
                if( _tiene_partida != value)
                {
                    _tiene_partida = value; NotifyPropertyChanged(nameof(tiene_partida));
                }
            }
        }
        #endregion

        #region creado
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set {
                if( _creado != value)
                {
                    _creado = value; NotifyPropertyChanged(nameof(creado));
                }
            }
        }
        #endregion

        #region confirmado_direccion
        protected bool? _confirmado_direccion = null;
        public bool? confirmado_direccion
        {
            get { return _confirmado_direccion; }
            set {
                if( _confirmado_direccion != value)
                {
                    _confirmado_direccion = value; NotifyPropertyChanged(nameof(confirmado_direccion));
                }
            }
        }
        #endregion

        #region persona (fk alumno.persona _o:o persona.id)
        protected Persona? _persona_ = null;
        public Persona? persona_
        {
            get { return _persona_; }
            set {
                if(_persona_ != null)
                    _persona_!.Alumno_ = null;

                _persona_ = value;

                if(value != null)
                {
                    _persona_!.Alumno_ = this;
                    persona = value.id;
                }
                else
                {
                    persona = null;
                }
                NotifyPropertyChanged(nameof(persona_));
            }
        }
        #endregion

        #region plan (fk alumno.plan _m:o plan.id)
        protected Plan? _plan_ = null;
        public Plan? plan_
        {
            get { return _plan_; }
            set {
                if ( _plan_ != value)
                {
                    _plan_ = value;
                    if(value != null)
                        plan = value.id;
                    else
                        plan = null;
                    NotifyPropertyChanged(nameof(plan_));
                }
            }
        }
        #endregion

        #region resolucion_inscripcion (fk alumno.resolucion_inscripcion _m:o resolucion.id)
        protected Resolucion? _resolucion_inscripcion_ = null;
        public Resolucion? resolucion_inscripcion_
        {
            get { return _resolucion_inscripcion_; }
            set {
                if ( _resolucion_inscripcion_ != value)
                {
                    _resolucion_inscripcion_ = value;
                    if(value != null)
                        resolucion_inscripcion = value.id;
                    else
                        resolucion_inscripcion = null;
                    NotifyPropertyChanged(nameof(resolucion_inscripcion_));
                }
            }
        }
        #endregion

        #region AlumnoComision_ (ref alumno_comision.alumno _m:o alumno.id)
        protected ObservableCollection<AlumnoComision> _AlumnoComision_ = new ();
        public ObservableCollection<AlumnoComision> AlumnoComision_
        {
            get { return _AlumnoComision_; }
            set { if( _AlumnoComision_ != value) { _AlumnoComision_ = value; NotifyPropertyChanged(nameof(AlumnoComision_)); } }
        }
        #endregion

        #region Calificacion_ (ref calificacion.alumno _m:o alumno.id)
        protected ObservableCollection<Calificacion> _Calificacion_ = new ();
        public ObservableCollection<Calificacion> Calificacion_
        {
            get { return _Calificacion_; }
            set { if( _Calificacion_ != value) { _Calificacion_ = value; NotifyPropertyChanged(nameof(Calificacion_)); } }
        }
        #endregion

        #region DisposicionPendiente_ (ref disposicion_pendiente.alumno _m:o alumno.id)
        protected ObservableCollection<DisposicionPendiente> _DisposicionPendiente_ = new ();
        public ObservableCollection<DisposicionPendiente> DisposicionPendiente_
        {
            get { return _DisposicionPendiente_; }
            set { if( _DisposicionPendiente_ != value) { _DisposicionPendiente_ = value; NotifyPropertyChanged(nameof(DisposicionPendiente_)); } }
        }
        #endregion

        public static IEnumerable<Alumno> QueryDapper(IDbConnection connection, string sql, object? parameters = null)
        {
            return connection.Query<Alumno, Persona, Domicilio, Plan, Resolucion, Alumno>(
                sql,
                (main, persona, domicilio, plan, resolucion_inscripcion) =>
                {
                    main.persona_ = persona;
                    if(!domicilio.IsNoE()) persona.domicilio_ = domicilio;
                    main.plan_ = plan;
                    main.resolucion_inscripcion_ = resolucion_inscripcion;
                    return main;
                },
                parameters,
                splitOn:"id"
            );
        }

    }
}
