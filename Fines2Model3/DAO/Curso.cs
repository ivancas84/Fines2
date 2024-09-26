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
    public static class CursoDAO
    {

        public static EntitySql BusquedaAproximadaCurso(string search)
        {
            return Context.db.Sql("curso")
               .Fields()
               .Size(0)
               .Where(@"
                    (CONCAT($sede__numero, $comision__division, '/', $planificacion__anio, $planificacion__semestre, ' ', $calendario__anio, '__', $calendario__semestre) LIKE @0)
                    OR ($sede__nombre LIKE @0)
                    OR (CONCAT($comision__pfid, '/', $planificacion__anio, $planificacion__semestre, ' ', $calendario__anio, '__', $calendario__semestre) LIKE @0)
                    OR (CONCAT($comision__pfid, '/', $calendario__anio, '__', $calendario__semestre) LIKE @0)

                ")
               .Order("$sede__numero ASC, $comision__division ASC, $planificacion__anio ASC, $planificacion__semestre ASC")
               .Param("@0","%" + search + "%");
        }

        public static EntitySql CursosAutorizadosPeriodoSql(object calendarioAnio, object calendarioSemestre, object? sede = null, bool? autorizada = null)
        {
            return Context.db.Sql("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true 
                ").
                Param("@0", calendarioAnio).
                Param("@1", calendarioSemestre);

        }

        public static EntitySql CursosAutorizadosCalendarioSql(object idCalendario)
        {
            return Context.db.Sql("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__id = @0 
                    AND $comision__autorizada = true 
                ").
                Param("@0", idCalendario);

        }

        public static EntitySql CursoDeComisionPfidCodigoAsignaturaCalendarioSql(object pfid, object codigo, object idCalendario)
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

            return Context.db.Sql("curso")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__id = @0 
                    AND $comision__pfid = @1
                    AND $asignatura__codigo IN ( @2 )
                ").
                Param("@0", idCalendario).
                Param("@1", pfid).
                Param("@2", codigos);
        }

    }
}
