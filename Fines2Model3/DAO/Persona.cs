using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Model3.DAO
{
    public static class Persona
    {
        public static EntitySql PersonaSearchLikeQuery(this Db db, string search)
        {
            return db.Sql("persona").
                Where("$nombres LIKE @0 ").
                Where("OR $apellidos LIKE @0 ").
                Where("OR $numero_documento LIKE @0 ").
                Where("OR $email LIKE @0 ").
                Where("OR $telefono LIKE @0 ").
                Order("$nombres ASC, $apellidos ASC").
                Parameters("%" + search + "%");
        }
    }
}
