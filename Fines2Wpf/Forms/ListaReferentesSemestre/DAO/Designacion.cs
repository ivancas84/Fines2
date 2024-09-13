using SqlOrganize;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Forms.ListaReferentesSemestre.DAO
{
    class Designacion
    {
        DAO.Comision comisionDAO = new ();

        public IEnumerable<Dictionary<string, object>> referentesSemestre(Search search)
        {
            IEnumerable<object> idSedes = comisionDAO.IdSedesSemestre(search);
            return ContainerApp.db.Sql("designacion").
                Where("$sede___Id IN (@0) AND $cargo = '1'").
                Param("@0", idSedes).Cache().Dicts();
        }

        public void UpdateValueRel(string key, object value, Dictionary<string, object> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("designacion", key, value, source).Exec().RemoveCache();
        }
    }
}
