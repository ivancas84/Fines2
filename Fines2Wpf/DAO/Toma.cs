using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using System.Collections.Generic;

namespace Fines2Wpf.DAO
{
    public class Toma
    {
        public IEnumerable<object> IdCursosConTomasAprobadasSemestre(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma").
                Fields("curso").
                Size(0).
                Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1
                    AND $estado = 'Aprobada'
                ")
                .Param("@0", calendarioAnio).Param("@1", calendarioSemestre).Cache().Dicts().ColOfVal<object>("curso");

        }

        public IEnumerable<Dictionary<string, object>> TomasSemestre(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                ")
                .Param("@0", calendarioAnio).Param("@1", calendarioSemestre).Cache().Dicts();

        }


        public EntitySql TomasAprobadasSemestreQuery(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor = 'Pasar'
                ")
                .Param("@0", calendarioAnio).Param("@1", calendarioSemestre);
        }

        



   

    }
}
