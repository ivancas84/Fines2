using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Reflection.Emit;
using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Comision
    {
        public PersistContext GenerarCursos()
        {
            if (IsNullOrEmpty("id", "planificacion"))
                throw new Exception("No se pueden generar los cursos: No está correctamente definido el id o la planificación");

            PersistContext persist = Context.db.Persist();

            IEnumerable<object> idsCursos = Context.db.Sql("curso").
                Where("$comision = @0").
                Param("@0", id).
                Dicts().
                ColOfVal<object>("id");

            if (idsCursos.Count() > 0)
                persist.DeleteIds("curso", idsCursos.ToArray());

            IEnumerable<Dictionary<string, object?>> distribucionesHorariasData = Context.db.Sql("distribucion_horaria").
                Select("SUM($horas_catedra) AS suma_horas_catedra").
                Group("$disposicion__asignatura, $disposicion__id").
                Where("$disposicion__planificacion IN ( @0 )").
                Param("@0", planificacion).
                Dicts();

            foreach (Dictionary<string, object?> dh in distribucionesHorariasData)
            {
                Curso curso = new Curso();
                curso.comision = id;
                curso.disposicion = (string)dh["disposicion__id"]!;
                curso.horas_catedra = Convert.ToInt32(dh["suma_horas_catedra"]!);
                curso.Reset();
                persist.Insert(curso);
            }

            return persist;
        }

        public string numero => sede_!.numero + division + "/" + planificacion_!.anio + planificacion_.semestre;

        public override string Label
        {
            get {
                if (!_Label.IsNoE())
                    return _Label;

                return (pfid ?? "?") + " " + (periodo ?? "?") + " " + (planificacion_?.Label ?? "?") + " " + (sede_?.nombre ?? "?");
            }
            set
            {
                if (_Label != value)
                {
                    _Label = value;
                    NotifyPropertyChanged(nameof(Label));
                }
            }
        }

        public string periodo => calendario_!.anio.ToString() + "-" + calendario_!.semestre.ToString();

        public string horario { get { 
                

                return ""; 
            
            } }

        #region comision_siguiente (fk comision.calendario _m:o calendario.id)
        protected Comision? _comision_siguiente_ = null;
        public Comision? comision_siguiente_
        {
            get { return _comision_siguiente_; }
            set
            {
                if (_comision_siguiente_ != value)
                {
                    _comision_siguiente_ = value;
                    if (value != null)
                        comision_siguiente = value.id;
                    else
                        comision_siguiente = null;
                    NotifyPropertyChanged(nameof(comision_siguiente_));
                }
            }
        }
        #endregion


        /// <summary>
        /// Horario de la comision
        /// </summary>
        /// <param name="horarios">Conjunto de valores (horarios) obtenidos de la base de datos</param>
        /// <returns>string con el horario de la comision</returns>
        public string Horario(IEnumerable<Dictionary<string, object?>> horarios)
        {
            if (horarios.IsNoE())
                return "?";

            List<TimeSpan?> horasInicio = horarios.ColOfVal<TimeSpan?>("hora_inicio").ToList();
            horasInicio.RemoveAll(x => x.IsNoE());
            horasInicio.Sort((x, y) => TimeSpan.Compare((TimeSpan)x!, (TimeSpan)y!));

            List<TimeSpan?> horasFin = horarios.ColOfVal<TimeSpan?>("hora_fin").ToList();
            horasFin.RemoveAll(x => x.IsNoE());
            horasFin.Sort((x, y) => TimeSpan.Compare((TimeSpan)y!, (TimeSpan)x!));

            List<string> horarios_ = (List<string>)horarios.OrderBy(x => x["dia__numero"]).ColOfVal<string>("dia__dia").Distinct().ToList();

            string dias = string.Join(", ", horarios_);
            string hora_inicio = !horasInicio.IsNoE() ? ((TimeSpan)horasInicio[0]!).ToString(@"hh\:mm") : "?";
            string hora_fin = !horasFin.IsNoE() ? ((TimeSpan)horasFin[0]!).ToString(@"hh\:mm") : "?";
            return dias + " " + hora_inicio + " " + hora_fin;
        }

        /// <summary>Persistencia de asignaciones a partir de datos definidos en un texto</summary>
        /// <returns>persists y asignaciones persistidas</returns>
        public void PersistAsignaciones(string text, params string[]? headers)
        {
            if (headers.IsNoE())
                headers = ["apellidos", "nombres", "numero_documento", "genero", "fecha_nacimiento", "telefono", "email"];


            string[] _data = text.Split("\r\n");
            if (_data.IsNoE())
                throw new Exception("Datos vacios");

            for (var j = 0; j < _data.Length; j++)
            {
                PersistContext persist = Context.db.Persist();

                try
                {
                    IDictionary<string, object?> dict = _data[j].DictFromText(headers!);

                    Persona persona = new Persona();
                    persona.SetNotNull(dict); 

                    CompareParams compare = new CompareParams
                    {
                        FieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };

                    persist.PersistCompare(persona, compare);

                    Alumno alumno = new Alumno();
                    alumno.persona_ = persona;
                    alumno.anio_ingreso = planificacion_!.anio;
                    alumno.semestre_ingreso = Convert.ToInt16(planificacion_!.semestre);
                    alumno.plan_ = planificacion_.plan_;
                    persist.InsertIfNotExists(alumno);

                    AlumnoComision asignacion = new AlumnoComision();
                    asignacion.alumno_ = alumno;
                    asignacion.comision_ = this;
                    persist.InsertIfNotExists(asignacion);

                    var otrasAsignaciones = AsignacionDAO.OtrasAsignacionesDeAlumnoSql(alumno.id!, id!).Cache().Entities<AlumnoComision>();
                    foreach (var oa in otrasAsignaciones)
                        persist.logging.AddLog("alumno_comision", "Asignacion existente " + oa.Label, "PersistAsignacionesComisionText", Logging.Level.Warning);

                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("alumno_comision", ex.Message, "PersistAsignacionesComisionText", Logging.Level.Error);
                }

            }

        }





    }
}
