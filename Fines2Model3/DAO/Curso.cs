using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Runtime.Intrinsics.X86;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class Curso
    {

        public static EntitySql BusquedaAproximadaCurso(this Db db, string search)
        {
            return db.Sql("curso")
               .Fields()
               .Size(0)
               .Where(@"
                    (CONCAT($sede-numero, $comision-division, '/', $planificacion-anio, $planificacion-semestre, ' ', $calendario-anio, '-', $calendario-semestre) LIKE @0)
                    OR ($sede-nombre LIKE @0)
                    OR (CONCAT($comision-pfid, '/', $planificacion-anio, $planificacion-semestre, ' ', $calendario-anio, '-', $calendario-semestre) LIKE @0)
                    OR (CONCAT($comision-pfid, '/', $calendario-anio, '-', $calendario-semestre) LIKE @0)

                ")
               .Order("$sede-numero ASC, $comision-division ASC, $planificacion-anio ASC, $planificacion-semestre ASC")
               .Parameters("%" + search + "%");
        }

        public static EntitySql CursosAutorizadosPeriodoSql(this Db db, object calendarioAnio, object calendarioSemestre, object? sede = null, bool? autorizada = null)
        {
            return db.Sql("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true 
                ")
                .Parameters(calendarioAnio, calendarioSemestre);

        }

        public static EntitySql CursosAutorizadosCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-id = @0 
                    AND $comision-autorizada = true 
                ")
                .Parameters(idCalendario);

        }

        public static EntitySql CursoDeComisionPfidCodigoAsignaturaCalendarioSql(this Db db, object pfid, object codigo, object idCalendario)
        {
            List<object> codigos = [codigo];

            switch (codigo)
            {
                case "WPV": case "ARTE1": case "ARTE2": case "ARTE3":
                    codigos = ["WPV", "ARTE1", "ARTE2", "ARTE3"];
                    break;

                case "WIN": case "LA":
                    codigos = ["WIN", "LA"];
                    break;

                case "WEA":
                case "WE2":
                case "WE3":
                    codigos = ["WEA", "WE2", "WE3"];
                    break;

            }

            return db.Sql("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-id = @0 
                    AND $comision-pfid = @1
                    AND $asignatura-codigo IN ( @2 )
                ")
                .Parameters(idCalendario, pfid, codigos);
        }

    }
}
