using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

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

        public static EntitySql CursoDeComisionPfidCodigoAsignaturaCalendarioSql(this Db db, object pfid, object codigo, object idCalendario)
        {
            List<object> codigos = new List<object>();
            codigos.Add(codigo);
            if (codigo.Equals("WPV"))
                codigos.Add("ARTE2");
            else if(codigo.Equals("ARTE2"))
                codigos.Add("WPV");

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
