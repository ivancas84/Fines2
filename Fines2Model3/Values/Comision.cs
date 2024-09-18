using SqlOrganize.CollectionUtils;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class ComisionValues : EntityVal
    {
        public ComisionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }


        public string Numero()
        {
            var s = "";

            EntityVal? v = GetValuesCache("sede");
            s += (!v.IsNoE()) ? (v.GetOrNull("numero")?.ToString() ?? "?") : "?";
            s += GetOrNull("division")?.ToString() ?? "?";
            s += "/";
            v = GetValuesCache("planificacion");
            if (!v.IsNoE())
            {
                s += v.GetOrNull("anio")?.ToString() ?? "?"; ;
                s += v.GetOrNull("semestre")?.ToString() ?? "?"; ;
            } else
            {
                s += "?";
            }
            return s.Trim();
        }

    
        public override string ToString()
        {
            var s = Numero();
            s += " ";
            s += GetOrNull("pfid")?.ToString() ?? "?"; ;
            s += " ";
            s += CalendarioAnioSemestre();
            s += " ";
            s += GetValuesCache("sede")?.GetOrNull("nombre")?.ToString() ?? "?";
            return s;
        }

        public string CalendarioAnioSemestre()
        {
            string s = "";
            var v = GetValuesCache("calendario");
            if (!v.IsNullOrEmpty())
            {
                s += v.GetOrNull("anio")?.ToString() ?? "?";
                s += "-";
                s += v.GetOrNull("semestre")?.ToString() ?? "?";
            }
            return s;
        }


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
                horasInicio.Sort((x,y) => TimeSpan.Compare((TimeSpan)x!, (TimeSpan)y!));

            List<TimeSpan?> horasFin = horarios.ColOfVal<TimeSpan?>("hora_fin").ToList();
                horasFin.RemoveAll(x => x.IsNoE());
                horasFin.Sort((x, y) => TimeSpan.Compare((TimeSpan)y!, (TimeSpan)x!));

            List<string> horarios_ = (List<string>)horarios.OrderBy(x => x["dia__numero"]).ColOfVal<string>("dia__dia").Distinct().ToList();

            string dias = string.Join(", ", horarios_);
            string hora_inicio = !horasInicio.IsNoE() ? ((TimeSpan)horasInicio[0]!).ToString(@"hh\:mm") : "?";
            string hora_fin = !horasFin.IsNoE() ? ((TimeSpan)horasFin[0]!).ToString(@"hh\:mm") : "?";
            return dias + " " + hora_inicio + " " + hora_fin;
        }

        public EntityPersist GenerarCursos()
        {
            EntityPersist persist = db.Persist();
            if (GetOrNull("id").IsNoE() || GetOrNull("planificacion").IsNoE())
                throw new Exception("No se pueden generar los cursos: No está correctamente definido el id o la planificación");

            IEnumerable<object> idsCursos = db.Sql("curso").
                Where("$comision = @0").
                Param("@0", Get("id")).
                Dicts().
                ColOfVal<object>("id");
            
            if(idsCursos.Count()>0)
                persist.DeleteIds("curso", idsCursos.ToArray());

            IEnumerable<Dictionary<string, object?>> distribucionesHorariasData = db.Sql("distribucion_horaria").
                Select("SUM($horas_catedra) AS suma_horas_catedra").
                Group("$disposicion__asignatura").
                Where("$disposicion__planificacion IN ( @0 )").
                Param("@0", Get("planificacion")).
                Dicts();

            foreach(Dictionary<string, object?> dh in distribucionesHorariasData)
            {
                EntityVal cursoVal = db.Values("curso").
                    Set("comision", Get("id")).
                    Set("asignatura", dh["disposicion__asignatura"]).
                    Set("horas_catedra", dh["suma_horas_catedra"]).
                    Default().Reset();

                persist.Insert(cursoVal);
            }

            return persist;
        }

        public IEnumerable<EntityPersist> GenerarComisionesSemestreSiguiente(object idCalendario, object idCalendarioComisionesSiguientes)
        {
            IEnumerable<Dictionary<string, object?>> comisionesAutorizadasSemestre = db.Sql("comision").
               Where(@" 
                        $calendario__id = @0 
                        AND $comision_siguiente IS NULL
                        AND $autorizada is true
                        AND (($planificacion__anio = '3' AND $planificacion__semestre = '1')
                        OR ($planificacion__anio = '2' AND $planificacion__semestre = '2')
                        OR ($planificacion__anio = '2' AND $planificacion__semestre = '1')
                        OR ($planificacion__anio = '1' AND $planificacion__semestre = '2')
                        OR ($planificacion__anio = '1' AND $planificacion__semestre = '1'))
                    ").
               Size(0).
               Param("@0", idCalendario).
               Dicts();

            List<EntityPersist> persists = new();
            for(var i = 0; i < comisionesAutorizadasSemestre.Count(); i++)
            {
                object actualId = comisionesAutorizadasSemestre.ElementAt(i)["id"]!;

                ComisionValues comValues = (ComisionValues)db.Values("comision").SetValues(comisionesAutorizadasSemestre.ElementAt(i)).
                    SetDefault("id").
                    SetDefault("alta").
                    Set("apertura", false).
                    Set("configuracion", "Histórica").
                    Set("calendario", idCalendario).
                    Reset();

                string? idPlanificacion = db.PlanificacionSiguienteSql(comValues.Get("planificacion__anio")!, comValues.Get("planificacion__semestre")!, comValues.Get("plan__id")!).Value<string>("id");
                
                comValues.Set("planificacion", idPlanificacion);
                if (!comValues.Check())
                {
                    logging.AddErrorLog(i.ToString(), comValues.Logging.ToString(), "generar_comisiones_siguientes");
                    continue;
                }

                comValues.
                    Insert().
                    UpdateValueIds("comision", "comision_siguiente", comValues.Get("id"), actualId!).
                    AddTo(persists);
            }

            return persists;
        }

        
    }
}
