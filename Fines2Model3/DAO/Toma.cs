using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class TomaDAO
    {
        public static EntitySql TomasAprobadasDeCalendarioSql(object idCalendario)
        {
            return Context.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__id = @0 
                    AND ($estado = 'Aprobada' OR $estado = 'Pendiente')
                    AND $estado_contralor != 'Modificar'
                ")
                .Param("@0", idCalendario);
        }

        public static EntitySql TomasNoAprobadasDeCalendarioSql(object idCalendario)
        {
            return Context.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__id = @0 
                    AND ($estado != 'Aprobada' OR $estado_contralor = 'Modificar')
                ")
                .Param("@0", idCalendario);
        }

        public static EntitySql TomaAprobadaDeCursoQuery(params object[] idCurso)
        {
            return Context.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso IN ( @0 ) 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Param("@0", idCurso);
        }

        public static EntitySql TomaAprobadaDeComisionQuery(params object[] idComisiones)
        {
            return Context.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso__comision IN ( @0 ) 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Param("@0", idComisiones);
        }

        public static EntitySql TomasPasarDeCalendarioSql(object idCalendario)
        {
            return Context.db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__id = @0
                    AND ($estado = 'Aprobada' OR $estado = 'Renuncia' OR $estado = 'Baja')
                    AND $estado_contralor = 'Pasar'
                ")
                .Param("@0", idCalendario);
        }

        public static EntitySql TomasPasarSinPlanillaDocenteDeCalendario(object idCalendario)
        {
            var idTomas = TomaDAO.IdTomasPasarSinPlanillaDocenteDeCalendario(idCalendario);
            return Context.db.Sql("toma").Where("$id IN (@0)").
                Order("$docente__numero_documento ASC").
                Size(0).
                Param("@0", idTomas);
        }

        public static IEnumerable<object> IdTomasPasarSinPlanillaDocenteDeCalendario(object idCalendario)
        {
            IEnumerable<object> id_tomas = TomasPasarDeCalendarioSql(idCalendario).Cache().Dicts().ColOfVal<object>("id");

            IEnumerable<object> id_tomas_con_planilla_docente = Context.db.Sql("asignacion_planilla_docente")
                .Fields()
                .Size(0)
                .Where("$calendario__id = @0").
                Param("@0", idCalendario).Cache().Dicts().ColOfVal<object>("toma");


            bool collectionsEqual = id_tomas.SequenceEqual(id_tomas_con_planilla_docente);

            // Return empty enumerable if collections are equal
            if (collectionsEqual)
                return Enumerable.Empty<object>();

            return id_tomas.Except(id_tomas_con_planilla_docente);
        }

    }
}
