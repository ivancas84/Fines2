using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2App.Views.AnalizarAlumnosComision
{
    public class AlumnoParaCargar
    {
		public string apellido { get; set; }
        public string nombre { get; set; }
		public string cuil1 { get; set; } = "";
        public string cuil2 { get; set; } = "";
        public string dni_cargar { get; set; } //dni
		public string sexo { get; set; } //1 masculino, 2 femenino y 3 no binario
		public string dia_nac { get; set; } = "1";
        public string mes_nac { get; set; } = "1";
        public string ano_nac { get; set; } = "2000";
        public string category { get; set; } = "1"; //periodo de inscripcion 1 = Agosto - Noviembre 2023
        public string subcategory { get; set; } //pfid comision
		public string verifica_session { get; set; } = "0";
        public string email { get; set; } = "";
        public string nacionalidad { get; set; } = "";
        public string cod_area { get; set; }
        public string nro_telefono { get; set; } = "";
        public string direccion { get; set; } = "";

        public string departamento { get; set; } = "";


    }
}
