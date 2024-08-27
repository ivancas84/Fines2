using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class DocentePfItem
    {
        public string numero_documento { set; get; }
        public string nombres { set; get; }
        public string apellidos { set; get; }
        public string? descripcion_domicilio { set; get; }
        public string? telefono { set; get; }
        public string? email_abc { set; get; }
        public int? dia_nacimiento { set; get; }
        public int? mes_nacimiento { set; get; }
        public int? anio_nacimiento { set; get; }
        public List<Dictionary<string, string>> cargos { set; get; }
    }
}
