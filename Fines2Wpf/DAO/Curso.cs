﻿using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

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
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true 
                ")
                .Parameters(calendarioAnio, calendarioSemestre).Cache().ColOfDict();

        }

        public IEnumerable<Dictionary<string, object>> CursosSemestre(object calendarioAnio, object calendarioSemestre)
        {
            return ContainerApp.db.Sql("curso")
                .Fields()
                .Select("CONCAT($sede-numero, $comision-division, '/', $planificacion-anio, $planificacion-semestre) AS numero")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(calendarioAnio, calendarioSemestre).Cache().ColOfDict();
        }

        public EntitySql TomaActivaDeCursoQuery(object idCurso)
        {
            return ContainerApp.db.Sql("toma").
                Where("$curso = @0 AND $estado = 'Aprobada' AND $estado_contralor = 'Pasar'").
                Parameters(idCurso);
        }

        public EntitySql BusquedaAproximadaQuery(string search)
        {
            return ContainerApp.db.Sql("curso")
               .Fields()
               .Size(0)
               .Where(@"
                    CONCAT($sede-numero, $comision-division, '/', $planificacion-anio, $planificacion-semestre, ' ', $calendario-anio, '-', $calendario-semestre) LIKE @0
                ")
               .Order("$sede-numero ASC, $comision-division ASC, $planificacion-anio ASC, $planificacion-semestre ASC")
               .Parameters("%" + search + "%");
        }

        public EntitySql CursosDeComisionQuery(object idComision)
        {
            return ContainerApp.db.Sql("curso")
              .Fields()
              .Size(0)
              .Where("$comision = @0")
              .Parameters(idComision)
              .Order("$asignatura-nombre ASC");
        }

    }
}
