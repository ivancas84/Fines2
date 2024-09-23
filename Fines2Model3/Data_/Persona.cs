using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Persona : EntityData
    {
        public static (string? dni, string? cuil) CuilDni(object? value)
        {

            if (value == null)
                return (null, null);

            string numbers = value!.ToString()!.CleanStringOfNonDigits()!;

            if (numbers.Length == 11) //longitud de cuil
                return (numbers.Substring(2, 8), numbers);

            if (numbers.Length == 8 || numbers.Length == 7) //longitud de dni
                return (numbers, null);

            throw new Exception("Error al definir CUIL o DNI");
        }
    }
}
