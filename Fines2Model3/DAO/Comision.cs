using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class ComisionDAO
    {

        public static IEnumerable<Comision> Comisiones__By_Search(string search)
        {
            string sql = @"
                SELECT id FROM comision
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                WHERE sede.nombre LIKE @Search
                OR CONCAT(sede.numero, comision.division, '/', planificacion.anio, planificacion.semestre) LIKE @Search
                OR comision.pfid = @Search
                OR CONCAT(calendario.anio, '/', calendario.semestre) LIKE @Search
                ORDER BY sede.numero ASC, comision.division ASC, calendario.anio DESC, calendario.semestre DESC
            ";

            search = "%" + search + "%";

            return Context.db.CacheSql().QueryIds<Comision>(sql, new { Search = search });

        }

        public static IEnumerable<Comision> ComisionesAutorizadas__By_IdCalendario(object idCalendario)
        {

            string sql = @"
                SELECT id FROM comision
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario = @IdCalendario AND comision.autorizada = true
                ORDER BY sede.numero ASC, comision.division ASC, calendario.anio DESC, calendario.semestre DESC
            ";

            return Context.db.CacheSql().QueryIds<Comision>(sql, new { IdCalendario = idCalendario });

        }

        public static IEnumerable<Comision> ComisionesAutorizadas__By_IdCalendario_IdSede(object idCalendario, object idSede)
        {
            string sql = @"
                SELECT id FROM comision
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario = @IdCalendario 
                AND comision.sede = @IdSede
                AND comision.autorizada = true
                ORDER BY sede.numero ASC, comision.division ASC, calendario.anio DESC, calendario.semestre DESC
            ";

            return Context.db.CacheSql().QueryIds<Comision>(sql, new { IdCalendario = idCalendario, IdSede = idSede });
        }

        public static IEnumerable<Comision> ComisionesAutorizadas__By_Periodo(object anio, object semestre)
        {
            string sql = @"
                SELECT id FROM comision
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario.anio = @Anio
                AND calendario.semestre = @Semestre
                AND comision.autorizada = true
                ORDER BY sede.numero ASC, comision.division ASC, calendario.anio DESC, calendario.semestre DESC
            ";

            return Context.db.CacheSql().QueryIds<Comision>(sql, new { Anio = anio, Semestre = semestre });
        }

        public static IEnumerable<Comision> Comisiones__By_Periodo(object anio, object semestre)
        {
            string sql = @"
                SELECT id FROM comision
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario.anio = @Anio
                AND calendario.semestre = @Semestre
                ORDER BY sede.numero ASC, comision.division ASC, calendario.anio DESC, calendario.semestre DESC
            ";

            return Context.db.CacheSql().QueryIds<Comision>(sql, new { Anio = anio, Semestre = semestre });
        }



        
    }
}
