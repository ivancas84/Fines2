using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class TomaDAO
    {
        public static EntitySql TomasAprobadasDeCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__id = @0 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Param("@0", idCalendario);
        }

        public static EntitySql TomaAprobadaDeCursoQuery(this Db db, params object[] idCurso)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso IN ( @0 ) 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Param("@0", idCurso);
        }

        public static EntitySql TomaAprobadaDeComisionQuery(this Db db, params object[] idComisiones)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso__comision IN ( @0 ) 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Param("@0", idComisiones);
        }

        public static EntitySql TomasPasarDeCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario__id = @0
                    AND ($estado = 'Aprobada' OR $estado = 'Renuncia' OR $estado = 'Baja')
                    AND $estado_contralor = 'Pasar'
                ")
                .Param("@0", idCalendario);
        }

        public static IEnumerable<object> IdTomasPasarSinPlanillaDocenteDeCalendario(this Db db, object idCalendario)
        {
            IEnumerable<object> id_tomas = db.TomasPasarDeCalendarioSql(idCalendario).Cache().Dicts().EnumOfVal<object>("id");

            IEnumerable<object> id_tomas_con_planilla_docente = db.Sql("asignacion_planilla_docente")
                .Fields()
                .Size(0)
                .Where("$calendario__id = @0").
                Param("@0", idCalendario).Cache().Dicts().EnumOfVal<object>("toma");


            bool collectionsEqual = id_tomas.SequenceEqual(id_tomas_con_planilla_docente);

            // Return empty enumerable if collections are equal
            if (collectionsEqual)
                return Enumerable.Empty<object>();

            return id_tomas.Except(id_tomas_con_planilla_docente);
        }

    }
}
