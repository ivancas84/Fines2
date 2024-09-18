#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class DistribucionHoraria : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "distribucion_horaria";

        public override void Default()
        {
            EntityVal val = db!.Values("distribucion_horaria");
            _id = (string?)val.GetDefault("id");
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
        protected int? _dia = null;
        public int? dia
        {
            get { return _dia; }
            set { _dia = value; NotifyPropertyChanged(nameof(dia)); }
        }
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { _disposicion = value; NotifyPropertyChanged(nameof(disposicion)); }
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

                case "dia":
                    if (_dia == null)
                        return "Debe completar valor.";
                    return "";

                case "disposicion":
                    return "";

            }

            return "";
        }
        //distribucion_horaria.disposicion _o:o disposicion.id
        protected Disposicion? _disposicion_ = null;
        public Disposicion? disposicion_
        {
            get { return _disposicion_; }
            set { _disposicion_ = value; NotifyPropertyChanged(nameof(disposicion_)); }
        }

    }
}
