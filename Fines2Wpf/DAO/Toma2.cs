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
        public static EntitySql TomasAprobadasSinModificarDePeriodoSql(object calendarioAnio, object calendarioSemestre)
        {

            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
        }

        public static EntitySql TomasRenunciaBajaSinModificarDePeriodoSql(object calendarioAnio, object calendarioSemestre)
        {

            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND ($estado = 'Renuncia' OR $estado = 'Baja')
                    AND $estado_contralor != 'Modificar'
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
        }

        public static EntitySql TomasPasarDePeriodoSql(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND ($estado = 'Aprobada' OR $estado = 'Renuncia' OR $estado = 'Baja')
                    AND $estado_contralor = 'Pasar'
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
        }

        public static EntitySql TomasParticularesDePeriodoSql(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                    AND (
                        $estado_contralor = 'Modificar'
                        OR (
                            $estado != 'Aprobada' AND $estado != 'Renuncia' AND $estado != 'Baja'
                        )
                    )
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
        }


        public static IEnumerable<object> IdTomasPasarSinPlanillaDocenteDePeriodo(object calendarioAnio, object calendarioSemestre)
        {
            IEnumerable<object> id_tomas = TomasPasarDePeriodoSql(calendarioAnio, calendarioSemestre).Cache().ColOfDict().ColOfVal<object>("id");

            IEnumerable<object> id_tomas_con_planilla_docente =  ContainerApp.db.Sql("asignacion_planilla_docente")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1
                ")
                .Parameters(calendarioAnio, calendarioSemestre).Cache().ColOfDict().ColOfVal<object>("toma");


            bool collectionsEqual = id_tomas.SequenceEqual(id_tomas_con_planilla_docente);

            // Return empty enumerable if collections are equal
            if (collectionsEqual)
                return Enumerable.Empty<object>();

            return id_tomas.Except(id_tomas_con_planilla_docente);
        }

        public static EntitySql TomaAprobadasPasarDeCursoQuery(object idCurso)
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
