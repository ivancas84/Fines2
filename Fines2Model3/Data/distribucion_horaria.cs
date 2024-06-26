#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using Utils;

namespace Fines2Model3.Data
{
    public class Data_distribucion_horaria : SqlOrganize.Data
    {

        public Data_distribucion_horaria ()
        {
        }

        public Data_distribucion_horaria(Db db, bool init = true)
        {
            this.db = db;
            if(init)
                Init();
        }

        protected void Init()
        {
            EntityValues val = db!.Values("distribucion_horaria");
            _id = (string?)val.GetDefault("id");
        }

        public string? Label { get; set; }

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
    }
}
