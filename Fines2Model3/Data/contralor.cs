#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_contralor : SqlOrganize.Data
    {

        public Data_contralor ()
        {
        }

        public Data_contralor(Db db, bool init = true)
        {
            this.db = db;
            if(init)
                Init();
        }

        protected void Init()
        {
            EntityValues val = db!.Values("contralor");
            _id = (string?)val.GetDefault("id");
            _insertado = (DateTime?)val.GetDefault("insertado");
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected DateTime? _fecha_contralor = null;
        public DateTime? fecha_contralor
        {
            get { return _fecha_contralor; }
            set { _fecha_contralor = value; NotifyPropertyChanged(nameof(fecha_contralor)); }
        }
        protected DateTime? _fecha_consejo = null;
        public DateTime? fecha_consejo
        {
            get { return _fecha_consejo; }
            set { _fecha_consejo = value; NotifyPropertyChanged(nameof(fecha_consejo)); }
        }
        protected DateTime? _insertado = null;
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(nameof(insertado)); }
        }
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { _planilla_docente = value; NotifyPropertyChanged(nameof(planilla_docente)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "fecha_contralor":
                    return "";

                case "fecha_consejo":
                    return "";

                case "insertado":
                    if (_insertado == null)
                        return "Debe completar valor.";
                    return "";

                case "planilla_docente":
                    if (_planilla_docente == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
