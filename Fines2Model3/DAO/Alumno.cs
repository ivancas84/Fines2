namespace SqlOrganize.Sql.Fines2Model3
{
    public static class AlumnoDAO
    {
        public static EntitySql AlumnoPersonaSql(this Db db, object persona)
        {
            return db.Sql("alumno").
                Where("$persona = @0").
                Parameters(persona);
        }
    }
}
