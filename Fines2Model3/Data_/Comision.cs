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
    public partial class Comision : EntityData
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
                Group("$disposicion__asignatura").
                Where("$disposicion__planificacion IN ( @0 )").
                Param("@0", planificacion).
                Dicts();

            foreach (Dictionary<string, object?> dh in distribucionesHorariasData)
            {
                Curso curso = new Curso();
                curso.comision = id;
                curso.disposicion = (string)dh["disposicion"]!;
                curso.horas_catedra = (int)dh["suma_horas_catedra"]!;
                curso.Default();
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

                return pfid + " " + periodo + " " + sede_.nombre;
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


    }
}
