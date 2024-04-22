using SqlOrganize;


namespace Fines2Wpf.DAO
{
    public static class Persona
    {

        public static EntityQuery SearchLikeQuery(string search)
        {
            return ContainerApp.db.Query("persona").
                Where("$nombres LIKE @0 ").
                Where("OR $apellidos LIKE @0 ").
                Where("OR $numero_documento LIKE @0 ").
                Where("OR $email LIKE @0 ").
                Where("OR $telefono LIKE @0 ").
                Order("$nombres ASC, $apellidos ASC").
                Parameters("%"+search+"%");
        }
    }
}
