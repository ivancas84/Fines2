using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using System.Collections.Generic;

namespace Fines2Wpf.DAO
{
    public class Sede
    {
        public EntitySql BusquedaAproximadaQuery(string search)
        {
            return ContainerApp.db.Sql("sede").
                Size(30).
                Where(@"
                    $nombre LIKE @0 
                    OR $numero LIKE @1
                ")
                .Parameters("%" + search + "%", "%" + search + "%")
                .Order("$numero ASC");
        }

        public IEnumerable<object> IdsSedesActivasSemestre(object anio, object semestre)
        {
            var comisionDAO = new DAO.Comision();
            var r = comisionDAO.ComisionesAutorizadasPorSemestreQuery(anio, semestre).Cache().ColOfDict();
            return r.ColOfVal<object>("sede");
        }


    }
}
