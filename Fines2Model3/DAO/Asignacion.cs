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
               .Where("$persona__numero_documento = @1")
               .Param("@0", curso).Param("@1", dni);
        }

        public static EntitySql AsignacionComisionDniSql(this Db db, object comision, object dni)
        {

            return db.Sql("alumno_comision")
               .Where("$comision = @0 AND $persona__numero_documento = @1")
               .Param("@0",comision).Param("@1", dni);
        }

        public static EntitySql AsignacionesDeComisionesSql(this Db db, params object[] id_comisiones)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $comision IN ( @0 )
                "
            )
               .Param("@0", id_comisiones);
        }

        public static EntitySql AsignacionesDeComisionesAutorizadasDelPeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true
                "
                )
               .Param("@0", anio).Param("@1", semestre);
        }


        public static EntitySql AsignacionesActivasDeComisionesAutorizadasDelPeriodoSinGeneroSql(this Db db, object anio, object semestre)
        {
            var subSql = db.Sql("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1 
                    AND $comision__autorizada = true
                    AND $estado = 'Activo'
                    AND $persona__genero IS NULL
                ");

            return db.Sql("alumno").
                Join("INNER JOIN (" + subSql + ") AS sub ON (sub.alumno = alumno.id)").
                Param("@0", anio).Param("@1", semestre);
        }

        public static EntitySql AsignacionesActivasRestantesComisionSql(this Db db, object comision, IEnumerable<object> alumnos)
        {
            var esql = db.Sql("alumno_comision")
               .Size(0)
               .Where(@"
                    $estado = 'Activo'
                    AND $comision = @0                    
                ")
               .Param("@0", comision);

            if (alumnos.Any())
                esql.And("$alumno NOT IN(@1)").Param("@1", alumnos);
 
            return esql;

        }

    }
    
}
