using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf.Windows.AlumnoComision.ListaAlumnosProgramaFines
{
    internal class AlumnoProgramaFinesData
    {
        public string extranjero { get; set; } = "0";
        public string? nacionalidad { get; set; }

        public string? apellido { get; set; }
        public string? nombre { get; set; }
        public string? cuil1 { get; set; }
        public string? dni_cargar { get; set; }
        public string? cuil2 { get; set; }
        public string? sexo { get; set; } //Masculino = 1, Femenino = 2, No binario = 3
        public string? dia_nac { get; set; } //1 .. 31
        public string? mes_nac { get; set; } //1 .. 12
        public string? ano_nac { get; set; } //1930 .. 2017

        //public string? category { get; set; } //1930 .. 2017
        public string? subcategory { get; set; } //pfid
        public string verifica_session { get; set; } = "0";
    }
}
