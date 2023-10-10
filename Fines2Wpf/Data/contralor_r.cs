using System;

namespace Fines2Wpf.Data
{
    public class Data_contralor_r : Data_contralor
    {
        protected string? _planilla_docente__id = (string?)ContainerApp.db.DefaultValue("planilla_docente", "id");
        public string? planilla_docente__id
        {
            get { return _planilla_docente__id; }
            set { _planilla_docente__id = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente__numero = null;
        public string? planilla_docente__numero
        {
            get { return _planilla_docente__numero; }
            set { _planilla_docente__numero = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _planilla_docente__insertado = (DateTime?)ContainerApp.db.DefaultValue("planilla_docente", "insertado");
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
