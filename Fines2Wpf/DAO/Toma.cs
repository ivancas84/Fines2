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
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND $estado = 'Aprobada'
                ")
                .Parameters(calendarioAnio, calendarioSemestre).Cache().ColOfDict().ColOfVal<object>("curso");

        }

        public IEnumerable<Dictionary<string, object>> TomasSemestre(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(calendarioAnio, calendarioSemestre).Cache().ColOfDict();

        }


        public EntitySql TomasAprobadasSemestreQuery(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor = 'Pasar'
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
        }

        



        public EntitySql TomaAprobadaDeCursoQuery(object idCurso)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso = @0 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor = 'Pasar'
                ")
                .Parameters(idCurso);
        }

    }
}
