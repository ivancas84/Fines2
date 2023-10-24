using SqlOrganize;
using System;
using System.ComponentModel;

namespace Fines2Wpf.Model
{
    public class Data_alumno : INotifyPropertyChanged
    {

        public Data_alumno ()
        {
            Initialize();
        }

        public Data_alumno(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("alumno").Default("id").Get("id");
                    _anio_ingreso = (string?)ContainerApp.db.Values("alumno").Default("anio_ingreso").Get("anio_ingreso");
                    _semestre_ingreso = (short?)ContainerApp.db.Values("alumno").Default("semestre_ingreso").Get("semestre_ingreso");
                    _tiene_dni = (bool?)ContainerApp.db.Values("alumno").Default("tiene_dni").Get("tiene_dni");
                    _tiene_constancia = (bool?)ContainerApp.db.Values("alumno").Default("tiene_constancia").Get("tiene_constancia");
                    _tiene_certificado = (bool?)ContainerApp.db.Values("alumno").Default("tiene_certificado").Get("tiene_certificado");
                    _previas_completas = (bool?)ContainerApp.db.Values("alumno").Default("previas_completas").Get("previas_completas");
                    _tiene_partida = (bool?)ContainerApp.db.Values("alumno").Default("tiene_partida").Get("tiene_partida");
                    _creado = (DateTime?)ContainerApp.db.Values("alumno").Default("creado").Get("creado");
                    _confirmado_direccion = (bool?)ContainerApp.db.Values("alumno").Default("confirmado_direccion").Get("confirmado_direccion");
                break;
            }
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _anio_ingreso = null;
        public string? anio_ingreso
        {
            get { return _anio_ingreso; }
            set { _anio_ingreso = value; NotifyPropertyChanged(); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(); }
        }
        protected string? _estado_inscripcion = null;
        public string? estado_inscripcion
        {
            get { return _estado_inscripcion; }
            set { _estado_inscripcion = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _fecha_titulacion = null;
        public DateTime? fecha_titulacion
        {
            get { return _fecha_titulacion; }
            set { _fecha_titulacion = value; NotifyPropertyChanged(); }
        }
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { _plan = value; NotifyPropertyChanged(); }
        }
        protected string? _resolucion_inscripcion = null;
        public string? resolucion_inscripcion
        {
            get { return _resolucion_inscripcion; }
            set { _resolucion_inscripcion = value; NotifyPropertyChanged(); }
        }
        protected short? _anio_inscripcion = null;
        public short? anio_inscripcion
        {
            get { return _anio_inscripcion; }
            set { _anio_inscripcion = value; NotifyPropertyChanged(); }
        }
        protected short? _semestre_inscripcion = null;
        public short? semestre_inscripcion
        {
            get { return _semestre_inscripcion; }
            set { _semestre_inscripcion = value; NotifyPropertyChanged(); }
        }
        protected short? _semestre_ingreso = null;
        public short? semestre_ingreso
        {
            get { return _semestre_ingreso; }
            set { _semestre_ingreso = value; NotifyPropertyChanged(); }
        }
        protected string? _adeuda_legajo = null;
        public string? adeuda_legajo
        {
            get { return _adeuda_legajo; }
            set { _adeuda_legajo = value; NotifyPropertyChanged(); }
        }
        protected string? _adeuda_deudores = null;
        public string? adeuda_deudores
        {
            get { return _adeuda_deudores; }
            set { _adeuda_deudores = value; NotifyPropertyChanged(); }
        }
        protected string? _documentacion_inscripcion = null;
        public string? documentacion_inscripcion
        {
            get { return _documentacion_inscripcion; }
            set { _documentacion_inscripcion = value; NotifyPropertyChanged(); }
        }
        protected bool? _anio_inscripcion_completo = null;
        public bool? anio_inscripcion_completo
        {
            get { return _anio_inscripcion_completo; }
            set { _anio_inscripcion_completo = value; NotifyPropertyChanged(); }
        }
        protected string? _establecimiento_inscripcion = null;
        public string? establecimiento_inscripcion
        {
            get { return _establecimiento_inscripcion; }
            set { _establecimiento_inscripcion = value; NotifyPropertyChanged(); }
        }
        protected string? _libro_folio = null;
        public string? libro_folio
        {
            get { return _libro_folio; }
            set { _libro_folio = value; NotifyPropertyChanged(); }
        }
        protected string? _libro = null;
        public string? libro
        {
            get { return _libro; }
            set { _libro = value; NotifyPropertyChanged(); }
        }
        protected string? _folio = null;
        public string? folio
        {
            get { return _folio; }
            set { _folio = value; NotifyPropertyChanged(); }
        }
        protected string? _comentarios = null;
        public string? comentarios
        {
            get { return _comentarios; }
            set { _comentarios = value; NotifyPropertyChanged(); }
        }
        protected bool? _tiene_dni = null;
        public bool? tiene_dni
        {
            get { return _tiene_dni; }
            set { _tiene_dni = value; NotifyPropertyChanged(); }
        }
        protected bool? _tiene_constancia = null;
        public bool? tiene_constancia
        {
            get { return _tiene_constancia; }
            set { _tiene_constancia = value; NotifyPropertyChanged(); }
        }
        protected bool? _tiene_certificado = null;
        public bool? tiene_certificado
        {
            get { return _tiene_certificado; }
            set { _tiene_certificado = value; NotifyPropertyChanged(); }
        }
        protected bool? _previas_completas = null;
        public bool? previas_completas
        {
            get { return _previas_completas; }
            set { _previas_completas = value; NotifyPropertyChanged(); }
        }
        protected bool? _tiene_partida = null;
        public bool? tiene_partida
        {
            get { return _tiene_partida; }
            set { _tiene_partida = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { _creado = value; NotifyPropertyChanged(); }
        }
        protected bool? _confirmado_direccion = null;
        public bool? confirmado_direccion
        {
            get { return _confirmado_direccion; }
            set { _confirmado_direccion = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
