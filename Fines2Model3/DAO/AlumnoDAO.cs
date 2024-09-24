namespace SqlOrganize.Sql.Fines2Model3
{
    public static class AlumnoDAO
    {
        public static EntitySql AlumnoPersonaSql(object persona)
        {
            return Context.db.Sql("alumno").
                Where("$persona = @0").
                Param("@0", persona);
        }
    }
}
