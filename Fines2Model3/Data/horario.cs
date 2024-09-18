#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Horario : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "horario";

        public override void Default()
        {
            EntityVal val = db!.Values("horario");
            _id = (string?)val.GetDefault("id");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected DateTime? _hora_inicio = null;
        public DateTime? hora_inicio
        {
            get { return _hora_inicio; }
            set { _hora_inicio = value; NotifyPropertyChanged(nameof(hora_inicio)); }
        }
        protected DateTime? _hora_fin = null;
        public DateTime? hora_fin
        {
            get { return _hora_fin; }
            set { _hora_fin = value; NotifyPropertyChanged(nameof(hora_fin)); }
        }
        protected string? _curso = null;
        public string? curso
        {
            get { return _curso; }
            set { _curso = value; NotifyPropertyChanged(nameof(curso)); }
        }
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set { _dia = value; NotifyPropertyChanged(nameof(dia)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "hora_inicio":
                    if (_hora_inicio == null)
                        return "Debe completar valor.";
                    return "";

                case "hora_fin":
                    if (_hora_fin == null)
                        return "Debe completar valor.";
                    return "";

                case "curso":
                    if (_curso == null)
                        return "Debe completar valor.";
                    return "";

                case "dia":
                    if (_dia == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
        //horario.curso _o:o curso.id
        protected Curso? _curso_ = null;
        public Curso? curso_
        {
            get { return _curso_; }
            set { _curso_ = value; NotifyPropertyChanged(nameof(curso_)); }
        }

        //horario.dia _o:o dia.id
        protected Dia? _dia_ = null;
        public Dia? dia_
        {
            get { return _dia_; }
            set { _dia_ = value; NotifyPropertyChanged(nameof(dia_)); }
        }

    }
}
