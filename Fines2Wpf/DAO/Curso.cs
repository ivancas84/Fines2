using SqlOrganize.Sql;
using System.Collections.Generic;

namespace Fines2Wpf.DAO
{
    internal class Curso
    {
        public IEnumerable<Dictionary<string, object>> CursosAutorizadosSemestre(object calendarioAnio, object calendarioSemestre, object? sede = null, bool? autorizada = null)
        {
            return ContainerApp.db.Sql("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true 
                ")
                .Param("@0", calendarioAnio).Param("@1", calendarioSemestre).Cache().Dicts();

        }

        public IEnumerable<Dictionary<string, object>> CursosSemestre(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("curso")
                .Fields()
                .Select("CONCAT($sede__numero, $comision__division, '/', $planificacion__anio, $planificacion__semestre) AS numero")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                ")
                .Param("@0", calendarioAnio).Param("@1", calendarioSemestre).Cache().Dicts();
        }

        public EntitySql TomaActivaDeCursoQuery(object idCurso)
        {
            return ContainerApp.db.Sql("toma").
                Where("$curso = @0 AND $estado = 'Aprobada' AND $estado_contralor = 'Pasar'").
                Param("@0", idCurso);
        }


        public EntitySql CursosDeComisionQuery(object idComision)
        {
            return ContainerApp.db.Sql("curso")
              .Fields()
              .Size(0)
              .Where("$comision = @0")
              .Param("@0", idComision)
              .Order("$asignatura__nombre ASC");
        }

    }
}
