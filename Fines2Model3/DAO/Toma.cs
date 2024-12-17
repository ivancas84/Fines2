using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class TomaDAO
    {
        public static IEnumerable<Toma> TomasAprobadas__By_IdCalendario(object idCalendario)
        {
            string sql = @"
                SELECT DISTINCT toma.id 
                INNER JOIN curso ON toma.curso = curso.id
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario.id = @IdCalendario
                AND (estado = 'Aprobada' OR estado = 'Pendiente')
                AND estado_contralor != 'Modificar'
";

            return Context.db.CacheSql().QueryIds<Toma>(sql, new { IdCalendario = idCalendario });
        }

        public static IEnumerable<Toma> TomasNoAprobadas__By_IdCalendario(object idCalendario)
        {
            string sql = @"
                SELECT DISTINCT toma.id 
                INNER JOIN curso ON toma.curso = curso.id
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE calendario.id = @IdCalendario
                AND estado != 'Aprobada'
                AND estado_contralor = 'Modificar'
";

            return Context.db.CacheSql().QueryIds<Toma>(sql, new { IdCalendario = idCalendario });
        }

        public static IEnumerable<Toma> TomasAprobadas_By_IdCurso(params object[] idsCursos)
        {
            string sql = @"
                SELECT DISTINCT toma.id 
                INNER JOIN curso ON toma.curso = curso.id
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                WHERE curso IN ( @IdsCursos ) 
                AND estado = 'Aprobada'
                AND estado_contralor != 'Modificar'
";

            return Context.db.CacheSql().QueryIds<Toma>(sql, new { IdsCursos = idsCursos });
        }

        public static IEnumerable<Toma> TomasAprobadas__By_IdsComisiones(params object[] idsComisiones)
        {
            string sql = @"
                SELECT DISTINCT toma.id 
                INNER JOIN curso ON toma.curso = curso.id
                WHERE curso.comision IN ( @IdsComisiones ) 
                AND estado = 'Aprobada'
                AND estado_contralor != 'Modificar'
";

            return Context.db.CacheSql().QueryIds<Toma>(sql, new { IdsComisiones = idsComisiones });
        }

        public static IEnumerable<Toma> TomasPasar__By_IdCalendario(object idCalendario)
        {
            string sql = @"
                SELECT DISTINCT toma.id 
                INNER JOIN curso ON toma.curso = curso.id
                INNER JOIN comision ON curso.comision = comision.id
                WHERE comision.calendario = @IdCalendario 
                AND estado_contralor = 'Pasar'
                AND ($estado = 'Aprobada' OR $estado = 'Renuncia' OR $estado = 'Baja')
";
            return Context.db.CacheSql().QueryIds<Toma>(sql, new { IdCalendario = idCalendario });

        }


        public static IEnumerable<Toma> TomasPasar__By_IdCalendario__Without_PlanillaDocente(object idCalendario)
        {
            string sql = @"
                SELECT DISTINCT toma.id 
                INNER JOIN curso ON toma.curso = curso.id
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN docente ON toma.docente = docente.id
                INNER JOIN persona ON docente.persona = persona.id
                LEFT JOIN asignacion_planilla_docente ON asignacion_planilla_docente.toma = toma.id
                WHERE comision.calendario = @IdCalendario 
                AND estado_contralor = 'Pasar'
                AND ($estado = 'Aprobada' OR $estado = 'Renuncia' OR $estado = 'Baja')
                AND asignacion_planilla_docente.id IS NULL
                ORDER BY persona.numero_documento ASC
            ";  
            return Context.db.CacheSql().QueryIds<Toma>(sql, new { IdCalendario = idCalendario });

        }
    }
}
