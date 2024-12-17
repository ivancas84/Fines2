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

        public static IEnumerable<Curso> Cursos__By_Search(string search)
        {
            string sql = @"
                SELECT id FROM curso
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                WHERE
                    CONCAT(sede.numero, comision.division, '/', planificacion.anio, planificacion.semestre, ' ', calendario.anio, '-', $calendario.semestre) LIKE @Search)
                    OR (sede.nombre LIKE @Search)
                    OR (CONCAT(comision.pfid, '/', planificacion.anio, planificacion.semestre, ' ', $calendario.anio, '-', $calendario.semestre) LIKE @Search)
                    OR (CONCAT(comision.pfid, '/', calendario.anio, '-', $calendario.semestre) LIKE @Search)
                ORDER BY sede.numero ASC, comision.division ASC, planificacion.anio ASC, planificacion.semestre ASC
                ";

            return Context.db.CacheSql().QueryIds<Curso>(sql, new { Search = search });
        }

        public static IEnumerable<Curso> Cursos__By_IdComision(object idComision)
        {
            string sql = @"
                SELECT id FROM curso
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                WHERE comision.id = @IdComision
                ORDER BY sede.numero ASC, comision.division ASC, planificacion.anio ASC, planificacion.semestre ASC
                ";

            return Context.db.CacheSql().QueryIds<Curso>(sql, new { IdComision = idComision });
        }

        public static IEnumerable<Curso> CursosAutorizados__By_Periodo(object anio, object semestre)
        {
            string sql = @"
                SELECT id FROM curso
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE planificacion.anio = @Anio AND planificacion.semestre = @Semestre
                ORDER BY sede.numero ASC, comision.division ASC, planificacion.anio ASC, planificacion.semestre ASC
                ";

            return Context.db.CacheSql().QueryIds<Curso>(sql, new { Anio = anio, Semestre = semestre });
        }

        public static IEnumerable<Curso> CursosAutorizados__By_IdCalendario(object idCalendario)
        {
            string sql = @"
                SELECT id FROM curso
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario.id = @IdCalendario
                AND comision.autorizada = true
                ORDER BY sede.numero ASC, comision.division ASC, planificacion.anio ASC, planificacion.semestre ASC
                ";

            return Context.db.CacheSql().QueryIds<Curso>(sql, new { IdCalendario = idCalendario });
        }

        public static Curso? CursosAutorizados__By_Pfid_Codigo_IdCalendario(object pfid, object codigo, object idCalendario)
        {
            string sql = @"
                SELECT id FROM curso
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                INNER JOIN disposicion ON comision.disposicion = disposicion.id
                WHERE comision.pfid = @Pfid
                AND comision.codigo IN @Codigos
                AND calendario.id = @IdCalendario
                AND comision.autorizada = true
                ORDER BY sede.numero ASC, comision.division ASC, planificacion.anio ASC, planificacion.semestre ASC
                ";

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

            return Context.db.CacheSql().QueryIds<Curso>(sql, new { Codigos = codigos, IdCalendario = idCalendario, Pfid = pfid}).FirstOrDefault();
        }

    }
}
