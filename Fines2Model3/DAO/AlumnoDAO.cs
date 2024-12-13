namespace SqlOrganize.Sql.Fines2Model3
{
    public static class AlumnoDAO
    {
        public static IEnumerable<Alumno> Alumno__BY_persona(object persona)
        {
            using (var connection = Context.db.Connection().Open())
            {
                var sql = Context.db.Sql().SelectDapper("alumno") + @"
                    WHERE persona = @Persona;
                ";

                return Alumno.QueryDapper(connection, sql, new { Persona = persona});
            }

        }

        public static IEnumerable<Alumno> AlumnosSinGenero__By_Periodo(object anio, object semestre)
        {
            string sql = @"
                SELECT id FROM alumno
                INNER JOIN persona ON persona.id = alumno.persona
                INNER JOIN (
                    SELECT alumno
                    FROM alumno_comision
                    INNER JOIN comision ON (alumno_comision.comision = comision.id)
                    INNER JOIN calendario ON comision.calendario = calendario.id
                    WHERE calendario.anio = @Anio 
                    AND calendario.semestre = @Semestre
                    AND comision.autorizada = true
                    AND estado = 'Activo'
                ) AS sub ON (sub.alumno = alumno.id)
                WHERE persona.genero IS NULL;
            ";

            return Context.db.CacheSql().QueryIds<Alumno>("alumno", sql, new { Anio = anio, Semestre = semestre });
        }
    }
}
