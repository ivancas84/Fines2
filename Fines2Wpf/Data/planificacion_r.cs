#nullable enable
using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_planificacion_r : Data_planificacion
    {

        public Data_planificacion_r () : base()
        {
            Initialize();
        }

        public Data_planificacion_r (DataInitMode mode) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Null)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
                    plan__id = (string?)ContainerApp.db.Values("plan").Default("id").Get("id");
                break;
            }
        }

        public string? plan__Label { get; set; }

        protected string? _plan__id = null;
        public string? plan__id
        {
            get { return _plan__id; }
            set { _plan__id = value; _plan = value; NotifyPropertyChanged(); }
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
