using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.DAO
{
    public class Sede
    {
        public EntityQuery BusquedaAproximadaQuery(string search)
        {
            return ContainerApp.db.Query("sede").
                Fields("id, nombre").
                Size(10).
                Where(@"
                    $nombre LIKE @0 
                    OR $numero LIKE @1
                ")
                .Parameters("%" + search + "%", "%" + search + "%")
                .Order("$nombre ASC");
        }

        public IEnumerable<object> IdsSedesActivasSemestre(object anio, object semestre)
        {
            var comisionDAO = new DAO.Comision();
            var r = comisionDAO.ComisionesAutorizadasPorSemestreQuery(anio, semestre).ColOfDictCache();
            return r.ColOfVal<object>("sede");
        }


    }
}
