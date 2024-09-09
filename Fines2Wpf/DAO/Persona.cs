using SqlOrganize;
using SqlOrganize.Sql;


namespace Fines2Wpf.DAO
{
    public static class Persona
    {

        public static EntitySql SearchLikeQuery(string search)
        {
            return ContainerApp.db.Sql("persona").
                Where("$nombres LIKE @0 ").
                Where("OR $apellidos LIKE @0 ").
                Where("OR $numero_documento LIKE @0 ").
                Where("OR $email LIKE @0 ").
                Where("OR $telefono LIKE @0 ").
                Order("$nombres ASC, $apellidos ASC").
                Param("@0", "%"+search+"%");
        }
    }
}
