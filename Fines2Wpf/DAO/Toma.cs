using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

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
                .Parameters(calendarioAnio, calendarioSemestre).ColOfDictCache().ColOfVal<object>("curso");

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
                .Parameters(calendarioAnio, calendarioSemestre).ColOfDictCache();

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

        public EntitySql TomasAprobadasConfirmadasQuery(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $estado = 'Aprobada'
                    AND $confirmada = true
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
