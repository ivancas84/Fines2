using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.DAO
{
    internal static class Toma2
    {

        public static EntitySql TomasAprobadasDePeriodoSql(object calendarioAnio, object calendarioSemestre)
        {

            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND $estado = 'Aprobada'
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
        }

        public static EntitySql TomasAprobadasPasarDePeriodoSql(object calendarioAnio, object calendarioSemestre)
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

        public static IEnumerable<object> IdTomasAprobadasSinPlanillaDocenteSemestre(object calendarioAnio, object calendarioSemestre)
        {

            IEnumerable<object> id_tomas = TomasAprobadasPasarDePeriodoSql(calendarioAnio, calendarioSemestre).ColOfDictCache().ColOfVal<object>("id");


            IEnumerable<object> id_tomas_con_planilla_docente =  ContainerApp.db.Sql("asignacion_planilla_docente")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND $toma-estado = 'Aprobada'
                    AND $toma-estado_contralor = 'Pasar'
                ")
                .Parameters(calendarioAnio, calendarioSemestre).ColOfDictCache().ColOfVal<object>("toma");


            bool collectionsEqual = id_tomas.SequenceEqual(id_tomas_con_planilla_docente);

            // Return empty enumerable if collections are equal
            if (collectionsEqual)
                return Enumerable.Empty<object>();

            return id_tomas.Except(id_tomas_con_planilla_docente);



        }





        public static EntitySql TomaAprobadasDeCursoQuery(object idCurso)
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
