#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Curso : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "curso";

        public override void Default()
        {
            EntityVal val = db!.Values("curso");
            _id = (string?)val.GetDefault("id");
            _alta = (DateTime?)val.GetDefault("alta");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected int? _horas_catedra = null;
        public int? horas_catedra
        {
            get { return _horas_catedra; }
            set { _horas_catedra = value; NotifyPropertyChanged(nameof(horas_catedra)); }
        }
        protected string? _ige = null;
        public string? ige
        {
            get { return _ige; }
            set { _ige = value; NotifyPropertyChanged(nameof(ige)); }
        }
        protected string? _comision = null;
        public string? comision
        {
            get { return _comision; }
            set { _comision = value; NotifyPropertyChanged(nameof(comision)); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(nameof(alta)); }
        }
        protected string? _descripcion_horario = null;
        public string? descripcion_horario
        {
            get { return _descripcion_horario; }
            set { _descripcion_horario = value; NotifyPropertyChanged(nameof(descripcion_horario)); }
        }
        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set { _codigo = value; NotifyPropertyChanged(nameof(codigo)); }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected string? _asignatura = null;
        public string? asignatura
        {
            get { return _asignatura; }
            set { _asignatura = value; NotifyPropertyChanged(nameof(asignatura)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "horas_catedra":
                    if (_horas_catedra == null)
                        return "Debe completar valor.";
                    return "";

                case "ige":
                    return "";

                case "comision":
                    if (_comision == null)
                        return "Debe completar valor.";
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "descripcion_horario":
                    return "";

                case "codigo":
                    return "";

                case "disposicion":
                    return "";

                case "observaciones":
                    return "";

                case "asignatura":
                    return "";

            }

            return "";
        }
        protected Comision? _comision_ = null;
        public Comision? comision_
        {
            get { return _comision_; }
            set { _comision_ = value; NotifyPropertyChanged(nameof(comision_)); }
        }

        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set { _disposicion_ = value; NotifyPropertyChanged(nameof(disposicion_)); }
        }

        protected Asignatura? _asignatura_ = null;
        public Asignatura? asignatura_
        {
            get { return _asignatura_; }
            set { _asignatura_ = value; NotifyPropertyChanged(nameof(asignatura_)); }
        }

    }
}
