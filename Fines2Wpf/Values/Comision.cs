using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection.Emit;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.Values
{
    class Comision : EntityValues
    {
        public Comision(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public string Numero()
        {
            var s = "";

            EntityValues? v = ValuesTree("sede");
            s += (!v.IsNullOrEmpty()) ? (v.GetOrNull("numero")?.ToString() ?? "?") : "?";
            s += GetOrNull("division")?.ToString() ?? "?";
            s += "/";
            v = ValuesTree("planificacion");
            if (!v.IsNullOrEmpty())
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
            s += ValuesTree("sede")?.GetOrNull("nombre")?.ToString() ?? "?";
            return s;
        }

        public string CalendarioAnioSemestre()
        {
            string s = "";
            var v = ValuesTree("calendario");
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
            if (horarios.IsNullOrEmpty())
                return "?";

            List<TimeSpan?> horasInicio = horarios.ColOfVal<TimeSpan?>("hora_inicio").ToList();
                horasInicio.RemoveAll(x => x.IsNullOrEmptyOrDbNull());
                horasInicio.Sort((x,y) => TimeSpan.Compare((TimeSpan)x!, (TimeSpan)y!));

            List<TimeSpan?> horasFin = horarios.ColOfVal<TimeSpan?>("hora_fin").ToList();
                horasFin.RemoveAll(x => x.IsNullOrEmptyOrDbNull());
                horasFin.Sort((x, y) => TimeSpan.Compare((TimeSpan)y!, (TimeSpan)x!));

            List<string> horarios_ = (List<string>)horarios.OrderBy(x => x["dia-numero"]).ColOfVal<string>("dia-dia").Distinct().ToList();

            string dias = string.Join(", ", horarios_);
            string hora_inicio = !horasInicio.IsNullOrEmpty() ? ((TimeSpan)horasInicio[0]!).ToString(@"hh\:mm") : "?";
            string hora_fin = !horasFin.IsNullOrEmpty() ? ((TimeSpan)horasFin[0]!).ToString(@"hh\:mm") : "?";
            return dias + " " + hora_inicio + " " + hora_fin;
        }

        public void GenerarCursos()
        {
            EntityPersist persist = ContainerApp.db.Persist();
            if (GetOrNull("id").IsNullOrEmptyOrDbNull() || GetOrNull("planificacion").IsNullOrEmptyOrDbNull())
                throw new Exception("No se pueden generar los cursos: No está correctamente definido el id o la planificación");

            IEnumerable<object> idsCursos = ContainerApp.db.Sql("curso").
                Where("$comision = @0").
                Parameters(Get("id")).
                ColOfDict().
                ColOfVal<object>("id");
            
            if(idsCursos.Count()>0)
                persist.DeleteIds("curso", idsCursos.ToArray());

            IEnumerable<Dictionary<string, object?>> distribucionesHorariasData = ContainerApp.db.Sql("distribucion_horaria").
                Select("SUM($horas_catedra) AS suma_horas_catedra").
                Group("$disposicion-asignatura").
                Where("$disposicion-planificacion IN ( @0 )").
                Parameters(Get("planificacion")).ColOfDict();

            foreach(Dictionary<string, object?> dh in distribucionesHorariasData)
            {
                EntityValues cursoVal = ContainerApp.db.Values("curso").
                    Set("comision", Get("id")).
                    Set("asignatura", dh["disposicion-asignatura"]).
                    Set("horas_catedra", dh["suma_horas_catedra"]).
                    Default().Reset();

                persist.Insert(cursoVal);
            }

            persist.Exec().RemoveCache();
        }
    }
}
