#nullable enable
using System;
using Newtonsoft.Json;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Data_contralor_r : Data_contralor
    {

        public void DefaultRel(params string[] fieldIds)
        {
            EntityValues val;
            foreach(string fieldId in fieldIds)
            {
                switch(fieldId)
                {
                    case "planilla_docente":
                        val = db!.Values("planilla_docente");
                        planilla_docente__id = (string?)val.GetDefault("id");
                        planilla_docente__insertado = (DateTime?)val.GetDefault("insertado");
                    break;
                }
            }
        }
        protected string? _planilla_docente__Label = null;

        public string? planilla_docente__Label
        {
            get { return _planilla_docente__Label; }
            set { _planilla_docente__Label = value; NotifyPropertyChanged(nameof(planilla_docente__Label)); }
        }

        protected string? _planilla_docente__id = null;

        public string? planilla_docente__id
        {
            get { return _planilla_docente__id; }
            set { _planilla_docente__id = value; planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente__id)); }
        }
        protected string? _planilla_docente__numero = null;

        public string? planilla_docente__numero
        {
            get { return _planilla_docente__numero; }
            set { _planilla_docente__numero = value; NotifyPropertyChanged(nameof(planilla_docente__numero)); }
        }
        protected DateTime? _planilla_docente__insertado = null;

        public DateTime? planilla_docente__insertado
        {
            get { return _planilla_docente__insertado; }
            set { _planilla_docente__insertado = value; NotifyPropertyChanged(nameof(planilla_docente__insertado)); }
        }
        protected DateTime? _planilla_docente__fecha_contralor = null;

        public DateTime? planilla_docente__fecha_contralor
        {
            get { return _planilla_docente__fecha_contralor; }
            set { _planilla_docente__fecha_contralor = value; NotifyPropertyChanged(nameof(planilla_docente__fecha_contralor)); }
        }
        protected DateTime? _planilla_docente__fecha_consejo = null;

        public DateTime? planilla_docente__fecha_consejo
        {
            get { return _planilla_docente__fecha_consejo; }
            set { _planilla_docente__fecha_consejo = value; NotifyPropertyChanged(nameof(planilla_docente__fecha_consejo)); }
        }
        protected string? _planilla_docente__observaciones = null;

        public string? planilla_docente__observaciones
        {
            get { return _planilla_docente__observaciones; }
            set { _planilla_docente__observaciones = value; NotifyPropertyChanged(nameof(planilla_docente__observaciones)); }
        }
    }
}
