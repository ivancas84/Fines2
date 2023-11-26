#nullable enable
using SqlOrganize;
using System;

namespace Fines2Wpf.Model
{
    public class Data_disposicion_r : Data_disposicion
    {

        public Data_disposicion_r () : base()
        {
            Initialize();
        }

        public Data_disposicion_r (DataInitMode mode = DataInitMode.Default) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
                    asignatura__id = (string?)ContainerApp.db.Values("asignatura").Default("id").Get("id");
                    planificacion__id = (string?)ContainerApp.db.Values("planificacion").Default("id").Get("id");
                    plan__id = (string?)ContainerApp.db.Values("plan").Default("id").Get("id");
                break;
            }
        }

        public string? asignatura__Label { get; set; }

        protected string? _asignatura__id = null;
        public string? asignatura__id
        {
            get { return _asignatura__id; }
            set { _asignatura__id = value; _asignatura = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__nombre = null;
        public string? asignatura__nombre
        {
            get { return _asignatura__nombre; }
            set { _asignatura__nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__formacion = null;
        public string? asignatura__formacion
        {
            get { return _asignatura__formacion; }
            set { _asignatura__formacion = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__clasificacion = null;
        public string? asignatura__clasificacion
        {
            get { return _asignatura__clasificacion; }
            set { _asignatura__clasificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__codigo = null;
        public string? asignatura__codigo
        {
            get { return _asignatura__codigo; }
            set { _asignatura__codigo = value; NotifyPropertyChanged(); }
        }
        protected string? _asignatura__perfil = null;
        public string? asignatura__perfil
        {
            get { return _asignatura__perfil; }
            set { _asignatura__perfil = value; NotifyPropertyChanged(); }
        }

        public string? planificacion__Label { get; set; }

        protected string? _planificacion__id = null;
        public string? planificacion__id
        {
            get { return _planificacion__id; }
            set { _planificacion__id = value; _planificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__anio = null;
        public string? planificacion__anio
        {
            get { return _planificacion__anio; }
            set { _planificacion__anio = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__semestre = null;
        public string? planificacion__semestre
        {
            get { return _planificacion__semestre; }
            set { _planificacion__semestre = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__plan = null;
        public string? planificacion__plan
        {
            get { return _planificacion__plan; }
            set { _planificacion__plan = value; NotifyPropertyChanged(); }
        }
        protected string? _planificacion__pfid = null;
        public string? planificacion__pfid
        {
            get { return _planificacion__pfid; }
            set { _planificacion__pfid = value; NotifyPropertyChanged(); }
        }

        public string? plan__Label { get; set; }

        protected string? _plan__id = null;
        public string? plan__id
        {
            get { return _plan__id; }
            set { _plan__id = value; _planificacion__plan = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__orientacion = null;
        public string? plan__orientacion
        {
            get { return _plan__orientacion; }
            set { _plan__orientacion = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__resolucion = null;
        public string? plan__resolucion
        {
            get { return _plan__resolucion; }
            set { _plan__resolucion = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__distribucion_horaria = null;
        public string? plan__distribucion_horaria
        {
            get { return _plan__distribucion_horaria; }
            set { _plan__distribucion_horaria = value; NotifyPropertyChanged(); }
        }
        protected string? _plan__pfid = null;
        public string? plan__pfid
        {
            get { return _plan__pfid; }
            set { _plan__pfid = value; NotifyPropertyChanged(); }
        }
    }
}
