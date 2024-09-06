using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class Toma
    {
        public static EntitySql TomasAprobadasDeCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-id = @0 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Parameters(idCalendario);
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
                .Parameters(idCurso);
        }

        public static EntitySql TomaAprobadaDeComisionQuery(this Db db, params object[] idComisiones)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $curso-comision IN ( @0 ) 
                    AND $estado = 'Aprobada'
                    AND $estado_contralor != 'Modificar'
                ")
                .Parameters(idComisiones);
        }

        public static EntitySql TomasPasarDeCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("toma")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-id = @0
                    AND ($estado = 'Aprobada' OR $estado = 'Renuncia' OR $estado = 'Baja')
                    AND $estado_contralor = 'Pasar'
                ")
                .Parameters(idCalendario);
        }

        public static IEnumerable<object> IdTomasPasarSinPlanillaDocenteDeCalendario(this Db db, object idCalendario)
        {
            IEnumerable<object> id_tomas = db.TomasPasarDeCalendarioSql(idCalendario).Cache().ColOfDict().ColOfVal<object>("id");

            IEnumerable<object> id_tomas_con_planilla_docente = db.Sql("asignacion_planilla_docente")
                .Fields()
                .Size(0)
                .Where("$calendario-id = @0")
                .Parameters(idCalendario).Cache().ColOfDict().ColOfVal<object>("toma");


            bool collectionsEqual = id_tomas.SequenceEqual(id_tomas_con_planilla_docente);

            // Return empty enumerable if collections are equal
            if (collectionsEqual)
                return Enumerable.Empty<object>();

            return id_tomas.Except(id_tomas_con_planilla_docente);
        }

    }
}
