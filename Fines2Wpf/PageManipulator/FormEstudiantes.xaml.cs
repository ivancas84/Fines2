using System.Windows;
using Utils;
using Fines2Wpf.DAO;
using Fines2Wpf.Data;

namespace Fines2Wpf.PageManipulator
{
    /// <summary>
    /// Lógica de interacción para FormEstudiantes.xaml
    /// </summary>
    public partial class FormEstudiantes : Window
    {

        AlumnoComision asignacionDAO = new();
        public FormEstudiantes()
        {

            InitializeComponent();
            var asignaciones = asignacionDAO.AsignacionesActivasDeComisionesAutorizadasPorSemestreQuery("2023", "2").ColOfDictCache();

            asignacionesTextBox.Text = @"[
";
            foreach (var asignacion in asignaciones)
            {
                var a = asignacion.Obj<Data_alumno_comision_r>();
                var sexo = (a.persona__genero.ToLower().Contains("f")) ? "2" : "1";
                var dia_nacimiento = (a.persona__fecha_nacimiento.IsNullOrEmpty()) ? "1" : a.persona__fecha_nacimiento?.ToString("d");
                var mes_nacimiento = (a.persona__fecha_nacimiento.IsNullOrEmpty()) ? "1" : a.persona__fecha_nacimiento?.ToString("M");
                var anio_nacimiento = (a.persona__fecha_nacimiento.IsNullOrEmpty()) ? "2000" : a.persona__fecha_nacimiento?.ToString("yyyy");

                asignacionesTextBox.Text += @"	{
		""apellido"": """ + a.persona__apellidos + @""",
		""nombre"": """ + a.persona__nombres + @""",
		""cuil1"": """",
		""dni_cargar"": """ + a.persona__numero_documento + @""",
		""cuil2"": """",
		""sexo"": """ + sexo + @""",
		""dia_nac"": """ + dia_nacimiento + @""",
		""mes_nac"": """ + mes_nacimiento + @""",
		""ano_nac"": """ + anio_nacimiento + @""",
		""category"": ""1"",
		""subcategory"":""" + a.comision__pfid + @""",
		""verifica_session"": ""0""
	},
";
            }


        }
    }
}
