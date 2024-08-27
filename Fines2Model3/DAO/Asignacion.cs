using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class AsignacionDAO
    {

        public static EntitySql AsignacionCursoDniSql(this Db db, object curso, object dni)
        {
            string subSql = "SELECT comision FROM curso WHERE id = @0";

            return db.Sql("alumno_comision")
               .Join("INNER JOIN (" + subSql + ") AS sub ON (sub.comision = $comision)")
               .Where("$persona-numero_documento = @1")
               .Parameters(curso, dni);
        }

        public static EntitySql AsignacionComisionDniSql(this Db db, object comision, object dni)
        {

            return db.Sql("alumno_comision")
               .Where("$comision = @0 AND $persona-numero_documento = @1")
               .Parameters(comision, dni);
        }

        public static EntitySql AsignacionesDeComisionesSql(this Db db, params object[] id_comisiones)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $comision IN ( @0 )
                "
            )
               .Parameters(id_comisiones.ToList());
        }

        public static EntitySql AsignacionesDeComisionesAutorizadasDelPeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                "
                )
               .Parameters(anio, semestre);
        }

        public static EntitySql AsignacionesDeComisionesAutorizadasDeCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $calendario-id = @0 
                    AND $comision-autorizada = true
                "
                )
               .Parameters(idCalendario);
        }


        public static EntitySql AsignacionesActivasDeComisionesAutorizadasDelPeriodoSinGeneroSql(this Db db, object anio, object semestre)
        {
            var subSql = db.Sql("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                    AND $estado = 'Activo'
                    AND $persona-genero IS NULL
                ");

            return db.Sql("alumno").
                Join("INNER JOIN (" + subSql + ") AS sub ON (sub.alumno = alumno.id)").
                Parameters(anio, semestre);
        }

        public static EntitySql AsignacionesActivasRestantesComisionSql(this Db db, object comision, IEnumerable<object> alumnos)
        {
            var esql = db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $estado = 'Activo'
                    AND $comision = @0                    
                ")
               .Parameters(comision);

            if (alumnos.Any())
                esql.And("$alumno NOT IN(@1)").Parameters(alumnos);
 
            return esql;

        }

    }
    
}
