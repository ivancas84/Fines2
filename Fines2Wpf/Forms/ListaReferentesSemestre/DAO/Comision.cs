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
                .Fields("sede-_Id")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(search.calendario__anio, search.calendario__semestre);
            var count = 2;
            if (!search.autorizada.IsNoE())
            {
                q.Where("AND $autorizada = @" + count + " ");
                q.Parameters(search.autorizada!);
                count++;
            }

            return q.Cache().ColOfDict().ColOfVal<object>("sede-_Id");


        }

        

    }
}
