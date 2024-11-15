using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class PersonaDAO
    {
        public static EntitySql SqlPersonas__SearchLike(string search)
        {
            return Context.db.Sql("persona").
                Where("$nombres LIKE @0 ").
                Where("OR $apellidos LIKE @0 ").
                Where("OR $numero_documento LIKE @0 ").
                Where("OR $email LIKE @0 ").
                Where("OR $telefono LIKE @0 ").
                Order("$nombres ASC, $apellidos ASC").
                Param("@0", "%" + search + "%");
        }

        public static EntitySql SqlPersona__BY_dni(object cuilDni)
        {
            (string cuil, string dni) = Persona.CuilDni(cuilDni);

            return Context.db.Sql("persona").
                Where("$numero_documento = @0").
                Param("@0", dni);
        }


        
    }
}
