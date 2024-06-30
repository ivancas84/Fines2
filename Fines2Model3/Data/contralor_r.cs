#nullable enable
using SqlOrganize;
using System;
using Newtonsoft.Json;

namespace Fines2Model3.Data
{
    public class Data_contralor_r : Data_contralor
    {

        public Data_contralor_r () : base()
        {
        }

        public Data_contralor_r (Db db, bool init = true) : base(db, init)
        {
        }

        public Data_contralor_r (Db db, bool init = true, params string[] fieldIds) : this(db, init)
        {
            Init(fieldIds);
        }

        protected void Init(params string[] fieldIds)
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

        public string? planilla_docente__Label { get; set; }

        protected string? _planilla_docente__id = null;

        [JsonProperty("planilla_docente-id")]
        public string? planilla_docente__id
        {
            get { return _planilla_docente__id; }
            set { _planilla_docente__id = value; planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente__id)); }
        }
        protected string? _planilla_docente__numero = null;

        [JsonProperty("planilla_docente-numero")]
        public string? planilla_docente__numero
        {
            get { return _planilla_docente__numero; }
            set { _planilla_docente__numero = value; NotifyPropertyChanged(nameof(planilla_docente__numero)); }
        }
        protected DateTime? _planilla_docente__insertado = null;

        [JsonProperty("planilla_docente-insertado")]
        public DateTime? planilla_docente__insertado
        {
            get { return _planilla_docente__insertado; }
            set { _planilla_docente__insertado = value; NotifyPropertyChanged(nameof(planilla_docente__insertado)); }
        }
        protected DateTime? _planilla_docente__fecha_contralor = null;

        [JsonProperty("planilla_docente-fecha_contralor")]
        public DateTime? planilla_docente__fecha_contralor
        {
            get { return _planilla_docente__fecha_contralor; }
            set { _planilla_docente__fecha_contralor = value; NotifyPropertyChanged(nameof(planilla_docente__fecha_contralor)); }
        }
        protected DateTime? _planilla_docente__fecha_consejo = null;

        [JsonProperty("planilla_docente-fecha_consejo")]
        public DateTime? planilla_docente__fecha_consejo
        {
            get { return _planilla_docente__fecha_consejo; }
            set { _planilla_docente__fecha_consejo = value; NotifyPropertyChanged(nameof(planilla_docente__fecha_consejo)); }
        }
        protected string? _planilla_docente__observaciones = null;

        [JsonProperty("planilla_docente-observaciones")]
        public string? planilla_docente__observaciones
        {
            get { return _planilla_docente__observaciones; }
            set { _planilla_docente__observaciones = value; NotifyPropertyChanged(nameof(planilla_docente__observaciones)); }
        }
    }
}
