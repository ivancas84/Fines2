using SqlOrganize.CollectionUtils;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class ComisionValues : EntityVal
    {
        public ComisionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }


      

        public IEnumerable<PersistContext> GenerarComisionesSemestreSiguiente(object idCalendario, object idCalendarioComisionesSiguientes)
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

            List<PersistContext> persists = new();
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
                    UpdateFieldIds("comision", "comision_siguiente", comValues.Get("id"), actualId!).
                    AddTo(persists);
            }

            return persists;
        }

        
    }
}
