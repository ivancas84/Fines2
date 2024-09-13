#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class Alumno : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "alumno";

        public override void Default()
        {
            EntityVal val = db!.Values("alumno");
            _id = (string?)val.GetDefault("id");
            _anio_ingreso = (string?)val.GetDefault("anio_ingreso");
            _semestre_ingreso = (short?)val.GetDefault("semestre_ingreso");
            _tiene_dni = (bool?)val.GetDefault("tiene_dni");
            _tiene_constancia = (bool?)val.GetDefault("tiene_constancia");
            _tiene_certificado = (bool?)val.GetDefault("tiene_certificado");
            _previas_completas = (bool?)val.GetDefault("previas_completas");
            _tiene_partida = (bool?)val.GetDefault("tiene_partida");
            _creado = (DateTime?)val.GetDefault("creado");
            _confirmado_direccion = (bool?)val.GetDefault("confirmado_direccion");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(nameof(id)); }
        }
        protected string? _anio_ingreso = null;
        public string? anio_ingreso
        {
            get { return _anio_ingreso; }
            set { _anio_ingreso = value; NotifyPropertyChanged(nameof(anio_ingreso)); }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(nameof(persona)); }
        }
        protected string? _estado_inscripcion = null;
        public string? estado_inscripcion
        {
            get { return _estado_inscripcion; }
            set { _estado_inscripcion = value; NotifyPropertyChanged(nameof(estado_inscripcion)); }
        }
        protected DateTime? _fecha_titulacion = null;
        public DateTime? fecha_titulacion
        {
            get { return _fecha_titulacion; }
            set { _fecha_titulacion = value; NotifyPropertyChanged(nameof(fecha_titulacion)); }
        }
        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { _plan = value; NotifyPropertyChanged(nameof(plan)); }
        }
        protected string? _resolucion_inscripcion = null;
        public string? resolucion_inscripcion
        {
            get { return _resolucion_inscripcion; }
            set { _resolucion_inscripcion = value; NotifyPropertyChanged(nameof(resolucion_inscripcion)); }
        }
        protected short? _anio_inscripcion = null;
        public short? anio_inscripcion
        {
            get { return _anio_inscripcion; }
            set { _anio_inscripcion = value; NotifyPropertyChanged(nameof(anio_inscripcion)); }
        }
        protected short? _semestre_inscripcion = null;
        public short? semestre_inscripcion
        {
            get { return _semestre_inscripcion; }
            set { _semestre_inscripcion = value; NotifyPropertyChanged(nameof(semestre_inscripcion)); }
        }
        protected short? _semestre_ingreso = null;
        public short? semestre_ingreso
        {
            get { return _semestre_ingreso; }
            set { _semestre_ingreso = value; NotifyPropertyChanged(nameof(semestre_ingreso)); }
        }
        protected string? _adeuda_legajo = null;
        public string? adeuda_legajo
        {
            get { return _adeuda_legajo; }
            set { _adeuda_legajo = value; NotifyPropertyChanged(nameof(adeuda_legajo)); }
        }
        protected string? _adeuda_deudores = null;
        public string? adeuda_deudores
        {
            get { return _adeuda_deudores; }
            set { _adeuda_deudores = value; NotifyPropertyChanged(nameof(adeuda_deudores)); }
        }
        protected string? _documentacion_inscripcion = null;
        public string? documentacion_inscripcion
        {
            get { return _documentacion_inscripcion; }
            set { _documentacion_inscripcion = value; NotifyPropertyChanged(nameof(documentacion_inscripcion)); }
        }
        protected bool? _anio_inscripcion_completo = null;
        public bool? anio_inscripcion_completo
        {
            get { return _anio_inscripcion_completo; }
            set { _anio_inscripcion_completo = value; NotifyPropertyChanged(nameof(anio_inscripcion_completo)); }
        }
        protected string? _establecimiento_inscripcion = null;
        public string? establecimiento_inscripcion
        {
            get { return _establecimiento_inscripcion; }
            set { _establecimiento_inscripcion = value; NotifyPropertyChanged(nameof(establecimiento_inscripcion)); }
        }
        protected string? _libro_folio = null;
        public string? libro_folio
        {
            get { return _libro_folio; }
            set { _libro_folio = value; NotifyPropertyChanged(nameof(libro_folio)); }
        }
        protected string? _libro = null;
        public string? libro
        {
            get { return _libro; }
            set { _libro = value; NotifyPropertyChanged(nameof(libro)); }
        }
        protected string? _folio = null;
        public string? folio
        {
            get { return _folio; }
            set { _folio = value; NotifyPropertyChanged(nameof(folio)); }
        }
        protected string? _comentarios = null;
        public string? comentarios
        {
            get { return _comentarios; }
            set { _comentarios = value; NotifyPropertyChanged(nameof(comentarios)); }
        }
        protected bool? _tiene_dni = null;
        public bool? tiene_dni
        {
            get { return _tiene_dni; }
            set { _tiene_dni = value; NotifyPropertyChanged(nameof(tiene_dni)); }
        }
        protected bool? _tiene_constancia = null;
        public bool? tiene_constancia
        {
            get { return _tiene_constancia; }
            set { _tiene_constancia = value; NotifyPropertyChanged(nameof(tiene_constancia)); }
        }
        protected bool? _tiene_certificado = null;
        public bool? tiene_certificado
        {
            get { return _tiene_certificado; }
            set { _tiene_certificado = value; NotifyPropertyChanged(nameof(tiene_certificado)); }
        }
        protected bool? _previas_completas = null;
        public bool? previas_completas
        {
            get { return _previas_completas; }
            set { _previas_completas = value; NotifyPropertyChanged(nameof(previas_completas)); }
        }
        protected bool? _tiene_partida = null;
        public bool? tiene_partida
        {
            get { return _tiene_partida; }
            set { _tiene_partida = value; NotifyPropertyChanged(nameof(tiene_partida)); }
        }
        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { _creado = value; NotifyPropertyChanged(nameof(creado)); }
        }
        protected bool? _confirmado_direccion = null;
        public bool? confirmado_direccion
        {
            get { return _confirmado_direccion; }
            set { _confirmado_direccion = value; NotifyPropertyChanged(nameof(confirmado_direccion)); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "anio_ingreso":
                    return "";

                case "observaciones":
                    return "";

                case "persona":
                    if (_persona == null)
                        return "Debe completar valor.";
                    if (!db.IsNoE() && !_persona.IsNoE()) {
                        var row = db.Sql("alumno").Equal("$persona", _persona).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "estado_inscripcion":
                    return "";

                case "fecha_titulacion":
                    return "";

                case "plan":
                    return "";

                case "resolucion_inscripcion":
                    return "";

                case "anio_inscripcion":
                    return "";

                case "semestre_inscripcion":
                    return "";

                case "semestre_ingreso":
                    return "";

                case "adeuda_legajo":
                    return "";

                case "adeuda_deudores":
                    return "";

                case "documentacion_inscripcion":
                    return "";

                case "anio_inscripcion_completo":
                    return "";

                case "establecimiento_inscripcion":
                    return "";

                case "libro_folio":
                    if (!db.IsNoE() && !_libro_folio.IsNoE()) {
                        var row = db.Sql("alumno").Equal("$libro_folio", _libro_folio).Cache().Dict();
                        if (!row.IsNoE() && !_id.ToString().Equals(row!["id"]!.ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "libro":
                    return "";

                case "folio":
                    return "";

                case "comentarios":
                    return "";

                case "tiene_dni":
                    if (_tiene_dni == null)
                        return "Debe completar valor.";
                    return "";

                case "tiene_constancia":
                    if (_tiene_constancia == null)
                        return "Debe completar valor.";
                    return "";

                case "tiene_certificado":
                    if (_tiene_certificado == null)
                        return "Debe completar valor.";
                    return "";

                case "previas_completas":
                    if (_previas_completas == null)
                        return "Debe completar valor.";
                    return "";

                case "tiene_partida":
                    if (_tiene_partida == null)
                        return "Debe completar valor.";
                    return "";

                case "creado":
                    if (_creado == null)
                        return "Debe completar valor.";
                    return "";

                case "confirmado_direccion":
                    if (_confirmado_direccion == null)
                        return "Debe completar valor.";
                    return "";

            }

            return "";
        }
    }
}
