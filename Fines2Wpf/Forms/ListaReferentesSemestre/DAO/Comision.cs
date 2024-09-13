using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System.Collections.Generic;
namespace Fines2Wpf.Forms.ListaReferentesSemestre.DAO
{
    class Comision
    {

        public IEnumerable<object> IdSedesSemestre(Search search)
        {
            var q = ContainerApp.db.Sql("comision")
                .Fields("sede___Id")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendariosemestre = @1 
                ")
                .Param("@0", search.calendario__anio).Param("@1", search.calendario__semestre);
            var count = 2;
            if (!search.autorizada.IsNoE())
            {
                q.Where("AND $autorizada = @" + count + " ");
                q.Param("@0", search.autorizada!);
                count++;
            }

            return q.Cache().Dicts().ColOfVal<object>("sede___Id");


        }

        

    }
}
