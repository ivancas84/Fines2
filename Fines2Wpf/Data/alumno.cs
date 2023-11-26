using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Data
{
    public class Data_alumno : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_alumno ()
        {
            Initialize();
        }

        public Data_alumno (DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("alumno").Default("id").Get("id");
                    _anio_ingreso = (string?)ContainerApp.db.Values("alumno").Default("anio_ingreso").Get("anio_ingreso");
                    _semestre_ingreso = (short?)ContainerApp.db.Values("alumno").Default("semestre_ingreso").Get("semestre_ingreso");
                    _tiene_dni = (bool?)ContainerApp.db.Values("alumno").Default("tiene_dni").Get("tiene_dni");
                    _tiene_constancia = (bool?)ContainerApp.db.Values("alumno").Default("tiene_constancia").Get("tiene_constancia");
                    _tiene_certificado = (bool?)ContainerApp.db.Values("alumno").Default("tiene_certificado").Get("tiene_certificado");
                    _previas_completas = (bool?)ContainerApp.db.Values("alumno").Default("previas_completas").Get("previas_completas");
                    _tiene_partida = (bool?)ContainerApp.db.Values("alumno").Default("tiene_partida").Get("tiene_partida");
                    _creado = (DateTime?)ContainerApp.db.Values("alumno").Default("creado").Get("creado");
                    _confirmado_direccion = (bool?)ContainerApp.db.Values("alumno").Default("confirmado_direccion").Get("confirmado_direccion");
                break;
            }

            Data_persona = new (mode);
            Data_plan = new (mode);
            Data_resolucion_inscripcion = new (mode);
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }

        protected string? _anio_ingreso = null;
        public string? anio_ingreso
        {
            get { return _anio_ingreso; }
            set { _anio_ingreso = value; NotifyPropertyChanged(); }
        }

        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { _observaciones = value; NotifyPropertyChanged(); }
        }

        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(); }
        }

        protected string? _estado_inscripcion = null;
        public string? estado_inscripcion
        {
            get { return _estado_inscripcion; }
            set { _estado_inscripcion = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _fecha_titulacion = null;
        public DateTime? fecha_titulacion
        {
            get { return _fecha_titulacion; }
            set { _fecha_titulacion = value; NotifyPropertyChanged(); }
        }

        protected string? _plan = null;
        public string? plan
        {
            get { return _plan; }
            set { _plan = value; NotifyPropertyChanged(); }
        }

        protected string? _resolucion_inscripcion = null;
        public string? resolucion_inscripcion
        {
            get { return _resolucion_inscripcion; }
            set { _resolucion_inscripcion = value; NotifyPropertyChanged(); }
        }

        protected short? _anio_inscripcion = null;
        public short? anio_inscripcion
        {
            get { return _anio_inscripcion; }
            set { _anio_inscripcion = value; NotifyPropertyChanged(); }
        }

        protected short? _semestre_inscripcion = null;
        public short? semestre_inscripcion
        {
            get { return _semestre_inscripcion; }
            set { _semestre_inscripcion = value; NotifyPropertyChanged(); }
        }

        protected short? _semestre_ingreso = null;
        public short? semestre_ingreso
        {
            get { return _semestre_ingreso; }
            set { _semestre_ingreso = value; NotifyPropertyChanged(); }
        }

        protected string? _adeuda_legajo = null;
        public string? adeuda_legajo
        {
            get { return _adeuda_legajo; }
            set { _adeuda_legajo = value; NotifyPropertyChanged(); }
        }

        protected string? _adeuda_deudores = null;
        public string? adeuda_deudores
        {
            get { return _adeuda_deudores; }
            set { _adeuda_deudores = value; NotifyPropertyChanged(); }
        }

        protected string? _documentacion_inscripcion = null;
        public string? documentacion_inscripcion
        {
            get { return _documentacion_inscripcion; }
            set { _documentacion_inscripcion = value; NotifyPropertyChanged(); }
        }

        protected bool? _anio_inscripcion_completo = null;
        public bool? anio_inscripcion_completo
        {
            get { return _anio_inscripcion_completo; }
            set { _anio_inscripcion_completo = value; NotifyPropertyChanged(); }
        }

        protected string? _establecimiento_inscripcion = null;
        public string? establecimiento_inscripcion
        {
            get { return _establecimiento_inscripcion; }
            set { _establecimiento_inscripcion = value; NotifyPropertyChanged(); }
        }

        protected string? _libro_folio = null;
        public string? libro_folio
        {
            get { return _libro_folio; }
            set { _libro_folio = value; NotifyPropertyChanged(); }
        }

        protected string? _libro = null;
        public string? libro
        {
            get { return _libro; }
            set { _libro = value; NotifyPropertyChanged(); }
        }

        protected string? _folio = null;
        public string? folio
        {
            get { return _folio; }
            set { _folio = value; NotifyPropertyChanged(); }
        }

        protected string? _comentarios = null;
        public string? comentarios
        {
            get { return _comentarios; }
            set { _comentarios = value; NotifyPropertyChanged(); }
        }

        protected bool? _tiene_dni = null;
        public bool? tiene_dni
        {
            get { return _tiene_dni; }
            set { _tiene_dni = value; NotifyPropertyChanged(); }
        }

        protected bool? _tiene_constancia = null;
        public bool? tiene_constancia
        {
            get { return _tiene_constancia; }
            set { _tiene_constancia = value; NotifyPropertyChanged(); }
        }

        protected bool? _tiene_certificado = null;
        public bool? tiene_certificado
        {
            get { return _tiene_certificado; }
            set { _tiene_certificado = value; NotifyPropertyChanged(); }
        }

        protected bool? _previas_completas = null;
        public bool? previas_completas
        {
            get { return _previas_completas; }
            set { _previas_completas = value; NotifyPropertyChanged(); }
        }

        protected bool? _tiene_partida = null;
        public bool? tiene_partida
        {
            get { return _tiene_partida; }
            set { _tiene_partida = value; NotifyPropertyChanged(); }
        }

        protected DateTime? _creado = null;
        public DateTime? creado
        {
            get { return _creado; }
            set { _creado = value; NotifyPropertyChanged(); }
        }

        protected bool? _confirmado_direccion = null;
        public bool? confirmado_direccion
        {
            get { return _confirmado_direccion; }
            set { _confirmado_direccion = value; NotifyPropertyChanged(); }
        }

        protected Data_persona? _Data_persona = null;
        public Data_persona? Data_persona
        {
            get { return _Data_persona; }
            set { _Data_persona = value; NotifyPropertyChanged(); }
        }

        protected Data_plan? _Data_plan = null;
        public Data_plan? Data_plan
        {
            get { return _Data_plan; }
            set { _Data_plan = value; NotifyPropertyChanged(); }
        }

        protected Data_resolucion? _Data_resolucion_inscripcion = null;
        public Data_resolucion? Data_resolucion_inscripcion
        {
            get { return _Data_resolucion_inscripcion; }
            set { _Data_resolucion_inscripcion = value; NotifyPropertyChanged(); }
        }

        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
        public string Error
        {
            get
            {
                PropertyInfo[] properties = this.GetType().GetProperties();

                List<string> errors = new ();
                foreach (PropertyInfo property in properties)
                    if (this[property.Name] != "")
                    {
                        NotifyPropertyChanged(property.Name);
                        errors.Add(this[property.Name]);
                    }

                if(errors.Count > 0)
                    return String.Join(" - ", errors.ToArray());

                return "";
            }
        }

        public string this[string columnName]
        {
            get
            {
                if (!Validate)
                    return "";

                // If there's no error, empty string gets returned
                return ValidateField(columnName);
            }
        }

        protected virtual string ValidateField(string columnName)
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
                    if (!_persona.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("alumno").Where("$persona = @0").Parameters(_persona).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
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
                    if (!_libro_folio.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("alumno").Where("$libro_folio = @0").Parameters(_libro_folio).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row!["id"]!.ToString()))
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
