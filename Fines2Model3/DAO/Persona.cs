using System.Net;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class PersonaDAO
    {
        public static IEnumerable<Persona> Personas__By_Search(string search)
        {
            string sql = @"SELECT DISTINCT id FROM persona
                WHERE nombres LIKE @Search
                OR apellidos LIKE @Search
                OR numero_documento LIKE @Search
                OR email LIKE @Search
                OR telefono LIKE @Search
                ORDER BY nombres ASC, apellidos ASC
                ";

            search = "%" + search+ "%";

            return Context.db.CacheSql().QueryIds<Persona>(sql, new { Search = search });
        }

        public static IEnumerable<Persona> Personas__BY_CuilDni(object cuilDni)
        {
            (string cuil, string dni) = Persona.CuilDni(cuilDni);

            string sql = @"SELECT DISTINCT id FROM persona WHERE
";
            bool existsDni = false;

            if (!dni.IsNoE())
            {
                sql += @"numero_documento = @Dni
";
                existsDni = true;
            }

            if (!cuil.IsNoE())
            {
                if (existsDni)
                    sql += " AND ";
                sql += @"cuil = @Cuil
";
            }

            sql += "ORDER BY nombres ASC, apellidos ASC";

            return Context.db.CacheSql().QueryIds<Persona>(sql, new { Cuil = cuil, Dni = dni});
        }


        
    }
}
