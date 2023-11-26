#nullable enable
using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_contralor_r : Data_contralor
    {

        public Data_contralor_r () : base()
        {
            Initialize();
        }

        public Data_contralor_r (DataInitMode mode = DataInitMode.Default) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
                    planilla_docente__id = (string?)ContainerApp.db.Values("planilla_docente").Default("id").Get("id");
                    planilla_docente__insertado = (DateTime?)ContainerApp.db.Values("planilla_docente").Default("insertado").Get("insertado");
                break;
            }
        }

        public string? planilla_docente__Label { get; set; }

        protected string? _planilla_docente__id = null;
        public string? planilla_docente__id
        {
            get { return _planilla_docente__id; }
            set { _planilla_docente__id = value; _planilla_docente = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente__numero = null;
        public string? planilla_docente__numero
        {
            get { return _planilla_docente__numero; }
            set { _planilla_docente__numero = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente__insertado = null;
        public DateTime? planilla_docente__insertado
        {
            get { return _planilla_docente__insertado; }
            set { _planilla_docente__insertado = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente__fecha_contralor = null;
        public DateTime? planilla_docente__fecha_contralor
        {
            get { return _planilla_docente__fecha_contralor; }
            set { _planilla_docente__fecha_contralor = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente__fecha_consejo = null;
        public DateTime? planilla_docente__fecha_consejo
        {
            get { return _planilla_docente__fecha_consejo; }
            set { _planilla_docente__fecha_consejo = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente__observaciones = null;
        public string? planilla_docente__observaciones
        {
            get { return _planilla_docente__observaciones; }
            set { _planilla_docente__observaciones = value; NotifyPropertyChanged(); }
        }
    }
}
