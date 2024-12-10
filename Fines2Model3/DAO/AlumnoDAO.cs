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
    }
}
