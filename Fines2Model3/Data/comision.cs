#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Comision : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "comision";

        public override void Default()
        {
            EntityVal val = db!.Values("comision");
            _id = (string?)val.GetDefault("id");
            _alta = (DateTime?)val.GetDefault("alta");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _turno = null;
        public string? turno
        {
            get { return _turno; }
            set { _turno = value; NotifyPropertyChanged(nameof(turno)); }
        }
        protected string? _division = null;
        public string? division
        {
            get { return _division; }
            set { _division = value; NotifyPropertyChanged(nameof(division)); }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(nameof(comentario)); }
        }
        protected bool? _autorizada = null;
        public bool? autorizada
        {
            get { return _autorizada; }
            set { _autorizada = value; NotifyPropertyChanged(nameof(autorizada)); }
        }
        protected bool? _apertura = null;
        public bool? apertura
        {
            get { return _apertura; }
            set { _apertura = value; NotifyPropertyChanged(nameof(apertura)); }
        }
        protected bool? _publicada = null;
        public bool? publicada
        {
            get { return _publicada; }
            set { _publicada = value; NotifyPropertyChanged(nameof(publicada)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(nameof(alta)); }
        }
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { _sede = value; NotifyPropertyChanged(nameof(sede)); }
        }
        protected string? _modalidad = null;
        public string? modalidad
        {
            get { return _modalidad; }
            set { _modalidad = value; NotifyPropertyChanged(nameof(modalidad)); }
        }
        protected string? _planificacion = null;
        public string? planificacion
        {
            get { return _planificacion; }
            set { _planificacion = value; NotifyPropertyChanged(nameof(planificacion)); }
        }
        protected string? _comision_siguiente = null;
        public string? comision_siguiente
        {
            get { return _comision_siguiente; }
            set { _comision_siguiente = value; NotifyPropertyChanged(nameof(comision_siguiente)); }
        }
        protected string? _calendario = null;
        public string? calendario
        {
            get { return _calendario; }
            set { _calendario = value; NotifyPropertyChanged(nameof(calendario)); }
        }
        protected string? _identificacion = null;
        public string? identificacion
        {
            get { return _identificacion; }
            set { _identificacion = value; NotifyPropertyChanged(nameof(identificacion)); }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(nameof(pfid)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "turno":
                    return "";

                case "division":
                    if (_division == null)
                        return "Debe completar valor.";
                    return "";

                case "comentario":
                    return "";

                case "autorizada":
                    if (_autorizada == null)
                        return "Debe completar valor.";
                    return "";

                case "apertura":
                    if (_apertura == null)
                        return "Debe completar valor.";
                    return "";

                case "publicada":
                    if (_publicada == null)
                        return "Debe completar valor.";
                    return "";

                case "observaciones":
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "sede":
                    if (_sede == null)
                        return "Debe completar valor.";
                    return "";

                case "modalidad":
                    if (_modalidad == null)
                        return "Debe completar valor.";
                    return "";

                case "planificacion":
                    return "";

                case "comision_siguiente":
                    return "";

                case "calendario":
                    if (_calendario == null)
                        return "Debe completar valor.";
                    return "";

                case "identificacion":
                    return "";

                case "pfid":
                    return "";

            }

            return "";
        }
    }
}
